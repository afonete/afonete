



<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title>Overview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

   <div class="content-wrapper">

<body>

   <div class="flex-1 p-4 ">
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pricing Plans</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #111827;
                color: #ffffff;
            }
            .card {
                background-color: #1F2937;
                border: none;
            }
            .card-header {
                font-size: 1.2rem;
                font-weight: bold;
                color: #ffffff;
                text-align: center;
            }
            .price {
                font-size: 1.5rem;
                font-weight: bold;
                color: #ffffff;
                text-align: center;
            }
            .details {
                font-size: 0.9rem;
                color: #9CA3AF;
                text-align: center;
            }
            .card-body ul {
                list-style: none;
                padding-left: 0;
            }
            .card-body {
                padding: 3px 3px 6px 6px !important;
            }
            .card-body li {
                color: #10B981;
                font-size: 14px;
                display: flex;
                word-wrap: none;

                /* list-style-type: disc; */
            }
            .btn-primary {
                background-color: #3B82F6;
                border: none;
                font-weight: bold;
                width: 100%;
            }

            .card-body  li::before {
            content: "✔️"; /* Any symbol or character */
            /* margin-right: 4px; */
            color: #ff5733;
                 }

        </style>
    </head>
    <body>

    <div class="container-fluid my-5">
        <h2 class="text-center text-dark">INVEST IN US</h2>
        <p class="text-center text-dark">SELECT YOUR BEST PLAN.</p>
        <div class="row">
            <!-- Basic Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">BASIC</div>
                    <div class="price">20 USDT</div>
                    <div class="details">From Licence Miner. <br> TOKEN | 20,000 FOC</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X30%</li>
                            <li>Direct Sponsors: 5%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limit</li>
                            <li>Volume Point: 4 Point</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Not Allowed Loan</li>
                            <li>No Access Investment Option</li>
                            <li><strong>TOTAL RETURN: 26,000 COIN</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Starter Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">STARTER</div>
                    <div class="price">100 USDT</div>
                    <div class="details">From Licence Miner. <br> TOKEN | 83,333 FOC</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X30%</li>
                            <li>Direct Sponsors: 8%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limit</li>
                            <li>Volume Bonus: 20</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Allowed Loan</li>
                            <li>Access Investment feature</li>
                            <li><strong>TOTAL RETURN: 108,333 FOC</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Light Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">LIGHT</div>
                    <div class="price">300 USDT</div>
                    <div class="details">From Licence Miner. <br> TOKEN | 250,000 FOC</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X40%</li>
                            <li>Direct Sponsors: 5%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limit</li>
                            <li>Volume Point: 4 Point</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Not Allowed Loan</li>
                            <li>No Access Investment Option</li>
                            <li><strong>TOTAL RETURN: 26,000 COIN</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Pro Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">PRO</div>
                    <div class="price">500 USDT</div>
                    <div class="details">From Licence Miner. <br> TOKEN | 250,000 FOC</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X45%</li>
                            <li>Direct Sponsors: 10%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limit</li>
                            <li>Volume Point: 4 Point</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Allowed Loan</li>
                            <li>Access Investment Option</li>
                            <li><strong>TOTAL RETURN: 604,116 FOC</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Advanced Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">ADVANCED</div>
                    <div class="price">1000 USDT</div>
                    <div class="details">From Licence Miner. <br> TOKEN | 833,333 FOC</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X50%</li>
                            <li>Direct Sponsors: 11%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limit</li>
                            <li>Volume BV: 200</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Allowed Loan</li>
                            <li>Investment Option</li>
                            <li><strong>TOTAL RETURN: 1,250,000 FOC</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <!-- Premium Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">PREMIUM</div>
                    <div class="price">3000 USDT</div>
                    <div class="details">From Licence Miner. <br> TOKEN | 2,500,000 FOCOIN</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X60%</li>
                            <li>Direct Sponsors: 12%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limit</li>
                            <li>Volume BV: 200</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Allowed Loan</li>
                            <li>Investment Option</li>
                            <li><strong>TOTAL RETURN: 4,000,000 FOCOIN</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Tycoon Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">TYCOON</div>
                    <div class="price">5000 USDT</div>
                    <div class="details">From Licence Miner. <br> TOKEN | 4,166,667 FOCOIN</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X70%</li>
                            <li>Direct Sponsors: 14%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limit</li>
                            <li>Volume Bonus: 1000</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Allowed Loan</li>
                            <li>Access Investment feature</li>
                            <li><strong>TOTAL RETURN: 7,083,333 FOCOIN</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Master Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">MASTER</div>
                    <div class="price">10,000 USDT</div>
                    <div class="details">From Licence Miner. <br> TOKEN | 20,000 FOCOIN</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X30%</li>
                            <li>Direct Sponsors: 5%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limite</li>
                            <li>Volume Point: 4 Point</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Not Allowed Loan</li>
                            <li>No Access Investment Option</li>
                            <li><strong>TOTAL RETURN: 26,000 FOCOIN</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Pro Master Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">PRO MASTER</div>
                    <div class="price">20,000 USDT</div>
                    <div class="details">From Licence Miner. <br> TOKEN | 16,666,667 FOCOIN</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X90%</li>
                            <li>Direct Sponsors: 18%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limit</li>
                            <li>Volume Bonus: 4000</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Allowed Loan</li>
                            <li>Access Investment feature</li>
                            <li><strong>TOTAL RETURN: 31,666,666 FOCOIN</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>

            <!-- Super Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">SUPER</div>
                    <div class="price">25K - 200K <small>USDT</small></div>
                    <div class="details">From Licence Miner. <br> TOKEN | 20,833,333 FOCOIN</div>
                    <div class="card-body">
                        <ul>
                            <li>Duration 600 Days</li>
                            <li>Token Bonus: X100%</li>
                            <li>Direct Sponsors: 20%</li>
                            <li>Affiliate V.bonus: 10%</li>
                            <li>Space Shop Room Limit</li>
                            <li>Volume Bonus: 6000</li>
                            <li>Unlocked Per Week: YES</li>
                            <li>Allowed Loan</li>
                            <li>Access Investment feature</li>
                            <li><strong>TOTAL RETURN: 41,666,666 FOCOIN</strong></li>
                        </ul>
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    </div>



</body>
</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/investment-package.blade.php ENDPATH**/ ?>