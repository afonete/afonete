<form action="{{route('transfer')}}" method="POST">
    @csrf
    <div class="flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
        <div>
            <label for="" class="text-xs font-semibold text-gray-700">AMOUNT</label>
            <div>
                <input type="number" placeholder="$"
                name="amount"
                 class="w-full md:w-32 border-gray-700 border-b-2 focus:outline-none text-end text-gray-700"
                 min="50"
                 max="{{$balance}}"
                 step="0.01"
                 >
                @error('amount')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <label for="" class="text-xs font-semibold text-gray-700">USERNAME</label>
            <div>
                <input type="text" placeholder="Username..."
                name="name"
                 class="w-full md:w-32 border-b-2 focus:outline-none  border-gray-700"

                 required
                 >
            </div>
            @error('name')
            <div class="text-red-600 font-semibold">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button class="bg-orange-600 px-8 py-0.5 mt-4 md:mt-6 focus:outline-none text-white rounded-xl">Send</button>
        </div>
    </div>
</form>
