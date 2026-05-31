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
            --primary-color: darkblue;
            --secondary-color: #f8f9fa;
            --text-color: #333;
        }
        body {
            background-color: var(--secondary-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .status-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            height: 100%;
        }
        .status-card:hover {
            transform: translateY(-5px);
            /* box-shadow: 0 10px 20px rgba(0,0,0,0.1); */
        }
        .status-icon {
            font-size: 3.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        .profile-card {
            background: linear-gradient(145deg, #ffffff, #f3f3f3);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            height: 500px;
        }
        .profile-info {
            margin: 0.5rem 0;
            padding: 0.5rem;
            border-radius: 8px;
            background-color: rgba(0,0,139,0.05);
        }
        .progress-timeline {
            position: relative;
            padding: 30px 0;
        }
        .progress-step {
            position: relative;
            padding: 25px;
            border-left: 3px solid #dee2e6;
            margin-left: 20px;
        }
        .progress-step.active {
            border-left: 3px solid var(--primary-color);
        }
        .progress-step::before {
            content: '';
            position: absolute;
            left: -11px;
            top: 30px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #dee2e6;
            transition: all 0.3s ease;
        }
        .progress-step.active::before {
            background: var(--primary-color);
            box-shadow: 0 0 0 5px rgba(0,0,139,0.2);
        }
        .help-card {
            background: white;
            border-radius: 15px;
            transition: all 0.3s ease;
            height: 100%;
        }
        .help-card:hover {
            transform: scale(1.02);
        }
        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            border-radius: 25px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #00008B;
            transform: translateY(-2px);
        }
        .flex-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        @media (max-width: 768px) {
            .flex-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-lg-8 order-lg-1 order-2">
                <div class="flex-container">
                    <div class="w-100  bg-white shadow rounded">
                        <div class="status-card mb-4">
                            <div class="card-body text-center p-5">
                                <i class="fas fa-clock status-icon"></i>
                                <h2 class="mb-4">Application Under Review</h2>
                                <p class="lead">Thank you for applying to become a Team Leader!</p>
                                <div class="alert alert-info mt-4 rounded-pill">
                                    <i class="fas fa-info-circle me-2"></i>Your application is currently being reviewed by our team.
                                </div>
                            </div>
                        </div>
                    </div>

                    
                      <div class="w-100 bg-white shadow rounded p-4">
                          <div class="claim-details mt-4 text-center">
                              <h4 class="mb-3">Your Activation Code</h4>
                              <p class="mb-2">Click the eye icon to view full code or copy button to copy</p>
                              <div id="alertPlaceholder"></div>
                              <div class="activation-code position-relative">
                                  <input type="text" id="activationCode" class="form-control text-center" value="ABC123XYZ" readonly style="font-family: monospace;">
                                  <button class="btn btn-link position-absolute" style="right: 40px; top: 50%; transform: translateY(-50%);" onclick="togglePassword()">
                                      <i class="fas fa-eye" id="toggleIcon"></i>
                                  </button>
                                  <button class="btn btn-link position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);" onclick="copyCode()">
                                      <i class="fas fa-copy"></i>
                                  </button>
                              </div>
                              <p class="text-muted mt-2">Please keep this code safe</p>
                            
                              <div class="sample-section mt-4">
                                  <button class="btn btn-custom mt-3" onclick="claimCode()">
                                      <i class="fas fa-check-circle me-2"></i>Claim Now
                                  </button>
                              </div>
                          </div>
                          <script>
                              document.getElementById('activationCode').type = 'password';
                              
                              function showAlert(message, type) {
                                  const alertPlaceholder = document.getElementById('alertPlaceholder');
                                  const wrapper = document.createElement('div');
                                  wrapper.innerHTML = `
                                      <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                          ${message}
                                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                      </div>
                                  `;
                                  alertPlaceholder.innerHTML = '';
                                  alertPlaceholder.append(wrapper);
                                  setTimeout(() => {
                                      wrapper.querySelector('.alert').remove();
                                  }, 3000);
                              }

                              function togglePassword() {
                                  const input = document.getElementById('activationCode');
                                  const icon = document.getElementById('toggleIcon');
                                  if (input.type === 'password') {
                                      input.type = 'text';
                                      const value = input.value;
                                      input.value = '...' + value.slice(-3);
                                      icon.classList.remove('fa-eye');
                                      icon.classList.add('fa-eye-slash');
                                  } else {
                                      input.type = 'password';
                                      input.value = input.defaultValue;
                                      icon.classList.remove('fa-eye-slash');
                                      icon.classList.add('fa-eye');
                                  }
                              }
                              function copyCode() {
                                  const input = document.getElementById('activationCode');
                                  input.select();
                                  document.execCommand('copy');
                                  showAlert('Activation code copied to clipboard!', 'success');
                              }
                              function claimCode() {
                                  showAlert('Code claimed successfully!', 'success');
                              }
                          </script>
                      </div>



                    <div class="w-100 bg-white shadow rounded">
                        <div class="status-card mb-4">
                            <div class="card-body p-4">
                                <h3 class="mb-4"><i class="fas fa-tasks me-2"></i>Application Progress</h3>
                                <div class="progress-timeline">
                                    <div class="progress-step active">
                                        <h5><i class="fas fa-check-circle me-2"></i>Application Submitted</h5>
                                        <p class="text-muted">Your application has been received</p>
                                    </div>
                                    <div class="progress-step active">
                                        <h5><i class="fas fa-search me-2"></i>Under Review</h5>
                                        <p class="text-muted">Our team is reviewing your application</p>
                                    </div>
                                    <div class="progress-step">
                                        <h5><i class="fas fa-flag-checkered me-2"></i>Final Decision</h5>
                                        <p class="text-muted">You'll be notified via email</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
   
                      </div>
            </div>
            <div class="col-lg-4 order-lg-2 order-1 mb-4 mb-lg-0">
                <div class="profile-card">
                    <div class="text-center">
                        <i class="fas fa-user-circle status-icon"></i>
                        <h3 class="mb-4">Profile Details</h3>
                        <div class="profile-info">
                            <p class="mb-2"><i class="fas fa-user me-2"></i><?php echo e($name); ?></p>
                        </div>
                        <div class="profile-info">
                            <p class="mb-2"><i class="fas fa-envelope me-2"></i><?php echo e($email); ?></p>
                        </div>
                        <div class="profile-info">
                            <p class="mb-2"><i class="fas fa-id-badge me-2"></i><?php echo e($username); ?></p>
                        </div>
                        <div class="profile-info">
                            <p class="mb-2"><i class="fas fa-phone me-2"></i><?php echo e($phone); ?></p>
                        </div>
                        <div class="profile-info">
                            <p class="mb-2"><i class="fas fa-globe me-2"></i><?php echo e($country); ?></p>
                        </div>
                    </div>
                </div>


                <div class=" w-100 ">
                        <div class=" my-4 mb-md-0">
                            <div class="help-card p-4 shadow">
                                <h5 class="text-primary"><i class="fas fa-info-circle me-2"></i>What's Next?</h5>
                                <ul class="mt-3 list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check me-2"></i>Review process takes 1-2 business days</li>
                                    <li class="mb-2"><i class="fas fa-envelope me-2"></i>You'll receive an email notification</li>
                                    <li class="mb-2"><i class="fas fa-bell me-2"></i>Keep an eye on your inbox</li>
                                </ul>
                            </div>
                        </div>
                        <div class=" my-4">
                            <div class="help-card p-4 shadow">
                                <h5 class="text-primary"><i class="fas fa-question-circle me-2"></i>Need Help?</h5>
                                <p class="mt-3">Contact our support team:</p>
                                <a href="mailto:support@bifonex.com" class="btn btn-custom">
                                    <i class="fas fa-envelope me-2"></i>support@bifonex.com
                                </a>
                            </div>
                        </div>
                    </div>  

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/team-leader/pending-approval.blade.php ENDPATH**/ ?>