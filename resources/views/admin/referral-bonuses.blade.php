@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center gap-3">
        <div class="bg-blue-100 text-blue-600 p-2.5 rounded-lg text-lg">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Referral Bonuses — All Users</h1>
            <p class="text-xs text-slate-500 mt-0.5">Overview of every user's referral network, active partners, and accumulated commission balances.</p>
        </div>
    </div>

    {{-- Summary Cards (Grid Layout) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        
        {{-- Card 1: Total Commissions --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="bg-amber-100 text-amber-600 p-3.5 rounded-xl text-xl">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Total Commissions</span>
                <span class="block text-2xl font-extrabold text-slate-800 mt-1">${{ number_format($totalCommissionPaid, 2) }}</span>
            </div>
        </div>

        {{-- Card 2: Registered Users --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="bg-blue-100 text-blue-600 p-3.5 rounded-xl text-xl">
                <i class="fas fa-user-friends"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Active Promoters</span>
                <span class="block text-2xl font-extrabold text-slate-800 mt-1">{{ $users->count() }}</span>
                <span class="block text-[10px] text-slate-400 mt-0.5">With referrals or balances</span>
            </div>
        </div>

        {{-- Card 3: Users with Active Referrals --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="bg-emerald-100 text-emerald-600 p-3.5 rounded-xl text-xl">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Qualifying referrers</span>
                <span class="block text-2xl font-extrabold text-slate-800 mt-1">{{ $users->where('active_referrals', '>', 0)->count() }}</span>
                <span class="block text-[10px] text-slate-400 mt-0.5">Users with active UVP investments</span>
            </div>
        </div>

    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-4 py-3.5 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-list text-slate-400 text-sm"></i>
                <span class="font-bold text-slate-800 text-sm">Users &amp; Their Referral Bonuses</span>
            </div>
            <span class="bg-slate-200 text-slate-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Sorted by highest balance</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">#</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">User</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Status</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Total Referrals</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Active Referrals</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Commission Balance</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Joined Date</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider text-right">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $u)
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="px-4 py-3.5 text-sm text-slate-500">
                                <span class="font-bold">{{ $i + 1 }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-sm">
                                <span class="font-bold text-slate-800">{{ $u->name }}</span>
                                <span class="block text-xs text-slate-400 mt-0.5">{{ $u->email }}</span>
                                <span class="block text-[10px] text-slate-300">ID: {{ $u->id }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-sm">
                                @if($u->has_paid_package !== 'no')
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-0.5 rounded"><i class="fas fa-check-circle mr-1"></i>Active</span>
                                @else
                                    <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2 py-0.5 rounded"><i class="fas fa-user-circle mr-1"></i>No Package</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-sm text-slate-600 font-semibold">
                                {{ $u->total_referrals }}
                            </td>
                            <td class="px-4 py-3.5 text-sm">
                                <span class="font-bold text-emerald-600">{{ $u->active_referrals }}</span>
                                <span class="text-xs text-slate-400">/ {{ $u->total_referrals }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-sm font-bold">
                                @if($u->commission_balance > 0)
                                    <span class="text-amber-500 font-extrabold">${{ number_format($u->commission_balance, 2) }}</span>
                                @else
                                    <span class="text-slate-400">$0.00</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-sm text-slate-400">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3.5 text-sm text-right">
                                <a href="{{ route('admin.referral-bonus-detail', $u->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm shadow-blue-500/10 transition-all text-xs inline-flex items-center gap-1">
                                    <i class="fas fa-eye"></i> <span>View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-slate-400 text-sm">
                                <i class="fas fa-folder-open mb-1 text-slate-300 block text-lg"></i>
                                No users with referral activity yet.
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
