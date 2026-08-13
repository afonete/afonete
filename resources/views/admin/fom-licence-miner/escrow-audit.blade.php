@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-100 text-indigo-600 p-3 rounded-lg text-xl">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Escrow Wallets &amp; Staking Audit Report</h1>
                <p class="text-xs text-slate-500 mt-0.5">Audit user Escrow Wallet balances (`ESCROW_TOKEN`), 12-month package release installments, and 1–5 Year Staking Vaults.</p>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.fom-licence-miner.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3 rounded-lg text-xs transition-all">
                <i class="fas fa-box"></i> Manage Plans
            </a>
            <a href="{{ route('admin.fom-licence-miner.codes') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3 rounded-lg text-xs transition-all">
                <i class="fas fa-ticket-alt"></i> Code Usage
            </a>
            <a href="{{ route('admin.fom-licence-miner.escrow-audit') }}" class="bg-indigo-600 text-white font-bold py-2 px-3 rounded-lg text-xs shadow-sm transition-all">
                <i class="fas fa-shield-alt"></i> Escrow &amp; Staking Audit
            </a>
        </div>
    </div>

    {{-- Stat Overview Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm border-l-4 border-l-amber-500">
            <span class="text-xs font-bold uppercase text-slate-400 block">Total Escrow Tokens in System</span>
            <h3 class="text-2xl font-extrabold text-slate-800 mt-1 mb-0">{{ number_format($totalEscrowInSystem) }}</h3>
            <span class="text-xs text-slate-500 font-semibold">{{ $tokenSymbol }} (ESCROW_TOKEN)</span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm border-l-4 border-l-indigo-500">
            <span class="text-xs font-bold uppercase text-slate-400 block">12-Month Release Installments Logged</span>
            <h3 class="text-2xl font-extrabold text-indigo-600 mt-1 mb-0">{{ number_format($installments->total()) }}</h3>
            <span class="text-xs text-slate-500 font-semibold">Scheduled Monthly Releases</span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm border-l-4 border-l-emerald-500">
            <span class="text-xs font-bold uppercase text-slate-400 block">1–5 Year Escrow Stakings Created</span>
            <h3 class="text-2xl font-extrabold text-emerald-600 mt-1 mb-0">{{ number_format($stakings->total()) }}</h3>
            <span class="text-xs text-slate-500 font-semibold">Active Compounding Vaults</span>
        </div>
    </div>

    {{-- Table 1: 12-Month Installments Releases Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fas fa-calendar-alt text-indigo-600"></i> User 12-Month Package Release Schedules
            </span>
            <span class="text-xs bg-indigo-50 text-indigo-700 font-bold px-2.5 py-1 rounded border border-indigo-100">
                Page {{ $installments->currentPage() }} of {{ $installments->lastPage() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12 text-center">#</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">User Account</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Package &amp; Installment</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Monthly Release Amount</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Scheduled Release Date</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($installments as $inst)
                        @php $isDone = ($inst->status === 'completed'); @endphp
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="p-4 text-sm font-semibold text-slate-500 text-center">
                                {{ ($installments->currentPage() - 1) * $installments->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-5 py-4 text-sm">
                                @if($inst->user)
                                    <span class="font-bold text-slate-800 block">{{ $inst->user->name ?: $inst->user->user }}</span>
                                    <span class="text-xs text-slate-500 block">@ {{ $inst->user->user }}</span>
                                    <span class="text-[11px] text-slate-400 block">{{ $inst->user->email }}</span>
                                @else
                                    <span class="text-xs text-slate-400">User ID: {{ $inst->user_id }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="font-bold text-amber-600 block">{{ strtoupper($inst->package_name) }}</span>
                                <span class="text-xs text-slate-500 block">Installment #{{ $inst->installment_number }} of 12</span>
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-indigo-600">
                                {{ number_format((float)$inst->amount) }} {{ $tokenSymbol }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-700">
                                {{ \Carbon\Carbon::parse($inst->release_date)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                @if($isDone)
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full">
                                        <i class="fas fa-check-circle me-1"></i> Released to Available
                                    </span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
                                        <i class="fas fa-lock me-1"></i> Pending in Escrow
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400 text-sm">
                                No package monthly installment schedules logged yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($installments->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-center">
                {{ $installments->links() }}
            </div>
        @endif
    </div>

    {{-- Table 2: 1–5 Year Escrow Stakings Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fas fa-award text-emerald-600"></i> User 1–5 Year Escrow Staking Vaults
            </span>
            <span class="text-xs bg-emerald-50 text-emerald-700 font-bold px-2.5 py-1 rounded border border-emerald-100">
                Page {{ $stakings->currentPage() }} of {{ $stakings->lastPage() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12 text-center">#</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">User Account</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Principal Staked</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Yield &amp; Profit</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Escrowed Amount</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Lock Duration &amp; Maturity</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stakings as $stk)
                        @php $isDone = ($stk->status === 'completed'); @endphp
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="p-4 text-sm font-semibold text-slate-500 text-center">
                                {{ ($stakings->currentPage() - 1) * $stakings->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-5 py-4 text-sm">
                                @if($stk->user)
                                    <span class="font-bold text-slate-800 block">{{ $stk->user->name ?: $stk->user->user }}</span>
                                    <span class="text-xs text-slate-500 block">@ {{ $stk->user->user }}</span>
                                    <span class="text-[11px] text-slate-400 block">{{ $stk->user->email }}</span>
                                @else
                                    <span class="text-xs text-slate-400">User ID: {{ $stk->user_id }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-slate-800">
                                {{ number_format((float)$stk->principal_amount) }} {{ $tokenSymbol }}
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="font-bold text-emerald-600 block">+{{ (float)$stk->yield_percent }}% Profit</span>
                                <span class="text-xs text-slate-500 block">+{{ number_format((float)$stk->profit_amount) }} {{ $tokenSymbol }}</span>
                            </td>
                            <td class="px-4 py-4 text-sm font-extrabold text-amber-600">
                                {{ number_format((float)$stk->total_staked) }} {{ $tokenSymbol }}
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded border border-blue-100 inline-block mb-1">{{ $stk->lock_years }} Year(s)</span>
                                <span class="text-xs text-slate-500 block">Maturity: {{ \Carbon\Carbon::parse($stk->release_date)->format('Y-m-d') }}</span>
                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                @if($isDone)
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full">
                                        <i class="fas fa-check-circle me-1"></i> Released to Available
                                    </span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
                                        <i class="fas fa-lock me-1"></i> Active in Escrow
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-slate-400 text-sm">
                                No 1–5 Year Escrow Stakings created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stakings->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-center">
                {{ $stakings->links() }}
            </div>
        @endif
    </div>

</div>

<script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
@endsection
