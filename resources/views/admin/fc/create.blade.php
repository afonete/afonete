@extends('admin.sidebar')

@section('contents')
    <div class="container bg-white h-screen py-4 px-3">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

       <header class="bg-blue-50 py-[2rem] rounded">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">FC VIP PACKAGE - CREATE</h1>
        <p class="text-sm text-slate-500 mt-2">
            FC VIP packages are lifetime memberships — they never expire and have no duration.
            Configure the four FC card benefit bullets (DMaster coin card, CryptoFoneLoan range,
            Tokens, Ads, Free Shop Room) and the total token grant that will be locked for 12 months
            and released in equal monthly installments to the user's Available Token balance.
        </p>
       </header>

<div class="mx-auto">
	<div class="relative overflow-x-auto sm:rounded-lg py-3">

        @if (session('message'))
        <p class="text-gray-200 mx-2 py-2 px-1 bg-green-800 rounded">{{ session('message') }}</p>
        @endif

        @if ($errors->any())
        <div class="bg-red-600 py-2 px-2 rounded shadow-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="text-red-200">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{route('fcpackages.store')}}" method="POST" class="flex flex-col w-auto sm:w-2/3 mx-auto gap-3">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 pb-2">Package Name:</label>
                    <input type="text" id="name" name="name" class="block w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                     placeholder="Ex: DMaster Coin Card" value="{{old('name')}}" required>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 pb-2">Price (USD):</label>
                    <input type="number" step="0.01" id="price" name="price" class="block w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                     placeholder="Ex: 1000" value="{{old('price')}}" required>
                </div>

                <div>
                    <label for="default_token" class="block text-sm font-medium text-gray-700 pb-2">
                        Total Tokens Granted (locked 12 months):
                    </label>
                    <input type="number" step="any" id="default_token" name="default_token" class="block w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                     placeholder="Ex: 100000" value="{{old('default_token', 0)}}">
                    <p class="text-xs text-slate-500 mt-1">This full amount goes into Locked Token on purchase and releases 1/12 to Available Token every month for 12 months.</p>
                </div>

                <div>
                    <label for="token_price" class="block text-sm font-medium text-gray-700 pb-2">Token Price (USD, informational):</label>
                    <input type="number" step="0.0001" id="token_price" name="token_price" class="block w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                     placeholder="Ex: 0.01" value="{{old('token_price')}}">
                </div>
            </div>

            <div class="border-t pt-4 mt-2">
                <h6 class="font-semibold text-slate-700 mb-2"><i class="fas fa-clipboard-list mr-1 text-blue-500"></i> FC Card Benefits (displayed on user /user/package card)</h6>
                <p class="text-xs text-slate-500 mb-3">
                    Leave a field blank to use its default shown in the placeholder. "DMaster coin card" is the card brand/header and does not need a separate field.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="loan_min" class="block text-sm font-medium text-gray-700 pb-2">Allowed CryptoFoneLoan — Minimum ($):</label>
                        <input type="number" step="0.01" id="loan_min" name="loan_min" class="block w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                         placeholder="1000" value="{{old('loan_min', 1000)}}">
                    </div>

                    <div>
                        <label for="loan_max" class="block text-sm font-medium text-gray-700 pb-2">Allowed CryptoFoneLoan — Maximum ($):</label>
                        <input type="number" step="0.01" id="loan_max" name="loan_max" class="block w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                         placeholder="50000" value="{{old('loan_max', 50000)}}">
                    </div>

                    <div>
                        <label for="ads_credits" class="block text-sm font-medium text-gray-700 pb-2">Ad Credits (e.g. 100000):</label>
                        <input type="number" step="1" id="ads_credits" name="ads_credits" class="block w-full border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                         placeholder="100000" value="{{old('ads_credits', 100000)}}">
                    </div>

                    <div class="flex items-end">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="hidden" name="free_shop_room" value="0">
                            <input type="checkbox" id="free_shop_room" name="free_shop_room" value="1"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2"
                                {{ old('free_shop_room', 1) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Include <strong>Free Shop Room Online</strong> benefit</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded p-3">
                <i class="fa fa-info-circle mr-1"></i> FC VIP packages are <strong>lifetime</strong> — they never expire. On purchase the user receives the configured token amount (locked 12 months), the loan eligibility, ad credits, and free shop room.
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 w-full">
                    Save <i class="fa fa-save"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
