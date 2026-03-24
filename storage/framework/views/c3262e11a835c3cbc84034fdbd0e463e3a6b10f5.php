<?php $__env->startSection('title'); ?>
    Setting
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Settings</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Setting</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                Business Setting
              </div>
              <div class="card-body">
                <?php if(session()->has('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session()->get('success')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <?php echo e(session()->get('error')); ?>

                    </div>
                <?php endif; ?>
                <form action="<?php echo e(route('setting.store')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>BUSINESS NAME</label>
                                <input type="text" class="form-control" name="bussiness_name" minlength="3" maxlength="100" value="<?php echo e($setting->bussiness_name); ?>" 
                                onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32" required>
                                <small class="form-text text-muted">Minimum 3 characters required. Maximum 100 characters allowed.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Logo <small>(max 2mb)</small> <img src="<?php echo e($setting->logo); ?>" width="30px;"></label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Favicon <small>(max 2mb)</small> <img src="<?php echo e($setting->favicon); ?>" width="30px;"></label>
                                <input type="file" class="form-control" name="favicon" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Msg91 Key</label>
                                <input type="text" class="form-control" name="msg91_key" value="<?php echo e($setting->msg91_key); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Msg91 Sender</label>
                                <input type="text" class="form-control" name="msg91_sender" value="<?php echo e($setting->msg91_sender); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Msg91 Flow Id</label>
                                <input type="text" class="form-control" name="msg91_flow_id" value="<?php echo e($setting->msg91_flow_id); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Razorpay Key</label>
                                <input type="text" class="form-control" name="razorpay_key" value="<?php echo e($setting->razorpay_key); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Razorpay Secret</label>
                                <input type="text" class="form-control" name="razorpay_secret" value="<?php echo e($setting->razorpay_secret); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>FCM Key</label>
                                <input type="text" class="form-control" name="fcm_key" value="<?php echo e($setting->fcm_key); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Google map api key</label>
                                <input type="text" class="form-control" name="google_map_api_key" value="<?php echo e($setting->google_map_api_key); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Call Support Number</label>
                                <input type="text" class="form-control" name="call_support_number" minlength="10" maxlength="10" value="<?php echo e($setting->call_support_number); ?>" 
                                minlength="10" maxlength="10"  
                                      onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Whatsapp Support Number</label>
                                <input type="text" class="form-control" name="whatsapp_support_number" value="<?php echo e($setting->whatsapp_support_number); ?>" 
                                minlength="10" maxlength="10"  
                                      onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pagination</label>
                                <input type="text" class="form-control" name="pagination" value="<?php echo e($setting->pagination); ?>" 
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-block bg-gradient-primary btn-flat" value="Save">
                        </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    

<?php $__env->startSection('script'); ?>

<script>
$(document).ready(function(){
    // Business Name validation
    $('input[name="bussiness_name"]').on('input', function() {
        var businessName = $(this).val();
        var length = businessName.length;
        var messageElement = $(this).siblings('.form-text');
        
        if (length < 3) {
            messageElement.removeClass('text-muted text-success').addClass('text-danger');
            messageElement.text('Minimum 3 characters required. Currently: ' + length + ' characters.');
        } else if (length > 100) {
            messageElement.removeClass('text-muted text-success').addClass('text-danger');
            messageElement.text('Maximum 100 characters allowed. Currently: ' + length + ' characters.');
        } else {
            messageElement.removeClass('text-danger').addClass('text-success');
            messageElement.text('Valid length: ' + length + '/100 characters.');
        }
    });

    // Form validation before submit
    $('form').on('submit', function(e) {
        var businessName = $('input[name="bussiness_name"]').val().trim();
        
        if (businessName.length < 3) {
            e.preventDefault();
            alert('Business Name must be at least 3 characters long.');
            return false;
        }
        
        if (businessName.length > 100) {
            e.preventDefault();
            alert('Business Name cannot exceed 100 characters.');
            return false;
        }
        
        // Check if business name contains only alphabets and spaces
        if (!/^[A-Za-z\s]+$/.test(businessName)) {
            e.preventDefault();
            alert('Business Name should contain only alphabets and spaces.');
            return false;
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/myflatin/test.superadmin.myflatinfo.com/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>