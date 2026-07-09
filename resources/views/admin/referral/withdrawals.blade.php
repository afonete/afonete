@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    {{-- Header & Back Navigation --}}
    <div class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <a href="{{ route('admin.referral.bonuses') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm transition dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:text-white">
            <i class="fas fa-arrow-left text-xs"></i> Back to Referral Bonuses
        </a>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200">
            <i class="fas fa-calendar-check mr-1.5"></i> Weekly Settlement Cycle: Monday
        </span>
    </div>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
            <i class="fas fa-check-circle text-lg mr-3 text-green-600"></i>
            <span class="font-medium flex-1">{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-500 hover:bg-green-100 p-1 rounded-lg" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <i class="fas fa-exclamation-circle text-lg mr-3 text-red-600"></i>
            <span class="font-medium flex-1">{{ session('error') }}</span>
            <button type="button" class="ml-auto text-red-500 hover:bg-red-100 p-1 rounded-lg" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm dark:from-gray-800 dark:to-gray-800 dark:border-gray-700">
        <h1 class="text-2xl sm:text-3xl uppercase text-slate-800 font-extrabold tracking-tight flex items-center dark:text-white">
            <span class="p-2.5 bg-amber-500 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center"><i class="fas fa-money-bill-wave text-xl"></i></span>
            Weekly Referral Withdrawals Queue
        </h1>
        <p class="text-slate-600 text-sm mt-2 font-medium max-w-2xl dark:text-gray-300">
            Audit and authorize queued commission payout requests scheduled for weekly Monday settlement across TRC-20 and fiat gateways.
        </p>
    </header>

    {{-- 3 Summary Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border-l-[5px] border-amber-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Pending Queue</span>
                <div class="text-2xl font-extrabold text-amber-500 font-mono">{{ $pending->count() }} <span class="text-xs font-normal text-gray-400">requests</span></div>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl dark:bg-amber-900/30 dark:text-amber-400"><i class="fas fa-clock text-xl"></i></div>
        </div>

        <div class="bg-white border-l-[5px] border-emerald-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Approved &amp; Paid</span>
                <div class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono">{{ $approved->count() }} <span class="text-xs font-normal text-gray-400">history</span></div>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl dark:bg-emerald-900/30 dark:text-emerald-400"><i class="fas fa-check-double text-xl"></i></div>
        </div>

        <div class="bg-white border-l-[5px] border-rose-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Rejected Payouts</span>
                <div class="text-2xl font-extrabold text-rose-600 dark:text-rose-400 font-mono">{{ $rejected->count() }} <span class="text-xs font-normal text-gray-400">history</span></div>
            </div>
            <div class="p-3 bg-rose-50 text-rose-600 rounded-xl dark:bg-rose-900/30 dark:text-rose-400"><i class="fas fa-times-circle text-xl"></i></div>
        </div>
    </div>

    {{-- SECTION 1: PENDING WITHDRAWALS TABLE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-10 dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-amber-500 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-hourglass-half"></i> Pending Commission Withdrawals</span>
            <span class="text-xs bg-amber-600 text-white px-3 py-1 rounded-full font-semibold">Action Required: {{ $pending->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">User / Account</th>
                        <th class="py-4 px-6">Week Start</th>
                        <th class="py-4 px-6 text-right">Amount ($)</th>
                        <th class="py-4 px-6">Transaction Ref</th>
                        <th class="py-4 px-6">Submitted Date</th>
                        <th class="py-4 px-6 text-center min-w-[320px]">Settlement Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($pending as $w)
                        <tr class="bg-white hover:bg-amber-50/30 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                <div>{{ $w->user->name ?? 'Unknown User' }}</div>
                                <div class="text-xs font-normal text-gray-500 font-mono">{{ $w->user->email ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6 font-mono text-xs">{{ $w->week_start ? $w->week_start->format('d M Y') : '—' }}</td>
                            <td class="py-4 px-6 text-right font-mono font-extrabold text-emerald-600 dark:text-emerald-400 text-base">${{ number_format($w->amount, 2) }}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $w->transaction_no ?? '—' }}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-500">{{ $w->created_at ? $w->created_at->format('d M Y, H:i') : '—' }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2 flex-wrap">
                                    <form method="POST" action="{{ route('admin.referral.withdrawals.approve', $w->id) }}" class="flex items-center gap-1.5 flex-1">
                                        @csrf
                                        <input type="text" name="admin_notes" class="bg-gray-50 border border-gray-300 text-gray-800 text-xs rounded-lg px-2.5 py-1.5 w-32 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Notes (optional)">
                                        <button type="submit" name="mark_paid" value="1" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs shadow-sm transition inline-flex items-center gap-1" title="Approve & Mark Paid"><i class="fas fa-check"></i> Pay</button>
                                        <button type="submit" name="mark_paid" value="0" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs shadow-sm transition inline-flex items-center gap-1" title="Approve Only"><i class="fas fa-thumbs-up"></i> Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.referral.withdrawals.reject', $w->id) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs shadow-sm transition inline-flex items-center gap-1" onclick="return confirm('Reject this withdrawal request?')"><i class="fas fa-times"></i> Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-gray-400">No weekly Monday withdrawal requests currently pending.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 2: APPROVED / PAID HISTORY TABLE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-10 dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-slate-800 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-check-circle text-emerald-400"></i> Approved &amp; Paid History</span>
            <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full font-semibold">Showing Top {{ $approved->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">User / Account</th>
                        <th class="py-4 px-6">Week Start</th>
                        <th class="py-4 px-6 text-right">Amount ($)</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6">Processed Date</th>
                        <th class="py-4 px-6">Admin Audit Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($approved as $w)
                        <tr class="bg-white hover:bg-blue-50/40 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                <div>{{ $w->user->name ?? 'Unknown User' }}</div>
                                <div class="text-xs font-normal text-gray-500 font-mono">{{ $w->user->email ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6 font-mono text-xs">{{ $w->week_start ? $w->week_start->format('d M Y') : '—' }}</td>
                            <td class="py-4 px-6 text-right font-mono font-extrabold text-blue-600 dark:text-blue-400 text-base">${{ number_format($w->amount, 2) }}</td>
                            <td class="py-4 px-6 text-center">{!! $w->statusBadge() !!}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-500">{{ optional($w->processed_at)->format('d M Y, H:i') ?? '—' }}</td>
                            <td class="py-4 px-6 text-xs text-gray-500 italic">{{ $w->admin_notes ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-gray-400">No approved or paid history recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 3: REJECTED HISTORY TABLE --}}
    @if($rejected->isNotEmpty())
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-rose-800 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-times-circle text-rose-300"></i> Rejected Withdrawals History</span>
            <span class="text-xs bg-rose-900 text-rose-200 px-3 py-1 rounded-full font-semibold">Showing Top {{ $rejected->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">User / Account</th>
                        <th class="py-4 px-6">Week Start</th>
                        <th class="py-4 px-6 text-right">Amount ($)</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6">Processed Date</th>
                        <th class="py-4 px-6">Rejection Reason / Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($rejected as $w)
                        <tr class="bg-white hover:bg-rose-50/30 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                <div>{{ $w->user->name ?? 'Unknown User' }}</div>
                                <div class="text-xs font-normal text-gray-500 font-mono">{{ $w->user->email ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6 font-mono text-xs">{{ $w->week_start ? $w->week_start->format('d M Y') : '—' }}</td>
                            <td class="py-4 px-6 text-right font-mono font-bold text-rose-600 dark:text-rose-400 text-base">${{ number_format($w->amount, 2) }}</td>
                            <td class="py-4 px-6 text-center">{!! $w->statusBadge() !!}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-500">{{ optional($w->processed_at)->format('d M Y, H:i') ?? '—' }}</td>
                            <td class="py-4 px-6 text-xs text-rose-600 dark:text-rose-400 font-medium">{{ $w->admin_notes ?? 'Rejected by admin' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
