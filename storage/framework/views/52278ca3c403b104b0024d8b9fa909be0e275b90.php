<?php $__env->startSection('title'); ?>
    Classified List
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-12">
                <?php if(session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?php echo e(session()->get('error')); ?>

                </div>
                <?php endif; ?>
                <?php if(session()->has('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('success')); ?>

                </div>
                <?php endif; ?>
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
                    <th>Desc</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                    <?php $i = 0; ?>
                  <?php $__empty_1 = true; $__currentLoopData = $classifieds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <?php $i++; ?>
                  <tr>
                    <td><?php echo e($i); ?></td>
                    <td><a href="<?php echo e(url('customer',$item->user_id)); ?>"><?php echo e($item->user->name); ?></a></td>
                    <td><?php echo e($item->category); ?></td>
                    <td><?php echo e($item->title); ?></td>
                    <td><?php echo e($item->desc); ?></td>
                    <td><?php echo e($item->status); ?></td>
                    <td>
                      <a href="<?php echo e(route('classified.show',$item->id)); ?>" target="_blank"  class="btn btn-sm btn-warning"><i class="fa fa-eye"></i></a>
                      <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal" data-id="<?php echo e($item->id); ?>" data-title="<?php echo e($item->title); ?>" data-desc="<?php echo e($item->desc); ?>"  
                       data-status="<?php echo e($item->status); ?>" data-reason="<?php echo e($item->reason); ?>" 
                      data-block_id="<?php echo e($item->block_id); ?>" data-flat_id="<?php echo e($item->flat_id); ?>" data-category="<?php echo e($item->category); ?>"><i class="fa fa-edit"></i></button>
                      <?php if($item->deleted_at): ?>
                      <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo e($item->id); ?>" data-action="restore"><i class="fa fa-undo"></i></button>
                      <?php else: ?>
                      <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo e($item->id); ?>" data-action="delete"><i class="fa fa-trash"></i></button>
                      <?php endif; ?>
                    </td>

                  </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <?php endif; ?>
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
      <form action="<?php echo e(route('classified.store')); ?>" method="post" class="add-form" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <div class="error"></div>
          <!--<div class="form-group">-->
          <!--  <label for="name" class="col-form-label">Category:</label>-->
          <!--  <select name="category" id="category" class="form-control" required>-->
          <!--      <option value="All Buildings">All Buildings</option>-->
          <!--  </select>-->
          <!--</div>-->
          <div class="form-group">
            <label class="col-form-label">Send Notification To:</label>
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
              <?php $__currentLoopData = \App\Models\Building::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($building->id); ?>"><?php echo e($building->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <small class="text-muted">Select one or more buildings from the list</small>
          </div>

          <div class="form-group">
            <label for="name" class="col-form-label">Title:</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" minlength="5" maxlength="100" pattern="[A-Za-z\s]+" onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32" required>
            <small class="form-text text-muted">Minimum 5 characters, Maximum 100 characters. Alphabets only.</small>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Description:</label>
            <textarea name="desc" id="desc" class="form-control" minlength="10" maxlength="500" placeholder="Enter description (10-500 characters)" required></textarea>
            <small class="form-text text-muted">Minimum 10 characters, Maximum 500 characters.</small>
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
            <label for="name" class="col-form-label">Photos: <small>(Max 2MB per image, JPG/PNG/JPEG only)</small></label>
            <input type="file" name="photos[]" id="photos" class="form-control" accept="image/jpeg,image/jpg,image/png" multiple>
            <small class="form-text text-muted">Image format: JPG, PNG, JPEG. Maximum size: 2MB per image.</small>
          </div>

          <input type="hidden" name="id" id="edit-id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="save-button" disabled>Save</button>
        </div>
      </form>
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


<?php $__env->startSection('script'); ?>


<script>
  $(document).ready(function(){
    var id = '';
    var action = '';
    var token = "<?php echo e(csrf_token()); ?>";

    // Initialize select2 for building selection
    $('.select2').select2({
      placeholder: "Select buildings",
      allowClear: true,
      width: '100%',
      theme: 'bootstrap4',
      closeOnSelect: false,
      selectionCssClass: 'select2--large',
      dropdownCssClass: 'select2--large',
      language: {
        noResults: function() {
          return "No buildings found";
        }
      }
    });

    // Show/hide building selection based on radio selection
    $('input[name="notification_type"]').change(function() {
      if($(this).val() === 'selected') {
        $('.building-selection').slideDown();
        $('#selected_buildings').prop('required', true);
      } else {
        $('.building-selection').slideUp();
        $('#selected_buildings').prop('required', false);
        $('#selected_buildings').val(null).trigger('change');
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
      var url = "<?php echo e(route('classified.destroy','')); ?>";
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
        $('#photos').removeAttr('required');
        $('#photos').siblings('.form-text').text('Image format: JPG, PNG, JPEG. Maximum size: 2MB per image. (Optional - keep existing images if not selected)');
      } else {
        $('#photos').attr('required', true);
        $('#photos').siblings('.form-text').text('Image format: JPG, PNG, JPEG. Maximum size: 2MB per image.');
      }
      
      // Clear any previous validation states
      $('#photos, #title, #desc').removeClass('is-valid is-invalid');
      $('.error').html('');
      
    });

    // Comprehensive form validation
    function validateForm() {
      var isValid = true;
      var title = $('#title').val().trim();
      var desc = $('#desc').val().trim();
      var photos = $('#photos')[0].files;
      var isEditMode = $('#edit-id').val() !== '';

      // Title validation
      if (title.length < 5 || title.length > 100) {
        isValid = false;
        $('#title').addClass('is-invalid');
      } else if (!/^[A-Za-z\s]+$/.test(title)) {
        isValid = false;
        $('#title').addClass('is-invalid');
      } else {
        $('#title').removeClass('is-invalid').addClass('is-valid');
      }

      // Description validation
      if (desc.length < 10 || desc.length > 500) {
        isValid = false;
        $('#desc').addClass('is-invalid');
      } else {
        $('#desc').removeClass('is-invalid').addClass('is-valid');
      }

      // Photo validation - Skip for edit mode
      if (!isEditMode) {
        if (photos.length === 0) {
          isValid = false;
          $('#photos').addClass('is-invalid');
        } else {
          var validFiles = true;
          for (var i = 0; i < photos.length; i++) {
            var file = photos[i];
            var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            var maxSize = 2 * 1024 * 1024; // 2MB

            if (!allowedTypes.includes(file.type)) {
              validFiles = false;
              break;
            }
            if (file.size > maxSize) {
              validFiles = false;
              break;
            }
          }
          
          if (validFiles) {
            $('#photos').removeClass('is-invalid').addClass('is-valid');
          } else {
            isValid = false;
            $('#photos').addClass('is-invalid');
          }
        }
      } else {
        // In edit mode, only validate if files are selected
        if (photos.length > 0) {
          var validFiles = true;
          for (var i = 0; i < photos.length; i++) {
            var file = photos[i];
            var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            var maxSize = 2 * 1024 * 1024; // 2MB

            if (!allowedTypes.includes(file.type)) {
              validFiles = false;
              break;
            }
            if (file.size > maxSize) {
              validFiles = false;
              break;
            }
          }
          
          if (validFiles) {
            $('#photos').removeClass('is-invalid').addClass('is-valid');
          } else {
            isValid = false;
            $('#photos').addClass('is-invalid');
          }
        } else {
          // No files selected in edit mode is OK
          $('#photos').removeClass('is-invalid is-valid');
        }
      }

      // Enable/disable save button
      $('#save-button').prop('disabled', !isValid);
      return isValid;
    }

    // Real-time validation
    $('#title, #desc').on('input', validateForm);
    $('#photos').on('change', function() {
      var files = this.files;
      var errorMessage = '';
      var isEditMode = $('#edit-id').val() !== '';
      
      if (files.length === 0) {
        if (!isEditMode) {
          $('.error').html('<div class="alert alert-danger">Please select at least one image.</div>');
        } else {
          $('.error').html('');
        }
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
        $(this).addClass('is-invalid');
        $('#save-button').prop('disabled', true);
      } else {
        $('.error').html('');
        $(this).removeClass('is-invalid').addClass('is-valid');
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
    });

    // Initialize validation on modal open
    $('#addModal').on('shown.bs.modal', function() {
      validateForm();
    });

  });
</script>

<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/vivekpatel/Herd/newupdatedcode/resources/views/admin/classified/index.blade.php ENDPATH**/ ?>