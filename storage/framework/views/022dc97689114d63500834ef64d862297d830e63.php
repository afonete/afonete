

<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="content-wrapper">


<!-- Main content -->
<div class="content">
    <div class="container-fluid py-4 text-left">

        <div class="mb-3">
            <h3 id="countdown" class="h4 font-weight-bold text-center bg-success text-white py-2 px-2 rounded w-25 mx-auto"></h3>
        </div>

        <div id="reward-button" class="d-none mb-4 text-center">

            <a href="<?php echo e(route('user.asking')); ?>?url=<?php echo e($url); ?>&ads=<?php echo e($id); ?>" class="btn btn-warning font-weight-bold py-2 px-4">
                Claim Reward <?php echo e($earn); ?>$
            </a>
            <p>Answer: <strong><?php echo e($answer); ?></strong></p>
        </div>

        <!-- iFrame Container -->
        <div class="embed-responsive embed-responsive-16by9 shadow-sm rounded mb-4">
            <iframe src="<?php echo e($url); ?>" class="embed-responsive-item border-0" title="Embedded Content"></iframe>
        </div>
    </div>

</div>
</div>


<script>
    // Initial countdown value in seconds
    let countdownTime = 15;

    // Function to update the countdown
    const countdownElement = document.getElementById('countdown');
    const rewardButton = document.getElementById('reward-button');

    const countdownInterval = setInterval(function() {
        countdownElement.textContent = `Claim in ${countdownTime}s`;
        countdownTime--;

        // If countdown reaches zero, show the reward button
        if (countdownTime <= 0) {
            clearInterval(countdownInterval);
            countdownElement.classList.add('d-none');
            rewardButton.classList.remove('d-none');
        }
    }, 1000); // Update every second
</script>


<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/user/myview.blade.php ENDPATH**/ ?>