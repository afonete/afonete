@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center gap-3">
        <div class="bg-blue-100 text-blue-600 p-2.5 rounded-lg text-lg">
            <i class="fas fa-history"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Withdrawal History (Processed)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Audit log of all processed, completed, and failed platform withdrawals.</p>
        </div>
    </div>

    {{-- History Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-4 py-3.5 bg-slate-50 border-b border-slate-200 flex justify-between items-center font-bold text-slate-800 text-sm">
            <span><i class="fas fa-list text-slate-400 mr-1"></i> Processed Withdrawals Log</span>
            <span class="bg-slate-200 text-slate-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $history->total() }} total records</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" style="min-width: 920px;">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">#</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">User</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Reference ID</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Requested</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Fee Charged</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Net Sent</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Destination</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Status</th>
                        <th class="text-xs font-semibold text-slate-500 px-4 py-3 uppercase tracking-wider">Processed Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $w)
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="px-4 py-3.5 text-sm text-slate-500 font-bold">
                                {{ ($history->currentPage() - 1) * $history->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3.5 text-sm">
                                <span class="font-semibold text-slate-800">{{ $w->user->name ?? '—' }}</span>
                                <span class="block text-xs text-slate-400 mt-0.5">{{ $w->user->email ?? '' }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-sm">
                                <code class="text-xs text-slate-500 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded select-all" title="{{ $w->transaction_no }}">
                                    {{ Str::limit($w->transaction_no, 15) }}
                                </code>
                            </td>
                            <td class="px-4 py-3.5 text-sm font-bold text-slate-700">
                                ${{ number_format($w->amount, 2) }}
                            </td>
                            <td class="px-4 py-3.5 text-sm font-semibold text-yellow-600">
                                ${{ number_format($w->fee_amount ?? 0.00, 2) }}
                            </td>
                            <td class="px-4 py-3.5 text-sm font-extrabold text-emerald-600">
                                ${{ number_format($w->net_amount ?? $w->amount, 2) }}
                            </td>
                            <td class="px-4 py-3.5 text-sm">
                                <code class="text-xs text-slate-500 bg-slate-50 border border-slate-200 px-2 py-1 rounded select-all" title="{{ $w->wallet_address }}">{{ Str::limit($w->wallet_address, 16) }}</code>
                                <span class="block text-xs text-slate-400 mt-1 uppercase font-bold">{{ $w->methodLabel() }}{{ $w->network ? ' · ' . $w->network : '' }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-sm">
                                @if($w->status === 'completed')
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-0.5 rounded"><i class="fas fa-check-circle mr-1"></i>Completed</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded" title="Reason: {{ $w->failure_reason }}"><i class="fas fa-times-circle mr-1"></i>Failed</span>
                                @endif
                                @if($w->txn_hash)
                                    <small class="text-slate-400 d-block mt-1 max-w-[120px] truncate" title="{{ $w->txn_hash }}">
                                        <i class="fas fa-link"></i> {{ Str::limit($w->txn_hash, 12) }}
                                    </small>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-sm text-slate-400 whitespace-nowrap">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $w->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-slate-400 text-sm">
                                <i class="fas fa-folder-open mb-1 text-slate-300 block text-lg"></i>
                                No processed withdrawal records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Custom Pagination --}}
        @if($history->hasPages())
            <div class="px-4 py-3 bg-slate-50 border-t border-slate-100 flex justify-center">
                {{ $history->links() }}
            </div>
        @endif
    </div>

</div>

<script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
@endsection
