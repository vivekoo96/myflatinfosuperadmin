<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $setting = \App\Models\Setting::first(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/css/login.css')); ?>"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <title>Forgot Password | <?php echo e($setting->business_name); ?></title>
    <link rel="shortcut icon" href="<?php echo e($setting->favicon); ?>">
    <style>
        @import  url('https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
        body {
            font-family: 'Jost', sans-serif;
            background-color: black;
        }
        
        /* Simple improvements */
        .main {
            background-color: black;
            min-height: 100vh;
        }
        
        .signin-image figure img {
            max-width: 200px;
            border-radius: 8px;
        }
        
        .form-title {
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .register-form input[type="email"] {
            border-radius: 8px;
            padding: 18px 25px 18px 50px;
            border: 2px solid #ccc;
            transition: border-color 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            color: #333;
            background-color: white;
        }
        
        .register-form input[type="email"]:focus {
            border-color: #999;
            outline: none;
            color: #000;
        }
        
        .register-form input[type="email"]::placeholder {
            color: #aaa;
            opacity: 1;
            font-weight: 400;
        }
        
        .register-form input[type="email"]:focus::placeholder {
            color: #666;
        }
        
        .form-submit {
            background-color: black;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        
        .form-submit:hover {
            background-color: #333;
        }
        
        .signup-image-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: 500;
        }
        
        .signup-image-link:hover {
            color: #c82333;
            text-decoration: none;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            padding: 12px 15px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            padding: 12px 15px;
        }
        
        .password-icon {
            float: right;
            margin-top: -22px;
        }
        
        .fa, .fas {
            font-weight: 900;
            padding: 10px;
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
                        <h2 class="form-title" style="font-size: 23px;">Enter register email to get link</h2>
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
                        <form method="post" action="<?php echo e(url('forget-password')); ?>" class="register-form" id="login-form">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="your_pass"><i class="fa fa-envelope"></i></label>
                                <input type="email" name="email" placeholder="Email" required>
                            </div>
                                <?php if($errors->has('email')): ?>
                                    <p style="color:red"><?php echo e($errors->first('email')); ?></p>
                                <?php endif; ?>
                            
                            <div class="form-group form-button">
                            	<input type="submit" id="signin" class="form-submit" value="Reset Password"/>
                            </div>
                            
                        </form>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html><?php /**PATH /Users/vivekpatel/Herd/newupdatedcode/resources/views/admin/forgot_password.blade.php ENDPATH**/ ?>