@extends('admin.sidebar')

@section('contents')
    <div class="container bg-white min-h-screen py-4 px-3">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <header class="bg-blue-50 py-[2rem] rounded  flex items-center justify-between">
            <h1 class="text-2xl uppercase text-slate-700 font-bold">VIDEO-CAMPAIN</h1>
            <button onclick="window.history.back()" class="text-white  rounded py-3 px-2 bg-purple-500 hover:bg-purple-600 cursor-pointer outline-none border-none">Back</button>
        </header>





<div class=" mx-auto">

	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <div x-data="{ activeTab: 'general' }" x-cloak class="p-6">
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
                {{-- <button
                    :class="{ 'bg-blue-500 text-white': activeTab === 'recurring', 'bg-gray-500 text-white': activeTab !== 'recurring' }"
                    class="px-4 py-2 rounded-lg"
                    @click="activeTab = 'recurring'">
                    Recurring Settings
                </button>
                <button
                    :class="{ 'bg-blue-500 text-white': activeTab === 'postback', 'bg-gray-500 text-white': activeTab !== 'postback' }"
                    class="px-4 py-2 rounded-lg"
                    @click="activeTab = 'postback'">
                    Postback
                </button>
                <button
                    :class="{ 'bg-blue-500 text-white': activeTab === 'conversion', 'bg-gray-500 text-white': activeTab !== 'conversion' }"
                    class="px-4 py-2 rounded-lg"
                    @click="activeTab = 'conversion'">
                    Conversion Api
                </button> --}}
            </div>

            <!-- General Settings Tab -->
        <div  x-show="activeTab === 'general'" >


            <div class="grid grid-cols-2 gap-3">
                {{-- 1 --}}
                <div>
                    <div class="border  rounded-lg self-start my-2">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Campain Name</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">

                            <input id="costPerAction" type="text" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" placeholder="Campain Name">
                        </div>
                    </div>
                    {{-- end-1 --}}
                    <div class="border  rounded-lg self-start my-2">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Campain Target Link</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">

                            <input id="costPerAction" type="text" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" placeholder="Campain Target Link">

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


                    <div x-data="{ viewType: 'upload', videoUrl: '', uploadedFile: null }" class="border rounded-lg self-start my-2 flex-1">
                        <!-- Header -->
                        <div class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Video Settings</h2>
                        </div>
                    
                        <!-- View Type Selector -->
                        <div class="mb-4 px-3 py-2">
                            <select class="block w-full mt-2 py-3 border border-gray-300 rounded-lg px-2" x-model="viewType">
                                <option value="upload">Upload</option>
                                <option value="url">External Link</option>
                            </select>
                        </div>
                    
                        <!-- URL Input and Video Preview -->
                        <div x-show="viewType === 'url'" class="border rounded-lg self-start my-2 flex-1">
                            <div class="bg-gray-200 rounded-e py-2 px-3">
                                <h2 class="text-lg font-semibold mb-4">Video Preview</h2>
                            </div>
                    
                            <!-- Input for Video URL -->
                            <div class="mb-4 px-3 py-2">
                                <input type="text" placeholder="Paste video URL here..." x-model="videoUrl" class="block w-full mt-2 py-3 border border-gray-300 rounded-lg" />
                            </div>
                    
                            <!-- Video Preview -->
                            <div class="mb-4 px-3 py-2">
                                <video x-show="videoUrl" controls class="w-full border border-gray-300 rounded-lg" :src="videoUrl" >
                                    <source :src="videoUrl" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    
                        <!-- File Upload Section -->
                        <div x-show="viewType === 'upload'" class="mb-4 px-3 py-2">
                            <input type="file" @change="uploadedFile = $event.target.files[0]" class="block w-full mt-2 py-2 border border-gray-300 rounded-lg" />
                    
                            <!-- Show Uploaded Video Preview if File Selected -->
                            <div x-show="uploadedFile" class="mt-4">
                                <h3 class="text-sm font-semibold mb-2">Uploaded Video Preview</h3>
                                <video x-ref="videoPlayer" controls class="w-full border border-gray-300 rounded-lg">
                                    <source :src="uploadedFile ? URL.createObjectURL(uploadedFile) : ''" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                    




{{-- 
                    <div class="border  rounded-lg self-start my-2 flex-1">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Feature Video</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">
                            <input  type="file" name="image" class="block w-full mt-2 py-2 border border-gray-300 rounded-lg" placeholder="Set Answer........">
                        </div>
                    </div> --}}


                  <div class="flex gap-2">
                    <div class="border  rounded-lg self-start my-2 flex-1">
                        <div  class="bg-gray-200 rounded-e py-2 px-3">
                            <h2 class="text-lg font-semibold mb-4">Time(Seconds)</h2>
                        </div>
                        <div class="mb-4 px-3 py-2">
                           <input type="number" value="0" class="block w-full mt-2 py-3 px-2 border border-gray-300 rounded-lg" name="time" placeholder="Time in seconds"/>

                        </div>
                    </div>

                    <div class="border  rounded-lg  my-2 flex-1">
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

                </div>
                {{-- end-2 --}}
            </div>

          
        </div>

            <!-- Level Settings Tab -->
            <div x-show="activeTab === 'level'" class="border p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-4">Tobe Discussed</h2>
              
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
@endsection
