@extends('admin.sidebar')

@section('contents')
    <div class="container bg-white h-screen py-4 px-3">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold"> UVP -  INVESTORS</h1>

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


			<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 my-3" id="myTable">
				<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
					<tr>
						<th scope="col" class="p-4">
							{{-- <div class="flex items-center">
								<input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
								<label for="checkbox-all-search" class="sr-only">checkbox</label>
							</div> --}}
                            #
						</th>

						<th scope="col" class="px-6 py-3">
						Names
						</th>


						<th scope="col" class="px-6 py-3">
							Email
						</th>
                        <th scope="col" class="px-6 py-3">
							RANK
						</th>
						<th scope="col" class="px-6 py-3">
                            Package Name
						</th>
						<th scope="col" class="px-6 py-3">
							Amount Invested
						</th>
                        <th scope="col" class="px-6 py-3">
							Expiration Date
						</th>

                        <th scope="col" class="px-2 py-3">
							Status
						</th>

                        {{-- <th scope="col" class="px-2 py-3">
							Recent Earning
						</th> --}}
                        {{-- <th scope="col" class="px-2 py-3">

						</th>


						<th scope="col" class="px-6 py-3">
							Actions
						</th> --}}
					</tr>
				</thead>
				<tbody>



                    @foreach($investors as $deposit)


                    @php

                    // dd($deposit->package()->first()->name);

                    @endphp

                    <tr
						class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
						<td class="w-4 p-4">
							{{-- <div class="flex items-center">
								<input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
								<label for="checkbox-table-search-1" class="sr-only">checkbox</label>
							</div> --}}
                            {{ $loop->iteration }}
						</td>

						<th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
							{{ $deposit->user()->first()->name }}
						</th>

						<td class="px-6 py-4">
                            {{ $deposit->user()->first()->email }}
						</td>

                        <td class="px-6 py-4">
                            coming soon
						</td>

						<td class="px-6 py-4 uppercase">
							{{ $deposit->package()->first()->name }}
						</td>
						<td class="px-6 py-4">
							{{ number_format($deposit->amount)}} $

						</td>
                        <td class="px-6 py-4">
							{{ $deposit->expiration_date }}
						</td>


                        <td class="px-2 py-4">
                          @if($deposit->is_expired == '0' )
                            <span class="py-2 px-3 bg-indigo-600 text-gray-100 rounded-3xl">Active</span>
                        @else
                         <span class="py-2 px-3 bg-indigo-600 text-gray-100 rounded-3xl">Inactive</span>
                         @endif
                        </td>


                        {{-- <td class="px-2 py-4">
                            {{ $deposit->total_return }} %
                        </td>
						<td class="px-6 py-4 text-right">

                           <div class="flex gap-2">



                                <a href="{{route('admin.adventure.edit',$deposit)}}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-2 rounded focus:outline-none" >
                                    <i class="fa fa-edit"></i>
                                </a>





                               <form id="deleteForm" action="{{ route('admin.adventures.destroy', $deposit->id) }}" method="POST" onsubmit="return confirmDeletion();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>

                            <script>
                                function confirmDeletion() {
                                    return confirm('Are you sure you want to delete this adventure?');
                                }
                            </script>

                                <a href="{{route("admin.adventures.investors",$deposit->id)}}" class="bg-gray-700 hover:bg-gray-800
                                 text-white px-2 py-2 rounded focus:outline-none flex gap-1 items-center" >
                                    Investors <i class="fa fa-eye"></i>
                                </a>







                           </div>

						</td> --}}
					</tr>

                @endforeach

               <tr>
                <td colspan="9" class="py-2 px-3">
                    {{$investors->links()}}
                </td>
               </tr>


				</tbody>
			</table>
		</div>


		{{-- <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script> --}}
	</div>

    <div class="my-3 py-5">

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
