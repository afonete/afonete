@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="bg-amber-100 text-amber-600 p-3 rounded-lg text-xl">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Purchased Package Activation Codes &amp; Usage Report</h1>
                <p class="text-xs text-slate-500 mt-0.5">Track all generated FOM Licence Miner package codes, purchaser details, and activated users.</p>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.fom-licence-miner.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3 rounded-lg text-xs transition-all">
                <i class="fas fa-box"></i> Manage Plans
            </a>
            <a href="{{ route('admin.fom-licence-miner.codes') }}" class="bg-amber-500 text-white font-bold py-2 px-3 rounded-lg text-xs shadow-sm transition-all">
                <i class="fas fa-ticket-alt"></i> Code Usage
            </a>
            <a href="{{ route('admin.fom-licence-miner.escrow-audit') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3 rounded-lg text-xs transition-all">
                <i class="fas fa-shield-alt"></i> Escrow &amp; Staking Audit
            </a>
        </div>
    </div>

    {{-- Code Usage Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-3">
            <span class="font-bold text-slate-800 text-sm">Package Activation Codes Audit Trail</span>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input type="text" id="searchInput"
                       class="bg-white border border-slate-300 text-slate-800 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-9 p-2.5 transition-all" 
                       placeholder="Search codes, users, emails, packages...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="myTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12 text-center">#</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Activation Code</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Package &amp; Value</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Purchased By (Buyer)</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Redeemed By (Activated User)</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codes as $code)
                        @php
                            $isUsed = in_array(strtolower(trim((string)$code->stutus)), ['used', 'activated', '1', 'true']);
                            $numReturn = \App\Models\FomLicenceMiner::cleanNum($code->token);
                            $numPrice  = \App\Models\FomLicenceMiner::cleanNum($code->price);
                            $buyer     = $code->purchaser ?? $code->myOwner ?? $code->user ?? null;
                            $userRedeemer = $code->redeemer ?? null;
                        @endphp
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="p-4 text-sm font-semibold text-slate-500 text-center">
                                {{ ($codes->currentPage() - 1) * $codes->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-5 py-4">
                                <code class="font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-1 rounded text-sm font-mono block w-max">{{ $code->code }}</code>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="font-bold text-slate-800 block">{{ strtoupper($code->package) }}</span>
                                <span class="text-xs text-emerald-600 font-semibold block">${{ number_format($numPrice, 2) }}</span>
                                @if($numReturn > 0)
                                    <span class="text-[11px] text-indigo-600 block mt-0.5">Return: {{ number_format($numReturn) }} {{ $tokenSymbol }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm">
                                @if($buyer)
                                    <span class="font-bold text-slate-800 block">{{ $buyer->name ?: $buyer->user }}</span>
                                    <span class="text-xs text-slate-500 block">@ {{ $buyer->user }}</span>
                                    <span class="text-[11px] text-slate-400 block">{{ $buyer->email }}</span>
                                @else
                                    <span class="text-xs text-slate-500 font-mono">{{ $code->email ?: 'Direct Purchase' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm">
                                @if($isUsed)
                                    @if($userRedeemer)
                                        <span class="font-bold text-emerald-800 block">{{ $userRedeemer->name ?: $userRedeemer->user }}</span>
                                        <span class="text-xs text-slate-500 block">@ {{ $userRedeemer->user }}</span>
                                        <span class="text-[11px] text-slate-400 block">{{ $userRedeemer->email }}</span>
                                    @else
                                        <span class="font-semibold text-emerald-700 text-xs block">{{ $code->email }}</span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 italic">Unused - Available to Share</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                @if($isUsed)
                                    <span class="bg-slate-200 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1">
                                        <i class="fas fa-check-circle text-emerald-600"></i> Activated
                                    </span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1">
                                        <i class="fas fa-ticket-alt text-emerald-600"></i> Ready / Unused
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-right">
                                <span class="text-xs text-slate-600 block">{{ $code->created_at ? $code->created_at->format('Y-m-d H:i') : 'N/A' }}</span>
                                @if($isUsed && $code->updated_at)
                                    <span class="text-[10px] text-emerald-600 block">Activated: {{ $code->updated_at->format('Y-m-d') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400 text-sm">
                                <i class="fas fa-folder-open mb-2 text-slate-300 block text-2xl"></i>
                                No activation codes generated yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($codes->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-center">
                {{ $codes->links() }}
            </div>
        @endif
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
