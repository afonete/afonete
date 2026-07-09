@extends('admin.sidebar')

@section('contents')
<div class="p-4 sm:p-6 bg-slate-50 min-h-screen">
    {{-- Breadcrumb --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Withdrawal Requests</h1>
            <nav class="text-sm text-slate-500 mt-1">
                <ol class="inline-flex items-center space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700"><i class="fa fa-home mr-1"></i>Dashboard</a></li>
                    <li><span class="text-slate-400">/</span></li>
                    <li class="text-slate-700 font-medium">Finance / Withdrawals</li>
                </ol>
            </nav>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span> LIVE QUEUE
            </span>
            @if(!empty($methodFilter))
                <span class="px-2.5 py-1 rounded-full bg-slate-800 text-white font-semibold uppercase text-[10px]">{{ $methodFilter }}</span>
            @endif
        </div>
    </div>

    @if(session('message'))
        <div class="mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
            <i class="fa fa-check-circle mr-2"></i>{{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <i class="fa fa-exclamation-triangle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    @php
        $pendingTotal   = $pending->sum('amount');
        $processingTotal = $processing->sum('amount') ?? 0;
        $completedPageTotal = $completed->getCollection()->sum('amount');
    @endphp

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm px-4 py-4 border-l-[5px] border-amber-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold">Pending</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $pending->count() }}</p>
                    <p class="text-xs text-amber-600 font-mono mt-1">${{ number_format($pendingTotal,2) }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-amber-50 flex items-center justify-center">
                    <i class="fa fa-clock text-amber-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm px-4 py-4 border-l-[5px] border-sky-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold">Processing</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $processing->count() }}</p>
                    <p class="text-xs text-slate-500 mt-1">${{ number_format($processingTotal,2) }} queued</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-sky-50 flex items-center justify-center">
                    <i class="fa fa-sync-alt text-sky-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm px-4 py-4 border-l-[5px] border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold">Completed (total)</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $completed->total() }}</p>
                    <p class="text-xs text-slate-500 mt-1">page {{ $completed->currentPage() }} / {{ $completed->lastPage() }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center">
                    <i class="fa fa-check-circle text-emerald-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm px-4 py-4 border-l-[5px] border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold">Completed Volume</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1 font-mono">${{ number_format($completedPageTotal,2) }}</p>
                    <p class="text-xs text-slate-500 mt-1">this page</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-indigo-50 flex items-center justify-center">
                    <i class="fa fa-wallet text-indigo-500"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Method filter pills --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 mb-5 flex flex-wrap items-center gap-2">
        <span class="text-xs font-semibold text-slate-500 mr-1">Filter method:</span>
        <a href="{{ route('admin.withdrawal') }}"
           class="px-3 py-1.5 text-xs font-semibold rounded-full {{ empty($methodFilter) ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            All
        </a>
        <a href="{{ route('admin.withdrawal', ['method' => 'crypto']) }}"
           class="px-3 py-1.5 text-xs font-semibold rounded-full inline-flex items-center gap-1.5 {{ ($methodFilter ?? '')==='crypto' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
            <i class="fab fa-bitcoin"></i> Crypto
        </a>
        <a href="{{ route('admin.withdrawal', ['method' => 'advcash']) }}"
           class="px-3 py-1.5 text-xs font-semibold rounded-full inline-flex items-center gap-1.5 {{ ($methodFilter ?? '')==='advcash' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
            <i class="fas fa-money-bill-wave"></i> Advcash
        </a>
        <a href="{{ route('admin.withdrawal', ['method' => 'perfect_money']) }}"
           class="px-3 py-1.5 text-xs font-semibold rounded-full inline-flex items-center gap-1.5 {{ ($methodFilter ?? '')==='perfect_money' ? 'bg-sky-600 text-white' : 'bg-sky-50 text-sky-700 hover:bg-sky-100' }}">
            <i class="fas fa-coins"></i> Perfect Money
        </a>
        @if(!empty($methodFilter))
            <a href="{{ route('admin.withdrawal') }}" class="text-xs text-slate-500 hover:text-slate-700 ml-2">Clear ×</a>
        @endif
        <div class="ml-auto flex items-center gap-2">
            <input type="text" id="withdrawalSearch" placeholder="Search user, wallet, ref…"
                   class="text-xs border border-slate-300 rounded-lg px-3 py-1.5 w-56 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    {{-- PENDING --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-4 py-3 border-b border-slate-200 bg-amber-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-clock text-amber-600"></i>
                <h2 class="font-semibold text-slate-800">Pending – requires approval</h2>
                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-200 text-amber-900 font-bold">{{ $pending->count() }}</span>
            </div>
            <span class="text-[11px] text-amber-700">Approve or reject manually</span>
        </div>

        @if($pending->isEmpty())
            <div class="p-8 text-center text-slate-400">
                <i class="fa fa-inbox text-2xl mb-2"></i>
                <p class="text-sm">No pending withdrawals{{ $methodFilter ? ' for '.$methodFilter : '' }}.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="pendingTable" style="min-width: 960px;">
                <thead class="text-[11px] text-slate-200 uppercase tracking-wider bg-slate-800">
                    <tr>
                        <th class="px-4 py-3 text-left">User / Ref</th>
                        <th class="px-4 py-3 text-left">Payout</th>
                        <th class="px-4 py-3 text-left">Destination</th>
                        <th class="px-4 py-3 text-left">Requested</th>
                        <th class="px-4 py-3 text-right w-[360px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pending as $w)
                    @php
                        $methodColor = match($w->method) {
                            'crypto' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
                            'advcash' => 'bg-amber-50 text-amber-700 ring-amber-200',
                            'perfect_money' => 'bg-sky-50 text-sky-700 ring-sky-200',
                            default => 'bg-slate-50 text-slate-700 ring-slate-200',
                        };
                        $initials = collect(explode(' ', $w->user->name ?? 'U'))->map(fn($p)=>mb_substr($p,0,1))->take(2)->join('');
                    @endphp
                    <tr class="hover:bg-slate-50 withdrawal-row" data-search="{{ strtolower(($w->user->name ?? '').' '.($w->user->email ?? '').' '.$w->wallet_address.' '.$w->transaction_no) }}">
                        {{-- User / Ref --}}
                        <td class="px-4 py-3 align-top">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center text-[11px] font-bold flex-shrink-0">
                                    {{ strtoupper($initials) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-800 truncate">{{ $w->user->name ?? '—' }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ $w->user->email ?? '' }}</div>
                                    <div class="font-mono text-[10px] text-slate-400 mt-1 truncate max-w-[200px]" title="{{ $w->transaction_no }}">
                                        {{ $w->transaction_no ?: '—' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        {{-- Payout --}}
                        <td class="px-4 py-3 align-top">
                            <div class="font-mono font-bold text-rose-600 text-[15px]">${{ number_format($w->amount,2) }}</div>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold ring-1 ring-inset {{ $methodColor }}">
                                    {{ method_exists($w, 'methodLabel') ? $w->methodLabel() : ucfirst($w->method) }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-500 mt-1">{{ strtoupper($w->currency) }}{{ $w->network ? ' · '.$w->network : '' }}</div>
                        </td>
                        {{-- Destination --}}
                        <td class="px-4 py-3 align-top max-w-[260px]">
                            <div class="font-mono text-[11px] text-slate-700 break-all">
                                {{ $w->wallet_address }}
                            </div>
                        </td>
                        {{-- Requested --}}
                        <td class="px-4 py-3 align-top text-[12px] text-slate-600 whitespace-nowrap">
                            {{ $w->created_at->format('M d, H:i') }}<br>
                            <span class="text-[11px] text-slate-400">{{ $w->created_at->diffForHumans() }}</span>
                        </td>
                        {{-- Actions --}}
                        <td class="px-4 py-3 align-top text-right">
                            <form method="POST" action="{{ route('admin.withdrawal.approve') }}" class="inline-block text-left">
                                @csrf
                                <input type="hidden" name="withdrawal_id" value="{{ $w->id }}">
                                <div class="flex flex-col gap-1.5 w-[340px] ml-auto">
                                    <div class="grid grid-cols-2 gap-1.5">
                                        <input type="text" name="txn_hash" placeholder="on-chain hash (optional)"
                                               class="col-span-2 px-2.5 py-1.5 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 font-mono"
                                               >
                                        <input type="text" name="admin_note" placeholder="admin note (optional)"
                                               class="col-span-2 px-2.5 py-1.5 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                                    </div>
                                    <div class="flex justify-end gap-1.5">
                                        <button type="button"
                                                onclick="openReject({{ $w->id }}, '{{ addslashes($w->user->name ?? 'User') }}', {{ $w->amount }})"
                                                class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-rose-300 text-rose-700 hover:bg-rose-50">
                                            <i class="fa fa-times mr-1"></i> Reject
                                        </button>
                                        <button type="submit"
                                                onclick="return confirm('Approve withdrawal of ${{ number_format($w->amount,2) }} for {{ addslashes($w->user->name ?? '') }}?')"
                                                class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                                            <i class="fa fa-check mr-1"></i> Approve
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- PROCESSING --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-4 py-3 border-b border-slate-200 bg-sky-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-sync-alt text-sky-600"></i>
                <h2 class="font-semibold text-slate-800">Processing / Queued</h2>
                <span class="text-xs px-2 py-0.5 rounded-full bg-sky-200 text-sky-900 font-bold">{{ $processing->count() }}</span>
            </div>
            <span class="text-[11px] text-sky-700">Automatic blockchain payout in progress</span>
        </div>

        @if($processing->isEmpty())
            <div class="p-6 text-center text-slate-400 text-sm">No processing withdrawals.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm" style="min-width: 900px;">
                <thead class="text-[11px] text-slate-200 uppercase tracking-wider bg-slate-800">
                    <tr>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left">Payout</th>
                        <th class="px-4 py-3 text-left">Destination</th>
                        <th class="px-4 py-3 text-left">Attempts / Risk</th>
                        <th class="px-4 py-3 text-left">Last attempt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($processing as $w)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-800">{{ $w->user->name ?? '—' }}</div>
                            <div class="text-[11px] text-slate-500">{{ $w->user->email ?? '' }}</div>
                            <div class="font-mono text-[10px] text-slate-400">{{ $w->transaction_no }}</div>
                        </td>
                        <td class="px-4 py-3 font-mono font-semibold text-slate-800">${{ number_format($w->amount,2) }}</td>
                        <td class="px-4 py-3 max-w-[280px]">
                            <div class="text-[11px] text-slate-600">{{ strtoupper($w->currency) }} {{ $w->network }}</div>
                            <div class="font-mono text-[11px] text-slate-700 break-all">{{ $w->wallet_address }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm font-semibold text-slate-800">{{ $w->attempts ?? 0 }}</span>
                            <span class="text-[11px] text-slate-500"> attempts</span><br>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ ($w->risk_score ?? 0) >= 50 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600' }}">
                                risk {{ $w->risk_score ?? 0 }}
                            </span>
                            @if(!empty($w->risk_flags))
                                <div class="text-[10px] text-slate-400 mt-1">{{ is_array($w->risk_flags) ? implode(', ', $w->risk_flags) : $w->risk_flags }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-[12px] text-slate-600">
                            {{ $w->last_attempt_at ? \Carbon\Carbon::parse($w->last_attempt_at)->format('M d H:i') : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- COMPLETED --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-emerald-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-600"></i>
                <h2 class="font-semibold text-slate-800">Completed</h2>
            </div>
            <span class="text-[11px] text-emerald-700">paginated – 20 per page</span>
        </div>

        @if($completed->isEmpty())
            <div class="p-6 text-center text-slate-400 text-sm">No completed withdrawals yet.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm" style="min-width: 920px;">
                <thead class="text-[11px] text-slate-200 uppercase tracking-wider bg-slate-800">
                    <tr>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left">Payout</th>
                        <th class="px-4 py-3 text-left">Destination</th>
                        <th class="px-4 py-3 text-left">Type / Note</th>
                        <th class="px-4 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($completed as $w)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-800 text-[13px]">{{ $w->user->name ?? '—' }}</div>
                            <div class="text-[11px] text-slate-500">{{ $w->user->email ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-mono font-semibold text-slate-800">${{ number_format($w->amount,2) }}</div>
                            <div class="text-[11px] text-slate-500">{{ method_exists($w,'methodLabel') ? $w->methodLabel() : $w->method }} · {{ $w->currency }}{{ $w->network ? ' · '.$w->network : '' }}</div>
                        </td>
                        <td class="px-4 py-3 max-w-[240px]">
                            <div class="font-mono text-[11px] text-slate-600 break-all">{{ \Illuminate\Support\Str::limit($w->wallet_address, 42) }}</div>
                            <div class="font-mono text-[10px] text-slate-400 mt-0.5">{{ $w->transaction_no }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">{{ method_exists($w,'typeLabel') ? $w->typeLabel() : ($w->type ?? '—') }}</span>
                            @if($w->admin_note)
                                <div class="text-[11px] text-slate-500 mt-1 max-w-[220px] truncate" title="{{ $w->admin_note }}">{{ $w->admin_note }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-[12px] text-slate-600 whitespace-nowrap">
                            {{ $w->created_at->format('M d, Y') }}<br>
                            <span class="text-[11px] text-slate-400">{{ $w->created_at->format('H:i') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs text-slate-600">
            <div>
                Showing <b class="text-slate-800">{{ $completed->firstItem() }}</b>–<b class="text-slate-800">{{ $completed->lastItem() }}</b>
                of <b class="text-slate-800">{{ $completed->total() }}</b>
            </div>
            <div>{{ $completed->links() }}</div>
        </div>
        @endif
    </div>

    <p class="text-[11px] text-slate-400 mt-3 text-center">Withdrawal inbox – Tailwind rebuild • 5 compact columns • fit-to-screen • no Bootstrap</p>
</div>

{{-- Reject Modal – Tailwind --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeReject()"></div>
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="px-5 py-4 bg-rose-600 text-white flex items-center justify-between">
                <h3 class="font-semibold"><i class="fa fa-times mr-2"></i>Reject Withdrawal</h3>
                <button onclick="closeReject()" class="text-rose-100 hover:text-white">✕</button>
            </div>
            <form method="POST" action="{{ route('admin.withdrawal.reject') }}">
                @csrf
                <div class="p-5 space-y-3">
                    <input type="hidden" name="withdrawal_id" id="rejectWithdrawalId">
                    <p id="rejectSummary" class="text-sm text-slate-600"></p>
                    <p class="text-xs text-sky-700 bg-sky-50 border border-sky-200 rounded-lg px-3 py-2">
                        <i class="fa fa-info-circle mr-1"></i> The amount will be <b>refunded</strong> to the user's CASHOUT balance.
                    </p>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Reason / Note (shown to user)</label>
                        <textarea name="admin_note" rows="3" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500" placeholder="e.g. Invalid wallet address, please resubmit..."></textarea>
                    </div>
                </div>
                <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex justify-end gap-2">
                    <button type="button" onclick="closeReject()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-rose-600 rounded-lg hover:bg-rose-700">
                        <i class="fa fa-times mr-1"></i> Confirm Reject & Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReject(id, name, amount){
    document.getElementById('rejectWithdrawalId').value = id;
    document.getElementById('rejectSummary').textContent = 'Rejecting $' + parseFloat(amount).toFixed(2) + ' withdrawal for ' + name + '.';
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeReject(){
    document.getElementById('rejectModal').classList.add('hidden');
}
document.addEventListener('DOMContentLoaded', function(){
    const s = document.getElementById('withdrawalSearch');
    if(s){
        s.addEventListener('input', function(){
            const q = this.value.toLowerCase();
            document.querySelectorAll('.withdrawal-row').forEach(row=>{
                row.style.display = (row.dataset.search||'').includes(q) ? '' : 'none';
            });
        });
    }
    document.addEventListener('keydown', e=>{ if(e.key==='Escape') closeReject(); });
});
</script>
@endsection
