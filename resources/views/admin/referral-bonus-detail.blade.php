@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Back Button & Page Header --}}
    <div class="flex flex-col gap-4 mb-6">
        <div>
            <a href="{{ route('admin.referral-bonuses') }}" class="bg-white hover:bg-slate-100 text-slate-700 font-semibold py-2 px-4 border border-slate-200 rounded-lg shadow-sm transition-all inline-flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-left text-xs"></i> <span>Back to All Users</span>
            </a>
        </div>
        
        <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 shadow-sm flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 p-2.5 rounded-lg text-lg">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $user->name }} — Referral Bonus Detail</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }} &bull; Joined {{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Summary Cards (4 Column Grid Layout) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        {{-- Card 1: Commission Balance --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="bg-amber-100 text-amber-600 p-3.5 rounded-xl text-xl">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Commission Balance</span>
                <span class="block text-xl font-extrabold text-slate-800 mt-1">${{ number_format($commissionBalance, 2) }}</span>
            </div>
        </div>

        {{-- Card 2: Direct Referrals --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="bg-emerald-100 text-emerald-600 p-3.5 rounded-xl text-xl">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Direct Referrals</span>
                <span class="block text-xl font-extrabold text-slate-800 mt-1">{{ $directReferrals->count() }}</span>
            </div>
        </div>

        {{-- Card 3: Indirect Referrals --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="bg-blue-100 text-blue-600 p-3.5 rounded-xl text-xl">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Indirect Referrals</span>
                <span class="block text-xl font-extrabold text-slate-800 mt-1">{{ $indirectReferrals->count() }}</span>
            </div>
        </div>

        {{-- Card 4: Bonus Transactions --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="bg-slate-100 text-slate-600 p-3.5 rounded-xl text-xl">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Bonus Trans.</span>
                <span class="block text-xl font-extrabold text-slate-800 mt-1">{{ $transactions->count() }}</span>
            </div>
        </div>

    </div>

    {{-- Grid: Direct vs Indirect Referrals --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        {{-- Direct Referrals --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-4 py-3.5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-user-check text-emerald-500"></i> Direct Referrals (10% bonus)
                </span>
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-0.5 rounded-full">${{ number_format($directBonusTotal, 2) }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-xs font-semibold text-slate-500 px-4 py-2.5 uppercase tracking-wider">Name</th>
                            <th class="text-xs font-semibold text-slate-500 px-4 py-2.5 uppercase tracking-wider">Invested</th>
                            <th class="text-xs font-semibold text-slate-500 px-4 py-2.5 uppercase tracking-wider">Bonus</th>
                            <th class="text-xs font-semibold text-slate-500 px-4 py-2.5 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($directReferrals as $r)
                            <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                                <td class="px-4 py-3 text-sm">
                                    <span class="font-bold text-slate-800">{{ $r->name }}</span>
                                    <span class="block text-xs text-slate-400 mt-0.5">{{ $r->email }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-700">
                                    ${{ number_format($r->total_invested, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-emerald-600">
                                    ${{ number_format($r->bonus_earned, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($r->is_active)
                                        <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-0.5 rounded">Active</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 text-xs font-semibold px-2 py-0.5 rounded">No Invest</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-slate-400 text-sm">
                                    No direct referrals found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Indirect Referrals --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-4 py-3.5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fas fa-users text-blue-500"></i> Indirect Referrals (1% bonus)
                </span>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full">${{ number_format($indirectBonusTotal, 2) }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-xs font-semibold text-slate-500 px-4 py-2.5 uppercase tracking-wider">Name</th>
                            <th class="text-xs font-semibold text-slate-500 px-4 py-2.5 uppercase tracking-wider">Via</th>
                            <th class="text-xs font-semibold text-slate-500 px-4 py-2.5 uppercase tracking-wider">Invested</th>
                            <th class="text-xs font-semibold text-slate-500 px-4 py-2.5 uppercase tracking-wider">Bonus</th>
                            <th class="text-xs font-semibold text-slate-500 px-4 py-2.5 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indirectReferrals as $r)
                            <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                                <td class="px-4 py-3 text-sm">
                                    <span class="font-bold text-slate-800">{{ $r->name }}</span>
                                    <span class="block text-xs text-slate-400 mt-0.5">{{ $r->email }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="text-xs text-slate-500 bg-slate-100 px-2 py-0.5 rounded font-semibold">{{ $r->referred_through }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-700">
                                    ${{ number_format($r->total_invested, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-blue-600">
                                    ${{ number_format($r->bonus_earned, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($r->is_active)
                                        <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-0.5 rounded">Active</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 text-xs font-semibold px-2 py-0.5 rounded">No Invest</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-slate-400 text-sm">
                                    No indirect referrals found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Commission Transaction History --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center font-bold text-slate-800 text-sm">
            <span><i class="fas fa-history text-slate-500 mr-1"></i> Commission Transaction History</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Reference</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">From User</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Description</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Amount</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="px-4 py-3.5 text-sm">
                                <code class="text-xs text-slate-500 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded select-all" title="{{ $t->transaction_no }}">
                                    {{ Str::limit($t->transaction_no, 15) }}
                                </code>
                            </td>
                            <td class="px-4 py-3.5 text-sm font-semibold text-slate-800">
                                {{ $t->from_user }}
                            </td>
                            <td class="px-4 py-3.5 text-sm text-slate-500">
                                {{ $t->parsed_description }}
                            </td>
                            <td class="px-4 py-3.5 text-sm font-extrabold text-emerald-600">
                                +${{ number_format($t->parsed_amount, 2) }}
                            </td>
                            <td class="px-4 py-3.5 text-sm text-slate-400">
                                <i class="far fa-clock mr-1"></i> {{ $t->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-400 text-sm">
                                No commission transactions recorded.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
@endsection
