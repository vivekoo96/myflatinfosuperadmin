@extends('layouts.admin')

@section('title') Guided Video Tutorials @endsection

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1>Guided Video Tutorials</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Guide Videos</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('error') }}
      </div>
    @endif

    {{-- Filter + Add --}}
    <div class="card card-outline card-secondary mb-3">
      <div class="card-body py-2">
        <form method="GET" class="form-inline">
          <label class="mr-2">Filter by App:</label>
          <select name="category" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
            <option value="">All</option>
            @foreach($categories as $key => $label)
              <option value="{{ $key }}" {{ $category == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
          <button type="button" class="btn btn-sm btn-success ml-auto"
            data-toggle="modal" data-target="#addVideoModal">
            <i class="fa fa-plus"></i> Add Video
          </button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3 class="card-title">All Tutorial Videos</h3></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="example1">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Thumbnail</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($videos as $i => $video)
                <tr class="{{ $video->deleted_at ? 'text-muted' : '' }}">
                  <td>{{ $i + 1 }}</td>
                  <td>
                    <strong>{{ $video->title }}</strong>
                    @if($video->description)
                      <br><small class="text-muted">{{ Str::limit($video->description, 60) }}</small>
                    @endif
                    @if($video->deleted_at)
                      <span class="badge badge-dark ml-1">Deleted</span>
                    @endif
                  </td>
                  <td>
                    @php
                      $catColors = ['all'=>'dark','user_app'=>'primary','security_app'=>'warning','role_app'=>'info','building_admin'=>'success'];
                    @endphp
                    <span class="badge badge-{{ $catColors[$video->category] ?? 'secondary' }}">
                      {{ $categories[$video->category] ?? $video->category }}
                    </span>
                  </td>
                  <td>
                    @if($video->youtube_id)
                      <img src="{{ $video->thumbnail }}" alt="thumb"
                        style="width:100px;height:60px;object-fit:cover;border-radius:4px;">
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td>
                    <span class="badge {{ $video->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                      {{ ucfirst($video->status) }}
                    </span>
                  </td>
                  <td><small>{{ $video->creator ? $video->creator->name : '—' }}</small></td>
                  <td><small>{{ $video->created_at->format('d M Y') }}</small></td>
                  <td style="white-space:nowrap;">
                    @if(!$video->deleted_at)
                      <button class="btn btn-sm btn-warning btn-edit-video"
                        data-id="{{ $video->id }}"
                        data-title="{{ $video->title }}"
                        data-description="{{ $video->description }}"
                        data-category="{{ $video->category }}"
                        data-youtube="{{ $video->youtube_link }}"
                        data-status="{{ $video->status }}"
                        title="Edit">
                        <i class="fa fa-edit"></i>
                      </button>
                      <button class="btn btn-sm btn-danger btn-delete-video"
                        data-id="{{ $video->id }}" data-action="delete" title="Delete">
                        <i class="fa fa-trash"></i>
                      </button>
                    @else
                      <button class="btn btn-sm btn-success btn-delete-video"
                        data-id="{{ $video->id }}" data-action="restore" title="Restore">
                        <i class="fa fa-undo"></i>
                      </button>
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center text-muted py-3">No videos found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</section>

{{-- Add / Edit Modal --}}
<div class="modal fade" id="addVideoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="videoModalTitle">Add Tutorial Video</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form method="POST" action="{{ route('guide-video.store') }}" id="videoForm">
        @csrf
        <input type="hidden" name="id" id="videoId">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="videoTitle" class="form-control"
                  placeholder="e.g. How to raise an issue" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select name="status" id="videoStatus" class="form-control">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Description <small class="text-muted">(optional)</small></label>
            <textarea name="description" id="videoDesc" class="form-control" rows="2"
              placeholder="Brief description of what this video covers..."></textarea>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Category <span class="text-danger">*</span></label>
                <select name="category" id="videoCategory" class="form-control" required>
                  <option value="">Select App</option>
                  @foreach($categories as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                  @endforeach
                </select>
                <small class="form-text text-muted">
                  Videos will only appear in the selected app.
                </small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>YouTube Link <span class="text-danger">*</span></label>
                <input type="url" name="youtube_link" id="videoYoutube" class="form-control"
                  placeholder="https://www.youtube.com/watch?v=..." required>
              </div>
            </div>
          </div>

          {{-- Preview --}}
          <div id="previewBox" class="d-none mt-2">
            <label>Preview</label>
            <div class="ratio" style="position:relative;padding-bottom:30%;height:0;overflow:hidden;max-width:400px;">
              <iframe id="previewFrame" src="" frameborder="0"
                style="position:absolute;top:0;left:0;width:100%;height:100%;"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Video</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Confirm Delete Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmTitle">Confirm</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="confirmBody">Are you sure?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
        <button class="btn btn-sm" id="btnConfirmAction">Confirm</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
$(function () {

  // ── YouTube preview ────────────────────────────────────────
  $('#videoYoutube').on('input', function () {
    var url = $(this).val();
    var match = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    if (match) {
      $('#previewFrame').attr('src', 'https://www.youtube.com/embed/' + match[1]);
      $('#previewBox').removeClass('d-none');
    } else {
      $('#previewFrame').attr('src', '');
      $('#previewBox').addClass('d-none');
    }
  });

  // ── Reset modal on open (only for Add, not Edit) ──────────
  var isEditing = false;

  $('#addVideoModal').on('show.bs.modal', function () {
    if (isEditing) { isEditing = false; return; }
    $('#videoModalTitle').text('Add Tutorial Video');
    $('#videoForm')[0].reset();
    $('#videoId').val('');
    $('#previewBox').addClass('d-none');
    $('#previewFrame').attr('src', '');
  });

  // ── Edit button ────────────────────────────────────────────
  $(document).on('click', '.btn-edit-video', function () {
    isEditing = true;
    $('#videoModalTitle').text('Edit Tutorial Video');
    $('#videoId').val($(this).data('id'));
    $('#videoTitle').val($(this).data('title'));
    $('#videoDesc').val($(this).data('description'));
    $('#videoCategory').val($(this).data('category'));
    $('#videoYoutube').val($(this).data('youtube'));
    $('#videoStatus').val($(this).data('status'));
    $('#videoYoutube').trigger('input');
    $('#addVideoModal').modal('show');
  });

  // ── Delete / Restore ───────────────────────────────────────
  var pendingAction = null;

  $(document).on('click', '.btn-delete-video', function () {
    var id = $(this).data('id');
    var isRestore = $(this).data('action') === 'restore';
    $('#confirmTitle').text(isRestore ? 'Restore Video' : 'Delete Video');
    $('#confirmBody').text(isRestore ? 'Restore this video?' : 'Delete this video? It will be hidden from all apps.');
    $('#btnConfirmAction')
      .removeClass()
      .addClass('btn btn-sm ' + (isRestore ? 'btn-success' : 'btn-danger'))
      .text(isRestore ? 'Restore' : 'Delete');
    pendingAction = function () {
      $.ajax({
        url: '/guide-video/' + id,
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', _method: 'DELETE', action: isRestore ? 'restore' : 'delete' },
        success: function () { location.reload(); },
        error: function (xhr) {
          var json = xhr.responseJSON;
          var msg = (json && json.error) ? json.error
                  : (json && json.message) ? json.message
                  : 'Failed.';
          alert(msg);
        }
      });
    };
    $('#confirmModal').modal('show');
  });

  $('#btnConfirmAction').on('click', function () {
    if (pendingAction) pendingAction();
    $('#confirmModal').modal('hide');
  });

});
</script>
@endsection
