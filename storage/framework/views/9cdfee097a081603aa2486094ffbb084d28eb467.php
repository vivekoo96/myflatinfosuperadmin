<?php $__env->startSection('title'); ?>
    About us
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="<?php echo e(asset('public/admin/plugins/summernote/summernote-bs4.min.css')); ?>">

<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>About us</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">About us</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if(session()->has('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session()->get('success')); ?>

                        </div>
                    <?php endif; ?>
                    <div class="card">
                        <!--<div class="card-header text-danger">Make sure this is important issue !!</div>-->
                        <div class="card-body">
                            <form action="<?php echo e(url('update-about-us')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label>Privacy Policy</label>
                                    <textarea name="about_us" id="summernote" class="form-control"><?php echo $about_us; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn bg-gradient-primary btn-flat" value="Save Changes">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('public/admin/plugins/summernote/summernote-bs4.min.js')); ?>"></script>

<script>
  $(function () {
    // Enhanced Summernote with image options
    $('#summernote').summernote({
      height: 400,
      minHeight: 200,
      maxHeight: 600,
      placeholder: 'Enter About Us content here...',
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ],
      callbacks: {
        onImageUpload: function(files) {
          for (let i = 0; i < files.length; i++) {
            uploadImage(files[i], this);
          }
        }
      },
      popover: {
        image: [
          ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
          ['float', ['floatLeft', 'floatRight', 'floatNone']],
          ['remove', ['removeMedia']]
        ]
      }
    });

    // Image upload function with validation
    function uploadImage(file, editor) {
      // Validate file type
      if (!file.type.match('image.*')) {
        alert('Invalid format. Only image files are accepted (JPG, PNG, JPEG, GIF).');
        return;
      }
      
      // Validate file size (max 2MB)
      if (file.size > 2 * 1024 * 1024) {
        var fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        alert('File size too large. Maximum 2MB allowed. Current: ' + fileSizeMB + 'MB.');
        return;
      }

      // For now, create a local URL for the image (you can implement server upload later)
      var reader = new FileReader();
      reader.onload = function(e) {
        var imageNode = $('<img>')
          .attr('src', e.target.result)
          .attr('alt', 'Uploaded image')
          .css({
            'max-width': '100%',
            'height': 'auto',
            'display': 'block',
            'margin': '10px auto',
            'border': '1px solid #ddd',
            'border-radius': '4px',
            'padding': '5px'
          });
        $(editor).summernote('insertNode', imageNode[0]);
      };
      reader.readAsDataURL(file);
    }
  })
</script>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/vivekpatel/Herd/newupdatedcode/resources/views/admin/settings/about_us.blade.php ENDPATH**/ ?>