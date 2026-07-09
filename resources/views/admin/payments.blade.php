@extends('admin.sidebar')

@section('contents')
<div class="p-4 sm:p-6 bg-slate-50 min-h-screen">
    {{-- Breadcrumb --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pending Deposits</h1>
            <nav class="text-sm text-slate-500 mt-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">
                            <i class="fa fa-home mr-1"></i> Dashboard
                        </a>
                    </li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-slate-700 font-medium">Payments / Deposited</li>
                </ol>
            </nav>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500">Auto-refresh</span>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                LIVE
            </span>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            <i class="fa fa-check-circle mr-2"></i>{{ session('status') }}
        </div>
    @endif
    @if(session('message'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            <i class="fa fa-check-circle mr-2"></i>{{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
            <i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    @php
        $collection = $deposits->getCollection();
        $pendingCount   = $collection->where('status','pending')->count();
        $reviewCount    = $collection->where('status','under-review')->count();
        $approvedCount  = $collection->where('status','approved')->count();
        $pageTotal      = $collection->sum('amount_deposited');
    @endphp

    {{-- Stat Cards – thick left border, matches admin-dashboard.blade.php --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm px-4 py-4 border-l-[5px] border-yellow-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold">Total Pending</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $deposits->total() }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $pendingCount }} on this page</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-yellow-50 flex items-center justify-center">
                    <i class="fa fa-hourglass-half text-yellow-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm px-4 py-4 border-l-[5px] border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold">Under Review</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $reviewCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Awaiting decision</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center">
                    <i class="fa fa-search text-blue-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm px-4 py-4 border-l-[5px] border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold">Approved (page)</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $approvedCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Ready to credit</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center">
                    <i class="fa fa-check-circle text-emerald-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm px-4 py-4 border-l-[5px] border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-slate-500 font-semibold">Page Volume</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1 font-mono">${{ number_format($pageTotal,2) }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $deposits->count() }} / 10 records</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-indigo-50 flex items-center justify-center">
                    <i class="fa fa-wallet text-indigo-500"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card – fits screen, no horizontal scroll on ≥1024px --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        {{-- Toolbar --}}
        <div class="px-4 py-3 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 bg-slate-50/70">
            <div class="flex items-center gap-2 text-sm text-slate-600">
                <i class="fa fa-filter text-slate-400"></i>
                <span class="font-medium">Deposits inbox</span>
                <span class="text-slate-400">•</span>
                <span class="text-xs">Showing {{ $deposits->firstItem() ?? 0 }}–{{ $deposits->lastItem() ?? 0 }} of {{ $deposits->total() }}</span>
            </div>
            <div class="flex items-center gap-2 w-full lg:w-auto">
                <div class="relative flex-1 lg:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                    </div>
                    <input type="text" id="searchInput"
                        class="w-full pl-9 pr-3 py-2 text-sm border border-slate-300 rounded-lg bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search name, TX, method…">
                </div>
                <button data-filter="all" class="filter-btn px-3 py-2 text-xs font-semibold rounded-lg bg-slate-800 text-white">All</button>
                <button data-filter="pending" class="filter-btn px-3 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50">Pending</button>
                <button data-filter="under-review" class="filter-btn px-3 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50">Review</button>
            </div>
        </div>

        {{-- Compact table: 6 columns only – fits 1024px without scroll --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600" id="myTable" style="min-width: 860px;">
                <thead class="text-[11px] text-slate-200 uppercase tracking-wider bg-slate-800">
                    <tr>
                        <th class="px-3 py-3 w-12">#</th>
                        <th class="px-4 py-3">Customer / TX</th>
                        <th class="px-4 py-3">Payment</th>
                        <th class="px-4 py-3">Submitted</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-3 py-3 text-right w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($deposits as $deposit)
                    @php
                        $statusColor = match($deposit->status) {
                            'pending'      => 'bg-amber-100 text-amber-800 ring-amber-200',
                            'approved'     => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                            'under-review' => 'bg-blue-100 text-blue-800 ring-blue-200',
                            'rejected'     => 'bg-violet-100 text-violet-800 ring-violet-200',
                            'cancelled'    => 'bg-rose-100 text-rose-800 ring-rose-200',
                            default        => 'bg-slate-100 text-slate-800 ring-slate-200',
                        };
                        $methodBadge = match(strtolower($deposit->deposit_method ?? '')) {
                            'usdt','trc20','usdt-trc20' => 'bg-emerald-50 text-emerald-700',
                            'btc','bitcoin' => 'bg-orange-50 text-orange-700',
                            'eth','ethereum' => 'bg-indigo-50 text-indigo-700',
                            default => 'bg-slate-50 text-slate-700',
                        };
                        $initials = collect(explode(' ', $deposit->user->name ?? 'U'))->map(fn($p)=>mb_substr($p,0,1))->take(2)->join('');
                    @endphp
                    <tr class="bg-white hover:bg-slate-50 transition-colors deposit-row" data-status="{{ $deposit->status }}">
                        {{-- # --}}
                        <td class="px-3 py-3 text-xs text-slate-500 font-medium">
                            {{ ($deposits->currentPage() - 1) * $deposits->perPage() + $loop->iteration }}
                        </td>

                        {{-- Customer / TX --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-[11px] font-bold flex-shrink-0">
                                    {{ strtoupper($initials) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-800 truncate max-w-[180px]">
                                        {{ $deposit->user->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-[11px] text-slate-500 truncate max-w-[180px]">
                                        {{ $deposit->user->email ?? '—' }}
                                    </div>
                                    <div class="font-mono text-[10px] text-slate-400 truncate max-w-[200px]" title="{{ $deposit->transaction_id }}">
                                        {{ $deposit->transaction_id ?: '—' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Payment --}}
                        <td class="px-4 py-3">
                            <div class="font-mono font-bold text-slate-900">
                                ${{ number_format($deposit->amount_deposited, 2) }}
                            </div>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $methodBadge }}">
                                    {{ strtoupper($deposit->deposit_method ?: '—') }}
                                </span>
                                <span class="text-[10px] text-slate-500">
                                    {{ strtoupper($deposit->currency_type ?? 'USD') }}
                                </span>
                            </div>
                        </td>

                        {{-- Submitted --}}
                        <td class="px-4 py-3">
                            <div class="text-[13px] text-slate-800">
                                {{ $deposit->created_at->format('M d, H:i') }}
                            </div>
                            <div class="text-[11px] text-slate-500">
                                {{ $deposit->created_at->diffForHumans() }}
                            </div>
                            @if($deposit->expires_at)
                                <div class="mt-1">
                                    <span class="deposit-countdown text-[11px] font-mono font-semibold text-orange-600"
                                          data-expires="{{ $deposit->expires_at->toIso8601String() }}">--:--</span>
                                    <span class="text-[10px] text-slate-400 ml-1">left</span>
                                </div>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold ring-1 ring-inset {{ $statusColor }}">
                                {{ ucfirst(str_replace('-', ' ', $deposit->status)) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-3 py-3 text-right">
                            <div class="relative inline-block text-left">
                                <button onclick="toggleDropdown(this)" type="button"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/>
                                    </svg>
                                </button>
                                <div class="dropdown-menu hidden absolute right-0 z-30 mt-1 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
                                    <div class="py-1 text-[13px]">
                                        <a href="{{ route('admin.payments.show', $deposit->id) }}"
                                           class="flex items-center gap-2 px-3 py-2 text-slate-700 hover:bg-slate-50">
                                            <i class="fa fa-eye w-4 text-indigo-500"></i> View details
                                        </a>
                                        @if($deposit->status != 'approved')
                                        <form action="{{ route('admin.approve-deposit') }}" method="POST" class="block">
                                            @csrf
                                            <input type="hidden" name="deposit" value="{{ $deposit->id }}"/>
                                            <button type="submit"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-left text-emerald-700 hover:bg-emerald-50">
                                                <i class="fa fa-check w-4"></i> Approve
                                            </button>
                                        </form>
                                        @endif
                                        @if($deposit->status != 'under-review')
                                        <button type="button" onclick="show('under-review','{{ $deposit->id }}')"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-left text-slate-700 hover:bg-slate-50">
                                            <i class="fa fa-hourglass-half w-4 text-blue-500"></i> Under review
                                        </button>
                                        @endif
                                        @if($deposit->status != 'rejected')
                                        <button type="button" onclick="show('rejected','{{ $deposit->id }}')"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-left text-amber-700 hover:bg-amber-50">
                                            <i class="fa fa-ban w-4"></i> Reject
                                        </button>
                                        @endif
                                        @if($deposit->status != 'cancelled')
                                        <button type="button" onclick="show('cancelled','{{ $deposit->id }}')"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-left text-rose-600 hover:bg-rose-50">
                                            <i class="fa fa-times-circle w-4"></i> Cancel
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <div class="text-slate-400">
                                <i class="fa fa-inbox text-3xl mb-2"></i>
                                <p class="text-sm">No deposits to review</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-xs text-slate-600">
                Showing <span class="font-semibold text-slate-800">{{ $deposits->firstItem() ?? 0 }}</span>
                to <span class="font-semibold text-slate-800">{{ $deposits->lastItem() ?? 0 }}</span>
                of <span class="font-semibold text-slate-800">{{ $deposits->total() }}</span> deposits
            </div>
            <div>
                {{ $deposits->links() }}
            </div>
        </div>
    </div>

    <p class="text-[11px] text-slate-400 mt-3 text-center">
        Table optimized: 6 compact columns • fits 1024px+ screens • no horizontal scroll
    </p>
</div>

{{-- Comment / Action Modal --}}
<div id="modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800">Deposit Action</h3>
                <button id="closeModal" type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.otherwise-decision-deposit') }}">
                @csrf
                <div class="px-5 py-4 space-y-3">
                    <div id="inputs"></div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Admin comment</label>
                        <textarea rows="4" name="comment"
                            class="w-full px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            placeholder="Reason / notes for this action..."></textarea>
                        <p class="text-[11px] text-slate-500 mt-1">This comment is saved to the deposit audit trail.</p>
                    </div>
                </div>
                <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Confirm action
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts – no jQuery / no Bootstrap – pure Tailwind + vanilla JS --}}
<script>
function renderDepositCountdowns(){
    function pad(n){ return String(n).padStart(2,'0'); }
    document.querySelectorAll('.deposit-countdown[data-expires]').forEach(function(el){
        var expires = new Date(el.getAttribute('data-expires')).getTime();
        var remaining = Math.floor((expires - Date.now()) / 1000);
        if (remaining <= 0) {
            el.textContent = 'EXPIRED';
            el.classList.remove('text-orange-600');
            el.classList.add('text-rose-600');
            return;
        }
        var m = Math.floor(remaining / 60);
        var s = remaining % 60;
        el.textContent = pad(m) + ':' + pad(s);
    });
}
function toggleDropdown(button){
    // close others
    document.querySelectorAll('.dropdown-menu:not(.hidden)').forEach(function(d){
        if (d !== button.nextElementSibling) d.classList.add('hidden');
    });
    const dropdown = button.nextElementSibling;
    dropdown.classList.toggle('hidden');
    // one-shot outside click
    setTimeout(()=>{
        const handler = (e)=>{
            if (!dropdown.contains(e.target) && !button.contains(e.target)){
                dropdown.classList.add('hidden');
                document.removeEventListener('click', handler);
            }
        };
        document.addEventListener('click', handler);
    },0);
}
function show(action, deposit){
    const modal = document.getElementById('modal');
    document.getElementById('inputs').innerHTML =
        '<input type="hidden" name="action" value="'+action+'" />' +
        '<input type="hidden" name="deposit" value="'+deposit+'" />' +
        '<div class="text-sm text-slate-600">Action: <span class="font-semibold capitalize text-slate-800">'+action.replace('-',' ')+'</span> • Deposit #'+deposit+'</div>';
    modal.classList.remove('hidden');
}
function closeModal(){
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('inputs').innerHTML = '';
}

document.addEventListener('DOMContentLoaded', function(){
    renderDepositCountdowns();
    setInterval(renderDepositCountdowns, 1000);

    // search
    const searchInput = document.getElementById('searchInput');
    if(searchInput){
        searchInput.addEventListener('input', function(){
            const q = this.value.toLowerCase();
            document.querySelectorAll('#myTable tbody tr.deposit-row').forEach(function(row){
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }

    // status filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn=>{
        btn.addEventListener('click', function(){
            const f = this.dataset.filter;
            // button styles
            document.querySelectorAll('.filter-btn').forEach(b=>{
                b.className = 'filter-btn px-3 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50';
            });
            this.className = 'filter-btn px-3 py-2 text-xs font-semibold rounded-lg bg-slate-800 text-white';
            // filter rows
            document.querySelectorAll('#myTable tbody tr.deposit-row').forEach(row=>{
                if(f==='all' || row.dataset.status===f){
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // ESC to close modal
    document.addEventListener('keydown', e=>{
        if(e.key === 'Escape') closeModal();
    });
});
</script>
@endsection
