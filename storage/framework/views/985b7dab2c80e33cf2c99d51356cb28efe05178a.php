

<?php $__env->startSection('title'); ?>
    Builder Details
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
            <h1>Builder Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Builder Details</li>
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

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                </div>
                <h3 class="profile-username text-center"><?php echo e($builder->name); ?></h3>

                <p class="text-muted text-center"><?php echo e($builder->company_name); ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Email</b> <a class=""><?php echo e($builder->email); ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Phone</b> <a class=""><?php echo e($builder->phone); ?></a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#buildings" data-toggle="tab">Buildings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content"> 
                  <div class="tab-pane active" id="buildings">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#addModal">Add New Building</button>
                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="white-space: nowrap; text-align: center; width: 60px;">S No</th>
                                    <th>Building Name</th>
                                    <th>Building Address</th>
                                     <th>Status</th>
                                    <th>BA Name</th>
                                    <th>BA Email</th>
                                    <th>BA Phone</th>
                                    
                               
                                    <th style="white-space: nowrap; text-align: center; min-width: 200px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php $__empty_1 = true; $__currentLoopData = $builder->buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php $i++; ?>
                                <tr>
                                    <td><?php echo e($i); ?></td>
                                    <td><?php echo e($building->name); ?></td>
                                     <td><?php echo e($building->address); ?></td>
                                      <td>
                                        <?php echo e($building->status); ?>

                                    </td>
                                    <td>
                                         <a href="<?php echo e(url('building-admin/' . $building->user->id)); ?>" class="text-primary" style="text-decoration: none; font-weight: 500;"><?php echo e($building->user->name); ?></a>
                                        <br><small class="text-muted">Total Buildings: <?php echo e($building->user->buildings->count()); ?></small>
                                    </td>
                                    <td><?php echo e($building->user ? $building->user->email : 'N/A'); ?></td>
                                    <td><?php echo e($building->user->phone); ?></td>
                                   
                                   
                                    <td style="text-align: center; white-space: nowrap;">
                                    <div class="btn-group" role="group" style="display: inline-flex; gap: 2px;">
                                        <!-- <a href="<?php echo e(route('buildings.show',$building->id)); ?>" class="btn btn-sm btn-warning"><i class="fa fa-eye"></i></a> -->
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal" data-id="<?php echo e($building->id); ?>" data-name="<?php echo e($building->name); ?>" data-first_name="<?php echo e($building->user->first_name); ?>" data-last_name="<?php echo e($building->user->last_name); ?>"
                                        data-phone="<?php echo e($building->phone); ?>" data-email="<?php echo e($building->user->email); ?>" data-city="<?php echo e($building->city_id); ?>" data-address="<?php echo e($building->address); ?>" data-status="<?php echo e($building->status); ?>" data-ba_id="<?php echo e($building->user_id); ?>"><i class="fa fa-edit"></i></button>
                                        
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#settingModal" data-id="<?php echo e($building->id); ?>" data-no_of_flats="<?php echo e($building->no_of_flats); ?>" data-no_of_logins="<?php echo e($building->no_of_logins); ?>"
                                        data-no_of_other_users="<?php echo e($building->no_of_other_users); ?>" data-valid_till="<?php echo e($building->valid_till); ?>" data-licence_key="<?php echo e($building->licence_key); ?>" data-permissions="<?php echo e(json_encode($building->permissions->pluck('id'))); ?>"><i class="fa fa-cog"></i></button>
                                        
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#accountModal" data-id="<?php echo e($building->id); ?>" data-payment_is_active="<?php echo e($building->payment_is_active); ?>" data-maintenance_is_active="<?php echo e($building->maintenance_is_active); ?>"
                                        data-corpus_is_active="<?php echo e($building->corpus_is_active); ?>" data-donation_is_active="<?php echo e($building->donation_is_active); ?>" data-facility_is_active="<?php echo e($building->facility_is_active); ?>" data-other_is_active="<?php echo e($building->other_is_active); ?>">
                                        <i class="fa fa-credit-card-alt"></i></button>

                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#classifiedModal" data-id="<?php echo e($building->id); ?>" data-within_for_month="<?php echo e($building->within_for_month); ?>" 
                                         data-classified_limit_within_building="<?php echo e($building->classified_limit_within_building); ?>" data-classified_limit_all_building="<?php echo e($building->classified_limit_all_building); ?>" data-all_for_month="<?php echo e($building->all_for_month); ?>">
                                        <i class="fa fa-bath"></i></button>
                                        
                                        <?php if($building->deleted_at): ?>
                                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo e($building->id); ?>" data-action="restore"><i class="fa fa-undo"></i></button>
                                        <?php else: ?>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo e($building->id); ?>" data-action="delete"><i class="fa fa-trash"></i></button>
                                        <?php endif; ?>
                                    </div>
                                    </td>

                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </tbody>
                            </table>
                        </div>
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
            <input type="text" name="name" id="name" class="form-control" placeholder="Building Name" minlength="4" maxlength="60" required>
          </div>
             <div class="form-group">
            <label for="city" class="col-form-label">City:</label>
            <select name="city" id="city" class="form-control" required>
                <option value="">--Select City--</option>
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
            <label for="name" class="col-form-label">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
          </div>
       
         
          <!--<div class="form-group">-->
          <!--  <label for="building_phone" class="col-form-label">Building Phone:</label>-->
          <!--  <input type="text" name="building_phone" id="building_phone" class="form-control" placeholder="Building Phone" minlength="10" maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57">-->
          <!--</div>-->

          <div class="form-group">
            <label for="ba_option" class="col-form-label">Building Admin Option:</label>
            <select name="ba_option" id="ba_option" class="form-control" required>
                <option value="">--Select Option--</option>
                <option value="existing">Assign Existing BA</option>
                <option value="promote">Promote Existing User to BA</option>
                <option value="new">Create New BA</option>
            </select>
          </div>

          <div class="form-group" id="existing-ba-section" style="display: none;">
            <label for="existing_ba" class="col-form-label">Select Building Admin:</label>
            <select name="existing_ba" id="existing_ba" class="form-control">
                <option value="">--Select Building Admin--</option>
                <?php $__empty_1 = true; $__currentLoopData = $building_admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ba): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <option value="<?php echo e($ba->id); ?>"><?php echo e($ba->name); ?> (<?php echo e($ba->email); ?>) - Buildings: <?php echo e($ba->buildings->count()); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <option value="">No Building Admins Available</option>
                <?php endif; ?>
            </select>
          </div>

          <div class="form-group" id="promote-user-section" style="display: none;">
            <label for="user_email" class="col-form-label">Search User by Email:</label>
            <div class="input-group">
              <input type="email" name="user_email" id="user_email" class="form-control" placeholder="Enter user email to search">
              <div class="input-group-append">
                <button type="button" class="btn btn-info" id="search-user-btn">Search User</button>
              </div>
            </div>
            <div id="user-search-result" class="mt-2"></div>
            <input type="hidden" name="promote_user_id" id="promote_user_id">
          </div>

          <div id="new-ba-section" style="display: none;">
            <div class="form-group">
              <label for="name" class="col-form-label">Building Admin Name:</label>
              <div class="row">
                  <div class="col-md-6">
                      <label for="first_name" class="col-form-label">First name</label>
                      <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" minlength="3" maxlength="40" pattern="[A-Za-z\s]+" onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32">
                  </div>
                  <div class="col-md-6">
                      <label for="last_name" class="col-form-label">Last name</label>
                      <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" minlength="3" maxlength="40" pattern="[A-Za-z\s]+" onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32">
                  </div>
              </div>
            </div>
            <div class="form-group">
              <label for="name" class="col-form-label">Email:</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$">
            </div>
            <div class="form-group">
              <label for="name" class="col-form-label">Phone:</label>
              <input type="text" name="ba_phone" id="ba_phone" class="form-control" placeholder="Phone" minlength="10" maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
            </div>
            
            <div class="form-group">
              <label for="name" class="col-form-label">Password:</label>
              <div class="input-group">
                <input type="password" name="password" class="form-control password" minlength="8" maxlength="14" id="password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="show-password password-icon"><i class="fa fa-eye-slash"></i></span>
                    <span class="hide-password password-icon" style="display:none;"><i class="fa fa-eye"></i></span>
                  </div>
                </div>
                <p clss="mt-1" style="color:grey;font-size:11px;">Password must contain at least 8 characters, including an uppercase letter, a lowercase letter, a number, and a special character</p>
              </div>
            </div>
          </div>
         
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="builder_id" value="<?php echo e($builder->id); ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="save-button">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="settingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Licence Configuration</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo e(url('building-configration')); ?>" method="post" class="add-form" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <div class="error"></div>
          <div class="form-group">
            <label for="name" class="col-form-label">Licence Key:</label>
            <textarea name="licence_key" id="licence_key" class="form-control" placeholder="Licence Key" disabled></textarea>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">No of flats:</label>
            <input type="number" name="no_of_flats" id="no_of_flats" class="form-control" min="0" step="1" pattern="[0-9]+" placeholder="Enter number of flats (minimum 0)" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
            <small class="form-text text-muted">Enter number of flats</small>
          </div>

          <div class="form-group">
            <label for="name" class="col-form-label">No of Users:</label>
            <input type="number" name="no_of_logins" id="no_of_logins" class="form-control" min="0" step="1" pattern="[0-9]+" placeholder="Enter number of users (minimum 0)" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
            <small class="form-text text-muted">Enter number of users</small>
          </div>
          
          <div class="form-group">
            <label for="name" class="col-form-label">No of Other Users:</label>
            <input type="number" name="no_of_other_users" id="no_of_other_users" class="form-control" min="0" step="1" pattern="[0-9]+" placeholder="Enter number of other users (minimum 0)" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
            <small class="form-text text-muted">Enter number of other users</small>
          </div>

          <div class="form-group">
            <label for="name" class="col-form-label">Valid till:</label>
            <input type="date" name="valid_till" id="valid_till" class="form-control" min="<?php echo e(\Carbon\Carbon::now()->toDateString()); ?>" placeholder="Valid Till" required>
          </div>

          <div class="form-group col-md-12">
              <label class="col-form-label">Feature Permissions:</label>
            
              
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="permission-all">
                <label class="form-check-label font-weight-bold" for="permission-all">Select All</label>
              </div>
            
              
              <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="permission-group border p-2 mb-2">
                  
                  <div class="form-check">
                    <input class="form-check-input permission-group-checkbox" type="checkbox" id="group-<?php echo e(\Illuminate\Support\Str::slug($group)); ?>">
                    <label class="form-check-label font-weight-bold text-primary" for="group-<?php echo e(\Illuminate\Support\Str::slug($group)); ?>"><?php echo e($group); ?></label>
                  </div>
            
                  
                  <div class="ml-3 mt-2">
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <div class="form-check">
                        <input class="form-check-input permission-checkbox permission-group-<?php echo e(\Illuminate\Support\Str::slug($group)); ?>" 
                               type="checkbox" 
                               name="permissions[]" 
                               value="<?php echo e($permission->id); ?>" 
                               id="permission-<?php echo e($permission->id); ?>">
                        <label class="form-check-label" for="permission-<?php echo e($permission->id); ?>"><?php echo e($permission->name); ?></label>
                      </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

          
          <input type="hidden" name="id" id="edit-id-2">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="settings-save-button" disabled>Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Payment Options</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo e(url('building-payment-options')); ?>" method="post" class="add-form" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <div class="error"></div>

          <div class="form-group">
            <label for="name" class="col-form-label">Own Razorpay Acces:</label>
            <select name="payment_is_active" id="payment_is_active" class="form-control" required>
              <option value="Yes">Own Payment Keys</option>
              <option value="No">Myflatinfo Payment Keys</option>
            </select>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Maintenance Payment:</label>
            <select name="maintenance_is_active" id="maintenance_is_active" class="form-control" required>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Corpus Payment:</label>
            <select name="corpus_is_active" id="corpus_is_active" class="form-control" required>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Donation Payment:</label>
            <select name="donation_is_active" id="donation_is_active" class="form-control" required>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Facility Payment:</label>
            <select name="facility_is_active" id="facility_is_active" class="form-control" required>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Essentials Payment:</label>
            <select name="other_is_active" id="other_is_active" class="form-control" required>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <input type="hidden" name="id" id="edit-id-3">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="save-button">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="classifiedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Classified Limit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo e(url('building-classified-limit')); ?>" method="post" class="add-form" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <div class="error"></div>

          <div class="form-group row">
            <div class="col-md-6">
                <label for="name" class="col-form-label">Within Building:</label>
                <input type="number" name="classified_limit_within_building" id="classified_limit_within_building" class="form-control" min="0" max="10000" step="1" pattern="[0-9]+" placeholder="Enter limit (0-10000)" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                <small class="form-text text-muted">Maximum limit: 10000</small>
            </div>
            <div class="col-md-6">
                <label for="name" class="col-form-label">For Month:</label>
                <input type="number"
                name="within_for_month"
                id="within_for_month"
                class="form-control"
                min="0"
                max="99"
                step="1"
                maxlength="2"
                oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);"
                placeholder="Enter number of months"
                required>
                <small class="form-text text-muted">Enter number of months</small>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-6">
                <label for="name" class="col-form-label">All Building:</label>
                <input type="number" name="classified_limit_all_building" id="classified_limit_all_building" class="form-control" min="0" max="10000" step="1" pattern="[0-9]+" placeholder="Enter limit (0-10000)" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                <small class="form-text text-muted">Maximum limit: 10000</small>
            </div>
            <div class="col-md-6">
                <label for="name" class="col-form-label">For Month:</label>
                 <input type="number"
                  name="all_for_month"
                  id="all_for_month"
                  class="form-control"
                  min="0"
                  max="99"
                  step="1"
                  maxlength="2"
                  oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);"
                  placeholder="Enter number of months"
                  required>
                
                <small class="form-text text-muted">Enter number of months</small>
            </div>
          </div>
          <input type="hidden" name="id" id="edit-id-4">
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
      $('#city').val(button.data('city'));
      $('#address').val(button.data('address'));
      $('#first_name').val(button.data('first_name'));
      $('#last_name').val(button.data('last_name'));
      $('#phone').val(button.data('phone'));
      $('#email').val(button.data('email'));
      $('#status').val(button.data('status'));
      $('.modal-title').text('Add New Building');
      $('#password').attr('required',true);
      if(edit_id){
          $('.modal-title').text('Update Building');
          $('#password').attr('required',false);
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

    $('#settingModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var edit_id2 = button.data('id');
      $('#edit-id-2').val(edit_id2);
      $('#no_of_flats').val(button.data('no_of_flats'));
      $('#no_of_logins').val(button.data('no_of_logins'));
      $('#no_of_other_users').val(button.data('no_of_other_users'));
      $('#valid_till').val(button.data('valid_till'));
      $('#licence_key').val(button.data('licence_key'));
      $('.modal-title').text('Licence Configuration');
      // Uncheck all facilities first
      $('input[name="permissions[]"]').prop('checked', false);

      // Get assigned facility IDs and check those checkboxes
      var permissions = button.data('permissions'); // already parsed as array by jQuery
      if (Array.isArray(permissions)) {
          permissions.forEach(function(permissionId) {
              $('#permission-' + permissionId).prop('checked', true);
          });
      }
      
    });

    $('#accountModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var edit_id3 = button.data('id');
      $('#edit-id-3').val(edit_id3);
      $('#payment_is_active').val(button.data('payment_is_active'));
      $('#maintenance_is_active').val(button.data('maintenance_is_active'));
      $('#corpus_is_active').val(button.data('corpus_is_active'));
      $('#donation_is_active').val(button.data('donation_is_active'));
      $('#facility_is_active').val(button.data('facility_is_active'));
      $('#other_is_active').val(button.data('other_is_active'));
      $('.modal-title').text('Payment Options');
      
    });

    $('#classifiedModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var edit_id4 = button.data('id');
      $('#edit-id-4').val(edit_id4);
      $('#classified_limit_within_building').val(button.data('classified_limit_within_building'));
      $('#classified_limit_all_building').val(button.data('classified_limit_all_building'));
      $('#within_for_month').val(button.data('within_for_month'));
      $('#all_for_month').val(button.data('all_for_month'));
      $('.modal-title').text('Classified Limit');
      
    });

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
    
    // Master "Select All" toggle
    $('#permission-all').change(function () {
      const isChecked = $(this).is(':checked');
      $('input.permission-checkbox, .permission-group-checkbox').prop('checked', isChecked);
      updateSaveButtonState();
    });

    // Group toggle
    $('.permission-group-checkbox').change(function () {
      const groupSlug = this.id.replace('group-', '');
      $('.permission-group-' + groupSlug).prop('checked', $(this).is(':checked'));
      updateSaveButtonState();
    });

    // When any individual checkbox changes, update group and master state
    $('input.permission-checkbox').change(function () {
      // Update group checkbox
      $('.permission-group-checkbox').each(function () {
        const groupSlug = this.id.replace('group-', '');
        const allInGroup = $('.permission-group-' + groupSlug).length;
        const checkedInGroup = $('.permission-group-' + groupSlug + ':checked').length;
        $(this).prop('checked', allInGroup === checkedInGroup);
      });

      // Update master checkbox
      const totalPermissions = $('input.permission-checkbox').length;
      const totalChecked = $('input.permission-checkbox:checked').length;
      $('#permission-all').prop('checked', totalPermissions === totalChecked);
      
      // Enable/disable Save button based on selection
      updateSaveButtonState();
    });

    // Function to update Save button state
    function updateSaveButtonState() {
      const checkedPermissions = $('input.permission-checkbox:checked').length;
      $('#settings-save-button').prop('disabled', checkedPermissions === 0);
    }


    // Update Save button state when modal opens
    $('#settingModal').on('shown.bs.modal', function () {
      updateSaveButtonState();
    });

    // Building Admin option handling
    $('#ba_option').change(function() {
      var selectedOption = $(this).val();
      
      // Hide all sections first
      $('#existing-ba-section').hide();
      $('#promote-user-section').hide();
      $('#new-ba-section').hide();
      
      // Reset required attributes
      $('#existing_ba').attr('required', false);
      $('#user_email').attr('required', false);
      $('#first_name, #last_name, #email, #ba_phone, #password').attr('required', false);
      
      if (selectedOption === 'existing') {
        $('#existing-ba-section').show();
        $('#existing_ba').attr('required', true);
      } else if (selectedOption === 'promote') {
        $('#promote-user-section').show();
        $('#user_email').attr('required', true);
        // Clear previous search results
        $('#user-search-result').html('');
        $('#promote_user_id').val('');
      } else if (selectedOption === 'new') {
        $('#new-ba-section').show();
        $('#first_name, #last_name, #email, #ba_phone, #password').attr('required', true);
      }
    });

    // Reset BA option when modal opens
    $('#addModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var edit_id = button.data('id');
      
      // Reset BA option
      $('#ba_option').val('');
      $('#existing-ba-section').hide();
      $('#promote-user-section').hide();
      $('#new-ba-section').hide();
      $('#existing_ba').attr('required', false);
      $('#user_email').attr('required', false);
      $('#first_name, #last_name, #email, #ba_phone, #password').attr('required', false);
      
      // Clear promote user search
      $('#user-search-result').html('');
      $('#promote_user_id').val('');
      $('#user_email').val('');
      
      $('#edit-id').val(edit_id);
      $('#name').val(button.data('name'));
      $('#city').val(button.data('city'));
      $('#address').val(button.data('address'));
      $('#building_phone').val(button.data('phone'));
      $('#status').val(button.data('status'));
      $('.modal-title').text('Add New Building');
      
      if(edit_id){
        $('.modal-title').text('Update Building');
        // For editing, show existing BA option and pre-select the current BA
        $('#ba_option').val('existing');
        $('#existing-ba-section').show();
        $('#existing_ba').attr('required', true);
        
        // Set the current BA as selected
        var currentBaId = button.data('ba_id');
        if (currentBaId) {
          $('#existing_ba').val(currentBaId);
        }
      }
    });

    // Search User functionality
    $('#search-user-btn').on('click', function() {
      var email = $('#user_email').val().trim();
      var token = "<?php echo e(csrf_token()); ?>";
      
      if (!email) {
        $('#user-search-result').html('<div class="alert alert-warning">Please enter an email address.</div>');
        return;
      }
      
      // Disable button and show loading
      $(this).prop('disabled', true).text('Searching...');
      $('#user-search-result').html('<div class="text-info"><i class="fa fa-spinner fa-spin"></i> Searching user...</div>');
      
      $.ajax({
        url: "<?php echo e(route('search-user-for-ba')); ?>",
        type: "POST",
        data: {
          '_token': token,
          'email': email
        },
        success: function(response) {
          $('#search-user-btn').prop('disabled', false).text('Search User');
          
          if (response.success && response.user) {
            var user = response.user;
            $('#promote_user_id').val(user.id);
            
            var resultHtml = '<div class="alert alert-success">';
            resultHtml += '<h6><strong>User Found!</strong></h6>';
            resultHtml += '<div class="row">';
            resultHtml += '<div class="col-md-8">';
            resultHtml += '<strong>Name:</strong> ' + user.name + '<br>';
            resultHtml += '<strong>Email:</strong> ' + user.email + '<br>';
            resultHtml += '<strong>Phone:</strong> ' + (user.phone || 'N/A') + '<br>';
            resultHtml += '<strong>Current Role:</strong> ' + (user.role || 'User') + '<br>';
            if (user.current_buildings && user.current_buildings.length > 0) {
              resultHtml += '<strong>Current Buildings:</strong><br>';
              user.current_buildings.forEach(function(building) {
                resultHtml += '• ' + building.name + ' (' + building.builder_name + ')<br>';
              });
            }
            resultHtml += '</div>';
            if (user.photo) {
              resultHtml += '<div class="col-md-4 text-center">';
              resultHtml += '<img src="' + user.photo + '" style="width: 80px; height: 80px; border-radius: 50%;" class="img-thumbnail">';
              resultHtml += '</div>';
            }
            resultHtml += '</div>';
            resultHtml += '<p class="mt-2 mb-0"><strong>This user will be promoted to Building Admin for this building.</strong></p>';
            resultHtml += '</div>';
            
            $('#user-search-result').html(resultHtml);
          } else {
            $('#user-search-result').html('<div class="alert alert-danger">' + (response.message || 'User not found with this email address.') + '</div>');
            $('#promote_user_id').val('');
          }
        },
        error: function() {
          $('#search-user-btn').prop('disabled', false).text('Search User');
          $('#user-search-result').html('<div class="alert alert-danger">Error occurred while searching. Please try again.</div>');
          $('#promote_user_id').val('');
        }
      });
    });

    // Prevent + and - symbols in classified and license configuration number fields
    $('#classified_limit_within_building, #within_for_month, #classified_limit_all_building, #all_for_month, #no_of_flats, #no_of_logins, #no_of_other_users').on('keypress', function(e) {
      // Allow: backspace, delete, tab, escape, enter
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
          // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
          (e.keyCode === 65 && e.ctrlKey === true) ||
          (e.keyCode === 67 && e.ctrlKey === true) ||
          (e.keyCode === 86 && e.ctrlKey === true) ||
          (e.keyCode === 88 && e.ctrlKey === true)) {
        return;
      }
      // Ensure that it is a number and stop the keypress
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
      }
    });

    // Prevent pasting non-numeric content
    $('#classified_limit_within_building, #within_for_month, #classified_limit_all_building, #all_for_month, #no_of_flats, #no_of_logins, #no_of_other_users').on('paste', function(e) {
      var pastedData = e.originalEvent.clipboardData.getData('text');
      if (!/^\d+$/.test(pastedData)) {
        e.preventDefault();
      }
    });

    // Add form validation for classified modal
    $('#classifiedModal form').on('submit', function(e) {
      var isValid = true;
      var errorMessage = '';

      // Validate Within Building limit
      var withinBuilding = parseInt($('#classified_limit_within_building').val());
      if (isNaN(withinBuilding) || withinBuilding < 0 || withinBuilding > 10000) {
        isValid = false;
        errorMessage += 'Within Building limit must be between 0 and 10000.<br>';
      }

      // Validate Within Building months
      var withinMonth = parseInt($('#within_for_month').val());
      if (isNaN(withinMonth) || withinMonth < 0) {
        isValid = false;
        errorMessage += 'Within Building months must be 0 or greater.<br>';
      }

      // Validate All Building limit
      var allBuilding = parseInt($('#classified_limit_all_building').val());
      if (isNaN(allBuilding) || allBuilding < 0 || allBuilding > 10000) {
        isValid = false;
        errorMessage += 'All Building limit must be between 0 and 10000.<br>';
      }

      // Validate All Building months
      var allMonth = parseInt($('#all_for_month').val());
      if (isNaN(allMonth) || allMonth < 0) {
        isValid = false;
        errorMessage += 'All Building months must be 0 or greater.<br>';
      }

      if (!isValid) {
        e.preventDefault();
        $('#classifiedModal .error').html('<div class="alert alert-danger">' + errorMessage + '</div>');
        return false;
      } else {
        $('#classifiedModal .error').html('');
      }
    });

    // Add form validation for settings modal (License configuration)
    $('#settingModal form').on('submit', function(e) {
      var isValid = true;
      var errorMessage = '';

      // Validate No of flats
      var noOfFlats = parseInt($('#no_of_flats').val());
      if (isNaN(noOfFlats) || noOfFlats < 0) {
        isValid = false;
        errorMessage += 'Number of flats must be 0 or greater.<br>';
      }

      // Validate No of users
      var noOfUsers = parseInt($('#no_of_logins').val());
      if (isNaN(noOfUsers) || noOfUsers < 0) {
        isValid = false;
        errorMessage += 'Number of users must be 0 or greater.<br>';
      }

      // Validate No of other users
      var noOfOtherUsers = parseInt($('#no_of_other_users').val());
      if (isNaN(noOfOtherUsers) || noOfOtherUsers < 0) {
        isValid = false;
        errorMessage += 'Number of other users must be 0 or greater.<br>';
      }

      // Check if at least one permission is selected
      var checkedPermissions = $('input.permission-checkbox:checked').length;
      if (checkedPermissions === 0) {
        isValid = false;
        errorMessage += 'Please select at least one Feature Permission.<br>';
      }

      if (!isValid) {
        e.preventDefault();
        $('#settingModal .error').html('<div class="alert alert-danger">' + errorMessage + '</div>');
        return false;
      } else {
        $('#settingModal .error').html('');
      }
    });

    // Add form validation for add building modal
    $('#addModal form').on('submit', function(e) {
      var isValid = true;
      var errorMessage = '';

      // Validate First Name (alphabets only)
      var firstName = $('#first_name').val().trim();
      if (!/^[A-Za-z\s]+$/.test(firstName)) {
        isValid = false;
        errorMessage += 'First name should contain only alphabets and spaces.<br>';
      }

      // Validate Last Name (alphabets only)
      var lastName = $('#last_name').val().trim();
      if (!/^[A-Za-z\s]+$/.test(lastName)) {
        isValid = false;
        errorMessage += 'Last name should contain only alphabets and spaces.<br>';
      }

      // Validate Email (single TLD)
      var email = $('#email').val().trim();
      var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
      if (!emailPattern.test(email)) {
        isValid = false;
        errorMessage += 'Please enter a valid email address with single TLD.<br>';
      }

      // Validate Phone (10 digits only)
      var phone = $('#phone').val().trim();
      if (!/^\d{10}$/.test(phone)) {
        isValid = false;
        errorMessage += 'Phone number must be exactly 10 digits.<br>';
      }

      if (!isValid) {
        e.preventDefault();
        $('#addModal .error').html('<div class="alert alert-danger">' + errorMessage + '</div>');
        return false;
      } else {
        $('#addModal .error').html('');
      }
    });

  });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/myflatin/superadmin.myflatinfo.com/resources/views/admin/builder/show.blade.php ENDPATH**/ ?>