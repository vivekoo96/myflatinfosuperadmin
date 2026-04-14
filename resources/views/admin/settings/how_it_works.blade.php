@extends('layouts.admin')

@section('title') Video Tutorials @endsection

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1>Video Tutorials</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Video Tutorials</li>
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

    {{-- Add Category + Add Video Buttons --}}
    <div class="mb-3">
      <button class="btn btn-success" data-toggle="modal" data-target="#addCategoryModal">
        <i class="fa fa-folder-plus mr-1"></i> Add Category
      </button>
      <button class="btn btn-primary" data-toggle="modal" data-target="#addVideoModal">
        <i class="fa fa-plus mr-1"></i> Add Video
      </button>
    </div>

    {{-- Categories & Videos grouped --}}
    @forelse($modules as $module)
      <div class="card card-primary card-outline mb-4">
        <div class="card-header">
          <h3 class="card-title font-weight-bold">{{ $module->title }}</h3>
          <div class="card-tools">
            <button class="btn btn-sm btn-warning" data-toggle="modal"
              data-target="#editCategoryModal{{ $module->id }}">
              <i class="fa fa-edit"></i> Edit
            </button>
            <form method="POST" action="{{ route('video-tutorial.destroy-module', $module->id) }}"
              style="display:inline;" onsubmit="return confirm('Delete this category and all its videos?')">
              @csrf
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="fa fa-trash"></i> Delete
              </button>
            </form>
          </div>
        </div>
        <div class="card-body p-0">
          @if($module->videos->isEmpty())
            <div class="text-center text-muted py-4">
              <i class="fa fa-film fa-2x mb-2"></i>
              <p>No videos yet. <a href="#" data-toggle="modal" data-target="#addVideoModal"
                onclick="$('#addVideoModuleId').val('{{ $module->id }}')">Add one</a></p>
            </div>
          @else
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>URL</th>
                    <th>Type</th>
                    <th>Roles</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($module->videos as $video)
                    <tr>
                      <td class="font-weight-bold">{{ $video->title }}</td>
                      <td class="text-muted small">{{ Str::limit($video->text, 60) ?: '—' }}</td>
                      <td>
                        <a href="{{ $video->video_url }}" target="_blank" class="text-truncate d-block" style="max-width:160px;">
                          {{ $video->video_url }}
                        </a>
                      </td>
                      <td><span class="badge badge-info">{{ ucfirst($video->video_type) }}</span></td>
                      <td>
                        @forelse($video->interfaces ?? [] as $role)
                          <span class="badge badge-secondary">{{ $role }}</span>
                        @empty <span class="text-muted">—</span>
                        @endforelse
                      </td>
                      <td style="white-space:nowrap;">
                        <button class="btn btn-sm btn-info" data-toggle="modal"
                          data-target="#editVideoModal{{ $video->id }}">
                          <i class="fa fa-edit"></i>
                        </button>
                        <form method="POST" action="{{ route('video-tutorial.destroy', $video->id) }}"
                          style="display:inline;" onsubmit="return confirm('Delete this video?')">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- Edit Category Modal --}}
      <div class="modal fade" id="editCategoryModal{{ $module->id }}" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST" action="{{ route('video-tutorial.update-module', $module->id) }}">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Category Name <span class="text-danger">*</span></label>
                  <input type="text" name="title" class="form-control" value="{{ $module->title }}" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-info">Update</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      {{-- Edit Video Modals --}}
      @foreach($module->videos as $video)
        <div class="modal fade" id="editVideoModal{{ $video->id }}" tabindex="-1">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <form method="POST" action="{{ route('video-tutorial.update', $video->id) }}">
                @csrf
                <div class="modal-header">
                  <h5 class="modal-title">Edit Video</h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select name="module_id" class="form-control" required>
                      @foreach($modules as $m)
                        <option value="{{ $m->id }}" {{ $video->module_id == $m->id ? 'selected' : '' }}>
                          {{ $m->title }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ $video->title }}" required>
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea name="text" class="form-control" rows="2">{{ $video->text }}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Video URL <span class="text-danger">*</span></label>
                    <input type="text" name="video_url" class="form-control" value="{{ $video->video_url }}" required>
                  </div>
                  <div class="form-group">
                    <label>Video Type <span class="text-danger">*</span></label>
                    <select name="video_type" class="form-control" required>
                      <option value="youtube" selected>YouTube</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Visible to Roles</label>
                    @foreach(['user', 'BA', 'president', 'secretary'] as $role)
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="edit_role_{{ $video->id }}_{{ $role }}"
                          name="interfaces[]" value="{{ $role }}"
                          {{ in_array($role, $video->interfaces ?? []) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="edit_role_{{ $video->id }}_{{ $role }}">{{ $role }}</label>
                      </div>
                    @endforeach
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-info">Update Video</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      @endforeach

    @empty
      <div class="alert alert-info">
        No categories yet. Click <strong>Add Category</strong> to create one.
      </div>
    @endforelse

  </div>
</section>

{{-- Add Category Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('video-tutorial.store-module') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Category</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-0">
            <label>Category Name <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Getting Started" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Create</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Add Video Modal --}}
<div class="modal fade" id="addVideoModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="{{ route('video-tutorial.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Video Tutorial</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Category <span class="text-danger">*</span></label>
            <select name="module_id" id="addVideoModuleId" class="form-control" required>
              <option value="">Select Category</option>
              @foreach($modules as $m)
                <option value="{{ $m->id }}">{{ $m->title }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Intro to the Platform" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="text" class="form-control" rows="2" placeholder="A quick overview of..."></textarea>
          </div>
          <div class="form-group">
            <label>Video URL <span class="text-danger">*</span></label>
            <input type="text" name="video_url" class="form-control" placeholder="https://youtu.be/..." required>
          </div>
          <div class="form-group">
            <label>Video Type <span class="text-danger">*</span></label>
            <select name="video_type" class="form-control" required>
              <option value="youtube" selected>YouTube</option>
            </select>
          </div>
          <div class="form-group">
            <label>Visible to Roles</label>
            @foreach(['user', 'BA', 'president', 'secretary'] as $role)
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="add_role_{{ $role }}"
                  name="interfaces[]" value="{{ $role }}">
                <label class="custom-control-label" for="add_role_{{ $role }}">{{ $role }}</label>
              </div>
            @endforeach
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Video</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
