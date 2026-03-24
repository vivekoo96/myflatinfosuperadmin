<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $setting = \App\Models\Setting::first(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/css/login.css')); ?>"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <title>Reset Password</title>
    <style>
        @import  url('https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body{font-family:Jost;}
        .password-icon{
            float: right;
            margin-top: -22px;
        }
        input[type="password"]{
            color: black;
        }
        input[type="email"]{
            color: black;
        }
        ::placeholder{
            color: black !important;
        }
    </style>
</head>
<body>
    <div class="main">

        <!-- Sign up form -->
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="<?php echo e($setting->logo); ?>" alt="sing up image"></figure>
                        <a href="<?php echo e(url('/')); ?>" class="signup-image-link">Login Here</a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Reset Password</h2>
                        <?php if(session()->has('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo e(session()->get('error')); ?>

                            </div>
                        <?php endif; ?>
                        <form method="post" action="<?php echo e(url('reset-password')); ?>" class="register-form" id="login-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="token" value="<?php echo e($password_reset->token); ?>">
                            <input type="hidden" name="email" value="<?php echo e($password_reset->email); ?>">
                            <div class="form-group">
                                <label for="your_pass"><i class="fa fa-envelope"></i></label>
                                <input type="email" name="old_email" value="<?php echo e($password_reset->email); ?>" disabled required>
                            </div>
                            
                            <div class="form-group">
                                <label for="pass"><i class="fa fa-lock"></i></label>
                                <input type="password" name="password" class="new-password" minlength="8" maxlength="14"id="pass" style="width:95%;"
                                placeholder="Password" required/>
                                <a href="javascript:void(0)" class="new-hide-password password-icon"><i class="fa fa-eye-slash" style="color:black;" aria-hidden="true"></i></a>
                                <a href="javascript:void(0)" class="new-show-password password-icon" style="display:none;"><i class="fa fa-eye" style="color:black;"></i></a>
                            </div>
                                <?php if($errors->has('password')): ?>
                                    <p style="color:red"><?php echo e($errors->first('password')); ?></p>
                                <?php endif; ?>
                            <div class="form-group">
                                <label for="re-pass"><i class="fa fa-lock"></i></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" class="confirm-password" minlength="8" maxlength="14" id="re_pass" style="width:95%;" placeholder="Confirm your password"/>
                                    <a href="javascript:void(0)" class="confirm-hide-password password-icon"><i class="fa fa-eye-slash" style="color:black;" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0)" class="confirm-show-password password-icon" style="display:none;"><i class="fa fa-eye" style="color:black;"></i></a>
                                </div>
                            </div>
                            
                            <div class="form-group form-button">
                            	<input type="submit" id="signin" class="form-submit" value="Change Password"/>
                            </div>
                            
                        </form>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
    
        <!-- jQuery -->
    <script src="<?php echo e(asset('admin/plugins/jquery/jquery.min.js')); ?>"></script>
    
    <script>
        $(document).ready(function(){
            // New password field toggle (scoped)
            $(document).on('click','.new-hide-password',function(){
                var $group = $(this).closest('.form-group');
                $group.find('.new-password').attr('type','text');
                $(this).hide();
                $group.find('.new-show-password').show();
            });
            $(document).on('click','.new-show-password',function(){
                var $group = $(this).closest('.form-group');
                $group.find('.new-password').attr('type','password');
                $(this).hide();
                $group.find('.new-hide-password').show();
            });

            // Confirm password field toggle (scoped)
            $(document).on('click','.confirm-hide-password',function(){
                var $group = $(this).closest('.input-group');
                $group.find('.confirm-password').attr('type','text');
                $(this).hide();
                $group.find('.confirm-show-password').show();
            });
            $(document).on('click','.confirm-show-password',function(){
                var $group = $(this).closest('.input-group');
                $group.find('.confirm-password').attr('type','password');
                $(this).hide();
                $group.find('.confirm-hide-password').show();
            });
        });
    </script>
    
</body>
</html><?php /**PATH /Users/vivekpatel/Herd/newupdatedcode/resources/views/admin/reset_password.blade.php ENDPATH**/ ?>