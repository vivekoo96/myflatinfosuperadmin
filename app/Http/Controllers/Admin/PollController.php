<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\PollQuestion;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\Building;
use App\Models\Flat;
use App\Helpers\NotificationHelper2 as NotificationHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PollController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (\Auth::user() && !\Auth::user()->hasPermission('menu.polls')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    // ─────────────────────────────────────────────────────────────
    // LIST
    // ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $buildings = Building::orderBy('name')->get();
        $selectedBuilding = null;
        $polls = collect();

        if ($request->building_id) {
            $selectedBuilding = Building::find($request->building_id);
            $polls = Poll::where('building_id', $request->building_id)
                ->withTrashed()
                ->with(['creator', 'questions', 'building'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $polls = Poll::withTrashed()
                ->with(['creator', 'questions', 'building'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.poll.index', compact('buildings', 'selectedBuilding', 'polls'));
    }

    // ─────────────────────────────────────────────────────────────
    // CREATE / UPDATE
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $rules = [
            'building_id' => 'required|exists:buildings,id',
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:poll,survey',
            'structure'   => 'required|in:single,multiple',
            'voting_type' => 'required|in:flat_based,user_based',
            'questions'   => 'required|array|min:1',
            'questions.*.question' => 'required|string|max:500',
            'questions.*.options'  => 'required|array|min:2',
            'questions.*.options.*' => 'required|string|max:255',
        ];

        if ($request->expiry_date) {
            $rules['expiry_date'] = 'date|after:now';
        }

        $messages = [
            'expiry_date.after' => 'The expiry date and time must be in the future (after the current time).',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            if ($request->id) {
                // UPDATE: only expiry_date is editable
                $poll = Poll::where('id', $request->id)->withTrashed()->firstOrFail();

                if ($request->expiry_date) {
                    $poll->expiry_date = Carbon::parse($request->expiry_date);
                    $poll->save();
                }

                DB::commit();
                return redirect()->back()->with('success', 'Poll expiry date updated successfully.');
            }

            // CREATE
            $user = Auth::user();
            $poll = Poll::create([
                'building_id'     => $request->building_id,
                'title'           => $request->title,
                'description'     => $request->description,
                'type'            => $request->type,
                'structure'       => $request->structure,
                'voting_type'     => $request->voting_type,
                'status'          => 'draft',
                'expiry_date'     => $request->expiry_date ? Carbon::parse($request->expiry_date) : null,
                'created_by'      => $user->id,
                'created_by_role' => 'Super Admin',
            ]);

            foreach ($request->questions as $qIndex => $qData) {
                $question = PollQuestion::create([
                    'poll_id'  => $poll->id,
                    'question' => $qData['question'],
                    'order'    => $qIndex,
                ]);

                foreach ($qData['options'] as $oIndex => $optText) {
                    PollOption::create([
                        'poll_question_id' => $question->id,
                        'option_text'      => $optText,
                        'order'            => $oIndex,
                    ]);
                }
            }

            if ($request->status_action === 'activate') {
                $poll->status = 'active';
                $poll->save();
                $this->notifyBuildingUsers(
                    $poll,
                    'New ' . ucfirst($poll->type) . ': ' . $poll->title,
                    'A new ' . $poll->type . ' is now available. Cast your vote before it expires.'
                );
                DB::commit();
                return redirect()->back()->with('success', ucfirst($poll->type) . ' created and activated successfully.');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Poll created successfully as draft.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SA Poll store failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to save poll: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    // SHOW / RESULTS
    // ─────────────────────────────────────────────────────────────
    public function show($id)
    {
        $poll = Poll::withTrashed()
            ->with(['questions.options', 'creator', 'building'])
            ->findOrFail($id);

        $results = $this->buildResults($poll);

        return view('admin.poll.show', compact('poll', 'results'));
    }

    // ─────────────────────────────────────────────────────────────
    // ACTIVATE (draft → active)
    // ─────────────────────────────────────────────────────────────
    public function activate($id)
    {
        $poll = Poll::findOrFail($id);

        if ($poll->status !== 'draft') {
            return response()->json(['error' => 'Only draft polls can be activated.'], 422);
        }

        $poll->status = 'active';
        $poll->save();

        $this->notifyBuildingUsers(
            $poll,
            'New ' . ucfirst($poll->type) . ': ' . $poll->title,
            'A new ' . $poll->type . ' is now available. Cast your vote before it expires.'
        );

        return response()->json(['msg' => 'success', 'status' => 'active']);
    }

    // ─────────────────────────────────────────────────────────────
    // CLOSE (active → closed)
    // ─────────────────────────────────────────────────────────────
    public function close($id)
    {
        $poll = Poll::findOrFail($id);

        if ($poll->status !== 'active') {
            return response()->json(['error' => 'Only active polls can be closed.'], 422);
        }

        $poll->status = 'closed';
        $poll->save();

        return response()->json(['msg' => 'success', 'status' => 'closed']);
    }

    // ─────────────────────────────────────────────────────────────
    // REOPEN (closed/published → active)
    // ─────────────────────────────────────────────────────────────
    public function reopen($id)
    {
        $poll = Poll::findOrFail($id);

        if (! in_array($poll->status, ['closed', 'published'])) {
            return response()->json(['error' => 'Only closed or published polls can be reopened.'], 422);
        }

        $poll->status = 'active';
        $poll->result_released_at = null;
        $poll->save();

        return response()->json(['msg' => 'success', 'status' => 'active']);
    }

    // ─────────────────────────────────────────────────────────────
    // RELEASE RESULTS (closed → published)
    // ─────────────────────────────────────────────────────────────
    public function releaseResults($id)
    {
        $poll = Poll::findOrFail($id);

        if ($poll->status !== 'closed') {
            return response()->json(['error' => 'Poll must be closed before releasing results.'], 422);
        }

        $poll->status = 'published';
        $poll->result_released_at = Carbon::now();
        $poll->save();

        $this->notifyBuildingUsers(
            $poll,
            ucfirst($poll->type) . ' Results: ' . $poll->title,
            'Results for the ' . $poll->type . ' "' . $poll->title . '" have been released. Tap to view.'
        );

        return response()->json(['msg' => 'success', 'status' => 'published']);
    }

    // ─────────────────────────────────────────────────────────────
    // DELETE / RESTORE
    // ─────────────────────────────────────────────────────────────
    public function destroy($id, Request $request)
    {
        $poll = Poll::withTrashed()->findOrFail($id);

        if ($request->action === 'restore') {
            $poll->restore();
        } else {
            $poll->delete();
        }

        return response()->json(['msg' => 'success']);
    }

    // ─────────────────────────────────────────────────────────────
    // UPDATE EXPIRY
    // ─────────────────────────────────────────────────────────────
    public function updateExpiry(Request $request, $id)
    {
        $request->validate(['expiry_date' => 'required|date|after:now']);

        $poll = Poll::findOrFail($id);

        if (in_array($poll->status, ['closed', 'published'])) {
            return response()->json(['error' => 'Cannot change expiry of a closed or published poll.'], 422);
        }

        $poll->expiry_date = Carbon::parse($request->expiry_date);
        $poll->save();

        return response()->json(['msg' => 'success', 'expiry_date' => $poll->expiry_date->format('d M Y, h:i A')]);
    }

    // ─────────────────────────────────────────────────────────────
    // EDIT DATA (GET - returns JSON for the edit modal)
    // ─────────────────────────────────────────────────────────────
    public function editData($id)
    {
        $poll = Poll::where('id', $id)
            ->where('status', 'draft')
            ->with(['questions.options'])
            ->firstOrFail();

        return response()->json([
            'id'          => $poll->id,
            'title'       => $poll->title,
            'description' => $poll->description,
            'type'        => $poll->type,
            'structure'   => $poll->structure,
            'voting_type' => $poll->voting_type,
            'expiry_date' => $poll->expiry_date ? $poll->expiry_date->format('Y-m-d\TH:i') : '',
            'questions'   => $poll->questions->map(function ($q) {
                return [
                    'question' => $q->question,
                    'options'  => $q->options->pluck('option_text')->toArray(),
                ];
            }),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // UPDATE DRAFT (POST - full edit of a draft poll)
    // ─────────────────────────────────────────────────────────────
    public function updateDraft(Request $request, $id)
    {
        $poll = Poll::where('id', $id)
            ->where('status', 'draft')
            ->firstOrFail();

        // Validate
        $rules = [
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:poll,survey',
            'structure'   => 'required|in:single,multiple',
            'voting_type' => 'required|in:flat_based,user_based',
        ];
        if ($request->expiry_date) {
            $rules['expiry_date'] = 'date|after:now';
        }
        $validator = \Validator::make($request->all(), $rules, [
            'expiry_date.after' => 'The expiry date must be in the future.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // Collect questions from flat keys like questions[0][question]
        $questions = [];
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^questions\[(\d+)\]\[question\]$/', $key, $m)) {
                $questions[(int) $m[1]]['question'] = $value;
            }
            if (preg_match('/^questions\[(\d+)\]\[options\]\[(\d+)\]$/', $key, $m)) {
                $questions[(int) $m[1]]['options'][(int) $m[2]] = $value;
            }
        }
        // Also handle array-style submission
        if ($request->has('questions') && is_array($request->questions)) {
            $questions = $request->questions;
        }

        if (empty($questions)) {
            return response()->json(['error' => 'At least one question is required.'], 422);
        }

        DB::beginTransaction();
        try {
            // Update poll metadata
            $poll->update([
                'title'       => $request->title,
                'description' => $request->description,
                'type'        => $request->type,
                'structure'   => $request->structure,
                'voting_type' => $request->voting_type,
                'expiry_date' => $request->expiry_date ? Carbon::parse($request->expiry_date) : null,
            ]);

            // Delete old questions and options, then recreate
            foreach ($poll->questions as $oldQ) {
                PollOption::where('poll_question_id', $oldQ->id)->delete();
            }
            PollQuestion::where('poll_id', $poll->id)->delete();

            // Recreate questions and options
            foreach ($questions as $qIndex => $qData) {
                $qText = is_array($qData) ? ($qData['question'] ?? '') : $qData;
                if (empty(trim($qText))) continue;

                $question = PollQuestion::create([
                    'poll_id'  => $poll->id,
                    'question' => $qText,
                    'order'    => $qIndex,
                ]);

                $opts = is_array($qData) ? ($qData['options'] ?? []) : [];
                foreach ($opts as $oIndex => $optText) {
                    if (empty(trim($optText))) continue;
                    PollOption::create([
                        'poll_question_id' => $question->id,
                        'option_text'      => $optText,
                        'order'            => $oIndex,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['msg' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SA Poll updateDraft failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update poll: ' . $e->getMessage()], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────
    public function buildResults(Poll $poll): array
    {
        $results = [];

        foreach ($poll->questions as $question) {
            $totalVotes = $question->votes()->count();

            $options = [];
            foreach ($question->options as $option) {
                $count = PollVote::where('poll_question_id', $question->id)
                    ->where('poll_option_id', $option->id)
                    ->count();

                $options[] = [
                    'id'         => $option->id,
                    'text'       => $option->option_text,
                    'votes'      => $count,
                    'percentage' => $totalVotes > 0 ? round(($count / $totalVotes) * 100, 1) : 0,
                ];
            }

            $results[] = [
                'question_id'  => $question->id,
                'question'     => $question->question,
                'total_votes'  => $totalVotes,
                'options'      => $options,
            ];
        }

        return $results;
    }

    private function notifyBuildingUsers(Poll $poll, string $title, string $body): void
    {
        try {
            $building = Building::find($poll->building_id);
            if (! $building) return;

            $flats = Flat::where('building_id', $building->id)
                ->where(function ($q) {
                    $q->whereNotNull('owner_id')->orWhereNotNull('tanent_id');
                })
                ->with(['owner', 'tanent'])
                ->get();

            $dataPayload = [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'screen'       => 'Timeline',
                'params'       => json_encode([
                    'ScreenTab'   => 'Polls',
                    'poll_id'     => (string) $poll->id,
                    'building_id' => (string) $poll->building_id,
                ]),
                'title'        => $title,
                'body'         => $body,
                'sound'        => 'bellnotificationsound.wav',
                'type'         => 'POLL_NOTIFICATION',
                'poll_id'      => (string) $poll->id,
            ];

            foreach ($flats as $flat) {
                foreach (collect([$flat->owner, $flat->tanent])->filter() as $targetUser) {
                    try {
                        NotificationHelper::sendNotification(
                            $targetUser->id,
                            $title,
                            $body,
                            array_merge($dataPayload, [
                                'user_id' => (string) $targetUser->id,
                                'flat_id' => (string) $flat->id,
                            ]),
                            [
                                'from_id'     => null,
                                'flat_id'     => $flat->id,
                                'building_id' => $building->id,
                                'type'        => 'poll_notification',
                                'ios_sound'   => 'default',
                            ],
                            ['user']
                        );
                    } catch (\Exception $e) {
                        Log::error('SA Poll notification failed', ['user_id' => $targetUser->id, 'error' => $e->getMessage()]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('SA notifyBuildingUsers failed', ['poll_id' => $poll->id, 'error' => $e->getMessage()]);
        }
    }
}
