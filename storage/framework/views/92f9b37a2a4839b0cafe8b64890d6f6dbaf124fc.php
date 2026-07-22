<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Leader Login | <?php echo e(env('APP_NAME')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: darkblue;
            --secondary-color: #f8f9fa;
            --text-color: #333;
        }
        body {
            background-color: var(--secondary-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            border: 1px solid #e2e8f0;
        }
        .login-header {
            background-color: #111827;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #00008B;
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-card">
        
        <div class="login-header">
            <h2 class="font-weight-bold uppercase mb-1" style="font-size: 1.4rem; tracking-wider;">
                <i class="fas fa-users-cog text-info me-2"></i>Team Leaders Portal
            </h2>
            <p class="text-gray-400 text-xs mb-0">Sign in to manage your team and track your application status.</p>
        </div>

        <div class="p-4">
            
            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show text-xs rounded-xl" role="alert">
                    <i class="fas fa-check-circle me-1"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show text-xs rounded-xl" role="alert">
                    <i class="fas fa-exclamation-triangle me-1"></i> <?php echo e($errors->first()); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('team-leader.login.post')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                
                <div class="mb-3">
                    <label class="form-label font-weight-bold text-dark text-sm">Username or Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" name="login" required value="<?php echo e(old('login')); ?>" class="form-control" placeholder="Enter username or email">
                    </div>
                </div>

                
                <div class="mb-3">
                    <label class="form-label font-weight-bold text-dark text-sm">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" required class="form-control" placeholder="Enter password">
                    </div>
                </div>

                <button type="submit" class="btn btn-custom w-100 mt-2 py-2.5">
                    <i class="fas fa-sign-in-alt me-1.5"></i> Sign In to Dashboard
                </button>
            </form>

            <div class="text-center mt-4 pt-3 border-t text-xs text-muted">
                Don't have an application yet?<br>
                <a href="<?php echo e(route('team.leader')); ?>" class="text-primary font-bold decoration-none underline block mt-1">
                    <i class="fas fa-user-plus mr-1"></i> Apply as a Team Leader
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/home/team-leader-login.blade.php ENDPATH**/ ?>