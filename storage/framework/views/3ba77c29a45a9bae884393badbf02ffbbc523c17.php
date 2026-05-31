<?php
use App\Models\TeamLeader;
$username = session('team_leader_Usen_Name');
$teamLeader = TeamLeader::where('User_name', $username)->first();
$email = $teamLeader->Email;
$phone = $teamLeader->Phone;
$country = $teamLeader->Country;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status | <?php echo e(env('APP_NAME')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0084D1;
        }
        .status-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
        }
        .status-icon {
            font-size: 3rem;
            color: var(--primary-color);
        }
        .progress-timeline {
            position: relative;
            padding: 20px 0;
        }
        .progress-step {
            position: relative;
            padding: 20px;
            border-left: 2px solid #dee2e6;
        }
        .progress-step.active {
            border-left: 2px solid var(--primary-color);
        }
        .progress-step::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 24px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #dee2e6;
        }
        .progress-step.active::before {
            background: var(--primary-color);
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-end" >
<div class="col-sm-4" >
<div class=" shadow-lg mb-4">
                    <div class="card-body text-center p-2">
                        <i class="fas fa-user status-icon mb-4"></i>
                        <h2 class="mb-4">
                            Profile</h2>
                        <p class="lead text-muted"><?php echo e($name); ?></p>
                        <p class="lead text-muted"><?php echo e($email); ?></p>
                        <p class="lead text-muted"><?php echo e($username); ?></p>
                        <p class="lead text-muted"><?php echo e($phone); ?></p>
                        <p class="lead text-muted"><?php echo e($country); ?></p>
                        
                    </div>
                </div>
</div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Status Card -->
                <div class="card status-card shadow-lg mb-4">
                    <div class="card-body text-center p-5">
                        <i class="fas fa-clock status-icon mb-4"></i>
                        <h2 class="mb-4">
                            Application Under Review</h2>
                        <p class="lead text-muted">Thank you for applying to become a Team Leader!</p>
                        <div class="alert alert-info mt-4">
                            Your application is currently being reviewed by our team.
                        </div>
                    </div>
                </div>

                <!-- Progress Timeline -->
                <div class="card status-card shadow-lg">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Application Progress</h3>
                        <div class="progress-timeline">
                            <div class="progress-step active">
                                <h5>Application Submitted</h5>
                                <p class="text-muted">Your application has been received</p>
                            </div>
                            <div class="progress-step">
                                <h5>Under Review</h5>
                                <p class="text-muted">Our team is reviewing your application</p>
                            </div>
                            <div class="progress-step">
                                <h5>Final Decision</h5>
                                <p class="text-muted">You'll be notified via email</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="row mt-4 g-4">
                    <div class="col-md-6">
                        <div class="card status-card shadow-sm h-100">
                            <div class="card-body">
                                <h5><i class="fas fa-info-circle me-2 text-primary"></i>What's Next?</h5>
                                <ul class="mt-3">
                                    <li>Review process takes 1-2 business days</li>
                                    <li>You'll receive an email notification</li>
                                    <li>Keep an eye on your inbox</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card status-card shadow-sm h-100">
                            <div class="card-body">
                                <h5><i class="fas fa-question-circle me-2 text-primary"></i>Need Help?</h5>
                                <p class="mt-3">Contact our support team:</p>
                                <a href="mailto:support@fonepo.com" class="btn btn-outline-primary">
                                    <i class="fas fa-envelope me-2"></i>support@bifonex.com
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /home/blackjay/Downloads/test.focoin.eu/resources/views/team-leader/pending-approval.blade.php ENDPATH**/ ?>