<div class="wrapper">
     <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <title>Team Building Overview</title>
     
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="content-wrapper">
        <div class=" px-4 py-8">

        <style>
        body {
            background-color: #f8f9fa;
        }
        .card-title {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .price-badge {
            font-size: 2rem;
            font-weight: bold;
            color: white;
        }
        .gradient-btn {
            background: linear-gradient(to right, #ff7eb3, #ff758c);
            color: white;
            border: none;
        }
        .gradient-btn:hover {
            background: linear-gradient(to right, #ff758c, #ff7eb3);
        }
        .alert-message {
            border: 1px solid #dc3545;
            background-color: #f8d7da;
            color: #842029;
            padding: 15px;
            border-radius: 5px;
        }

        .custom-btn {
            background-color: #003366; /* Dark Blue */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .custom-btn:hover {
            background-color: #00509e; /* Lighter Dark Blue */
            transform: scale(1.05); /* Slightly enlarge */
        }

        .custom-btn:active {
            background-color: #002244; /* Darker Blue */
            transform: scale(0.95); /* Slightly shrink */
        }

        img{
            border-radius: 50px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <header class="text-center mb-4">
            <h1>Team Building Overview</h1>
        </header>

        <!-- Alert Message -->
        <div class="alert-message text-center">
            <span>You do not have any qualified package subscription to access NE Education.</span>
            <span>Please purchase Education package to proceed.</span>  <button class="custom-btn">Click Here</button>
        </div>

        <!-- Cards Section -->
        <div class="row mt-5 g-4">
            <!-- NE Starter Card -->
            <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="<?php echo e(asset('image/e0.png')); ?>" class="img-fluid rounded mb-3" alt="NE50 Image">
                            <h5 class="card-title">NE50 - Basic Level</h5>
                            <p class="card-text">Activation Fee: $10</p>
                            <p class="card-text">Course Duration: 75 weeks</p>
                            <ul class="list-unstyled mb-3">
                                <li>1 - Personal Development</li>
                                <li>2 - Entrepreneurs Mindset</li>
                            </ul>
                            <button class="btn gradient-btn">Buy Now</button>
                        </div>
                    </div>
                </div>

            <!-- NE50 - Basic Level Card -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="<?php echo e(asset('image/e1.png')); ?>" class="img-fluid rounded mb-3" alt="NE50 Image">
                            <h5 class="card-title">NE50 - Basic Level</h5>
                            <p class="card-text">Activation Fee: $10</p>
                            <p class="card-text">Course Duration: 75 weeks</p>
                            <ul class="list-unstyled mb-3">
                                <li>1 - Personal Development</li>
                                <li>2 - Entrepreneurs Mindset</li>
                            </ul>
                            <button class="btn gradient-btn">Buy Now</button>
                        </div>
                    </div>
                </div>

                <!-- card3 -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="<?php echo e(asset('image/e2.png')); ?>" class="img-fluid rounded mb-3" alt="NE50 Image">
                            <h5 class="card-title">NE50 - Basic Level</h5>
                            <p class="card-text">Activation Fee: $10</p>
                            <p class="card-text">Course Duration: 75 weeks</p>
                            <ul class="list-unstyled mb-3">
                                <li>1 - Personal Development</li>
                                <li>2 - Entrepreneurs Mindset</li>
                            </ul>
                            <button class="btn gradient-btn">Buy Now</button>
                        </div>
                    </div>
                </div>

                <!-- card 4 -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="<?php echo e(asset('image/e3.png')); ?>" class="img-fluid rounded mb-3" alt="NE50 Image">
                            <h5 class="card-title">NE50 - Basic Level</h5>
                            <p class="card-text">Activation Fee: $10</p>
                            <p class="card-text">Course Duration: 75 weeks</p>
                            <ul class="list-unstyled mb-3">
                                <li>1 - Personal Development</li>
                                <li>2 - Entrepreneurs Mindset</li>
                            </ul>
                            <button class="btn gradient-btn">Buy Now</button>
                        </div>
                    </div>
                </div>

                <!-- card 5 -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="<?php echo e(asset('image/e4.png')); ?>" class="img-fluid rounded mb-3" alt="NE50 Image">
                            <h5 class="card-title">NE50 - Basic Level</h5>
                            <p class="card-text">Activation Fee: $10</p>
                            <p class="card-text">Course Duration: 75 weeks</p>
                            <ul class="list-unstyled mb-3">
                                <li>1 - Personal Development</li>
                                <li>2 - Entrepreneurs Mindset</li>
                            </ul>
                            <button class="btn gradient-btn">Buy Now</button>
                        </div>
                    </div>
                </div>


                <!-- card 6 -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="<?php echo e(asset('image/e5.png')); ?>" class="img-fluid rounded mb-3" alt="NE50 Image">
                            <h5 class="card-title">NE50 - Basic Level</h5>
                            <p class="card-text">Activation Fee: $10</p>
                            <p class="card-text">Course Duration: 75 weeks</p>
                            <ul class="list-unstyled mb-3">
                                <li>1 - Personal Development</li>
                                <li>2 - Entrepreneurs Mindset</li>
                            </ul>
                            <button class="btn gradient-btn">Buy Now</button>
                        </div>
                    </div>
                </div>  
        </div>
    </div>


        </div>
        </div>
        </div><?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/user/education.blade.php ENDPATH**/ ?>