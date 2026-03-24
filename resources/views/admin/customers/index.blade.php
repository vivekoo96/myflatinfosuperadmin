@extends('layouts.admin')


@section('title')
    Customers List
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
            <h1>Customers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Customers</li>
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
                <!-- <button class="btn btn-sm btn-success right" data-toggle="modal" data-target="#addModal">Add New Customer</button> -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th style="white-space: nowrap; text-align: center; width: 60px;">S No</th>
                    <th>Name</th>
                    <!--<th>Role</th>-->
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                    <?php $i = 0; ?>
                  @forelse($users as $user)
                  <?php $i++; ?>
                  <tr>
                    <td>{{$i}}</td>
                   {{-- <td><img src="{{$user->photo}}" style="width:40px"></td> --}}
                    <td>{{$user->name}}</td>
                   {{-- @if($user->role == "test")
                    <td>Customer</td>
                    @endif --}}
                    <td>{{$user->phone}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->desc}}</td>
                    {{-- <td>{{$user->city ? $user->city->name : ''}}</td>  --}}
                    <td>
                       {{-- <input type="checkbox" name="my-checkbox" class="status" data-id="{{$user->id}}" data-bootstrap-switch data-on-text="Active" 
                        data-off-text="Inactive" {{$user->status == 'Active' ? 'checked' : ''}}> --}}
                        {{$user->status}}
                    </td>
                    <td>
                      <!--<a href="{{url('customer',$user->id)}}" target="_blank"  class="btn btn-sm btn-warning"><i class="fa fa-eye"></i></a>-->
                      <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal" data-id="{{$user->id}}" data-first_name="{{$user->first_name}}" data-last_name="{{$user->last_name}}"
                      data-status="{{$user->status}}" data-email="{{$user->email}}" data-phone="{{$user->phone}}" data-gender="{{$user->gender}}" data-city_id="{{$user->city_id}}" data-address="{{$user->address}}" data-photo="{{$user->photo}}"><i class="fa fa-edit"></i></button>
                      @if($user->deleted_at)
                      <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#deleteModal" data-id="{{$user->id}}" data-action="restore"><i class="fa fa-undo"></i></button>
                      @else
                      <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{$user->id}}" data-action="delete"><i class="fa fa-trash"></i></button>
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
        <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
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
            <input type="text" name="first_name" id="first_name" class="form-control" min="3" max="30" placeholder="First Name" minlength="4" maxlength="20"
                          onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32" required>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Last Name:</label>
            <input type="text" name="last_name" id="last_name" class="form-control" min="3" max="30" placeholder="Last Name" minlength="4" maxlength="20"
                          onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32" required>
          </div>
          <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input type="email" name="email" class="form-control" id="email" maxlength="40" placeholder="Email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" required>
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
            <label for="phone" class="col-form-label">City:</label>
            <select name="city_id" class="form-control" id="city_id" required>
                <option value="">--Select City--</option>
                @forelse($cities as $city)
                <option value="{{$city->id}}">{{$city->name}}</option>
                @empty
                @endforelse
            </select>
          </div>
          <div class="form-group">
            <label for="phone" class="col-form-label">Address:</label>
            <textarea name="address" class="form-control" id="address" required></textarea>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Photo: (max 2 mb) <img src="" id="image" style="width:40px"></label>
            <input type="file" name="photo" id="photo" class="form-control" placeholder="Photo" accept="image/*" required>
          </div>
          <!--<div class="form-group">-->
          <!--  <label for="role" class="col-form-label">Role:</label>-->
          <!--  <select name="role" class="form-control">-->
          <!--    <option value="BA">BA</option>-->
          <!--  </select>-->
          <!--</div>-->
          <div class="form-group">
                <label>Password (Leave Blank if dont want to update the password)</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control password" minlength="8" maxlength="14" id="re_pass" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="show-password password-icon"><i class="fa fa-eye-slash"></i></span>
                            <span class="hide-password password-icon" style="display:none;"><i class="fa fa-eye"></i></span>
                        </div>
                    </div>
                </div>
            </div>
          <div class="form-group">
            <label for="phone" class="col-form-label">Status:</label>
            <select name="status" class="form-control" id="status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
          </div>
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="role" id="role" value="customer">
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
      $('.modal-title').text('Are you sure ?');
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
      $('#city_id').val(button.data('city_id'));
      $('#address').val(button.data('address'));
      $('.modal-title').text('Add New Customer');
      $('#password').attr('required',true);
      $('#image').attr('src',button.data('photo'));
      $('#status').val(button.data('status'));
    //   $('#photo').attr('required',true);
      if(edit_id){
          $('.modal-title').text('Update Customer');
          $('#password').attr('required',false);
          $('#photo').attr('required',false);
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

  });
</script>
@endsection

@endsection


