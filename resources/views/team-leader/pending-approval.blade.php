@php
use App\Models\TeamLeader;
use App\Models\Activations;
$username = session('team_leader_Usen_Name');
$teamLeader = TeamLeader::where('User_name', $username)->first();
$email = $teamLeader->Email ?? '';
$phone = $teamLeader->Phone ?? '';
$country = $teamLeader->Country ?? '';
$name = $teamLeader->Names ?? '';
$status = $teamLeader->status ?? 'pending';
$whatsapp = $teamLeader->whatsapp ?? '';
$instagram = $teamLeader->instagram ?? '';

// Fetch Team Leader Activation Code if they are confirmed
$activation = null;
if ($status === 'confirmed') {
    $activation = Activations::where('email', $email)->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])->first();
}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status | {{ env('APP_NAME') }}</title>
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
            color: white;
        }
        .btn-success-custom {
            background-color: #198754;
            color: white;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-success-custom:hover {
            background-color: #146c43;
            transform: translateY(-2px);
            color: white;
        }
        .flex-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
    </style>
</head>

<body>
    
    {{-- Header / Navbar with Logout --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand font-weight-bold uppercase tracking-wider" href="#">
                <i class="fas fa-users-cog text-info mr-1"></i> {{ env('APP_NAME') }} Team Leaders
            </a>
            <div class="d-flex gap-2">
                <form action="{{ route('team-leader.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm font-weight-bold" style="border-radius: 20px; padding: 6px 16px;">
                        <i class="fas fa-sign-out-alt mr-1"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        {{-- Session Feedback Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-xl mb-4 p-3" role="alert">
                <i class="fas fa-check-circle mr-2 text-success"></i> <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 order-lg-1 order-2">
                <div class="flex-container">
                    
                    {{-- 1. Status Overview Card --}}
                    <div class="w-100 bg-white shadow-sm rounded-xl p-5 border border-light">
                        <div class="status-card text-center">
                            @if($status === 'confirmed')
                                <i class="fas fa-check-circle text-success status-icon" style="font-size: 4rem;"></i>
                                <h2 class="mb-3 font-weight-bold text-success">Application Approved! 🎉</h2>
                                <p class="lead text-muted">Congratulations! Your Team Leader application has been approved by the administration.</p>
                                <div class="alert alert-success mt-4 text-start" style="border-radius: 10px;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Ready to Activate:</strong> Copy your unique activation code below, click the green <strong>Activate Now</strong> button, and enter it to activate your Team Leader account!
                                </div>
                            @else
                                <i class="fas fa-clock status-icon"></i>
                                <h2 class="mb-3 font-weight-bold text-dark">Application Under Review</h2>
                                <p class="lead text-muted">Thank you for applying to become a Team Leader!</p>
                                
                                @if(empty($whatsapp) || empty($instagram))
                                    <div class="alert alert-warning mt-4 text-start" style="border-radius: 10px;">
                                        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                                        <strong>Application Incomplete:</strong> To help us expedite your review, please complete your application by adding your active WhatsApp Group Link and Telegram Group Link in the section below.
                                    </div>
                                @else
                                    <div class="alert alert-success mt-4 text-start" style="border-radius: 10px;">
                                        <i class="fas fa-check-circle me-2 text-success"></i>
                                        <strong>Application Complete!</strong> Your WhatsApp and Telegram group links are loaded. Your profile is currently placed in our active administrator review queue.
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- 2. Activation Code & Tasks (Only for Confirmed Leaders) --}}
                    @if($status === 'confirmed' && $activation)
                        <div class="w-100 bg-white shadow-sm rounded-xl p-4 border border-light">
                            <div class="claim-details mt-2 text-center">
                                <h4 class="mb-3 font-weight-bold text-dark"><i class="fas fa-key text-warning me-2"></i>Your Unique Activation Code</h4>
                                <p class="text-muted text-sm mb-3">Copy this activation code and paste it on the package activation page.</p>
                                
                                <div id="alertPlaceholder"></div>
                                <div class="activation-code position-relative mb-4" style="max-width: 400px; margin: 0 auto;">
                                    <input type="text" id="activationCode" class="form-control text-center font-bold" value="{{ $activation->code }}" readonly style="font-family: monospace; font-size: 1.3rem; padding-right: 80px; letter-spacing: 2px;">
                                    <button class="btn btn-link position-absolute" style="right: 40px; top: 50%; transform: translateY(-50%);" onclick="togglePassword()">
                                        <i class="fas fa-eye text-secondary" id="toggleIcon"></i>
                                    </button>
                                    <button class="btn btn-link position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);" onclick="copyCode()">
                                        <i class="fas fa-copy text-primary"></i>
                                    </button>
                                </div>

                                {{-- Assigned Tasks & Reward Info --}}
                                <div class="p-4 rounded-xl border mb-4 text-start" style="background-color: #f8fafc; border-color: #e2e8f0;">
                                    <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                                        <i class="fas fa-list-check text-indigo-600 me-2"></i>Your Assigned Leadership Plan
                                    </h5>
                                    <div class="row text-sm mb-3">
                                        <div class="col-6">
                                            <span class="text-muted d-block">Timeline Duration:</span>
                                            <strong class="text-dark" style="font-size: 1.05rem;">{{ (int)$activation->period }} Days</strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted d-block">Token Reward:</span>
                                            <strong class="text-success" style="font-size: 1.05rem;">{{ number_format($activation->token, 0) }} Tokens</strong>
                                        </div>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-muted d-block mb-1.5 font-bold">Written Tasks To Complete:</span>
                                        <div class="p-3 bg-white rounded border text-slate-700" style="font-family: inherit; white-space: pre-line; border-color: #e2e8f0; line-height: 1.5;">{{ $activation->task }}</div>
                                    </div>
                                    <small class="text-muted d-block mt-3" style="font-size: 0.78rem; line-height: 1.4;">
                                        <i class="fas fa-info-circle text-info mr-1"></i> Note: These tokens will be credited as locked tokens during your {{ (int)$activation->period }} days timeline, and automatically release to your Available Token balance upon completion.
                                    </small>
                                </div>

                                <div class="mt-4">
                                    {{-- §94: goes to the dedicated activation page (code field only) —
                                         no more scrolling past packages on /user/venture-package --}}
                                    <a href="{{ url('/user/activate-code') }}" class="btn btn-success-custom shadow-lg">
                                        <i class="fas fa-check-circle me-2"></i>Activate My Account Now
                                    </a>
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
                                        icon.classList.remove('fa-eye');
                                        icon.classList.add('fa-eye-slash');
                                    } else {
                                        input.type = 'password';
                                        icon.classList.remove('fa-eye-slash');
                                        icon.classList.add('fa-eye');
                                    }
                                }
                                function copyCode() {
                                    const input = document.getElementById('activationCode');
                                    const originalType = input.type;
                                    input.type = 'text';
                                    input.select();
                                    document.execCommand('copy');
                                    input.type = originalType;
                                    showAlert('Activation code copied to clipboard!', 'success');
                                }
                            </script>
                        </div>
                    @endif

                    {{-- 3. Social Links Form (COMPLETE APPLICATION) --}}
                    @if($status === 'pending')
                        <div class="w-100 bg-white shadow-sm rounded-xl p-4 border border-light">
                            <h4 class="mb-3 font-weight-bold text-slate-800 border-bottom pb-2">
                                <i class="fas fa-id-card-clip text-primary me-2"></i>Complete Your Application
                            </h4>
                            <p class="text-muted text-sm">Please provide your active WhatsApp Group Link and Telegram Group Link. Administrators will check these before making a final decision.</p>
                            
                            <form action="{{ route('team-leader.complete') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="username" value="{{ $username }}">
                                
                                {{-- Leadership Level Selector --}}
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold text-slate-700">Choose Your Leadership Level <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-crown text-warning"></i></span>
                                        <select name="leadership_level" required class="form-select" id="leadershipLevel">
                                            <option value="TEAM_LEADER" {{ old('leadership_level', $teamLeader->leadership_level ?? 'TEAM_LEADER') === 'TEAM_LEADER' ? 'selected' : '' }}>TEAM LEADER</option>
                                            <option value="SUPER_LEADER" {{ old('leadership_level', $teamLeader->leadership_level ?? '') === 'SUPER_LEADER' ? 'selected' : '' }}>SUPER LEADER</option>
                                        </select>
                                    </div>
                                    <small class="text-muted">Select the leadership tier you are applying for.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold text-slate-700">WhatsApp Group Link <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fab fa-whatsapp text-success"></i></span>
                                        <input type="text" name="whatsapp" required value="{{ old('whatsapp', $whatsapp) }}" class="form-control" placeholder="e.g. https://chat.whatsapp.com/...">
                                    </div>
                                    <small class="text-muted">Enter the direct invite URL to your WhatsApp group.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold text-slate-700">Telegram Group Link <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fab fa-telegram text-primary"></i></span>
                                        <input type="url" name="instagram" required value="{{ old('instagram', $instagram) }}" class="form-control" placeholder="e.g. https://t.me/your_group">
                                    </div>
                                    <small class="text-muted">Direct invite link to your Telegram channel or group.</small>
                                </div>

                                <button type="submit" class="btn btn-custom w-100 font-weight-bold py-2.5 mt-2">
                                    <i class="fas fa-save me-2"></i> Save Social Details &amp; Submit
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- 4. Application Progress Timeline --}}
                    <div class="w-100 bg-white shadow-sm rounded-xl p-4 border border-light">
                        <h4 class="mb-3 font-weight-bold text-slate-800 border-bottom pb-2">
                            <i class="fas fa-tasks text-primary me-2"></i>Application Progress
                        </h4>
                        <div class="progress-timeline">
                            <div class="progress-step active">
                                <h5 class="font-weight-bold"><i class="fas fa-check-circle text-success me-2"></i>Application Submitted</h5>
                                <p class="text-muted text-sm">Your base details have been recorded.</p>
                            </div>
                            <div class="progress-step active">
                                <h5 class="font-weight-bold">
                                    @if($status === 'confirmed')
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                    @elseif(!empty($whatsapp) && !empty($instagram))
                                        <i class="fas fa-spinner fa-spin text-info me-2"></i>
                                    @else
                                        <i class="fas fa-exclamation-circle text-warning me-2"></i>
                                    @endif
                                    Under Review
                                </h5>
                                <p class="text-muted text-sm">Our team is reviewing your details and social media pages.</p>
                            </div>
                            <div class="progress-step {{ $status === 'confirmed' ? 'active' : '' }}">
                                <h5 class="font-weight-bold">
                                    @if($status === 'confirmed')
                                        <i class="fas fa-trophy text-warning me-2"></i>Approved
                                    @else
                                        <i class="fas fa-flag-checkered me-2"></i>Final Decision
                                    @endif
                                </h5>
                                <p class="text-muted text-sm">Your profile status will be updated immediately upon review.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            {{-- Right Column (Profile & Support) --}}
            <div class="col-lg-4 order-lg-2 order-1 mb-4 mb-lg-0">
                
                {{-- Profile Details --}}
                <div class="profile-card shadow-sm mb-4">
                    <div class="text-center">
                        <i class="fas fa-user-circle status-icon"></i>
                        <h3 class="mb-4 font-weight-bold text-dark">Profile Details</h3>
                        <div class="profile-info text-start">
                            <p class="mb-0 text-sm"><i class="fas fa-user text-primary me-2" style="width: 16px;"></i><strong>Names:</strong> {{ $name }}</p>
                        </div>
                        <div class="profile-info text-start">
                            <p class="mb-0 text-sm"><i class="fas fa-envelope text-primary me-2" style="width: 16px;"></i><strong>Email:</strong> {{ $email }}</p>
                        </div>
                        <div class="profile-info text-start">
                            <p class="mb-0 text-sm"><i class="fas fa-id-badge text-primary me-2" style="width: 16px;"></i><strong>Username:</strong> {{ $username }}</p>
                        </div>
                        <div class="profile-info text-start">
                            <p class="mb-0 text-sm"><i class="fas fa-phone text-primary me-2" style="width: 16px;"></i><strong>Phone:</strong> {{ $phone }}</p>
                        </div>
                        <div class="profile-info text-start">
                            <p class="mb-0 text-sm"><i class="fas fa-globe text-primary me-2" style="width: 16px;"></i><strong>Country:</strong> {{ $country }}</p>
                        </div>
                    </div>
                </div>

                {{-- Support & Info Cards --}}
                <div class="w-100">
                    <div class="help-card p-4 shadow-sm mb-4 border border-light">
                        <h5 class="text-primary font-weight-bold"><i class="fas fa-info-circle me-2"></i>What's Next?</h5>
                        <ul class="mt-3 list-unstyled text-sm text-slate-600">
                            @if($status === 'confirmed')
                                <li class="mb-2.5"><i class="fas fa-check text-success me-2"></i>Copy your custom code and activate your package now!</li>
                                <li class="mb-2.5"><i class="fas fa-check text-success me-2"></i>You earn referral commission bonuses on all direct recruit purchases.</li>
                            @else
                                <li class="mb-2.5"><i class="fas fa-check text-success me-2"></i>Application auditing takes 1-2 business days.</li>
                                <li class="mb-2.5"><i class="fas fa-check text-success me-2"></i>You can log in anytime to check your live approval status.</li>
                                <li class="mb-2.5"><i class="fas fa-check text-success me-2"></i>Double-check your WhatsApp and Telegram group links are valid!</li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="help-card p-4 shadow-sm border border-light">
                        <h5 class="text-primary font-weight-bold"><i class="fas fa-question-circle me-2"></i>Need Help?</h5>
                        <p class="mt-3 text-sm text-slate-600">Our leadership support team is here to assist you:</p>
                        <a href="mailto:support@bifonex.com" class="btn btn-custom w-100 font-weight-bold text-sm">
                            <i class="fas fa-envelope me-2"></i>support@bifonex.com
                        </a>
                    </div>
                </div>  

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
