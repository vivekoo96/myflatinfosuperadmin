@extends('layouts.admin')

@section('title')
    Privacy Policy
@endsection


@section('content')

<!-- Loader overlay -->
<div id="editor-loader" style="position:fixed;top:0;width:100vw;height:100vh;z-index:2000;background:rgba(255,255,255,1);display:flex;align-items:center;justify-content:center;">
  <div style="text-align:center;">
    <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;">
      <span class="sr-only"></span>
    </div>
    <div style="margin-top:10px;font-size:1.2em;"></div>
  </div>
</div>

<link rel="stylesheet" href="{{asset('public/admin/plugins/summernote/summernote-bs4.min.css')}}">

<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Privacy Policy</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Privacy Policy</li>
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
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <div class="card">
                        <!--<div class="card-header text-danger">Make sure this is important issue !!</div>-->
                        <div class="card-body">
                            <form action="{{url('update-privacy-policy')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Privacy Policy</label>
                                    <textarea name="privacy_policy" id="summernote" class="form-control">{!! $privacy_policy !!}</textarea>
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


@section('script')
<script src="{{asset('public/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>

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
      placeholder: 'Enter Privacy Policy content here...',
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

    // Image upload function
    function uploadImage(file, editor) {
      // Validate file type
      if (!file.type.match('image.*')) {
        alert('Invalid format. Only image files are accepted (JPG, PNG, JPEG, GIF).');
        return;
      }
      
      // Validate file size (max 2MB)
      if (file.size > 2 * 1024 * 1024) {
        alert('File size too large. Maximum 2MB allowed.');
        return;
      }

      // Create FormData for upload
      var data = new FormData();
      data.append("file", file);
      data.append("_token", "{{ csrf_token() }}");

      // Upload image via AJAX
      $.ajax({
        url: "{{ url('upload-image') }}", // You'll need to create this route
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "POST",
        success: function(response) {
          if (response.success) {
            // Insert image with proper attributes
            var imageNode = $('<img>')
              .attr('src', response.url)
              .attr('alt', 'Uploaded image')
              .css({
                'max-width': '100%',
                'height': 'auto',
                'display': 'block',
                'margin': '10px auto'
              });
            $(editor).summernote('insertNode', imageNode[0]);
          } else {
            alert('Image upload failed: ' + response.message);
          }
        },
        error: function() {
          alert('Image upload failed. Please try again.');
        }
      });
    }
  })
</script>
@endsection

@endsection