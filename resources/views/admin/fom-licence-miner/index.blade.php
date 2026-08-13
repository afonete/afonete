@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Alert Banner --}}
    @if(session('message'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 font-semibold flex items-center justify-between" role="alert">
            <span><i class="fas fa-check-circle mr-1"></i> {{ session('message') }}</span>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="bg-amber-100 text-amber-600 p-3 rounded-lg text-xl">
                <i class="fas fa-microchip"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">FOM Licence Miner Packages</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Configure numerical &amp; textual plan values for <code class="bg-slate-100 text-amber-700 px-1.5 py-0.5 rounded border border-slate-200 font-mono text-[11px]">/investment-package</code>.
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.fom-licence-miner.index') }}" class="bg-amber-500 text-white font-bold py-2 px-3 rounded-lg text-xs shadow-sm transition-all">
                <i class="fas fa-box"></i> Manage Plans
            </a>
            <a href="{{ route('admin.fom-licence-miner.codes') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3 rounded-lg text-xs transition-all">
                <i class="fas fa-ticket-alt"></i> Code Usage
            </a>
            <a href="{{ route('admin.fom-licence-miner.escrow-audit') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3 rounded-lg text-xs transition-all">
                <i class="fas fa-shield-alt"></i> Escrow &amp; Staking Audit
            </a>
            <a href="{{ route('admin.fom-licence-miner.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3.5 rounded-lg shadow-sm transition-all flex items-center gap-1.5 text-xs ms-2">
                <i class="fas fa-plus"></i> <span>New Package</span>
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        
        {{-- Search Actions --}}
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-3">
            <span class="font-bold text-slate-800 text-sm">Active &amp; Configured Licence Miner Plans</span>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input type="text" id="searchInput"
                       class="bg-white border border-slate-300 text-slate-800 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-9 p-2.5 transition-all" 
                       placeholder="Search packages, prices, bonuses...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="myTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12 text-center">#</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Package Name</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Price (USDT)</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tokens Allocated</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bonuses &amp; Sponsors</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Duration &amp; Limits</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Return</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $pkg)
                        @php
                            $numPrice       = \App\Models\FomLicenceMiner::cleanNum($pkg->price);
                            $numTokens      = \App\Models\FomLicenceMiner::cleanNum($pkg->tokens);
                            $numTokenBonus  = \App\Models\FomLicenceMiner::cleanNum($pkg->token_bonus);
                            $numSponsors    = \App\Models\FomLicenceMiner::cleanNum($pkg->direct_sponsors);
                            $numAffiliate   = \App\Models\FomLicenceMiner::cleanNum($pkg->affiliate_vbonus);
                            $numVolumePoint = \App\Models\FomLicenceMiner::cleanNum($pkg->volume_point);
                            $numTotalReturn = \App\Models\FomLicenceMiner::cleanNum($pkg->total_return);
                        @endphp
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="p-4 text-sm font-semibold text-slate-500 text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800 text-sm block">{{ $pkg->name }}</span>
                                <span class="text-xs text-slate-400 mt-0.5 block">Display: {{ $pkg->display_price ?: (number_format($numPrice, 0) . ' USDT') }}</span>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="font-extrabold text-emerald-600 block">${{ number_format($numPrice, 2) }}</span>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <span class="font-bold text-indigo-600 block">{{ number_format($numTokens) }} {{ $tokenSymbol }}</span>
                                <span class="text-[11px] text-slate-400 block mt-0.5">Bonus: X{{ $numTokenBonus }}%</span>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="space-y-0.5 text-xs">
                                    <div><span class="text-slate-400">Direct Sponsor:</span> <span class="font-semibold text-slate-700">{{ $numSponsors }}%</span></div>
                                    <div><span class="text-slate-400">Affiliate V.Bonus:</span> <span class="font-semibold text-slate-700">{{ $numAffiliate }}%</span></div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="text-xs space-y-0.5">
                                    <span class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded border border-blue-100 inline-block mb-1">{{ $pkg->duration_days }} Days</span>
                                    <div class="text-slate-500 text-[11px]">
                                        @if($numVolumePoint > 10)
                                            Volume Bonus: {{ number_format($numVolumePoint) }}
                                        @else
                                            Volume Point: {{ $numVolumePoint }}
                                        @endif
                                    </div>
                                    <div class="text-slate-400 text-[11px]">{{ $pkg->allowed_loan }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-slate-700">
                                {{ number_format($numTotalReturn) }} {{ $tokenSymbol }}
                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                <form action="{{ route('admin.fom-licence-miner.toggle', $pkg->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="cursor-pointer focus:outline-none">
                                        @if($pkg->is_active)
                                            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1 hover:bg-emerald-200 transition-all">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1 hover:bg-slate-300 transition-all">
                                                <i class="fas fa-times-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Edit Button --}}
                                    <a href="{{ route('admin.fom-licence-miner.edit', $pkg->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold p-2 rounded-lg shadow-sm transition-all text-xs flex items-center justify-center" title="Edit Package">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.fom-licence-miner.destroy', $pkg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FOM Licence Miner package?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-lg shadow-sm transition-all text-xs flex items-center justify-center" title="Delete Package">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-slate-400 text-sm">
                                <i class="fas fa-folder-open mb-2 text-slate-300 block text-2xl"></i>
                                No FOM Licence Miner packages found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#myTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection
