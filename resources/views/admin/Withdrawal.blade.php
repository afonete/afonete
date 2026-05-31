@extends('admin.sidebar')

@section('contents')
    <div class="container bg-white h-screen py-4 px-3">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

       <header class="bg-blue-50 py-[2rem] rounded ">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">WITHDRAWAL</h1>

    </header>





<div class=" mx-auto">
	<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div x-data="{ crypto: 'BTC', network: '', amount: 0, address: '' }" class="max-w-lg mx-auto p-6 bg-white border rounded-lg shadow-lg">

            <!-- Withdraw Header -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-semibold">Withdraw Cryptocurrency</h2>
            </div>

            <!-- Select Cryptocurrency -->
            <div class="mb-4">
                <label class="block mb-2 text-gray-700 font-semibold">Select Coin</label>
                <select x-model="crypto" class="block w-full mt-2 py-2 border border-gray-300 rounded-lg">
                    <option value="BTC">Bitcoin (BTC)</option>
                    <option value="ETH">Ethereum (ETH)</option>
                    <option value="BNB">Binance Coin (BNB)</option>
                    <option value="USDT">Tether (USDT)</option>
                </select>
            </div>

            <!-- Network -->
            <div class="mb-4">
                <label class="block mb-2 text-gray-700 font-semibold">Network</label>
                <select x-model="network" class="block w-full mt-2 py-2 border border-gray-300 rounded-lg">
                    <option value="">Select Network</option>
                    <option value="BTC">Bitcoin Network (BTC)</option>
                    <option value="ERC20">Ethereum Network (ERC20)</option>
                    <option value="BEP20">Binance Smart Chain (BEP20)</option>
                </select>
            </div>

            <!-- Withdraw Address -->
            <div class="mb-4">
                <label class="block mb-2 text-gray-700 font-semibold">Withdraw Address</label>
                <input type="text" x-model="address" placeholder="Paste or enter the withdrawal address" class="block w-full mt-2 py-2 border border-gray-300 rounded-lg">
            </div>

            <!-- Amount Input -->
            <div class="mb-4">
                <label class="block mb-2 text-gray-700 font-semibold">Amount to Withdraw</label>
                <input type="number" x-model="amount" placeholder="Enter the amount" class="block w-full mt-2 py-2 border border-gray-300 rounded-lg">
            </div>

            <!-- Withdraw Button -->
            <div class="text-center">
                <button @click="withdraw()" class="bg-yellow-400 text-white font-semibold py-2 px-4 rounded-lg hover:bg-yellow-500 transition duration-200">
                    Withdraw
                </button>
            </div>
        </div>

        <script>
            function withdraw() {
                // Your withdraw logic will go here.
                console.log('Withdraw clicked');
            }
        </script>

	</div>
</div>
@endsection
