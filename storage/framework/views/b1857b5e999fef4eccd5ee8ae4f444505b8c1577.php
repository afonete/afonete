<div class="wrapper">
     <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <title>Team Building Overview</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="content-wrapper">
        <div class=" px-4 py-8">

   <!-- Menu Section -->

   <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.navbar','data' => []]); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
        
    <div class="flex flex-col md:flex-row gap-4 mb-4">

    <div class="w-full md:w-[20%] mx-auto flex justify-center items-center">
        <a href="" class="bg-green-700 px-2 text-white rounded-md block text-center" >Scroll to up<i class="fa fa-arrow-up"></i> </a>
      </div> 

      <div class="w-full md:w-[30%] ">
          <fieldset class="border-2 border-orange-700 rounded-xl px-3 py-2 shadow-md bg-white" style="border: 2px solid rgb(194 65 12);">
              <legend class="font-semibold text-center text-orange-700 rounded-full border-2 border-orange-800 p-2"><i class="fa-solid fa-trophy"></i></legend>
              <div class="flex justify-between items-center">
                  <div>
                      <span class="bg-orange-800 rounded-md text-md text-white px-1" >CURRENT</span>
                      <span class="text-center" >None</span>
                  </div>

                  <div>
                      <p class="text-orange-800" >Rank & Rewards</p>
                      <span class="text-center border-2 rounded-md py-0.5   border-orange-800"> <a href="" class="text-orange-800">Details</a> </span>
                  </div>

                  <div>
                      <p class=" bg-blue-950 text-center text-white rounded-md " >NEXT</p>
                      <div class="bg-blue-950 mt-1	 text-white rounded-full w-20 h-20 flex items-center justify-center">
                        <div class="text-center">
                        <span class="text-sm" >VIP</span>
                        <span class="text-sm" >MEMBER</span>
                        </div>
                      </div>
                  </div>
              </div>
          </fieldset>  
      </div>

      <div class="w-full md:w-[30%]">
          <fieldset class="border-2 border-orange-700 rounded-xl px-3 py-2 shadow-md bg-white" style="border: 2px solid rgb(194 65 12);">
              <legend class="font-semibold text-center text-orange-700 rounded-full border-2 border-orange-800 p-2"><i class="fa-solid fa-trophy"></i></legend>
              <div class="">
                <span class="text-orange-800 text-center font-medium">: Sponsor Link</span>
                  <div class="flex flex-col ">
                                        <div class="relative">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Left Link</label>
                                            <div class="flex">
                                                <input type="text" id="leftLink" value="<?php echo e(url('/register?position=left&sponsor=' . auth()->user()->username)); ?>" class="w-full px-2 py-1 h-8 border border-gray-300 rounded-l-md shadow-sm focus:ring-orange-500 focus:border-orange-500" readonly>
                                                <span onclick="copyToClipboard('leftLink')" class="h-8 bg-orange-700 text-white rounded-r-md hover:bg-orange-800 px-3 flex items-center justify-center">
                                                    <i class="fa fa-copy"></i>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="relative">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Right Link</label>
                                            <div class="flex">
                                                <input type="text" id="rightLink" value="<?php echo e(url('/register?position=right&sponsor=' . auth()->user()->username)); ?>" class="w-full px-2 py-1 h-8 border border-gray-300 rounded-l-md shadow-sm focus:ring-orange-500 focus:border-orange-500" readonly>
                                                <span onclick="copyToClipboard('rightLink')" class="h-8 bg-orange-700 text-white rounded-r-md hover:bg-orange-800 px-3 flex items-center justify-center">
                                                    <i class="fa fa-copy"></i>
                                                </span>
                                            </div>
                                        </div>                                
                                </div>
                  
                  
              </div>
          </fieldset>  
      </div>


   

      <div class="w-full md:w-[20%] mx-auto flex justify-center items-center">
        <a href="" class="bg-green-700 px-2 text-white rounded-md block text-center" >Go one level up <i class="fa fa-arrow-up"></i> </a>
      </div> 

     </div>




  <h1 class="text-3xl font-bold mb-6 text-blue-900 text-center">Team Building Structure </h1>
            
            <div class="">
 

<div class="bg-white rounded-lg shadow-md p-6">
         
<button  class="bg-blue-500 md:hidden hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"  id="backButton">
     Back
</button>

<button onclick="openModal()" class="bg-blue-500  my-5 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    Add Team Member
</button>
               
                <div class="tree" id="tree">
                    <!-- Tree structure will be rendered here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-8 border md:w-[40%] w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-6 text-center">Team Member Details</h3>
            <div class="mt-2">
                <form id="userForm" class="grid grid-cols-2 gap-6">
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">Sponsor ID:</label> -->
                        <input type="text" id="sponsorId" placeholder="Sponsor ID" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">Position:</label> -->
                        <select id="position" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                            <option value=" " selected disabled> -- Select Position --</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">Package:</label> -->
                        <select id="package" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                            <option value="" selected disabled  > -- Select Package --</option>
                            <option value="">Package1</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">Username:</label> -->
                        <input type="text" id="username"  placeholder="Enter Username" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">Login Password:</label> -->
                        <input type="password" id="loginPassword" placeholder="Enter Login Password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Login Password:</label> -->
                        <input type="password" id="confirmLoginPassword" placeholder="Confirm Login Password:" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">New Member's Name:</label> -->
                        <input type="text" id="memberName" placeholder="Enter New Member's Name" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">New Member's Email:</label> -->
                        <input type="email" id="memberEmail" placeholder="Enter New Member's Email" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">Country:</label> -->
                        <select id="country" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                            <option value="" selected disabled> -- Select Country -- </option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">New Member's Phone Number:</label> -->
                        <input type="tel" id="memberPhone" placeholder="Enter New Member's Phone Number" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">New Member's Address:</label> -->
                        <input type="text" id="memberAddress" placeholder="Enter New Member's Address" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">New Member's City:</label> -->
                        <input type="text" id="memberCity" placeholder="Enter New Member's City" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">New Member's State:</label> -->
                        <input type="text" id="memberState" placeholder="Enter New Member's State" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="mb-2">
                        <!-- <label class="block text-gray-700 text-sm font-bold mb-2">BTC Address:</label> -->
                        <input type="text" id="btcAddress" placeholder="Enter BTC Address" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 ease-in-out">
                    </div>
                    <div class="col-span-2 mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="termsAgree" class="form-checkbox h-5 w-5 text-blue-500">
                            <span class="ml-2 text-gray-700">I agree to the terms and conditions</span>
                        </label>
                    </div>
                    <div class="col-span-2 flex items-center justify-end gap-4 ">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 ease-in-out">Continue</button>
                        <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 ease-in-out">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
        .tree {
            text-align: center;
            width: 100%;
            margin: 20px auto;
        }
        .tree ul {
            display: flex;
            justify-content: center;
            padding-top: 20px;
            position: relative;
        }
        .tree li {
            list-style-type: none;
            text-align: center;
            position: relative;
            padding: 20px 5px 0 5px;
        }
        .tree li::before, .tree li::after {
            content: '';
            position: absolute;
            top: 0;
            right: 50%;
            border-top: 2px solid #ccc;
            width: 50%;
            height: 20px;
        }
        .tree li::after {
            right: auto;
            left: 50%;
            border-left: 2px solid #ccc;
        }
        .tree li:only-child::before, .tree li:only-child::after {
            display: none;
        }
        .tree li:only-child {
            padding-top: 0;
        }
        .tree li:first-child::before, .tree li:last-child::after {
            border: 0 none;
        }
        .tree li:last-child::before {
            border-right: 2px solid #ccc;
            border-radius: 0 5px 0 0;
        }
        .tree li:first-child::after {
            border-radius: 5px 0 0 0;
        }
        .tree li div {
            border: 2px solid #ccc;
            padding: 10px 20px;
            text-decoration: none;
            color: #666;
            font-family: Arial, sans-serif;
            font-size: 14px;
            display: inline-block;
            border-radius: 5px;
            transition: all 0.5s;
            cursor: pointer;
            word-break: break-word;
            text-align: center;
        }
        .tree li div:hover, .tree li div:hover+ul li div {
            background: #e0e0e0;
            color: #000;
            border: 2px solid #94a3b8;
        }
        .tree li img {
            width: 48px;
            height: 48px;
            display: block;
            margin: 0 auto;
            border: none;
        }
        .back-button {
            margin-bottom: 20px;
            display: none;
        }
    </style>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/gojs/release/go.js"></script>
<script>

        // Generate unique alphabetic identifier
        function generateIdentifier(num) {
            let identifier = '';
            while (num >= 0) {
                identifier = String.fromCharCode((num % 26) + 65) + identifier;
                num = Math.floor(num / 26) - 1;
            }
            return identifier;
        }

        // const treeData = {
        //     id: 'A',
        //     name: 'Person A',
        //     children: generateChildren('A', 20) // Generate children up to 20 stages deep
        // };
        let treeData={};
            console.log(treeData);
       // Function to generate children up to a specific depth
       function generateChildren(prefix, depth, currentDepth = 1, index = 0) {
            if (currentDepth > depth) return [];
            const numChildren = 2; // Each node has 2 children
            const children = [];
            for (let i = 0; i < numChildren; i++) {
                const id = `${prefix}${generateIdentifier(index + i)}`;
                children.push({
                    id,
                    name: `Person ${id}`,
                    children: generateChildren(id, depth, currentDepth + 1, index * numChildren + i)
                });
            }
            return children;
        }

        
        // Function to break long names into lines
        function breakLongName(name) {
            if (name.length <= 15) return name;
            const parts = [];
            for (let i = 0; i < name.length; i += 15) {
                parts.push(name.slice(i, i + 15));
            }
            return parts.join('<br>');
        }

         // Function to render the tree recursively
         function renderTree(node, depth = 0) {
                if (!node) return '';
                if (depth > 3) return '';

                const children = node.children.map(child => renderTree(child, depth + 1)).join('');
                const hasChildren = node.children.length > 0;
                // Correct way to handle images
                const images = ['rf3.png', 'rf4.png', 'rf5.png'];
                const randomImage = images[Math.floor(Math.random() * images.length)];
                
                // Use the correct path to your images
                return `
                    <li>
                        <div class="text-black bg-white" data-id="${node.id}">
                            <img src="/image/${randomImage}" alt="Person Icon"><br>
                            ${breakLongName(node.name)}
                        </div>
                        ${hasChildren ? `<ul>${children}</ul>` : ''}
                    </li>
                `;
        }

         // Function to find a node by ID
         function findNode(id, currentNode) {
            if (currentNode.id == id) return currentNode;
            for (let child of currentNode.children) {
                const result = findNode(id, child);
                if (result) return result;
            }
            return null;
        }

        
        async function fetchTreeData(userId) {
            try {
                const response = await fetch(`/api/getReferralTree/${userId}`);
                return await response.json();
            } catch (error) {
                console.error('Error fetching tree data:', error);
                return null;
            }
        }



        async function updateTree(nodeId = 0) {
            // console.log(nodeId)
            // return null/
            const treeData = await fetchTreeData(nodeId);
            if (!treeData) return;

            const treeHtml = renderTree(treeData);
            document.getElementById('tree').innerHTML = `<ul>${treeHtml}</ul>`;

            document.getElementById('backButton').style.display = historyStack.length > 1 ? 'block' : 'none';

            document.querySelectorAll('.tree div').forEach(div => {
                div.addEventListener('click', (e) => {
                    e.stopPropagation();
                    navigateToNode(div.dataset.id);
                });
            });
        }
            
        const historyStack = [];

// Function to navigate to a node and update the history stack
function navigateToNode(nodeId) {
    historyStack.push(nodeId);
    updateTree(nodeId);
}
  // Function to go back to the previous node
  function goBack() {
            if (historyStack.length > 1) {
                historyStack.pop(); // Remove current node
                const previousNodeId = historyStack.pop(); // Get previous node ID
                updateTree(previousNodeId);
                // Show or hide the back button based on history
                document.getElementById('backButton').style.display = historyStack.length > 1 ? 'block' : 'none';
            } else {
                // If only one item in history, go back to the root
                updateTree(<?php echo e(auth()->user()->id); ?>);
                historyStack.length = 0; // Clear history
                document.getElementById('backButton').style.display = 'none';
            }
        }

        // Add click event listener to the back button
        document.getElementById('backButton').addEventListener('click', goBack);

            

       
        updateTree(<?php echo e(auth()->user()->id); ?>);
    </script>


<script>

    // modal
    function openModal() {
    document.getElementById('userModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

// Optional: Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('userModal');
    if (event.target === modal) {
        closeModal();
    }
});


    function copyToClipboard(elementId) {
        var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
                  document.execCommand("copy");
                                  
        // Optional: Show a tooltip or notification that the text was copied
         alert("Copied the link!");
       }
    
 </script><?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/user/team/team-structure.blade.php ENDPATH**/ ?>