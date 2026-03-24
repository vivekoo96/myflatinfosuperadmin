<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About My Flat Info</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="<?php echo e($setting->favicon); ?>">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #000000;
            min-height: 100vh;
        }
        .app-info-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .info-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .info-header {
            background: #fff;
            color: black;
            padding: 40px;
            text-align: center;
        }
        .info-header img {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
            filter: none;
        }
        .info-body {
            padding: 40px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section h4 {
            color: #000000;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .info-section p {
            color: #000000;
            line-height: 1.6;
        }
        .info-section ul {
            color: #000000;
        }
        .info-section ul li {
            margin-bottom: 8px;
        }
        .info-section a {
            color: #000000;
            text-decoration: underline;
        }
        .info-section a:hover {
            color: #333333;
            text-decoration: underline;
        }
        .back-btn {
            background: #000000;
            border: 1px solid #ffffff;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background: #333333;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="app-info-container">
        <a href="javascript:history.back()" class="back-btn">← Back to Login</a>
        
        <div class="info-card">
            <div class="info-header bg-white">
                <img src="<?php echo e($setting->logo); ?>" alt="My Flat Info Logo">
                <h1>My Flat Info</h1>
                <!-- Removed non-serious content -->
            </div>
            
            <div class="info-body">
                <div class="info-section">
                    <h4>About the Application</h4>
                    <p><?php echo strip_tags($setting->about_us ?? 'My Flat Info is a comprehensive apartment and building management system for residents, administrators, and property managers.'); ?></p>
                </div>
                
                <div class="info-section">
                    <h4>Key Features</h4>
                    <ul>
                        <li>Resident Management System</li>
                        <li>Building Administration Tools</li>
                        <li>Maintenance Request Management</li>
                        <li>Communication Platform</li>
                        <li>Payment Management</li>
                        <li>Visitor Management</li>
                        <li>Notice Board & Announcements</li>
                        <li>Event Management</li>
                    </ul>
                </div>
                
                <div class="info-section">
                    <h4>Contact Information</h4>
                    <p><strong>Business Name:</strong> <?php echo e($setting->business_name); ?></p>
                    <p><strong>Email:</strong> <?php echo e($setting->email ?? 'info@myflatinfo.com'); ?></p>
                    <p><strong>Phone:</strong> <?php echo e($setting->phone ?? ''); ?></p>
                </div>
                
                <div class="info-section">
                    <h4>Version Information</h4>
                    <p><strong>Version:</strong> 1.0.0</p>
                    <p><strong>Last Updated:</strong> <?php echo e(date('F Y')); ?></p>
                    <p><strong>Developed by:</strong> <a href="https://analogueitsolutions.com/" target="_blank"></a>Analogue IT Solutions Pvt Ltd</a></p>
                </div>
                
                <div class="info-section">
                    <h4>Terms & Policies</h4>
                    <p>This application is governed by our terms of service and privacy policy. All user data is handled in accordance with applicable data protection regulations.</p>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-muted">© <?php echo e(date('Y')); ?> My Flat Info. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/vivekpatel/Herd/newupdatedcode/resources/views/public/app-info.blade.php ENDPATH**/ ?>