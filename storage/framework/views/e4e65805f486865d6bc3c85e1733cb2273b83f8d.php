<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(strtoupper(env('APP_NAME'))); ?> Become a Team Leader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0084D1;
            --secondary-color: #1a1a1a;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('/assets/images/leader-bg.jpg');
            background-size: cover;
            background-position: center;
            min-height: 60vh;
            display: flex;
            align-items: center;
        }

        .feature-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .benefits-list li {
            margin-bottom: 15px;
            padding-left: 30px;
            position: relative;
        }

        .benefits-list li:before {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--primary-color);
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero-section text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h3 class="text-3xl font-bold text-blue-500 mb-4"><?php echo e(strtoupper(env('APP_NAME'))); ?></h3>
                    <h1 class="display-4 fw-bold mb-4">Become a Team Leader</h1>
                    <h3 class="lead mb-4">World's Best Unique Referral Program for Leaders</h3>
                    <p class="mb-4">Join our unique Referral Program innovation community and make more money!</p>
                    <p class="mb-4">Earn more when you help others earn with you. Help people to achieve their goals and give everyone financial freedom for innovative technology-friendly users to spend time. Full access to the platform.</p>
                    <p class="mb-4">Afonete has created a unique affiliate platform solution for the global community of people who love innovative technologies, AI, web3, blockchain technologies, and opened access for all people to benefit from a system we can implement future innovations today.</p>
                    <button class="btn btn-primary btn-lg px-5 rounded-4" data-bs-toggle="modal" data-bs-target="#applyModal">
                        <i class="fas fa-rocket me-2"></i>Become a Team Leader
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card feature-card shadow-lg">
                        <div class="card-body p-5">
                        <div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold">Affiliate Team Leader</h2>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="card feature-card shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Leader Program?</h3>
                <p class="text-lg">Leaders with a large community network of referrals and investors will benefit from a special agreement with us.</p>
            </div>

            <div class="card feature-card shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4">What Are Referral Bonuses?</h3>
                <p class="text-lg">Referral bonuses are exactly what they sound like: you get a reward for referring your friend to our site. It is a very easy way to make hundreds to thousands of dollars to your wallet. There is no limit to how many referrals you introduce. Earn as much as you want. You can earn in 15 lines and 10 levels within your referral network - lifetime.</p>
            </div>

            <div class="card feature-card shadow-lg p-6 lg:col-span-2">
                <h3 class="text-xl font-semibold mb-4">Team Leaders Reward</h3>
                <p class="text-lg mb-4">Three types of reward bonuses:</p>
                <ul class="benefits-list grid grid-cols-1 md:grid-cols-3 gap-6">
                    <li class="p-4 bg-light rounded-4">General reward: activation code in referral program</li>
                    <li class="p-4 bg-light rounded-4">Daily earn reward: cash $500-$5000</li>
                    <li class="p-4 bg-light rounded-4">Prize reward: $10,000 in Focoin</li>
                </ul>
            </div>
        </div>
    </div>
</div>


                            <div class="mb-4">
                                <h3 class="text-lg font-semibold mb-3">Benefits of Being a Leader</h3>
                                <p>Activation package includes:</p>
                                <ul class="benefits-list">
                                    <li>Master card</li>
                                    <li>Token 60000 Focoin</li>
                                    <li>Allowed loan $1000-$500000</li>
                                    <li>Get 100,000 ads reward bonus 0.09% each ads</li>
                                    <li>Upgrade Stage2</li>
                                    <li>Affiliate 10%</li>
                                    <li>Referral Upgrade 10%</li>
                                    <li>Second dashboard</li>
                                    <li>Access more features</li>
                                    <li>Earn 10 Level Downline</li>
                                    <li>Direct ads 25%</li>
                                    <li>Earn 15 lines</li>
                                </ul>
                            </div>

                            <button class="btn btn-primary btn-lg mt-4" data-bs-toggle="modal" data-bs-target="#applyModal">
                                Become a Leader Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title">Team Leader Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
    <form id="leaderApplicationForm">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Country</label>
                <select id="countrySelect" name="country" class="form-select" required>
                    <option value="">Select Country</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" required>
            </div>

            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
        </div>
    </form>
</div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- sweatalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        fetch("https://restcountries.com/v3.1/all?fields=name,idd")
            .then(response => response.json())
            .then(data => {
                const countrySelect = document.getElementById("countrySelect");
                const phoneInput = document.getElementById("phone");
                
                data.sort((a, b) => a.name.common.localeCompare(b.name.common));
                
                data.forEach(country => {
                    const option = document.createElement("option");
                    option.value = country.name.common;
                    option.textContent = country.name.common;
                    countrySelect.appendChild(option);
                });

                countrySelect.addEventListener('change', function() {
                    const selectedCountry = data.find(country => country.name.common === this.value);
                    const countryCode = selectedCountry?.idd?.root + (selectedCountry?.idd?.suffixes?.[0] || '');
                    phoneInput.value = countryCode;
                    
                    phoneInput.addEventListener('input', function(e) {
                        let cursorPosition = this.selectionStart;
                        let inputValue = this.value;
                        
                        if (inputValue.length < countryCode.length) {
                            this.value = countryCode;
                            cursorPosition = countryCode.length;
                        } else {
                            let numbers = inputValue.slice(countryCode.length).replace(/\D/g, '');
                            this.value = countryCode + numbers;
                            cursorPosition = Math.min(cursorPosition, this.value.length);
                        }
                        
                        this.setSelectionRange(cursorPosition, cursorPosition);
                    });
                });
            })
            .catch(error => console.error("Error fetching countries:", error));


    //    subbmit application
    document.getElementById('leaderApplicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'Submitting Application',
        html: 'Please wait...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('/team-leader/apply', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#0084D1'
            }).then(() => {
                // redirect to team-leader/pending-approval
                window.location.href = `/team-leader/pending-approval?username=${formData.get('username')}`;

            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Application Failed',
                text: data.message,
                confirmButtonColor: '#0084D1'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: 'Please try again later.',
            confirmButtonColor: '#0084D1'
        });
    });
});
    </script>
</body>
</html>
<?php /**PATH /home/blackjay/Downloads/test.focoin.eu/resources/views/home/team-leader.blade.php ENDPATH**/ ?>