<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\video;

use App\Models\User;
$user = Auth::user();

$name = $user->user;

$video = video::orderByRaw("FIELD(status, 'pending', 'rejected', 'approved')")->get();
global $status;

$accepted = video::where('status','approved')->count();
$rejected=video::where('status','rejected')->count();
?>
<?php echo $__env->make('admin.admin-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link rel="stylesheet"
href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
<style>
.content-wrapper {
    position: relative;
}
</style>
<div class="content-wrapper">
<div class="container-fluid">
    <div class="tabs tab_links mt-2">
        <span class="links_tabs d-flex ">
            <a href="<?php echo e(route('admin.videos')); ?>" class="fomoLink"><i class="fa-solid fa-video"></i> Manage
                videos</a>
            <a href="<?php echo e(route('admin.video.upload')); ?>" class="fomoLink"><i class="fa-solid fa-plus"></i> Add
                videos </a>
                <button class="py-2 px-3 bg-purple-500 text-gray-50" id="openModalButton" >
                    Level Settings
                </button>
        </span>


    </div>

    
    <div class="tables my-2">

        <div class="container mx-auto p-8">
            <h1 class="text-2xl font-bold mb-4 bg-white py-2 text-left rounded shadow-sm px-2">Upload Video</h1>

            <?php if(session('fail')): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    <?php echo e(session('fail')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('message')): ?>
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>


                <form action="<?php echo e(route('admin.video.create')); ?>" method="POST" enctype="multipart/form-data"
                 class="bg-white p-6 rounded shadow-md grid grid-cols-3 gap-1">
                    <?php echo csrf_field(); ?>

                    <div class="mb-4">
                        <label for="tittle" class="block text-gray-700 font-bold mb-2">Video Title</label>
                        <input type="text" name="tittle"
                        id="tittle" class="border border-gray-300 py-2 px-1  w-full rounded" required placeholder="Enter title.....">
                    </div>

                    <div class="mb-4">
                        <label for="views" class="block text-gray-700 font-bold mb-2">Targeted Views</label>
                        <select name="views" id="views" onchange="calculate()"
                         class="border border-gray-300 py-2 px-1 w-full rounded" required >
                            <option value="1000">1000 views</option>
                            <option value="5000">5000 Views</option>
                            <option value="10000">10000 views</option>
                            <option value="500000">500000 Views</option>
                            <option value="250000">250000 views</option>
                            <option value="500000">50000 Views</option>
                          </select>
                    </div>

                    <div class="mb-4">
                        <label for="duration" class="block text-gray-700 font-bold mb-2">Duration</label>

                        <select name="duration" id="duration" onchange="calculate()" class="border border-gray-300 py-2 px-1 w-full rounded">
                           <option value="1">1 Day</option>
                           <option value="3">3 Days </option>
                           <option value="7">7 Days </option>
                           <option value="15">15 Days </option>
                           <option value="30">30 Days </option>
                           <option value="60">60 Days </option>

                         </select>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 font-bold mb-2">Price Per View($): </label>

                        <input type="number" name="user_earns" id="user_earns" step="0.001"
                         placeholder="Enter price per view....." class="border border-gray-300 py-2 px-1 w-full rounded">

                    </div>

                    <div class="mb-4">
                        <label for="geo" class="block text-gray-700 font-bold mb-2">Location(Geo)</label>

                        <select name="geo" id="geo" class="border border-gray-300 py-2 px-1 w-full rounded">
                            <option>North America</option>
                            <option>United states</option>
                            <option>Europe</option>
                            <option>Africa</option>
                            <option>Asia</option>
                            <option>United kingdom</option>
                            <option>Ociania</option>
                          </select>
                    </div>
                    <div class="mb-4">
                        <label for="question" class="block text-gray-700 font-bold mb-2">Time(seconds): </label>
                        <input type="number" name="time" id="time"
                        placeholder="Enter Your Time....."
                        class="border border-gray-300 py-2 px-1 w-full rounded">
                    </div>
                    <div class="mb-4">
                        <label for="question" class="block text-gray-700 font-bold mb-2">Question</label>
                        <input type="text" name="question" id="question" placeholder="Enter Question....." class="border border-gray-300 py-2 px-1 w-full rounded">
                    </div>

                    <div class="mb-4">
                        <label for="answer" class="block text-gray-700 font-bold mb-2">Answer</label>
                        <input type="text" name="answer" id="answer" placeholder="Enter Answer......." class="border border-gray-300 py-2 px-1 w-full rounded">
                    </div>



                    <div class="mb-4 ">
                        <label for="video" class="block text-gray-700 font-bold mb-2">
                            Upload Video (MP4 only)
                        </label>
                        <input type="file" name="video" id="video" accept="video/mp4" class="border border-gray-300 py-2 px-1 w-full rounded" required>
                    </div>
                    <div class="col-span-3">

                        <button type="submit" class="bg-blue-500 w-full text-white font-bold py-2 px-4 rounded">
                            Upload Video
                        </button>
                    </div>



                </form>



            </div>
        </div>


    </div>



</div>
</div>

<div class="container mx-auto p-6">
    <!-- Trigger Button -->


    <!-- Modal (hidden by default) -->
    <div id="myModal" class="fixed z-40 inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center my-3 hidden">
      <div class="bg-white rounded-lg w-full max-w-4xl p-6">
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b pb-3">
          <h3 class="text-lg font-medium text-gray-900">Modal Title</h3>
          <button id="closeModalButton" class=" hover:text-gray-900 bg-red-500 px-3 py-2 rounded text-white font-bold">&times;</button>
        </div>

        <div class="mt-4">

            <div class="flex py-2 gap-3">
                <div>
                    <label for="geo" class="block text-gray-700 font-bold mb-2">Commission Type</label>
                    <select name="geo" id="geo" class="border border-gray-300 py-2 px-1 w-full rounded">
                        <option selected>Default</option>
                        <option>Custom</option>
                        <option>Disabled</option>

                    </select>
                </div>
                <div>
                    <label for="geo" class="block text-gray-700 font-bold mb-2">Refer Level</label>
                    <select name="geo" id="referLevel" class="border border-gray-300 py-2 px-1 w-full rounded">
                        <option></option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">

                  <thead>

                    <tr class="bg-gray-100 text-left">
                      <th class="px-4 py-2 text-gray-600 whitespace-nowrap">#</th>
                      <th class="px-4 py-2 text-gray-600 whitespace-nowrap">CPR (Cost per Registration)</th>
                      <th class="px-4 py-2 text-gray-600 whitespace-nowrap">CPS (Cost per Sale)</th>
                      <th class="px-4 py-2 text-gray-600 whitespace-nowrap">CPC (Cost per Click)</th>
                      <th class="px-4 py-2 text-gray-600 whitespace-nowrap">CPA (Cost per Action)</th>

                    </tr>
                  </thead>
                  <tbody id="tableBody">
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">1</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="flex border items-center justify-center p-0 rounded">
                                <input type="number" step="0.001" class="px-2 py-2 outline-none border-none" placeholder="Cost Per Registration"/>
                                <div class="bg-gray-200 py-2 px-2 h-full">
                                    <span class="fa-solid fa-dollar bg-gray-200 text-gray-900 h-full py-1"></span>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="flex border items-center justify-center p-0 rounded">
                                <input type="number" step="0.001" class="px-2 py-2 outline-none border-none" placeholder="Cost Per Registration"/>
                                <div class="bg-gray-200 py-2 px-2 h-full">
                                    <span class="fa-solid fa-dollar bg-gray-200 text-gray-900 h-full py-1"></span>
                                </div>
                            </div>
                        </td>



                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="flex border items-center justify-center p-0 rounded">
                                <input type="number" step="0.001" class="px-2 py-2 outline-none border-none" placeholder="Cost Per Registration"/>
                                <div class="bg-gray-200 py-2 px-2 h-full">
                                    <span class="fa-solid fa-dollar bg-gray-200 text-gray-900 h-full py-1"></span>
                                </div>
                            </div>
                        </td>



                        <td class="px-4 py-2 whitespace-nowrap">
                            <div class="flex border items-center justify-center p-0 rounded">
                                <input type="number" step="0.001" class="px-2 py-2 outline-none border-none" placeholder="Cost Per Registration"/>
                                <div class="bg-gray-200 py-2 px-2 h-full">
                                    <span class="fa-solid fa-dollar bg-gray-200 text-gray-900 h-full py-1"></span>
                                </div>
                            </div>
                        </td>



                    </tr>
                  </tbody>
                </table>
        </div>

        <div class="mt-4 flex justify-end space-x-2">
          <button id="closeModalButton2" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Close
          </button>
          <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Tailwind Modal Script -->
  <script>



    document.addEventListener("DOMContentLoaded",function(){

        const referLevel = document.querySelector("#referLevel")
        const openModalButton = document.getElementById('openModalButton');
        const closeModalButton = document.getElementById('closeModalButton');
        const closeModalButton2 = document.getElementById('closeModalButton2');
        const myModal = document.getElementById('myModal');


    for(let x =1; x<=20;x++){
        referLevel.innerHTML += `<option value='${x}'>${x}</option>`;
    }


// Open modal
   openModalButton.addEventListener('click', () => {
      myModal.classList.remove('hidden');
    });

    // Close modal
    closeModalButton.addEventListener('click', () => {
      myModal.classList.add('hidden');
    });

    closeModalButton2.addEventListener('click', () => {
      myModal.classList.add('hidden');
    });
    })


  </script>



<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>






























<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/admin/newvideo.blade.php ENDPATH**/ ?>