@extends('admin.sidebar')

@section('contents')
    <div class="container bg-white h-screen py-4 px-3">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">USERS - MEMBERSHIP PLANS</h1>

    </header>





<div class=" mx-auto">

	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <div class="flex justify-between items-center  px-3">
             {{-- <a href="{{route("admin.adventures.create")}}" class="bg-blue-500 py-2 px-3 rounded text-gray-50">New <i class="fa fa-save"></i></a> --}}
             <div class="p-4">
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                     focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5  dark:bg-gray-700
                      dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                       dark:focus:border-blue-500" placeholder="Search username,names,email,date,..">
                </div>
            </div>
        </div>


        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">#</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">User Details</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Plan Type</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Amount</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Interest(%)</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Daily Bonus</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Duration</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Reward</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Plan Status</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">User Level</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Membership Details</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Role Position</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Groups</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Country</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Approved Date</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">KYC Status</th>
                        <th class="px-4 py-2 text-left text-sm font-bold uppercase text-gray-600 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    @foreach ($users  as $user)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">{{$user->id}}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">
                            <div class="flex flex-col gap-2 ">
                                <div class="flex"> <i class="fa fa-user text-xs pr-1 text-gray-300"></i> <span> {{$user->username}}</span></div>
                                <div class="flex"> <i class="fa fa-envelope text-xs pr-1 text-gray-300"></i> <span>{{$user->email}}</span></div>
                                <div class="flex"> <i class="fa fa-phone-square text-xs pr-1 text-gray-300"></i> <span>{{$user->phone}}</span></div>
                                <div class="flex"> <i class="fa fa-calendar-check text-xs pr-1 text-gray-300"></i> <span>{{$user->created_at}}</span></div>
                            </div>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">
                            {{$user->has_paid_package}}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">
                            @php
                                // dd($user->investments);
                                $amount = 0;
                                if($user->have_activation_code){
                                    $amount = $user->have_activation_code->price;
                                }
                            @endphp
                            {{'$'.$amount}}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">
                            {{-- <div>UVD</div>
                            <div>364 Days 11 Hours 28 Minutes</div>
                            <div>Available withdraw: $1000</div> --}}
                            soon
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">Soon</td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">
                            <span class="text-red-500">✖</span>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700 border-b">
                            <div class="flex space-x-2">
                                <button class="bg-teal-500 text-white p-1 rounded">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="bg-blue-500 text-white p-1 rounded">
                                    <i class="fa fa-users"></i>
                                </button>
                                <button class="bg-teal-500 text-white p-1 rounded">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="bg-teal-500 text-white p-1 rounded">
                                    <i class="fa fa-info-circle"></i>
                                </button>
                                <button class="bg-red-500 text-white p-1 rounded">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button class="bg-teal-500 text-white p-1 rounded">
                                    <i class="fa fa-lock"></i>
                                </button>
                                <button class="bg-green-500 text-white p-1 rounded">
                                    <i class="fa fa-sign-in"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="10" class="py-2 px-3">
                            {{$users->links()}}
                        </td>
                    </tr>
                    <!-- Add more rows here -->
                </tbody>
            </table>
        </div>


		</div>


		{{-- <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script> --}}
	</div>

    <script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script>



      $(document).ready(function(){

        $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#myTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });



    $('#openModal').click(function() {
                $('#modal').removeClass('hidden');
            });

            $('#closeModal').click(function() {
                $('#modal').addClass('hidden');
                $("#action").value = ""
            });

      })
      function show(action,deposit){
        // alert(deposit)

        $('#modal').removeClass('hidden');
        // $("#action").value(action)
        // $("#deposit").value(deposit);
        $("#inputs").html(`
        <input type="hidden" name="action" value="${action}"/>
        <input type="hidden" name="deposit" value="${deposit}"/>
        `)

    }
    </script>
    </div>
@endsection
