<?php $__env->startSection('contents'); ?>
    <div class="container bg-white min-h-screen py-4 px-3">
        <?php if(session('status')): ?>
            <div class="alert alert-success">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <header class="bg-blue-50 py-[2rem] rounded  flex items-center justify-between">
            <h1 class="text-2xl uppercase text-slate-700 font-bold">TEXT-CAMPAIN</h1>
            <button onclick="window.history.back()" class="text-white  rounded py-3 px-2 bg-purple-500 hover:bg-purple-600 cursor-pointer outline-none border-none">Back</button>
        </header>





<div class=" mx-auto">

	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <div x-data="{ activeTab: 'general', showProgram:false,compain_name:'',compain_description:'' }" x-cloak class="p-6">
            

            <div x-data="programForm()" x-cloak x-show="showProgram" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <form @submit.prevent="submitForm" class="bg-white p-6 rounded-lg max-w-4xl w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Add Program</h3>
                        <button type="button" @click="showProgram = !showProgram"
                            class="px-3 py-2 outline-none border-none font-bold text-white rounded bg-red-500">X</button>
                    </div>

                    <!-- Program Name -->
                    <div class="mb-4 px-3 py-2 w-full">
                        <label>Program Name: </label>
                        <input type="text" placeholder="Enter Program Name..." x-model="program_name"
                            class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" />
                    </div>

                    <!-- Sales Setting -->
                    <div class="grid grid-cols-2 gap-2">
                        <div class="border">
                            <div class="py-3 px-2 bg-gray-800 text-gray-200">
                                <h3>Sales Setting</h3>
                            </div>
                            <div class="px-2 py-3 flex flex-col">
                                <div class="my-1 px-3 py-2 w-full">
                                    <label>Commission Type: </label>
                                    <select x-model="commission_type" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2">
                                        <option value=""></option>
                                        <option value="percentage">Percentage(%)</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </div>
                                <div class="my-1 px-3 py-2 w-full">
                                    <label>Commission For Sale: </label>
                                    <input type="number" step="0.001" x-model="commission_for_sale"
                                        class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" />
                                </div>
                                <div class="my-1 px-3 py-2 w-full">
                                    <label>Sale Status: </label>
                                    <div class="my-1 px-3 py-2 w-full flex flex-col">
                                        <label>Disable: <input type="radio" x-model="sale_status" value="disable"> </label>
                                        <label>Enable: <input type="radio" x-model="sale_status" value="enable"> </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Click Setting -->
                        <div class="border">
                            <div class="py-3 px-2 bg-gray-800 text-gray-200">
                                <h3>Click Setting</h3>
                            </div>
                            <div class="px-2 py-3 flex flex-col">
                                <div class="my-1 px-3 py-2 w-full">
                                    <label>Click Allow: </label>
                                    <select x-model="click_allow" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2">
                                        <option value="single_click">Allow Single Click</option>
                                        <option value="multi_click">Allow Multi clicks</option>
                                    </select>
                                </div>
                                <div class="flex gap-2">
                                    <div class="px-3 py-2 w-full">
                                        <label>Number Of Click: </label>
                                        <input type="number" x-model="nbr_click" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" />
                                    </div>
                                    <div class="px-3 py-2 w-full">
                                        <label>Amount Per Click($): </label>
                                        <input type="number" step="0.001" x-model="cost_per_click"
                                            class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" />
                                    </div>
                                </div>
                                <div class="my-1 px-3 py-2 w-full">
                                    <label>Click Status: </label>
                                    <div class="my-1 px-3 py-2 w-full flex flex-col">
                                        <label>Disable: <input type="radio" x-model="click_status" value="disable"> </label>
                                        <label>Enable: <input type="radio" x-model="click_status" value="enable"> </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-span-2 flex justify-end mt-4">
                        <button type="submit" class="px-3 py-2 outline-none border-none font-bold text-white rounded bg-blue-500">
                            Save & Close
                        </button>
                        <button type="button" @click="showProgram= !showProgram"
                            class="px-3 py-2 outline-none border-none font-bold text-white rounded bg-red-500 ml-2">Close</button>
                    </div>
                </form>
            </div>

            <script>
                function programForm() {
                    return {

                        program_name: '',
                        commission_type: '',
                        commission_for_sale: 0,
                        sale_status: '',
                        click_allow: '',
                        nbr_click: 0,
                        cost_per_click: 0,
                        click_status: '',

                        async submitForm() {
                            const data = {
                                program_name: this.program_name,
                                commission_type: this.commission_type,
                                commission_for_sale: this.commission_for_sale,
                                sale_status: this.sale_status,
                                click_allow: this.click_allow,
                                nbr_click: this.nbr_click,
                                cost_per_click: this.cost_per_click,
                                click_status: this.click_status,
                            };

                            try {
                                const response = await fetch('/your-endpoint-url', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify(data)
                                });

                                if (response.ok) {
                                    alert("Program saved successfully!");
                                    this.showProgram = false;
                                } else {
                                    console.error('Failed to save program:', await response.text());
                                }
                            } catch (error) {
                                console.error('Error:', error);
                            }
                        }
                    };
                }
            </script>

            <!-- Tabs -->
            <div class="flex space-x-4 mb-4">
                <button
                    :class="{ 'bg-blue-500 text-white': activeTab === 'general', 'bg-gray-500 text-white': activeTab !== 'general' }"
                    class="px-4 py-2 rounded-lg"
                    @click="activeTab = 'general'">
                    General Settings
                </button>
                <button
                    :class="{ 'bg-blue-500 text-white': activeTab === 'level', 'bg-gray-500 text-white': activeTab !== 'level' }"
                    class="px-4 py-2 rounded-lg"
                    @click="activeTab = 'level'">
                    Level Settings
                </button>
                
            </div>

            <!-- General Settings Tab -->
        <div  x-show="activeTab === 'general'" >


            <div class="grid grid-cols-2 gap-3"  x-data="compain_description:''}" >
                
                <div >
                    <div class="border  rounded-lg self-start my-2 flex-1"  x-data="{toolType:''}">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Tool Type</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">
                            <select class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" x-model="toolType">
                                <option value="">Select Tool Type</option>
                                <option value="program">Sale Integration</option>
                                <option value="single_action">Single Action Integration</option>
                                <option value="action">Multi Action Integration</option>
                                <option value="general_click">Click Integration</option>
                            </select>
                        </div>
                        

                        <div class=" self-start my-2 flex-1" x-show="toolType=='program'"  x-cloak>
                            <div  class="bg-gray-300 rounded-e py-2 px-3">
                                <h2 class="text-lg font-semibold mb-4">Select Program Header</h2>
                            </div>

                            <div class="flex gap-2 items-center" x-data="{marketProgram:''}">
                                <div class="mb-4 px-3 py-2 w-full">
                                    <select class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2">
                                        <option value="">Select Market Program</option>
                                    </select>
                                </div>

                                <div class="mb-4 px-3 py-2 w-full">
                                    <button type="button" @click="showProgram= true"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                        Add Program
                                    </button>
                                </div>
                            </div>
                        </div>

                            
                        <div class=" self-start my-2 flex-1" x-show="toolType=='general_click'"  x-cloak>
                            <div  class="bg-gray-300 rounded-e py-2 px-3">
                                <h2 class="text-lg font-semibold mb-4">General Click Commission Settings</h2>
                            </div>

                            <div class="flex gap-2">
                                <div class="mb-4 px-3 py-2 w-full">
                                    <label>Number Of Clicks: </label>
                                    <input type="number" name="nclicks"
                                     placeholder="Enter number of clicks..." class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" />

                                </div>

                                <div class="mb-4 px-3 py-2 w-full">
                                    <label>Cost per click ($): </label>
                                    <input type="number"
                                    placeholder="cost per click..." name="cost_per_click" step="0.001" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" />

                                </div>
                            </div>
                        </div>

                        


                        <div class=" self-start my-2 flex-1" x-show="toolType=='action' || toolType=='single_action'"  x-cloak>

                            <div class="flex gap-2">
                                <div class="mb-4 px-3 py-2 w-full">
                                    <label>Number Of actions: </label>
                                    <input type="number" name="nactions"
                                    placeholder="Enter number of actions..."
                                    :value="toolType=='single_action'?'1':''"
                                    :readonly="toolType=='single_action'"
                                    class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" />

                                </div>

                                <div class="mb-4 px-3 py-2 w-full">
                                    <label>Cost per action ($): </label>
                                    <input type="number"
                                    placeholder="cost per action..." name="cost_per_action" step="0.001" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" />

                                </div>
                            </div>

                            <div class="flex gap-2 items-center" x-data="{ actionCode: Math.random().toString(36).substr(2, 8) }">
                                <div class="mb-4 px-3 py-2 w-full">
                                    <label>Action Code: </label>
                                    <input type="text" name="action_code" x-model="actionCode" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" readonly />
                                </div>

                                <div class="mb-4 px-3 py-2 w-full">
                                    <button type="button" @click="actionCode = Math.random().toString(36).substr(2, 8)"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                        Generate
                                    </button>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="border  rounded-lg self-start my-2">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Campain Name</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">

                            


                            <input  type="text" x-model="compain_name" name="compain_name" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" placeholder="Campain Name">
                        </div>
                    </div>
                    
                    

                    <div class="border  rounded-lg self-start my-2 flex-1">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Campain Description</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">
                            <textarea rows="6" x-model='compain_description' name="description" class="block w-full mt-2 py-3 px-2 border border-gray-300 rounded-lg" placeholder="Enter Description........"></textarea>
                        </div>
                    </div>

                    <div class="border  rounded-lg self-start my-2">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Campain Target Link</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">

                            <input  type="text" name="link" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" placeholder="Campain Target Link">

                        </div>
                    </div>



                   <div x-data="{viewType:'default'}" class="flex gap-2">

                    <div class="border  rounded-lg self-start my-2 flex-1">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Campain Target Views</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">

                            <select class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" x-model="viewType">
                                <option value="default">Choose</option>
                                <option value="1000">1k Views</option>
                                <option value="5000">5k Views</option>
                                <option value="10000">10k Views</option>
                                <option value="500000">500k Views</option>
                                <option value="2500000">2500k Views</option>
                                <option value="custom">Custom Views</option>

                            </select>

                        </div>
                    </div>


                    <div class="border  rounded-lg self-start my-2 flex-1" x-show="viewType=='custom'">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Enter Custom Target Views</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">

                            <input id="costPerAction" name="customviews" type="number" min="1" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" placeholder="Enter views Target">


                        </div>
                    </div>

                   </div>

                   <div class="flex gap-2" x-data="{viewType:'default'}" >

                   <div class="border  rounded-lg self-start my-2 flex-1">
                    <div  class="bg-gray-200 rounded-e py-2 px-3">
                        <h2 class="text-lg font-semibold mb-4">Campain Target Duration</h2>
                    </div>
                    <div class="mb-4 px-3 py-2">

                        <select class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" x-model="viewType" name="duration">
                            <option value="default">Choose</option>
                            <option value="1">1 Day(0.004$ per view)</option>
                            <option value="3">3 Days(0.012$ per view)</option>
                            <option value="7">7 Days(0.012$ per view)</option>
                            <option value="15">15 Days(0.012$ per view)</option>
                            <option value="30">30 Days(0.012$ per view)</option>
                            <option value="60">60 Days(0.012$ per view)</option>
                            <option value="custom">custom</option>


                        </select>

                    </div>
                   </div>


                   <div x-data="{
                    startDate: '',
                    endDate: '',
                    duration: '',
                    calculateDuration() {
                        if (this.startDate && this.endDate) {
                            const start = new Date(this.startDate);
                            const end = new Date(this.endDate);
                            const timeDiff = end - start;
                            const daysDiff = timeDiff / (1000 * 3600 * 24);
                            this.duration = daysDiff > 0 ? daysDiff + ' days' : '';
                        }
                    }
                         }" class="flex flex-wrap gap-4" x-show="viewType == 'custom'">

                <!-- Start Date -->
                <div class="border rounded-lg self-start my-2 flex-1">
                    <div class="bg-gray-200 rounded-e py-2 px-3">
                        <h2 class="text-lg font-semibold mb-4">Start Date</h2>
                    </div>
                    <div class="mb-4 px-3 py-2">
                        <input type="date" x-model="startDate" @change="calculateDuration" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <!-- End Date -->
                <div class="border rounded-lg self-start my-2 flex-1">
                    <div class="bg-gray-200 rounded-e py-2 px-3">
                        <h2 class="text-lg font-semibold mb-4">End Date</h2>
                    </div>
                    <div class="mb-4 px-3 py-2">
                        <input type="date" x-model="endDate" @change="calculateDuration" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <!-- Duration (Read-only) -->
                <div class="border rounded-lg self-start my-2 flex-1">
                    <div class="bg-gray-200 rounded-e py-2 px-3">
                        <h2 class="text-lg font-semibold mb-4">Duration (Days)</h2>
                    </div>
                    <div class="mb-4 px-3 py-2">
                        <input type="text" x-model="duration" readonly class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" placeholder="Duration will be calculated...">
                    </div>
                </div>

                <!-- Cost -->
                <div class="border rounded-lg self-start my-2 flex-1">
                    <div class="bg-gray-200 rounded-e py-2 px-3">
                        <h2 class="text-lg font-semibold mb-4">Cost(Per View)</h2>
                    </div>
                    <div class="mb-4 px-3 py-2">
                        <input type="number" name="cost" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" placeholder="Set Cost...">
                    </div>
                </div>
            </div>


                </div>

                   <div class="border  rounded-lg self-start my-2 flex-1">
                    <div  class="bg-gray-200 rounded-e py-2 px-3">
                        <h2 class="text-lg font-semibold mb-4">Geo Targeting</h2>
                    </div>
                    <div class="mb-4 px-3 py-2">

                        <select class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" x-model="viewType">
                            <option value="default">Choose</option>
                            <option value="1000">US</option>
                            <option value="5000">Africa</option>
                            <option value="10000">Europe</option>
                            <option value="500000">Asia</option>
                            <option value="2500000">U.K</option>
                            <option value="custom">Oceania</option>

                        </select>

                    </div>
                   </div>

                <div class="flex gap-2">
                    <div class="border  rounded-lg self-start my-2 flex-1">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Set Question</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">
                            <input  type="text" name="question" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" placeholder="Set Question........">
                        </div>
                    </div>
                    <div class="border  rounded-lg self-start my-2 flex-1">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Set Answer</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">
                            <input  type="text" name="answer" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" placeholder="Set Answer........">
                        </div>
                    </div>
                </div>
                </div>



                <div>


                    <div class="max-w-3xl mx-auto bg-gray-900 text-gray-300 p-6 rounded-lg shadow-lg">
                        <h4 class="text-center text-xl font-semibold text-gray-400 mb-2">How does this work?</h4>
                        <hr class="border-gray-600 mb-4">

                        <div id="show" class="bg-gray-800 p-4 rounded-md mb-6" x-show="compain_name !==''"  x-cloak>
                            <h3 id="titts" class="text-lg font-semibold text-yellow-400" x-text="compain_name"></h3>
                            <hr class="border-gray-600 my-2">
                            <p id="descriptions" class="text-sm text-gray-200" x-text='compain_description'>

                            </p>
                        </div>

                        <p class="text-sm mb-4">
                            You can create a PTC (Paid to Click) advertisement and purchase traffic for any website that does not break our rules. After your purchase is complete and your ad is approved, Fonepo users will view your website for the amount of time you have chosen. Every time a user views your ad they will receive a small amount of money.
                        </p>
                        <p class="text-sm mb-4">
                            You can pay for PTC ads with your main Coin balance, or you can
                            <a href="<?php echo e(route('user.dashboard.deposit')); ?>" class="text-red-500 underline">Deposit</a> to add Coins to your balance.
                        </p>

                        <h4 class="text-center text-xl font-semibold text-gray-400 mb-2">Advertisement Guidelines</h4>
                        <ul class="list-disc list-inside text-sm space-y-2 pl-4">
                            <li>No URL shorteners</li>
                            <li>No illegal products or services (weapons, drugs, escorts, etc.)</li>
                            <li>No malicious websites or frame breakers / frame busters</li>
                            <li>No malware, viruses, spyware, or ransomware</li>
                            <li>No infinite redirect loops</li>
                            <li>No browser locking scripts or other attempts to hijack the browser</li>
                            <li>No copyright-infringing material</li>
                            <li>No x-rated adult content</li>
                        </ul>
                    </div>

                    <div class="border rounded-lg self-start my-2">
                        <div class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Terms Settings</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">
                            <!-- Trix Editor -->
                            <input id="terms-content" type="hidden" name="content">
                            <trix-editor input="terms-content" class="trix-editor"></trix-editor>
                        </div>
                    </div>



                    <div class="border  rounded-lg self-start my-2 flex-1">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Feature Image</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">
                            <input  type="file" name="image" class="block w-full mt-2 py-2 border border-gray-300 rounded-lg" placeholder="Set Answer........">
                        </div>
                    </div>

                    <div class="border  rounded-lg self-start my-2">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Cookies Settings</h2>
                        </div>
                        <div class="mb-4 px-3 py-2" x-data="{cookie_setting:'default'}">

                            <select name="cookie_setting"  class="block w-full mt-2 py-2 px-2 border border-gray-300 rounded-lg" x-model="cookie_setting">
                                <option value="default">Default</option>
                                <option value="custom">Custom</option>
                            </select>
                            <p x-show="cookie_setting=='default'" x-cloak class="py-1 text-gray-500">Default Cookies Tracker: 30 Days</p>
                            <div x-show="cookie_setting=='custom'" x-cloak>
                                <label class="block font-bold text-sm text-gray-600 py-2">Custom Cookies Tracker [In Days]</label>
                                <input class="block w-full  py-3 border border-gray-300 rounded-lg" type="number" placeholder="Enter number....">
                            </div>
                        </div>
                    </div>

                    <div class="border  rounded-lg self-start my-2 flex-1">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Status</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">
                            <label>
                                <input type="radio" name="status" value="Draft"> Draft
                            </label>

                            <label>
                                <input type="radio" checked name="status" value="Public"> Public
                            </label>
                        </div>
                    </div>

                </div>
                



              <div class="col-span-2">
                <div class="border  rounded-lg self-start my-2 flex-1">
                    <div  class="bg-gray-200 rounded-e py-2 px-3">
                        <h2 class="text-lg font-semibold mb-4">Content Settings</h2>
                    </div>

                    <div>
                        <div class="my-1 px-3 py-2">
                            <label class="block font-bold py-2">Content</label>
                            <textarea name="content" class="block w-full mt-2 py-3 px-2 border border-gray-300 rounded-lg" placeholder="Enter Description........"></textarea>
                        </div>

                        <div class="my-1 px-3 py-2">
                            <label class="block font-bold py-2">Text Size (px)</label>
                            <input type="number" name="description"
                             class="block w-full mt-2 py-3 px-2 border border-gray-300 rounded-lg"
                              placeholder="Enter Number........"></textarea>
                        </div>

                        <div class="flex space-x-4 px-2 gap-3 justify-around  border m-3 rounded-lg py-2 ">
                            <div class="flex flex-col gap-2 justify-center">
                                <label class="font-bold text-sm">Text Color</label>
                                <div class="border px-2 py-2 rounded self-start">
                                    <div id="text-color-picker" class="color-picker"></div>
                                    <input type="hidden" id="textColorInput" name="textColor" />
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 justify-center">
                                <label lass="font-bold text-sm">Background Color</label>
                                <div class="border px-2 py-2 rounded self-start">
                                    <div id="bg-color-picker" class="color-picker"></div>
                                    <input type="hidden" id="bgColorInput" name="bgColor" />
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 justify-center">
                                <label lass="font-bold text-sm">Border Color</label>
                                <div class="border px-2 py-2 rounded self-start">
                                    <div id="border-color-picker" class="color-picker"></div>
                                    <input type="hidden" id="borderColorInput" name="borderColor" />
                                </div>
                            </div>
                        </div>


                    </div>


                </div>
              </div>

            </div>


        </div>

            <!-- Level Settings Tab -->
            <div x-show="activeTab === 'level'" class="border p-4 rounded-lg">


                <div x-data="programLevels()" class="p-6 max-w-full">
                    <div class="mb-4">
                        <label class="font-semibold">Commission Type</label>
                        <select x-model="commissionType" placeholder="Custom" class="w-full mt-2 py-2 border border-gray-300 rounded-lg px-2">
                            <option value="default">Default</option>
                            <option value="custom">Custom</option>
                            <option value="disabled">Disabled</option>
                        </select>
                    </div>

                    <div class="mb-4" x-cloak x-show="commissionType == 'custom'">
                        <label class="font-semibold">Refer Level</label>
                        <input type="number"  x-model="referLevel" @input="updateLevels"  class="w-full mt-2 py-2 border border-gray-300 rounded-lg px-2" />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-sm">Level</th>
                                    <th class="px-4 py-2 text-sm">
                                        CPR - Cost per registration
                                        <select x-model="cprChoice" @change="cprChange()" class="w-full border border-gray-300 rounded-lg py-2 px-2">

                                            <option value="disabled">Select registration commission plan</option>
                                            <option symbal="%" value="percentage">Membership registration commission (%)</option>
                                            <option symbal="%" value="custom_percentage">Registration Custom Commission Amount (%)</option>
                                            <option symbal="$" value="fixed">Registration Fixed Amount</option>
                                        </select>
                                      <div x-show="cprChoice == 'custom_percentage'" x-cloak>
                                          <input type="number" name="custom_commission" class="px-2 py-1 my-1 rounded outline-none border" placeholder="custom commission..."/>
                                      </div>

                                    </th>
                                    <th class="px-4 py-2 text-sm">CPS - Cost per sale
                                        <select x-model="cpsChoice" @change="cpsChange()" class="w-full border border-gray-300 rounded-lg py-2 px-2">
                                            <option symbal="%" value="percentage">Percentage(%)</option>
                                            <option symbal="$" value="fixed">Fixed</option>
                                        </select>

                                    </th>
                                    <th class="px-4 py-2 text-sm">Click Count & CPC - Cost per click</th>
                                    <th class="px-4 py-2 text-sm">CPA - Cost per action</th>
                                </tr>
                            </thead>
                            <tbody>
                              <template x-for="(level, index) in  levels" >
                                <tr class="border-t border-gray-300" x-show="commissionType=='custom'" x-cloak>
                                    <td class="px-4 py-2 text-center" x-text='index+1'></td>

                                    <td class="px-4 py-2">
                                      <div class="flex gap-0 items-center">
                                       <div>
                                        <input type="number" x-model="levels[0].cprValue" class="w-full  py-2 border border-gray-300 rounded-l-lg px-2"
                                        step="0.001" />
                                       </div>
                                        <div class="py-1"> <button type="button" class="px-3 py-2 h-full bg-gray-300 font-bold text-gray-600 rounded-r-lg" x-text="cprSign"></button></div>
                                      </div>
                                    </td>

                                    <td class="px-4 py-2 flex">
                                        <div>
                                            <input type="number" x-model="levels[0].cprValue" class="w-full  py-2 border border-gray-300 rounded-l-lg px-2"
                                            step="0.001" />
                                           </div>
                                            <div > <button type="button" class="px-3 py-2 h-full bg-gray-300 font-bold text-gray-600 rounded-r-lg" x-text="cpsSign"></button></div>
                                          </div>
                                    </td>

                                    <td class="px-4 py-2">

                                        <div class="flex gap-2 justify-between">
                                            <input type="number" x-model="levels[0].clickCount" class="w-full  py-2 border border-gray-300 rounded-lg px-2"  />

                                        <div class="flex">
                                            <div>
                                                <input type="number" x-model="levels[0].cprValue" class="w-full  py-2 border border-gray-300 rounded-l-lg px-2"
                                                step="0.001" />
                                            </div>
                                                <div > <button type="button" class="px-3 py-2  bg-gray-300 font-bold text-gray-600 rounded-r-lg">$</button></div>
                                            </div>
                                        </div>
                                       </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex">
                                            <div>
                                                <input type="number" x-model="levels[0].cprValue" class="w-full  py-2 border border-gray-300 rounded-l-lg px-2"
                                                step="0.001" />
                                            </div>
                                                <div > <button type="button" class="px-3 py-2  bg-gray-300 font-bold text-gray-600 rounded-r-lg">$</button></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                              </template>
                            </tbody>
                        </table>
                    </div>


                </div>


                <script>
                    function programLevels() {
                        return {
                            commissionType: '',
                            cprSign:'$',
                            cpsSign:'%',
                            cprChoice:'',
                            cpsChoice:'',

                            cpr:'',

                            referLevel: 5,
                            levels: [],

                            // Initialize the levels array when the component loads
                            init() {
                                this.updateLevels();
                            },
                            cprChange(){
                                if(this.cprChoice == "percentage" || this.cprChoice == "custom_percentage"){this.cprSign ='%'}
                                if(this.cprChoice == "fixed" ){this.cprSign ='$'}
                                if(this.cprChoice == "disabled" ){this.cprSign =''}
                                

                            },

                             cpsChange(){

                                // percentage,fixed
                                if(this.cpsChoice == "percentage"){this.cpsSign ='%'}
                                if(this.cpsChoice == "fixed" ){this.cpsSign ='$'}
                                // if(this.cprChoice == "disabled" ){this.cprSign =''}


                            },

                            // Update levels based on the referLevel value
                            updateLevels() {
                                this.levels = Array.from({ length: this.referLevel }, () => ({
                                    cprValue: '',
                                    cpsValue: '',
                                    clickCount: '',
                                    cpcValue: '',
                                    cpaValue: ''
                                }));
                            },

                            saveData() {
                                console.log("Data Saved:", this.levels);
                            },
                            saveAndClose() {
                                this.saveData();
                                alert("Data Saved and Closed");
                            }
                        }
                    }
                </script>


            </div>

            <!-- Recurring Settings Tab -->
            <div x-show="activeTab === 'recurring'" class="border p-4 rounded-lg">
                <h2 class="text-lg font-semibold">Recurring Settings Content</h2>
                <!-- Add your recurring settings content here -->
            </div>

            <!-- Postback Tab -->
            <div x-show="activeTab === 'postback'" class="border p-4 rounded-lg">
                <h2 class="text-lg font-semibold">Postback Settings Content</h2>
                <!-- Add your postback settings content here -->
            </div>

            <!-- Conversion API Tab -->
            <div x-show="activeTab === 'conversion'" class="border p-4 rounded-lg">
                <h2 class="text-lg font-semibold">Conversion API Settings Content</h2>
                <!-- Add your conversion API content here -->
            </div>
        </div>

        <div class="px-2 pb-4">
            <button type="submit" class="w-full py-3 bg-purple-400 my-2 text-white  px-2 font-bold rounded hover:bg-purple-800 ">Save Campain</button>

        </div>

	</div>



    </div>

    <script>
        const pickrText = Pickr.create({
    el: '#text-color-picker',
    theme: 'classic',
    default: '#fff',
    components: {
        preview: true,
        opacity: true,
        hue: true,
        interaction: {
            hex: true,
            rgba: true,
            input: true,
            save: true
        }
    }
});

const pickrBackground = Pickr.create({
    el: '#bg-color-picker',
    theme: 'classic',
    default: '#2CB6BA',
    components: {
        preview: true,
        opacity: true,
        hue: true,
        interaction: {
            hex: true,
            rgba: true,
            input: true,
            save: true
        }
    }
});

const pickrBorder = Pickr.create({
    el: '#border-color-picker',
    theme: 'classic',
    default: '#cccccc',
    components: {
        preview: true,
        opacity: true,
        hue: true,
        interaction: {
            hex: true,
            rgba: true,
            input: true,
            save: true
        }
    }
});

// Update hidden inputs on color change
pickrText.on('change', (color) => {
    const colorValue = color.toHEXA().toString();
    document.getElementById('textColorInput').value = colorValue;
//    document.body.style.color = colorValue;
});

pickrBackground.on('change', (color) => {
    const colorValue = color.toHEXA().toString();
    document.getElementById('bgColorInput').value = colorValue;
//    document.body.style.backgroundColor = colorValue;
});

pickrBorder.on('change', (color) => {
    const colorValue = color.toHEXA().toString();
    document.getElementById('borderColorInput').value = colorValue;
//    document.body.style.borderColor = colorValue;
});

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/admin/campain/TextCampain.blade.php ENDPATH**/ ?>