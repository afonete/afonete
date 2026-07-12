@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Alert Banner --}}
    @if(session('status'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 font-semibold" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 p-2.5 rounded-lg text-lg">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Manage UVP (Adventures)</h1>
                <p class="text-xs text-slate-500 mt-0.5">Create, edit, and configure active Unique Venture Portfolio packages and interest rates.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.adventures.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-sm shadow-blue-500/10 transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-plus"></i> <span>New Adventure</span>
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        
        {{-- Search Actions --}}
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-3">
            <span class="font-bold text-slate-800 text-sm">Active Adventure Plans</span>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input type="text" id="searchInput"
                       class="bg-white border border-slate-300 text-slate-800 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-9 p-2.5 transition-all" 
                       placeholder="Search packages, plans, rates...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="myTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">#</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">UVP Plan &amp; Name</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Daily ROI / Range</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Investment Limits</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Token Swap Price</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Return</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Investors</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adventures as $deposit)
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="p-4 text-sm font-semibold text-slate-500">
                                {{ ($adventures->currentPage() - 1) * $adventures->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800 text-sm block">{{ $deposit->plan }}</span>
                                <span class="text-xs text-slate-400 mt-0.5">Name: {{ $deposit->name }}</span>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                @if(!empty($deposit->percentage_range))
                                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2 py-0.5 rounded border border-blue-100 block w-max">{{ $deposit->percentage_range }}</span>
                                @else
                                    <span class="font-bold text-indigo-600 block">{{ $deposit->percentage }}%</span>
                                @endif
                                <span class="text-xs text-slate-400 mt-1 block">Base Rate: {{ $deposit->percentage }}%</span>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <span class="font-semibold text-slate-700 block">${{ number_format($deposit->min_amount, 0) }} - ${{ number_format($deposit->max_amount, 0) }}</span>
                                <span class="text-xs text-slate-400 mt-0.5 block">Currency: {{ $deposit->currency }}</span>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="font-semibold text-slate-600 block">${{ number_format($deposit->current_price, 4) }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5 block">Per UVP Swap</span>
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-slate-600">
                                {{ $deposit->total_return }}%
                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                {{-- Polymorphic relation count --}}
                                @if($deposit->payments()->count() > 0)
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full"><i class="fas fa-users mr-1"></i>{{ $deposit->payments()->count() }}</span>
                                @else
                                    <span class="bg-slate-100 text-slate-400 text-xs font-semibold px-2.5 py-1 rounded-full">0</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Edit Button --}}
                                    <a href="{{ route('admin.adventure.edit', $deposit) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold p-2 rounded-lg shadow-sm shadow-blue-500/10 transition-all text-xs flex items-center justify-center" title="Edit Plan">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.adventures.destroy', $deposit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this adventure?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-lg shadow-sm shadow-red-500/10 transition-all text-xs flex items-center justify-center" title="Delete Plan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    {{-- View Investors Link --}}
                                    <a href="{{ route('admin.adventures.investors', $deposit->id) }}" class="bg-slate-700 hover:bg-slate-800 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm transition-all text-xs inline-flex items-center gap-1.5" title="View Investors">
                                        <i class="fas fa-eye"></i> <span>Investors</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-slate-400 text-sm">
                                <i class="fas fa-folder-open mb-1 text-slate-300 block text-lg"></i>
                                No active adventure plans found.
                            </td>
                        </tr>
                    @endforelse
                    
                    {{-- Pagination Controls Row (Using Clean Tailwind CSS layout) --}}
                    @if($adventures->hasPages())
                        <tr class="bg-slate-50">
                            <td colspan="8" class="px-6 py-4">
                                <div class="flex justify-center">
                                    {{ $adventures->links() }}
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#myTable tbody tr').filter(function() {
                // Ignore the pagination row
                if ($(this).find('td').attr('colspan') != '8') {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                }
            });
        });
    });
</script>
@endsection
