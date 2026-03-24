<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $setting = \App\Models\Setting::first(); ?>
  <title><?php echo $__env->yieldContent('title'); ?> | <?php echo e($setting->bussiness_name); ?></title>
  <link rel="shortcut icon" href="<?php echo e($setting->favicon); ?>">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('admin/plugins/fontawesome-free/css/all.min.css')); ?>">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo e(asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')); ?>">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo e(asset('admin/plugins/select2/css/select2.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
<!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('admin/dist/css/adminlte.min.css')); ?>">
 
   <style>
    @import  url('https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    tfoot{display:none;}
    .btn-primary{background-color:#3c5795;}
    .btn-primary:hover{background-color:white;color:black;}
    .page-item.active .page-link {
      z-index: 3;
      color: #fff;
      background-color: #3c5795;
      border-color: #007bff;
    }
    .page-item .page-link {
      color: black;
    }
    .active-tab{
        background-color: #3c5795;
        color: white;
    }
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
      color: #fff;
      background-color: #3c5795;
      border-radius:0px;
    }
    .nav-pills .nav-link:hover, .nav-pills .show > .nav-link:hover {
      color: #fff !important;
    }
    .custom-map-control-button{padding:14px;}
    .breadcrumb{display:none;}
    .right{float: right !important}
    .sidebar-dark-primary{background-color:white;}
    [class*="sidebar-dark-"] .sidebar a {
        color: black;
    }
    .nav-item:hover{background-color:#3c5795;color:white;border-bottom:2px solid white;}
    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
        background-color: #3c5795;
        color: white !important;
        border-radius:0px;
    }
    [class*="sidebar-dark-"] .nav-sidebar > .nav-item.menu-open > .nav-link, [class*="sidebar-dark-"] .nav-sidebar > .nav-item:hover > .nav-link, [class*="sidebar-dark-"] .nav-sidebar > .nav-item > .nav-link:focus {
        background-color: #3c5795;
        color: #fff;
    }
  .sidebar {
    padding-top: 2px;
    padding-bottom: 0;
    height: auto;
    overflow: unset !important;
   
  }
    .card-header {
        background-color: #ebf6f8;
    }
    .brand-image{
        border-bottom:1px solid black;
    }
    .buttons-html5{padding:5px 8px !important;}
    .btn{margin-bottom:3px;}
    
    .bg-info{background-color:#3c5795 !important; color:white !important;}
    .bg-info:hover{background-color:white !important; color:black !important;}
    .btn-success{background-color:#3c5795;color:white;}
    .btn-success:hover{background-color:white;color:black;border-color:black;}
    .humburger-menu:hover{color:white !important;}
    .profile-user-img {
        width: 150px !important;
        height: 150px !important;
        object-fit: cover !important; 
        border: 3px solid #ddd !important;
        border-radius: 50% !important;
        display: block !important;
        margin: 0 auto !important;
    }
    
    /* Small profile image in form */
    .img-thumbnail {
        object-fit: cover !important;
        border-radius: 8px !important;
    }
    
    /* Simple sidebar behavior - NO HOVER */
  .main-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100vh;
    z-index: 1000;
    transition: margin-left 0.3s ease-in-out;
    overflow-y: auto;
    overflow-x: hidden;
  }
    
    .sidebar-collapse .main-sidebar {
        margin-left: -250px;
    }
    
    body:not(.sidebar-collapse) .main-sidebar {
        margin-left: 0;
    }
    
    /* Content adjustment */
    .content-wrapper {
        transition: margin-left 0.3s ease-in-out;
    }
    
    .sidebar-collapse .content-wrapper {
        margin-left: 0;
    }
    
    body:not(.sidebar-collapse) .content-wrapper {
        margin-left: 250px;
    }
    
    /* Navbar adjustment */
    .main-header.navbar {
        transition: margin-left 0.3s ease-in-out;
    }
    
    .sidebar-collapse .main-header.navbar {
        margin-left: 0;
    }
    
    body:not(.sidebar-collapse) .main-header.navbar {
        margin-left: 250px;
    }
    
    /* Enhanced alert styling */
    .alert {
        position: relative;
        padding: 15px 45px 15px 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        animation: slideInDown 0.5s ease-out;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 4px solid #28a745;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }
    
    .alert-warning {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        color: #856404;
        border-left: 4px solid #ffc107;
    }
    
    .alert-info {
        background: linear-gradient(135deg, #d1ecf1, #bee5eb);
        color: #0c5460;
        border-left: 4px solid #17a2b8;
    }
    
    @keyframes  slideInDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    /* Alert close button */
    .alert-close {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        background: none;
        border: none;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
        transition: opacity 0.3s ease;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .alert-close:hover {
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
    }
    
    .humburger-menu {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .humburger-menu:hover {
        background-color: #3c5795 !important;
        color: white !important;
        border-radius: 4px;
    }
    
    /* NO HOVER BEHAVIOR - sidebar only responds to clicks */
    .main-sidebar:hover {
        /* Explicitly do nothing on hover */
    }
  </style>
</head>
<body class="hold-transition layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link humburger-menu" href="#" role="button" id="sidebar-toggle"><i class="fas fa-bars"></i></a>
      </li>
      <!--<li class="nav-item d-none d-sm-inline-block">-->
      <!--  <a href="#" class="nav-link">Home</a>-->
      <!--</li>-->
      <!--<li class="nav-item d-none d-sm-inline-block">-->
      <!--  <a href="#" class="nav-link">Contact</a>-->
      <!--</li>-->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">


      <!-- Notifications Dropdown Menu -->
      <!--<li class="nav-item dropdown">-->
      <!--  <a class="nav-link" data-toggle="dropdown" href="#">-->
      <!--    <i class="far fa-bell"></i>-->
      <!--    <?php $notifications = \App\Models\Notification::where('admin_read',0)->orderBy('id','desc')->get(); ?>-->
      <!--    <span class="badge badge-warning navbar-badge"><?php echo e($notifications->count()); ?></span>-->
      <!--  </a>-->
      <!--  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">-->
      <!--    <span class="dropdown-item dropdown-header"><?php echo e($notifications->count()); ?> Notifications</span>-->
      <!--    <div class="dropdown-divider"></div>-->
      <!--    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>-->
      <!--    <a href="<?php echo e(route('notification.show',$notification->id)); ?>" class="dropdown-item">-->
      <!--      <i class="fas fa-envelope mr-2"></i> <?php echo e($notification->title); ?>-->
      <!--      <span class="float-right text-muted text-sm"><?php echo e($notification->created_at->diffForHumans()); ?></span>-->
      <!--    </a>-->
      <!--    <div class="dropdown-divider"></div>-->
      <!--    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>-->
      <!--    <?php endif; ?>-->
      <!--    <a href="<?php echo e(route('notification.index')); ?>" class="dropdown-item dropdown-footer">See All Notifications</a>-->
      <!--  </div>-->
      <!--</li>-->
      <!--<li class="nav-item">-->
      <!--  <a class="nav-link humburger-menu" data-widget="fullscreen" href="#" role="button">-->
      <!--    <i class="fas fa-expand-arrows-alt"></i>-->
      <!--  </a>-->
      <!--</li>-->
      <!--<li class="nav-item">-->
      <!--  <a class="nav-link humburger-menu" data-widget="control-sidebar" data-slide="true" href="#" role="button">-->
      <!--    <i class="fas fa-th-large"></i>-->
      <!--  </a>-->
      <!--</li>-->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    
    <a href="<?php echo e(url('/')); ?>">
        <center>
            <img src="<?php echo e($setting->logo); ?>" alt="AdminLTE Logo" class="brand-image" style="width:100%" height="80px">
        </center>
    </a>
    <?php $user = Auth::User(); ?>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <!--<div class="user-panel mt-3 pb-3 mb-3 d-flex">-->
      <!--  <div class="image">-->
      <!--    <img src="<?php echo e(asset('images/profiles/'.$user->photo)); ?>" class="img-circle elevation-2" alt="User Image">-->
      <!--  </div>-->
      <!--  <div class="info">-->
      <!--    <a href="<?php echo e(url('profile')); ?>" class="d-block"><?php echo e($user->name); ?></a>-->
      <!--  </div>-->
      <!--</div>-->

      <!-- SidebarSearch Form -->
      <!--<div class="form-inline">-->
      <!--  <div class="input-group" data-widget="sidebar-search">-->
      <!--    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">-->
      <!--    <div class="input-group-append">-->
      <!--      <button class="btn btn-sidebar">-->
      <!--        <i class="fas fa-search fa-fw"></i>-->
      <!--      </button>-->
      <!--    </div>-->
      <!--  </div>-->
      <!--</div>-->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <!-- <li class="nav-item">
          <!--  <a href="#" class="nav-link">-->
          <!--    <i class="nav-icon fas fa-tachometer-alt"></i>-->
          <!--    <p>-->
          <!--      Dashboard-->
          <!--      <i class="right fas fa-angle-left"></i>-->
          <!--    </p>-->
          <!--  </a>-->
          <!--  <ul class="nav nav-treeview">-->
          <!--    <li class="nav-item">-->
          <!--      <a href="<?php echo e(url('admin/index.html')); ?>" class="nav-link">-->
          <!--        <i class="far fa-circle nav-icon"></i>-->
          <!--        <p>Dashboard v1</p>-->
          <!--      </a>-->
          <!--    </li>-->
          <!--  </ul>-->
          <!--</li> -->
          
            <?php if($user->hasPermission('menu.dashboard')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('/')); ?>" class="nav-link <?php echo e(request()->is('dashboard*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <?php endif; ?>
         
          <?php if($user->hasPermission('menu.profile')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('/profile')); ?>" class="nav-link <?php echo e(request()->is('profile*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-user-circle"></i>
              <p>Profile</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.admins')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('/admins')); ?>" class="nav-link <?php echo e(request()->is('admin*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Admins</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.customers')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('/customers')); ?>" class="nav-link <?php echo e(request()->is('customers*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Customers</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.building.admins')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('/building-admins')); ?>" class="nav-link <?php echo e(request()->is('building-admins*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Building Admins</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.ads')): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('ads.index')); ?>" class="nav-link <?php echo e(request()->is('ads*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>Ads</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.builder')): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('builder.index')); ?>" class="nav-link <?php echo e(request()->is('builder*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-hard-hat"></i>
              <p>Builder</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.buildings')): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('buildings.index')); ?>" class="nav-link <?php echo e(request()->is('buildings*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-building"></i>
              <p>Buildings</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.city')): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('city.index')); ?>" class="nav-link <?php echo e(request()->is('city*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-city"></i>
              <p>City</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.permissions')): ?>
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo e(route('permission.index')); ?>" class="nav-link <?php echo e(request()->is('permission*') ? 'active' : ''); ?>">-->
          <!--    <i class="nav-icon fas fa-duotone fa-user"></i>-->
          <!--    <p>Permissions</p>-->
          <!--  </a>-->
          <!--</li>-->
          
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo e(route('notification.index')); ?>" class="nav-link <?php echo e(request()->is('notification*') ? 'active' : ''); ?>">-->
          <!--    <i class="nav-icon fas far fa-bell"></i>-->
          <!--    <p>Notifications <i class="badge badge-danger right"><?php echo e($notifications->count()); ?></i></p>-->
          <!--  </a>-->
          <!--</li>-->
          <?php endif; ?>
          <?php if($user->hasPermission('menu.settings')): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('setting.index')); ?>" class="nav-link <?php echo e(request()->is('setting*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.classified')): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('classified.index')); ?>" class="nav-link <?php echo e(request()->is('classified*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-tags"></i>
              <p>Classified</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.taxes')): ?>
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo e(url('taxes')); ?>" class="nav-link <?php echo e(request()->is('taxes*') ? 'active' : ''); ?>">-->
          <!--    <i class="nav-icon fas fa-solid fa-leaf"></i>-->
          <!--    <p>Taxes</p>-->
          <!--  </a>-->
          <!--</li>-->
          <?php endif; ?>
          <?php if($user->hasPermission('menu.privacy.policy')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('privacy-policy')); ?>" class="nav-link <?php echo e(request()->is('privacy-policy*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-shield-alt"></i>
              <p>Privacy Policy</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.terms.conditions')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('terms-conditions')); ?>" class="nav-link <?php echo e(request()->is('terms-conditions*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-file-contract"></i>
              <p>Terms & Conditions</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.about.us')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('about-us')); ?>" class="nav-link <?php echo e(request()->is('about-us*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-info-circle"></i>
              <p>About us</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.how.it.works')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('how-it-works')); ?>" class="nav-link <?php echo e(request()->is('how-it-works*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-question-circle"></i>
              <p>How it works</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.return.and.refund.policy')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('return-and-refund-policy')); ?>" class="nav-link <?php echo e(request()->is('return-and-refund-policy*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-undo-alt"></i>
              <p>Return and Refund Policy</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.cancellation.policy')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('cancellation-policy')); ?>" class="nav-link <?php echo e(request()->is('cancellation-policy') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-times-circle"></i>
              <p>Cancellation Policy</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.delete.account.policy')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('delete-account-policy')); ?>" class="nav-link <?php echo e(request()->is('delete-account-policy') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-user-times"></i>
              <p>Delete Account Policy</p>
            </a>
          </li>
          <?php endif; ?>
          <?php if($user->hasPermission('menu.faqs')): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('faqs')); ?>" class="nav-link <?php echo e(request()->is('faqs*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-question"></i>
              <p>FAQ's</p>
            </a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a href="<?php echo e(url('logout')); ?>" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>

            <form id="logout-form" action="<?php echo e(url('logout')); ?>" method="POST" style="display: none;">
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
            </form>

          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php echo $__env->yieldContent('content'); ?>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; <?php echo e(date('Y')); ?> <a href="https://myflatinfo.com/home/" target="_blank">Myflat info</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="<?php echo e(asset('admin/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo e(asset('admin/plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/pdfmake/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/pdfmake/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/datatables-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/plugins/inputmask/jquery.inputmask.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('admin/dist/js/adminlte.min.js')); ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo e(asset('admin/dist/js/demo.js')); ?>"></script>

<!-- Select2 -->
<script src="<?php echo e(asset('admin/plugins/select2/js/select2.full.min.js')); ?>"></script>

<!-- Bootstrap Switch -->
<script src="<?php echo e(asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js')); ?>"></script>

<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>

<script>
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
</script>
<script type="module">

  var firebaseConfig = {
        apiKey: "AIzaSyDRoJqSqcb613Y66-ey3ZgqNPj2lJ85V3k",
        authDomain: "myflatinfo-37675.firebaseapp.com",
        projectId: "myflatinfo-37675",
        storageBucket: "myflatinfo-37675.firebasestorage.app",
        messagingSenderId: "643088971389",
        appId: "1:643088971389:web:923f39d61f805a2ec6e314"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    
    async function startFCM() {
        try {
            // Request permission
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                console.log('Notification permission denied');
                return;
            }

            // Get token
            const token = await messaging.getToken({
                vapidKey: 'BALAriAKrgC8UL3txmvobWMeu2wRZ4g-7wX4TxOI6-JzE9b5oZWUMwUANBJ2w0V3glmlsBGIV0SNlofoApPf9e0'
            });

            // Save token to server
            const response = await fetch('<?php echo e(route("save.token")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    device_token: token,
                    device_type: 'web'
                })
            });

            if (response.ok) {
                console.log('FCM Token stored successfully');
            } else {
                throw new Error('Failed to store token');
            }
        } catch (error) {
            console.error('FCM Token Error:', error);
        }
    }
    // Handle foreground messages
    messaging.onMessage(function (payload) {
        console.log('Received foreground message:', payload);
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: '/favicon.ico',
            data: payload.data,
            sound: 'bellnotificationsound.wav'
        };
        new Notification(title, options);
    });
    
</script>

<script>
$(document).ready(function(){
    
    // Add close button to alerts
    $('.alert').each(function() {
        if (!$(this).find('.alert-close').length) {
            $(this).append('<button type="button" class="alert-close" aria-label="Close">&times;</button>');
        }
    });
    
    // Manual close functionality
    $(document).on('click', '.alert-close', function() {
        $(this).parent('.alert').fadeOut('fast', function() {
            $(this).remove();
        });
    });
    
    // Auto-dismiss alert messages after 3 seconds
    setTimeout(function() {
        $('.alert-success, .alert-danger, .alert-warning, .alert-info').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 3000); // 3 seconds
    
    // Simple hamburger menu functionality
    $('#sidebar-toggle').click(function(e) {
        e.preventDefault();
        console.log('Hamburger clicked!');
        
        var body = $('body');
        
        if (body.hasClass('sidebar-collapse')) {
            body.removeClass('sidebar-collapse');
            console.log('Sidebar opened');
        } else {
            body.addClass('sidebar-collapse');
            console.log('Sidebar closed');
        }
        
        return false;
    });
    
    // Alternative click handler in case the first doesn't work
    $(document).on('click', '#sidebar-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Alternative hamburger clicked!');
        
        $('body').toggleClass('sidebar-collapse');
        
        return false;
    });
    
    // Simple: Remove any AdminLTE hover behavior
    $('.main-sidebar').off('mouseenter mouseleave');
    $(document).off('mouseenter mouseleave', '.main-sidebar');
    
    $(function () {
        $(".table").DataTable({
          "responsive": false, "scrollX": true, "ordering": true, "lengthChange": false, "autoWidth": false,"bPaginate": true,"bInfo": false,"searching": true,"pageLength": parseInt("<?php echo e($setting->pagination); ?>"),
          order: [[0, 'asc']],
          buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
          "bPaginate": false,
          "bInfo": false,
        });
    });
    
    document.querySelectorAll('input[type="password"], input[type="number"]').forEach(input => {
        ['copy','paste','cut','drop'].forEach(evt => {
            input.addEventListener(evt, e => e.preventDefault());
        });
    });

  document.querySelectorAll('input[type="text"], textarea').forEach(input => {
    input.addEventListener('input', e => {
      // For address field, allow all visible ASCII except emojis
      if (input.name === 'address' || input.id === 'address') {
        // Accept letters, numbers, and all common special characters
        e.target.value = e.target.value.replace(/[^a-zA-Z0-9 ~!@#$%^&*()_+\-=`{}\[\]:;"'<>,.?\/|]/g, '');
      } else {
        // For other text fields, keep previous restrictions
        e.target.value = e.target.value.replace(
          /[^a-zA-Z0-9 .,!?@#%&*()_+\-=:;"'<>\/\\[\]{}|`~$^]/g,
          ''
        );
      }
    });
  });
        
    document.querySelectorAll('input[type="password"]').forEach(input => {
        input.addEventListener('input', e => {
            // Remove spaces
            e.target.value = e.target.value.replace(/\s+/g, '');
        });
    });
    
    document.querySelectorAll('input[type="email"]').forEach(input => {
        input.addEventListener('input', e => {
            // Remove spaces
            e.target.value = e.target.value.replace(/\s+/g, '').toLowerCase();
        });
    });
});
</script>

<script>
    window.addEventListener("load", function () {
        let activeMenu = document.querySelector(".sidebar .active");
        if (activeMenu) {
            activeMenu.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    });
    
  function isValidNameChar(event, input) {
    const char = event.key;

    // Allow navigation and control keys (Backspace, Delete, arrows, Tab, Enter)
    if (event.ctrlKey || event.metaKey || ['Backspace','Delete','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Tab','Enter'].includes(char)) {
      return true;
    }

    // Allow letters (both cases), space, hyphen and apostrophe
    if (!/^[a-zA-Z '\-]$/.test(char)) {
      return false;
    }

    // Block space at start
    if (char === ' ' && input.value.length === 0) {
      return false;
    }

    // Block consecutive spaces
    if (char === ' ' && input.value.slice(-1) === ' ') {
      return false;
    }

    // Disallow more than one internal space (keep names simple) - optional: allow multiple spaces by removing this check
    // if (char === ' ' && input.value.includes(' ')) {
    //     return false;
    // }

    // Allow lowercase as well as uppercase; don't strictly enforce auto-capitalization here to avoid blocking input
    return true;
  }
</script>



<?php echo $__env->yieldContent('script'); ?>
</body>
</html>


<?php /**PATH C:\Users\vvive\Herd\myflatinfos\superadmin\resources\views/layouts/admin.blade.php ENDPATH**/ ?>