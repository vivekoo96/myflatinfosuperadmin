<?php $__env->startSection('title'); ?>
    Ad List
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
            <h1>Ads</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Ads</li>
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
                <button class="btn btn-sm btn-success right" data-toggle="modal" data-target="#addModal">Add New Ad</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S No</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>From Time</th>
                    <th>To Time</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                    <?php $i = 0; ?>
                  <?php $__empty_1 = true; $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <?php $i++; ?>
                  <tr>
                    <td><?php echo e($i); ?></td>
                    <td><img src="<?php echo e($ad->image); ?>" style="width:40px"></td>
                    <td><?php echo e($ad->name); ?></td>
                    <td><?php echo e($ad->from_time ? \Carbon\Carbon::parse($ad->from_time)->format('d M Y, h:i A') : '-'); ?></td>
                    <td><?php echo e($ad->to_time ? \Carbon\Carbon::parse($ad->to_time)->format('d M Y, h:i A') : '-'); ?></td>
                    <td style="word-break:break-all; white-space:pre-line; max-width:220px;"><?php echo e($ad->link); ?></td>
                    <td><?php echo e($ad->status); ?></td>
                    <td>
                      <!--<a href="<?php echo e(route('ads.show',$ad->id)); ?>" target="_blank"  class="btn btn-sm btn-warning"><i class="fa fa-eye"></i></a>-->
                      <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal" data-id="<?php echo e($ad->id); ?>" 
                      data-name="<?php echo e($ad->name); ?>" data-link="<?php echo e($ad->link); ?>" data-status="<?php echo e($ad->status); ?>" data-image="<?php echo e($ad->image); ?>" 
                      data-from_time="<?php echo e($ad->from_time); ?>" data-to_time="<?php echo e($ad->to_time); ?>"
                      data-notification_type="<?php echo e($ad->notification_type); ?>"><i class="fa fa-edit"></i></button>
                      <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo e($ad->id); ?>" data-action="delete"><i class="fa fa-trash"></i></button>
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
        <h5 class="modal-title" id="exampleModalLabel">Add New Ad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo e(route('ads.store')); ?>" method="post" class="add-form" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          
          <div class="form-group">
              <label for="name" class="col-form-label">Name:</label>
            <input type="text" name="name" id="name" class="form-control" minlength="3" maxlength="40" placeholder="Name" required>
             </div>
               <div class="form-group">
            <label for="name" class="col-form-label">Link:</label>
            <input type="text" name="link" id="link" class="form-control" placeholder="Link" required>
          </div>
          <div class="form-group">
            <label for="from_time" class="col-form-label">From Date & Time:</label>
            <input type="datetime-local" name="from_time" id="from_time" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="to_time" class="col-form-label">To Date & Time:</label>
            <input type="datetime-local" name="to_time" id="to_time" class="form-control" required>
          </div>
            
         
        
          
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
              <?php $__currentLoopData = \App\Models\Building::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($building->id); ?>"><?php echo e($building->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <small class="text-muted">Select one or more buildings from the list</small>
          </div>

          <div class="form-group">
            <label for="name" class="col-form-label">Image:(max 2mb) <img src="<?php echo e($building->image); ?>" id="image2" style="width:40px"></label>
            <input type="file" name="image" id="image" accept="image/*" class="form-control" required>
            <small class="text-muted">Supported formats: jpg, png, jpeg. Max size: 2MB.</small>
      <script>
      document.addEventListener('DOMContentLoaded', function() {
        var imageInput = document.getElementById('image');
        var imagePreview = document.getElementById('image2');
        if(imageInput && imagePreview) {
          imageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
              var reader = new FileReader();
              reader.onload = function(ev) {
                imagePreview.src = ev.target.result;
              }
              reader.readAsDataURL(e.target.files[0]);
            }
          });
        }
      });
      </script>
          </div>
          
          <div class="form-group">
            <label for="status" class="col-form-label">Status:</label>
            <select name="status" class="form-control" id="status" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
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

    // Initialize select2 for building selection with improved UX
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
      $('.modal-title').text('Are you sure ?');
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
      var url = "<?php echo e(route('ads.destroy','')); ?>";
      $.ajax({
        url : url + '/' + id,
        type: "DELETE",
        data : {'_token':token,'id':id,'action':action},
        success: function(data)
        {
          window.location.reload();
        }
      });
    });

    $('#addModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var edit_id = button.data('id');
      $('#edit-id').val(edit_id);
      $('#name').val(button.data('name'));
      $('#link').val(button.data('link'));
      $('#status').val(button.data('status'));
      
      // Reset building selection
      $('input[name="notification_type"][value="all"]').prop('checked', true).trigger('change');
      $('#selected_buildings').val(null).trigger('change');
      
      // Format the datetime for the datetime-local input (local time, not UTC)
      function formatDateForInput(dateString) {
        if (!dateString) return '';
        var date = new Date(dateString);
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        var hours = ('0' + date.getHours()).slice(-2);
        var minutes = ('0' + date.getMinutes()).slice(-2);
        return year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
      }
      var fromTime = button.data('from_time');
      var toTime = button.data('to_time');
      $('#from_time').val(formatDateForInput(fromTime));
      $('#to_time').val(formatDateForInput(toTime));

      // If editing existing ad, fetch buildings
      if (edit_id) {
        $.ajax({
          url: '/admin/ads/' + edit_id + '/buildings',
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
      }
      $('.modal-title').text('Add New Ad');
      var imagePath = button.data('image');
      if(imagePath && imagePath !== '') {
        $('#image2').attr('src', imagePath.startsWith('http') ? imagePath : ('/images/' + imagePath));
      } else {
        $('#image2').attr('src', '/images/default-ad.png');
      }
      $('#image').attr('required',true);
      if(edit_id){
          $('.modal-title').text('Update Ad');
          $('#image').attr('required',false);
      }
    });
    // Frontend datetime validation
    $('.add-form').on('submit', function(e) {
      var fromTime = new Date($('#from_time').val());
      var toTime = new Date($('#to_time').val());
      
      if (fromTime && toTime && fromTime >= toTime) {
        alert('Start Date & Time must be before End Date & Time');
        e.preventDefault();
        return false;
      }

      // Also validate that dates are not in the past
      var now = new Date();
      if (fromTime < now) {
        alert('Start Date & Time cannot be in the past');
        e.preventDefault();
        return false;
      }

      // Validate building selection
      if ($('input[name="notification_type"]:checked').val() === 'selected') {
        if (!$('#selected_buildings').val() || $('#selected_buildings').val().length === 0) {
          alert('Please select at least one building when using Selected Buildings option.');
          e.preventDefault();
          return false;
        }
      }
    });
    
    $('.status').bootstrapSwitch('state');
        $('.status').on('switchChange.bootstrapSwitch',function () {
            var id = $(this).data('id');
            $.ajax({
                url : "<?php echo e(url('update-ads-status')); ?>",
                type: "post",
                data : {'_token':token,'id':id,},
                success: function(data)
                {
                  //
                }
            });
        });

  });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/myflatin/dev.superadmin.myflatinfo.com/resources/views/admin/ads/index.blade.php ENDPATH**/ ?>