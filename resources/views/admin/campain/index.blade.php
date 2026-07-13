@extends('admin.sidebar')

@section('contents')
    <div class="container bg-white h-screen py-4 px-3">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">CAMPAIN</h1>

    </header>





<div class=" mx-auto">

	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 py-3 px-4">
            <!-- Banner Campaign -->
            <div class="bg-cyan-500 text-white text-center rounded-lg shadow-lg p-6 ">
                <h2 class="text-lg font-semibold">Banner Campaign</h2>
                <button class="bg-white text-black font-semibold py-2 px-4 rounded mt-4">
                    <a href="{{route("admin.banner-campain")}}">+ Create New</a>
                </button>
            </div>

            <!-- Text Campaign -->
            <div class="bg-gray-500 text-white text-center rounded-lg shadow-lg p-6 ">
                <h2 class="text-lg font-semibold">Text Campaign</h2>
                <button class="bg-white text-black font-semibold py-2 px-4 rounded mt-4">
                   <a href="{{route("admin.text-campain")}}">+ Create New</a>
                </button>
            </div>

            <!-- Link Campaign -->
            <div class="bg-blue-500 text-white text-center rounded-lg shadow-lg p-6 ">
                <h2 class="text-lg font-semibold">Link Campaign</h2>
                <button class="bg-white text-black font-semibold py-2 px-4 rounded mt-4">
                    <a href="{{route("admin.link-campain")}}" >+ Create New</a>
                </button>
            </div>

            <!-- Video Campaign -->
            <div class="bg-gray-800 text-white text-center rounded-lg shadow-lg p-6 ">
                <h2 class="text-lg font-semibold">Video Campaign</h2>
                <button  class="bg-white text-black font-semibold py-2 px-4 rounded mt-4">
                    <a href="{{route('admin.video-campain')}}">+ Create New</a>
                </button>
            </div>
        </div>



			<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="myTable">
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
						Name
						</th>


						<th scope="col" class="px-6 py-3">
							Plan
						</th>
						<th scope="col" class="px-3 py-3">
                            Percentage
						</th>
						<th scope="col" class="px-6 py-3">
							Max-Amount
						</th>
                        <th scope="col" class="px-6 py-3">
							Min-Amount
						</th>
                        <th scope="col" class="px-2 py-3">
							Current Price
						</th>
                        <th scope="col" class="px-2 py-3">
							Currency
						</th>
                        <th scope="col" class="px-2 py-3">
							Total Return
						</th>

                        <th scope="col" class="px-2 py-3">
							Investors
						</th>


						<th scope="col" class="px-6 py-3">
							Actions
						</th>
					</tr>
				</thead>
				<tbody>


                    @foreach($Adventures as $deposit)



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
							{{ $deposit->name }}
						</th>

						<td class="px-6 py-4">
                            {{ $deposit->plan }}
						</td>
						<td class="px-3 py-4 uppercase">
							{{ $deposit->percentage}} %
						</td>
						<td class="px-6 py-4">
							{{ $deposit->max_amount }} {{ $deposit->currency == 'USD' ? '$' : 'EUR' }}
						</td>
                        <td class="px-6 py-4">
							{{ $deposit->min_amount }} {{ $deposit->currency == 'USD' ? '$' : 'EUR' }}
						</td>

                        <td class="px-6 py-4">
							{{ $deposit->current_price }} {{ $deposit->currency == 'USD' ? '$' : 'EUR' }}
						</td>

                        <td class="px-2 py-4">
                            {{ $deposit->currency }}
                        </td>

                        <td class="px-2 py-4">
                            {{ $deposit->total_return }} %
                        </td>
                        <td class="px-2 py-4 text-center">
                            {{$deposit->investments()->count()}}
                        </td>
						<td class="px-6 py-4 text-right">

                           <div class="flex gap-2">



                                <a href="{{route('admin.adventure.edit',$deposit)}}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-2 rounded focus:outline-none" >
                                    <i class="fa fa-edit"></i>
                                </a>





                               <form id="deleteForm" action="{{ route('admin.Adventures.destroy', $deposit->id) }}" method="POST" onsubmit="return confirmDeletion();">
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

                                <a href="{{route("admin.Adventures.investors",$deposit->id)}}" class="bg-gray-700 hover:bg-gray-800
                                 text-white px-2 py-2 rounded focus:outline-none flex gap-1 items-center" >
                                    Investors <i class="fa fa-eye"></i>
                                </a>







                           </div>

                            {{-- route('admin.approve-deposit')}} --}}



						</td>
					</tr>

                @endforeach



                <tr>
                    <td>
                        <div>
                            @php

                                //$Adventures->links()
                            @endphp
                        </div>
                    </td>
                </tr>

				</tbody>
			</table>
		</div>


		{{-- <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script> --}}
	</div>



    </div>
@endsection
