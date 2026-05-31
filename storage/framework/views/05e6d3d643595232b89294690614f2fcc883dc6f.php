    <?php
    
   
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
    use App\Models\ads;
    
    use App\Models\User;
    $user = Auth::user();
    
    $name = $user->user;
     // $ads=ads::orderBy('id','desc')->get(); 
    $ads = Ads::orderByRaw("FIELD(status, 'pending', 'rejected', 'approved')")->get();
global $status;

  $accepted = ads::where('status','approved')->count();
    $rejected=ads::where('status','rejected')->count();
    ?>
  


<?php $__env->startSection('contents'); ?>
<section>

    
    <!-- <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet"> -->
    
    <style>
        .content-wrapper {
            position: relative;
        }
    </style>
    
    <div class="content-wrapper p-4">
    <div class="container mx-auto">
        <!-- Navigation Tabs -->
        <div class="tabs mt-2">
            <div class="flex space-x-4">
                <a href="<?php echo e(route('admin.ads')); ?>" class="fomoLink flex items-center space-x-1 font-bold text-gray-900">
                    <i class="fa-solid fa-rectangle-ad font-bold"></i>
                    <span>Manage ads</span>
                </a>
                <a href="<?php echo e(route('admin.ads.create')); ?>" class="fomoLink flex items-center space-x-1 font-bold text-gray-900">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add ads</span>
                </a>
            </div>
        </div>

        <!-- Top Boxes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
            <div class="bg-white shadow rounded p-4 flex items-center">
                <div class="text-2xl text-blue-700 mr-4">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900">Total Ads</div>
                    <div class="text-sm text-gray-600">Advertisement: <?php echo e(count($ads)); ?></div>
                </div>
            </div>
            <a href="#" class="bg-white shadow rounded p-4 flex items-center">
                <div class="text-2xl text-green-700 mr-4">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900">Accepted Ads</div>
                    <div class="text-sm text-gray-600">Advertisement: <?php echo e($accepted); ?></div>
                </div>
            </a>
            <a href="#" class="bg-white shadow rounded p-4 flex items-center">
                <div class="text-2xl text-red-700 mr-4">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900">Rejected Ads</div>
                    <div class="text-sm text-gray-600">Advertisement: <?php echo e($rejected); ?></div>
                </div>
            </a>
        </div>

        <!-- Table for All Ads -->
        <div class="table-container my-4 bg-white shadow rounded">
            <div class="p-4 border-b">
                <p class="text-center font-bold text-xl">All advertisements <span class="text-green-700"><?php echo e(count($ads)); ?></span></p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 text-center">
                    <thead class="bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                        <tr>
                            <th class="py-2 px-4 border-r">#</th>
                            <th class="py-2 px-4 border-r">Title</th>
                            <th class="py-2 px-4 border-r">Description</th>
                            <th class="py-2 px-4 border-r">Status</th>
                            <th class="py-2 px-4 border-r">Owner</th>
                            <th class="py-2 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($ads as $ad) { 
                            switch ($ad->status) {
                                case 'pending':
                                    $status = "<span class='bg-blue-500 text-white py-1 px-2 rounded'>$ad->status</span>";
                                    break;
                                case 'rejected':
                                    $status = "<span class='bg-red-500 text-white py-1 px-2 rounded'>$ad->status</span>";
                                    break;
                                case 'approved':
                                    $status = "<span class='bg-green-500 text-white py-1 px-2 rounded'>$ad->status</span>";
                                    break;
                                default:
                                    $status = "<span class='bg-gray-500 text-white py-1 px-2 rounded'>no status found</span>";
                                    break;
                            } ?>
                        <tr class="border-t">
                            <td class="py-2 px-4 border-r"><?php echo e($i++); ?></td>
                            <td class="py-2 px-4 border-r"><?php echo e($ad->tittle); ?></td>
                            <td class="py-2 px-4 border-r"><?php echo e($ad->description); ?></td>
                            <td class="py-2 px-4 border-r"><?php echo $status; ?></td>
                            <td class="py-2 px-4 border-r"><?php echo e($ad->user); ?></td>
                            <td class="py-2 px-4">
                                <div class="relative inline-block text-left">
                                    <div class="action drop-btn ellipsMore cursor-pointer">
                                        <i class="las la-ellipsis-v text-2xl"></i>
                                    </div>
                                    <div class="absolute right-0 w-48 py-2 mt-2 bg-white rounded-md shadow-xl hidden" id="drop-content">
                                        <a href="<?php echo e(route('admin.adsDetail')); ?>?id=<?php echo e($ad->id); ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-400 hover:text-white">View</a>
                                        <a href="#?id=<?php echo $i; ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-400 hover:text-white">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="reject bg-white rounded-xl shadow-lg py-4 px-6 fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden" id="reject">
            <div class="delIcon bg-gray-100 h-14 w-14 flex items-center justify-center rounded-full mx-auto my-2">
                <i class="fa-solid fa-xmark text-red-500"></i>
            </div>
            <div class="font-bold text-lg text-center mb-4">Reason for rejecting the ad</div>
            <div class="text-center mb-4">
                <form action="POST">
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Reason">
                </form>
            </div>
            <div class="flex justify-center space-x-4">
                <button class="bg-gray-300 hover:bg-gray-400 rounded-lg px-4 py-2" id="cancel">Cancel</button>
                <button class="bg-red-500 hover:bg-red-600 rounded-lg px-4 py-2 text-white">Reject</button>
            </div>
        </div>
    </div>
</div>


    <script>
        // ellipse more
        let ellipsMore = document.getElementsByClassName("ellipsMore");

        for (let i = 0; i < ellipsMore.length; i++) {
            const elem = ellipsMore[i];
            elem.addEventListener("click", () => {
                let panel = elem.nextElementSibling;
                console.log('next: ', panel);
                if (panel.style.display === "block") {
                    panel.style.display = "none"
                } else {
                    panel.style.display = "block"
                }
            })
        }
        // reject model
        const reject = document.getElementsByClassName("rejectBtn");
        const cancel = document.getElementById("cancel");
        const rejModel = document.getElementById("reject");

        for (let i = 0; i < reject.length; i++) {
            const element = reject[i];
            element.addEventListener("click", () => {
                rejModel.style.display = "block";
            })
        }
        cancel.addEventListener("click", () => {
            rejModel.style.display = "none";
        })
    </script>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/admin/payclick.blade.php ENDPATH**/ ?>