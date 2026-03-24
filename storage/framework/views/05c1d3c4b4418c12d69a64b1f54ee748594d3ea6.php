<?php $__env->startSection('title'); ?>
    Customer Details
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
            <h1>Customer Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Customer Details</li>
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
                  <li class="nav-item"><a class="nav-link" href="#edit" data-toggle="tab">Update Customer details</a></li>
                  <li class="nav-item"><a class="nav-link" href="#buildings" data-toggle="tab">Buildings</a></li>
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
                          <td><?php echo e($customer->city ? $customer->city->name : 'N/A'); ?></td>
                        </tr>
                        <tr>
                          <td>Address</td>
                          <td colspan="3"><?php echo e($customer->address); ?></td>
                        </tr>
                        <tr>
                          <td>Desc</td>
                          <td colspan="3"><?php echo e($customer->desc); ?></td>
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
                  
                  <div class="tab-pane" id="edit">
                    <form action="<?php echo e(url('store-user')); ?>" method="post" enctype="multipart/form-data">
                      <?php echo csrf_field(); ?>
                      <div class="form-group row">
                        <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="first_name" value="<?php echo e($customer->first_name); ?>" pattern="[A-Za-z\s]+" onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="last_name" value="<?php echo e($customer->last_name); ?>" pattern="[A-Za-z\s]+" onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" name="email" value="<?php echo e($customer->email); ?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="phone" value="<?php echo e($customer->phone); ?>" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                        <div class="col-sm-10">
                          <select name="gender" class="form-control" required>
                            <option value="Male" <?php echo e($customer->gender == 'Male' ? 'selected' : ''); ?>>Male</option>
                            <option value="Female" <?php echo e($customer->gender == 'Female' ? 'selected' : ''); ?>>Female</option>
                            <option value="Others" <?php echo e($customer->gender == 'Others' ? 'selected' : ''); ?>>Others</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                          <textarea name="address" class="form-control" required><?php echo e($customer->address); ?></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                          <select name="status" class="form-control" required>
                            <option value="Active" <?php echo e($customer->status == 'Active' ? 'selected' : ''); ?>>Active</option>
                            <option value="Inactive" <?php echo e($customer->status == 'Inactive' ? 'selected' : ''); ?>>Inactive</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-primary">Update Customer</button>
                        </div>
                      </div>
                      <input type="hidden" name="id" value="<?php echo e($customer->id); ?>">
                      <input type="hidden" name="role" value="<?php echo e($customer->role); ?>">
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                  
                  <div class="tab-pane" id="buildings">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#addModal">Add New Building</button>
                        </div>
                        <?php $__empty_1 = true; $__currentLoopData = $customer->buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-md-4 d-flex">
                                <div class="card h-100 w-100"> <!-- Ensures uniform height and width -->
                                    <div class="card-header text-center font-weight-bold"><?php echo e($building->name); ?></div>
                                    <div class="card-body d-flex align-items-center justify-content-center">
                                        <img src="<?php echo e($building->image); ?>" class="img-fluid w-100" style="height: 200px; object-fit: cover;" alt="<?php echo e($building->name); ?>">
                                    </div>
                                    <div class="card-footer mt-auto">
                                        <!--<a href="<?php echo e(route('buildings.show',$building->id)); ?>" target="_blank" class="btn btn-sm btn-warning"><i class="fa fa-eye"></i></a>-->
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal"
                                            data-id="<?php echo e($building->id); ?>" data-name="<?php echo e($building->name); ?>" data-status="<?php echo e($building->status); ?>"
                                            data-city_id="<?php echo e($building->city_id); ?>" data-zip_code="<?php echo e($building->zip_code); ?>" data-address="<?php echo e($building->address); ?>" 
                                            data-image="<?php echo e($building->image); ?>" data-owner_name="<?php echo e($building->owner_name); ?>" data-owner_contact_no="<?php echo e($building->owner_contact_no); ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <?php if($building->deleted_at): ?>
                                            <button class="btn btn-sm btn-success right" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo e($building->id); ?>" data-action="restore">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-danger right" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo e($building->id); ?>" data-action="delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    </div>
                </div>

                  
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
    
<!-- Add Modal -->

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Building</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo e(route('buildings.store')); ?>" method="post" class="add-form" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <div class="error"></div>
          <div class="form-group">
            <label for="name" class="col-form-label">Building Name:</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Building Name" minlength="4" maxlength="20" required>
          </div>
          
          <div class="form-group">
            <label for="name" class="col-form-label">Owner Name:</label>
            <input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Owner Name" minlength="4" maxlength="20" required>
          </div>
          
          <div class="form-group">
            <label for="name" class="col-form-label">Owner Contact Number:</label>
            <input type="text" name="owner_contact_no" id="owner_contact_no" class="form-control" placeholder="Owner Contact No" required>
          </div>
          
          <div class="form-group">
            <label for="name" class="col-form-label">City:</label>
            <select name="city_id" id="city_id" class="form-control" required>
                <?php $__empty_1 = true; $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <option value="<?php echo e($city->id); ?>"><?php echo e($city->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php endif; ?>
            </select>
          </div>
          
          <div class="form-group">
            <label for="name" class="col-form-label">Address:</label>
            <textarea name="address" id="address" class="form-control"></textarea>
          </div>
          
          <div class="form-group">
            <label for="name" class="col-form-label">Zip Code:</label>
            <input type="text" name="zip_code" id="zip_code" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="name" class="col-form-label">Image: <img src="" id="image2" style="width:40px"></label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
          </div>
          
          <div class="form-group">
            <label for="name" class="col-form-label">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Pending">Pending</option>
                <option value="Active">Active</option>
            </select>
          </div>
          
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="user_id" value="<?php echo e($customer->id); ?>">
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
    
    $('#deleteModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      id = button.data('id');
      $('.modal-title').text('Are you sure ?')
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
      var url = "<?php echo e(route('buildings.destroy','')); ?>";
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
      $('#edit-id').val(edit_id);
      $('#name').val(button.data('name'));
       $('#name').val(button.data('name'));
      $('#owner_name').val(button.data('owner_name'));
      $('#owner_contact_no').val(button.data('owner_contact_no'));
      $('#city_id').val(button.data('city_id'));
      $('#zip_code').val(button.data('zip_code'));
      $('#address').val(button.data('address'));
      $('#status').val(button.data('status'));
      $('#image2').attr('src',button.data('image'));
      $('.modal-title').text('Add New Building');
      $('#image').attr('required',true);
      if(edit_id){
          $('#image').attr('required',false);
          $('.modal-title').text('Update Building');
      }
      
    });
    
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/vivekpatel/Herd/newupdatedcode/resources/views/admin/customers/show.blade.php ENDPATH**/ ?>