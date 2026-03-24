@extends('layouts.admin')


@section('title')
    Admin List
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
            <h1>Admins</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Admins</li>
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
                <button class="btn btn-sm btn-success right" data-toggle="modal" data-target="#addModal">Add New Admin</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th style="text-align:center; vertical-align:top; white-space:nowrap;">S.No</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Permissions</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                    <?php $i = 0; ?>
                  @forelse($users as $user)
                  <?php $i++; ?>
                  <tr>
                    <td style="text-align:center; vertical-align:top; white-space:nowrap;">{{$i}}</td>
          <td>
           
              <img src="{{ $user->photo }}" style="width:40px; height:40px; object-fit:cover;">
          
          </td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->phone}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->city ? $user->city->name : ''}}</td>
                    <td>
                        @forelse($user->permissions as $permission)
                        <span class="badge badge-primary">{{ str_replace('Menu', '', $permission->name) }}</span>
                        @empty
                        @endforelse
                    </td>
                    <td>
                        <!--<input type="checkbox" name="my-checkbox" class="status" data-id="{{$user->id}}" data-bootstrap-switch data-on-text="Active" -->
                        <!--data-off-text="Inactive" {{$user->status == 'Active' ? 'checked' : ''}}>-->
                        {{$user->status}}
                    </td>
                    <td style="text-align:center; vertical-align:middle; white-space:nowrap; display:flex; align-items:center; gap:6px; justify-content:center;">
                      <a href="{{url('admin',$user->id)}}" target="_blank"  class="btn btn-sm btn-warning d-inline-block"><i class="fa fa-eye"></i></a>
                      <button class="btn btn-sm btn-primary d-inline-block" data-toggle="modal" data-target="#addModal" data-id="{{$user->id}}" data-first_name="{{$user->first_name}}" data-last_name="{{$user->last_name}}" data-permissions="{{ json_encode($user->permissions->pluck('id')) }}"
                      data-email="{{$user->email}}" data-phone="{{$user->phone}}" data-gender="{{$user->gender}}" data-city_id="{{$user->city_id}}" data-address="{{$user->address}}" data-status="{{$user->status}}"><i class="fa fa-edit"></i></button>
                      @if($user->deleted_at)
                      <button class="btn btn-sm btn-success d-inline-block" data-toggle="modal" data-target="#deleteModal" data-id="{{$user->id}}" data-action="restore"><i class="fa fa-undo"></i></button>
                      @else
                      <button class="btn btn-sm btn-danger d-inline-block" data-toggle="modal" data-target="#deleteModal" data-id="{{$user->id}}" data-action="delete"><i class="fa fa-trash"></i></button>
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
        <h5 class="modal-title" id="exampleModalLabel">Add Admin</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
  <form action="{{url('store-user')}}" method="post" class="add-form" enctype="multipart/form-data">
         
        @csrf
        <div class="modal-body">
          <div class="error"></div>
          <div class="form-group">
            <label for="name" class="col-form-label">First Name:</label>
            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" minlength="2" maxlength="20"
                          onkeypress="return isValidNameChar(event, this)" required>
          </div>
          <!--<div class="form-group">-->
          <!--  <label for="photo" class="col-form-label">Profile Image:</label>-->
          <!--  <input type="file" name="photo" id="photo" class="form-control" accept="image/*">-->
          <!--  <div id="photo-preview-wrapper" style="margin-top:10px; text-align:center;">-->
          <!--    <img id="photo-preview" src="{{ asset('images/default-user.png') }}" style="max-width:80px; max-height:80px; border-radius:50%; object-fit:cover; display:none;">-->
          <!--  </div>-->
          <!--</div>-->
          <div class="form-group">
            <label for="name" class="col-form-label">Last Name:</label>
            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" minlength="2" maxlength="20"
                          onkeypress="return isValidNameChar(event, this)" required>
          </div>
          <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input type="email" name="email" class="form-control" id="email" maxlength="40" placeholder="Email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" title="Please enter valid Email id" required>
          </div>
          <div class="form-group">
            <label for="phone" class="col-form-label">Phone:</label>
            <input type="text" name="phone" class="form-control" id="phone" value="{{old('phone')}}" placeholder="Phone" minlength="10" maxlength="10" 
                                      onkeypress="return event.charCode >= 48 && event.charCode <= 57" required />
          </div>
          <div class="form-group">
            <label for="phone" class="col-form-label">Gender:</label>
            <select name="gender" class="form-control" id="gender" required>
                <option value="">--Select Gender--</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Others">Others</option>
            </select>
          </div>
          <div class="form-group">
            <label for="role" class="col-form-label">City:</label>
            <select name="city_id" id="city_id" class="form-control" required>
              <option value="">--Select City--</option>
              @forelse($cities as $city)
                <option value="{{$city->id}}">{{$city->name}}</option>
              @empty
              @endforelse
            </select>
          </div>
          <div class="form-group">
            <label for="role" class="col-form-label">Address:</label>
            <textarea name="address" id="address" class="form-control" placeholder="Address" required></textarea>
          </div>

          <!--<div class="form-group">-->
          <!--  <label for="role" class="col-form-label">Role:</label>-->
          <!--  <select name="role" class="form-control">-->
          <!--    <option value="admin">Admin</option>-->
          <!--  </select>-->
          <!--</div>-->
          <div class="form-group">
                <label>Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control password" minlength="8" maxlength="14" id="re_pass" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="show-password password-icon"><i class="fa fa-eye-slash"></i></span>
                            <span class="hide-password password-icon" style="display:none;"><i class="fa fa-eye"></i></span>
                        </div>
                    </div>
                </div>
                <p clss="mt-1" style="color:grey;font-size:11px;">Password must contain at least 8 characters, including an uppercase letter, a lowercase letter, a number, and a special character</p>
          </div>
          <div class="form-group">
            <label for="phone" class="col-form-label">Status:</label>
            <select name="status" class="form-control" id="status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label fw-bold">User Permissions:</label>
        
            <!-- Select All Checkbox -->
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="select-all">
        <label class="form-check-label fw-bold" for="select-all">
          <strong>Select All</strong>
        </label>
      </div>
        
            <!-- Permission Groups -->
      @forelse($permissions as $group => $items)
        <div class="border p-3 mb-3 rounded">
          <!-- Group Checkbox -->
          <div class="form-check mb-2">
            <input class="form-check-input group-checkbox" 
                 type="checkbox" 
                 id="group-{{ \Illuminate\Support\Str::slug($group) }}">
            <label class="form-check-label fw-bold text-primary" 
                 for="group-{{ \Illuminate\Support\Str::slug($group) }}">
              <strong>{{ $group }}</strong>
            </label>
          </div>
        
                    <!-- Group Permissions -->
                    <div class="ms-4">
                        @foreach($items as $permission)
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox group-{{ \Illuminate\Support\Str::slug($group) }}" 
                                       type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}" 
                                       id="permission-{{ $permission->id }}"
                                       >
                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                   {{ str_replace('Menu', '', $permission->name) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p>No permissions available</p>
            @endforelse
        </div>


          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="role" id="role" value="admin">
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


@section('script')


<script>
  $(document).ready(function(){
    var id = '';
    var action = '';
    var token = "{{csrf_token()}}";
    
    $('.hide-password').hide();
            
    $(document).on('click','.show-password',function(){
        $('.password').attr('type','text');
        $('.show-password').hide();
        $('.hide-password').show();
    });
    $(document).on('click','.hide-password',function(){
        $('.password').attr('type','password');
        $('.hide-password').hide();
        $('.show-password').show();
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
      var url = "{{url('delete-user')}}";
      $.ajax({
        url : url,
        type: "POST",
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
      $('#first_name').val(button.data('first_name'));
      $('#last_name').val(button.data('last_name'));
      $('#email').val(button.data('email'));
      $('#phone').val(button.data('phone'));
      $('#gender').val(button.data('gender'));
      $('.modal-title').text('Add New Admin');
      $('#city_id').val(button.data('city_id'));
      $('#address').val(button.data('address'));
      $('#password').attr('required',true);
      $('#status').val(button.data('status'));
      // Image preview logic
      var photoUrl = button.data('photo');
      if(edit_id && photoUrl){
        $('#photo-preview').attr('src', photoUrl.startsWith('http') ? photoUrl : ('/images/' + photoUrl)).show();
      } else {
        $('#photo-preview').attr('src', '{{ asset('images/default-user.png') }}').show();
      }
      if(edit_id){
          $('.modal-title').text('Update Admin');
           $('#password').attr('required',false);
      }
      $('input[name="permissions[]"]').prop('checked', false);

      // Get assigned facility IDs and check those checkboxes
      var permissions = button.data('permissions'); // already parsed as array by jQuery
      if (Array.isArray(permissions)) {
          permissions.forEach(function(permissionId) {
              $('#permission-' + permissionId).prop('checked', true);
          });
      }
    });

    // Show preview when selecting a new image
    $('#photo').on('change', function(e){
      var reader = new FileReader();
      reader.onload = function(e){
        $('#photo-preview').attr('src', e.target.result).show();
      }
      if(this.files && this.files[0]){
        reader.readAsDataURL(this.files[0]);
      }
    });
    
    $('.status').bootstrapSwitch('state');
        $('.status').on('switchChange.bootstrapSwitch',function () {
            var id = $(this).data('id');
            $.ajax({
                url : "{{url('update-user-status')}}",
                type: "post",
                data : {'_token':token,'id':id,},
                success: function(data)
                {
                  //
                }
            });
        });
        
        
        // Master select-all
        $('#select-all').on('change', function() {
            $('.permission-checkbox, .group-checkbox').prop('checked', $(this).prop('checked'));
        });
        
        // Group select
        $('.group-checkbox').on('change', function() {
            let groupClass = '.group-' + this.id.replace('group-', '');
            $(groupClass).prop('checked', $(this).prop('checked'));
        });
        
        // Update group checkbox if all children selected
        $('.permission-checkbox').on('change', function() {
            let group = $(this).attr('class').match(/group-(\S+)/)[1];
            let groupClass = '.group-' + group;
            let groupBox = '#group-' + group;
            
            $(groupBox).prop('checked', $(groupClass).length === $(groupClass + ':checked').length);
        
            // Update master select-all
            $('#select-all').prop('checked', $('.permission-checkbox').length === $('.permission-checkbox:checked').length);
        });
        
        document.querySelectorAll('input[type="text"]').forEach(input => {
            ['copy','paste','cut','drop'].forEach(evt => {
                input.addEventListener(evt, e => e.preventDefault());
            });
        });

        // Custom email validation message
        $('input[name="email"]').on('invalid', function() {
            this.setCustomValidity('Please enter valid Email id');
        }).on('input', function() {
            this.setCustomValidity('');
        });

    // Address field validation - allow all valid address characters
    $('#address').on('input', function() {
      var value = $(this).val();
      // Allow letters, numbers, spaces, commas, periods, hyphens, slashes, hash, parentheses, apostrophes, tilde, exclamation, at, dollar, percent
      var validPattern = /^[a-zA-Z0-9\s,\.\-\/\#\(\)\'\~\!\@\$\%]*$/;
      if (!validPattern.test(value)) {
        $(this).val(value.replace(/[^a-zA-Z0-9\s,\.\-\/\#\(\)\'\~\!\@\$\%]/g, ''));
      }
      // Check length
      if (value.length > 100) {
        $(this).val(value.substring(0, 100));
      }
    });
  });
</script>
<script>
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        </script>
@endsection

@endsection


