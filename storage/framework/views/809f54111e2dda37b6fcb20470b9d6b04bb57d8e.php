<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $setting = \App\Models\Setting::first(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | <?php echo e($setting->business_name); ?></title>
    <link rel="shortcut icon" href="<?php echo e($setting->favicon); ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            background: url('<?php echo e(url("/public/admin/ChatGPT Image Nov 15_ 2025_ 04_23_23 PM.png")); ?>') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        /* Dark overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(37, 62, 88, 0.6);
            z-index: 1;
        }
        
        .container {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        .reset-title {
            color: white;
            font-size: 48px;
            font-weight: 300;
            margin-bottom: 20px;
            letter-spacing: 2px;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .reset-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 18px;
            font-weight: 300;
            margin-bottom: 40px;
            line-height: 1.5;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .form-container {
            margin-bottom: 30px;
        }
        
        .form-input {
            width: 100%;
            padding: 18px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.95);
            color: black;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .form-input:focus {
            outline: none;
            background: white;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
        }
        
        .form-input::placeholder {
            color: black;
            font-weight: 300;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .form-input:disabled {
            background: rgba(255, 255, 255, 0.7);
            color: black;
        }
        
        .password-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .password-input {
            width: 100%;
            padding: 18px 50px 18px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.95);
            color: black;
            transition: all 0.3s ease;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .password-input:focus {
            outline: none;
            background: white;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
        }
        
        .password-input::placeholder {
            color: black;
            font-weight: 300;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: black;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: black;
        }
        
        .submit-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .submit-btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }
        
        .back-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 16px;
            font-weight: 300;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .back-link:hover {
            color: white;
            text-decoration: none;
            transform: translateX(-5px);
        }
        
        .back-link::before {
            content: '←';
            font-size: 18px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 400;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.9);
            color: white;
            border: none;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
        }
        
        .error-text {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: -20px;
            margin-bottom: 20px;
            text-align: left;
            font-family: 'Times New Roman', Times, serif;
        }
        
        @media (max-width: 768px) {
            .reset-title {
                font-size: 36px;
            }
            
            .reset-subtitle {
                font-size: 16px;
            }
            
            .container {
                width: 95%;
                padding: 0 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="reset-title">RESET PASSWORD</h1>
        <p class="reset-subtitle">Enter your new password below.</p>
        
        <div class="form-container">
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
            
            <form method="post" action="<?php echo e(url('reset-password')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="token" value="<?php echo e($password_reset->token); ?>">
                <input type="hidden" name="email" value="<?php echo e($password_reset->email); ?>">
                
                <input type="email" name="old_email" value="<?php echo e($password_reset->email); ?>" class="form-input" placeholder="Email Address" disabled required>
                
                <div class="password-group">
                    <input type="password" name="password" class="password-input new-password" minlength="8" maxlength="14" placeholder="New Password" required>
                    <button type="button" class="password-toggle new-show-password"><i class="fa fa-eye-slash"></i></button>
                    <button type="button" class="password-toggle new-hide-password" style="display:none;"><i class="fa fa-eye"></i></button>
                </div>
                <?php if($errors->has('password')): ?>
                    <div class="error-text"><?php echo e($errors->first('password')); ?></div>
                <?php endif; ?>
                
                <div class="password-group">
                    <input type="password" name="password_confirmation" class="password-input confirm-password" minlength="8" maxlength="14" placeholder="Confirm New Password" required>
                    <button type="button" class="password-toggle confirm-show-password"><i class="fa fa-eye-slash"></i></button>
                    <button type="button" class="password-toggle confirm-hide-password" style="display:none;"><i class="fa fa-eye"></i></button>
                </div>
                
                <button type="submit" class="submit-btn">RESET PASSWORD</button>
            </form>
        </div>
        
        <a href="<?php echo e(url('/')); ?>" class="back-link">Back to Login</a>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            document.addEventListener('click', function(e) {
                var target = e.target;
                var button = target.closest('button');
                
                if (!button) return;
                
                // New password field toggle
                if (button.classList.contains('new-show-password')) {
                    var group = button.closest('.password-group');
                    var input = group.querySelector('.new-password');
                    var hideBtn = group.querySelector('.new-hide-password');
                    
                    input.type = 'text';
                    button.style.display = 'none';
                    hideBtn.style.display = 'block';
                }
                
                if (button.classList.contains('new-hide-password')) {
                    var group = button.closest('.password-group');
                    var input = group.querySelector('.new-password');
                    var showBtn = group.querySelector('.new-show-password');
                    
                    input.type = 'password';
                    button.style.display = 'none';
                    showBtn.style.display = 'block';
                }
                
                // Confirm password field toggle
                if (button.classList.contains('confirm-show-password')) {
                    var group = button.closest('.password-group');
                    var input = group.querySelector('.confirm-password');
                    var hideBtn = group.querySelector('.confirm-hide-password');
                    
                    input.type = 'text';
                    button.style.display = 'none';
                    hideBtn.style.display = 'block';
                }
                
                if (button.classList.contains('confirm-hide-password')) {
                    var group = button.closest('.password-group');
                    var input = group.querySelector('.confirm-password');
                    var showBtn = group.querySelector('.confirm-show-password');
                    
                    input.type = 'password';
                    button.style.display = 'none';
                    showBtn.style.display = 'block';
                }
            });
        });
    </script>
    
</body>
</html><?php /**PATH /home/myflatin/dev.superadmin.myflatinfo.com/resources/views/admin/reset_password.blade.php ENDPATH**/ ?>