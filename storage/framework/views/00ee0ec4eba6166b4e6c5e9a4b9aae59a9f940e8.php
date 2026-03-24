<?php $__env->startSection('title'); ?>
    How it works ?
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>

<!-- Loader overlay -->


<!-- Loader overlay -->
<div id="editor-loader" style="position:fixed;top:0;width:100vw;height:100vh;z-index:2000;background:rgba(255,255,255,1);display:flex;align-items:center;justify-content:center;">
  <div style="text-align:center;">
    <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;">
      <span class="sr-only">Loading...</span>
    </div>
    <div style="margin-top:10px;font-size:1.2em;">Loading editor...</div>
  </div>
</div>

<link rel="stylesheet" href="<?php echo e(asset('public/admin/plugins/summernote/summernote-bs4.min.css')); ?>">

<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>How it works</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">How it works</li>
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
                            <form action="<?php echo e(url('update-how-it-works')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label>How it works</label>
                                    <textarea name="how_it_works" id="summernote" class="form-control"><?php echo $how_it_works; ?></textarea>
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
    // Hide loader when Summernote is ready
    $('#summernote').on('summernote.init', function() {
      $('#editor-loader').fadeOut(300);
    });
    // Enhanced Summernote with image options
    $('#summernote').summernote({
      height: 400,
      minHeight: 200,
      maxHeight: 600,
      placeholder: 'Enter How it works content here...',
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
        onInit: function() {
          // Fallback: hide loader if summernote.init doesn't fire
          $('#editor-loader').fadeOut(300);
        },
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/myflatin/test.superadmin.myflatinfo.com/resources/views/admin/settings/how_it_works.blade.php ENDPATH**/ ?>