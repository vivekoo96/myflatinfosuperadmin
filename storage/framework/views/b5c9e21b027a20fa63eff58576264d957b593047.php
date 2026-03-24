<?php $__env->startSection('title'); ?>
    Admin Details
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Admin Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Admin Details</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="<?php echo e($customer->photo); ?>"
                       alt="User profile picture">
                </div>
                <h3 class="profile-username text-center"><?php echo e($customer->name); ?></h3>

                <p class="text-muted text-center"><?php echo e($customer->role); ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Status</b> <a class="float-right">
                        <input type="checkbox" name="my-checkbox" class="status" data-id="<?php echo e($customer->id); ?>" data-bootstrap-switch data-on-text="Active" 
                        data-off-text="Inactive" <?php echo e($customer->status == 'Active' ? 'checked' : ''); ?>>
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#basic" data-toggle="tab">Basic Info</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="basic">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                          <tr style="display:none;">
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                          </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Name</td>
                          <td><?php echo e($customer->name); ?></td>
                          <td>Role</td>
                          <td><?php echo e($customer->role); ?></td>
                        </tr>

                        <tr>
                          <td>Email</td>
                          <td><?php echo e($customer->email); ?></td>
                          <td>Phone</td>
                          <td><?php echo e($customer->phone); ?></td>
                        </tr>
                        <tr>
                          <td>Gender</td>
                          <td><?php echo e($customer->gender); ?></td>
                          <td>City</td>
                          <td><?php echo e($customer->city->name); ?></td>
                        </tr>
                        <tr>
                          <td>Address</td>
                          <td colspan="3"><?php echo e($customer->address); ?></td>
                        </tr>
                        <tr>
                          <td>Created at</td>
                          <td><?php echo e($customer->created_at); ?></td>
                          <td>Updated at</td>
                          <td><?php echo e($customer->updated_at); ?></td>
                        </tr>
                        
                      </tbody>
                    </table>
                    </div>
                  </div>
                  <!-- /.tab-pane -->
                  
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    
    


<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function(){
        var id = '';
        var action = '';
        var token = "<?php echo e(csrf_token()); ?>";
        
        $('.status').bootstrapSwitch('state');
        $('.status').on('switchChange.bootstrapSwitch',function () {
            var id = $(this).data('id');
            $.ajax({
                url : "<?php echo e(url('update-user-status')); ?>",
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/vivekpatel/Herd/newupdatedcode/resources/views/admin/admin/show.blade.php ENDPATH**/ ?>