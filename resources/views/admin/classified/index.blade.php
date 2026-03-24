@extends('layouts.admin')


@section('title')
    Classified List
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-12">
                @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
                @endif
                @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
                @endif
            </div>
          <div class="col-sm-6">
            <h1>Classified List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Classifieds</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <button class="btn btn-sm btn-success right" data-toggle="modal" data-target="#addModal">Add New Classified</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S No</th>
                    <th>Posted By</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Desc</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                    <?php $i = 0; ?>
                  @forelse($classifieds as $item)
                  <?php $i++; ?>
                  <tr>
                    <td>{{$i}}</td>
                    <td><a href="{{url('customer',$item->user_id)}}">{{$item->user->name}}</a></td>
                    <td>{{$item->category}}</td>
                     
                    <td>{{$item->title}}</td>
                    <td>
                        @php
                            $photos = is_string($item->photos)
                                ? json_decode($item->photos, true)
                                : $item->photos;
                        @endphp
                    
                        @if(!empty($photos) && count($photos) > 0)
                            <a href="{{ $photos[0]['photo'] }}"
                               target="_blank"
                               class="text-primary" style="text-decoration:underline">
                                View Image
                            </a>
                        @else
                            <span>No Image</span>
                        @endif
                    </td>
                    <td>{{$item->desc}}</td>
                    <td>{{$item->status}}</td>
                    <td>
                      <a href="{{route('classified.show',$item->id)}}" target="_blank"  class="btn btn-sm btn-warning"><i class="fa fa-eye"></i></a>
                      <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal" data-id="{{$item->id}}" data-title="{{$item->title}}" data-desc="{{$item->desc}}"  
                       data-status="{{$item->status}}" data-reason="{{$item->reason}}" 
                      data-block_id="{{$item->block_id}}" data-flat_id="{{$item->flat_id}}" data-category="{{$item->category}}"><i class="fa fa-edit"></i></button>
                      @if($item->deleted_at)
                      <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#deleteModal" data-id="{{$item->id}}" data-action="restore"><i class="fa fa-undo"></i></button>
                      @else
                      <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{$item->id}}" data-action="delete"><i class="fa fa-trash"></i></button>
                      @endif
                    </td>

                  </tr>
                  @empty
                  @endforelse
                  </tbody>
                </table>
                </div>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<!-- Add Modal -->

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Classified</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('classified.store')}}" method="post" class="add-form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="error"></div>
          <!--<div class="form-group">-->
          <!--  <label for="name" class="col-form-label">Category:</label>-->
          <!--  <select name="category" id="category" class="form-control" required>-->
          <!--      <option value="All Buildings">All Buildings</option>-->
          <!--  </select>-->
          <!--</div>-->
          <div class="form-group">
            <label class="col-form-label">Send To:</label>
            <div class="mt-2">
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="all_buildings" name="notification_type" value="all" class="custom-control-input" checked required>
                <label class="custom-control-label" for="all_buildings">All Buildings</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="selected_buildings_radio" name="notification_type" value="selected" class="custom-control-input" required>
                <label class="custom-control-label" for="selected_buildings_radio">Selected Buildings</label>
              </div>
            </div>
          </div>
          
          <div class="form-group building-selection" style="display:none;">
            <label for="selected_buildings" class="col-form-label">Select Buildings: <span class="text-danger">*</span></label>
            <select name="selected_buildings[]" id="selected_buildings" class="form-control select2" multiple>
              @foreach(\App\Models\Building::orderBy('name')->get() as $building)
                <option value="{{ $building->id }}" {{ (old('selected_buildings') && in_array($building->id, old('selected_buildings'))) ? 'selected' : '' }}>
                  {{ $building->name }}
                </option>
              @endforeach
            </select>
            <small class="text-muted">Select one or more buildings from the list</small>
          </div>

          <div class="form-group">
            <label for="name" class="col-form-label">Title:</label>
             <input type="text" name="title" id="title" class="form-control"
       placeholder="Title"
       minlength="2"
       maxlength="500"
       pattern="^[A-Za-z\s]{2,500}$"
       required
       oninvalid="this.setCustomValidity('Name must be 2–500 letters only. Numbers or special characters are not allowed.')"
       oninput="this.setCustomValidity('')">
       <!--     <input type="text" name="title" id="title" class="form-control"-->
       <!--placeholder="Title"-->
       <!--pattern="^[A-Za-z\s]{2,30}$"-->
       <!--required>-->
          <!--  <input type="text" name="title" id="title" class="form-control" placeholder="Title" minlength="2" maxlength="100" pattern="[A-Za-z\s]+" onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32" required>-->
          <!--</div>-->
          <div class="form-group">
            <label for="name" class="col-form-label">Description:</label>
            <textarea name="desc" id="desc" class="form-control" minlength="2" maxlength="500" placeholder="Enter description (10-500 characters)" required></textarea>
          </div>
          <!--<div class="form-group">-->
          <!--  <label for="name" class="col-form-label">Status:</label>-->
          <!--  <select name="status" id="status" class="form-control" required>-->
          <!--      <option value="Approved">Approved</option>-->
          <!--  </select>-->
          <!--</div>-->
          
          <!-- Hidden Reason field -->
        <div class="form-group" id="reasonBox" style="display: none;">
          <label for="reason" class="col-form-label">Reason:</label>
          <textarea name="reason" id="reason" class="form-control" rows="3"></textarea>
        </div>
        
        <div class="form-group">
            <label for="name" class="col-form-label">Photos: <small>(Optional - Max 2MB per image, JPG/PNG/JPEG only)</small></label>
            <input type="file" name="photos[]" id="photos" class="form-control" accept="image/jpeg,image/jpg,image/png" multiple>
            <!--<small class="form-text text-muted">Optional. Image format: JPG, PNG, JPEG. Maximum size: 2MB per image.</small>-->
          </div>

          <input type="hidden" name="id" id="edit-id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="save-button">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" id="delete-button">Confirm Delete</button>
      </div>
    </div>
  </div>
</div>


@section('script')
<style>
  .select2-results__option [class*="fa-"] {
    margin-right: 5px;
  }
  .select2-selection__rendered [class*="fa-"] {
    margin-right: 5px;
  }
  .select2-container--bootstrap4 .select2-results__option {
    padding: 6px;
  }
  /* Show dropdown arrow */
  .select2-container--bootstrap4 .select2-selection--multiple::after {
    content: "\f078";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    right: 10px;
    top: 8px;
    color: #6c757d;
    pointer-events: none;
  }
  .select2-container--bootstrap4.select2-container--open .select2-selection--multiple::after {
    content: "\f077";
  }
  /* Placeholder styling */
  .select2-selection__rendered.select2-placeholder {
    color: #999;
  }
</style>

<script>
  $(document).ready(function(){
    var id = '';
    var action = '';
    var token = "{{csrf_token()}}";

    // Initialize select2 for building selection
    $('.select2').select2({
      placeholder: "Select buildings",
      allowClear: false,
      width: '100%',
      theme: 'bootstrap4',
      closeOnSelect: false,
      selectionCssClass: 'select2--large',
      dropdownCssClass: 'select2--large',
      escapeMarkup: function(markup) { return markup; },
      templateResult: function(data) {
        if (!data.id) return data.text;
        return $('<span><i class="fa fa-building"></i> ' + data.text + '</span>');
      },
      templateSelection: function(data, container) {
        if (!data.id) {
          $(container).addClass('select2-placeholder');
          return "Select buildings";
        }
        $(container).removeClass('select2-placeholder');
        return $('<span><i class="fa fa-building"></i> ' + data.text + '</span>');
      },
      language: {
        noResults: function() {
          return "No buildings found";
        }
      }
    }).on('change', function() {
      // Enable save button only if buildings are selected when in 'selected' mode
      if ($('input[name="notification_type"]:checked').val() === 'selected') {
        $('#save-button').prop('disabled', $(this).val().length === 0);
      }
    });

    // Show/hide building selection based on radio selection
    $('input[name="notification_type"]').change(function() {
      if($(this).val() === 'selected') {
        $('.building-selection').slideDown();
        $('#selected_buildings').prop('required', true);
        // Enable/disable save button based on selection
        $('#save-button').prop('disabled', $('#selected_buildings').val().length === 0);
      } else {
        $('.building-selection').slideUp();
        $('#selected_buildings').prop('required', false);
        $('#selected_buildings').val(null).trigger('change');
        // Enable save button for "All Buildings" option
       
      }
    });
    
    $('#deleteModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      id = button.data('id');
      $('#delete-id').val(id);
      action= button.data('action');
      $('#delete-button').removeClass('btn-success');
      $('#delete-button').removeClass('btn-danger');
      if(action == 'delete'){
          $('#delete-button').addClass('btn-danger');
          $('#delete-button').text('Confirm Delete');
          $('.text').text('You are going to permanently delete this item..');
      }else{
          $('#delete-button').addClass('btn-success');
          $('#delete-button').text('Confirm Restore');
          $('.text').text('You are going to restore this item..');
      }
    });

    $(document).on('click','#delete-button',function(){
      var url = "{{route('classified.destroy','')}}";
      $.ajax({
        url : url + '/' + id,
        type: "DELETE",
        data : {'_token':token,'action':action},
        success: function(data)
        {
          window.location.reload();
        }
      });
    });

    $('#addModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var edit_id = button.data('id');
      $('#edit-id').val(button.data('id'));
      $('#category').val(button.data('category'));
      $('#title').val(button.data('title'));
      $('#desc').val(button.data('desc'));
      $('#status').val(button.data('status'));
      $('#reason').val(button.data('reason'));
      $('#image2').attr('src',button.data('image'));
      $('.modal-title').text('Add New Classified');
      
      // Reset building selection
      $('input[name="notification_type"][value="all"]').prop('checked', true).trigger('change');
      $('#selected_buildings').val(null).trigger('change');

      // If editing existing classified, fetch buildings
      if (edit_id) {
        $.ajax({
          url: '/admin/classifieds/' + edit_id + '/buildings',
          type: 'GET',
          success: function(response) {
            if (response.notification_type === 'selected' && response.buildings.length > 0) {
              $('input[name="notification_type"][value="selected"]').prop('checked', true).trigger('change');
              $('#selected_buildings').val(response.buildings).trigger('change');
            } else {
              $('input[name="notification_type"][value="all"]').prop('checked', true).trigger('change');
            }
          }
        });

        $('.modal-title').text('Update Classified');
        $('#photos').siblings('.form-text').text('Optional. Image format: JPG, PNG, JPEG. Maximum size: 2MB per image. (Keep existing images if not selected)');
      } else {
        $('#photos').siblings('.form-text').text('Optional. Image format: JPG, PNG, JPEG. Maximum size: 2MB per image.');
      }
      
      // Clear any previous validation messages
      $('.error').html('');
      
    });

    // Comprehensive form validation
  

    // Real-time validation
 
    $('#photos').on('change', function() {
      var files = this.files;
      var errorMessage = '';
      var isEditMode = $('#edit-id').val() !== '';
      
      if (files.length === 0) {
        $('.error').html('');
        return;
      }

      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        var maxSize = 2 * 1024 * 1024; // 2MB

        if (!allowedTypes.includes(file.type)) {
          errorMessage = 'Invalid format. Only image files are accepted (JPG, PNG, JPEG).';
          break;
        }
        if (file.size > maxSize) {
          var fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
          errorMessage = 'File size too large. Maximum 2MB allowed. Current: ' + fileSizeMB + 'MB.';
          break;
        }
      }

      if (errorMessage) {
        $('.error').html('<div class="alert alert-danger">' + errorMessage + '</div>');
        $('#save-button').prop('disabled', true);
      } else {
        $('.error').html('');
        validateForm();
      }
    });

    // Form submission validation
    $('.add-form').on('submit', function(e) {
      if (!validateForm()) {
        e.preventDefault();
        $('.error').html('<div class="alert alert-danger">Please fix all validation errors before submitting.</div>');
        return false;
      }
      
      // Additional validation for building selection
      if ($('input[name="notification_type"]:checked').val() === 'selected') {
        if (!$('#selected_buildings').val() || $('#selected_buildings').val().length === 0) {
          e.preventDefault();
          $('.error').html('<div class="alert alert-danger">Please select at least one building when using Selected Buildings option.</div>');
          return false;
        }
      }
    });

    // Initialize validation on modal open
    // $('#addModal').on('shown.bs.modal', function() {
    //   validateForm();
    // });

  });
</script>

@endsection

@endsection
