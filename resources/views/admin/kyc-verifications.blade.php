@extends('admin.sidebar')

@section('contents')
<div class="container-fluid py-4 px-4 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Flash Messages --}}
        @if(session('message'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
                <span><i class="fas fa-check-circle mr-2 text-emerald-600"></i>{{ session('message') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
                <span><i class="fas fa-exclamation-circle mr-2 text-rose-600"></i>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700"><i class="fas fa-times"></i></button>
            </div>
        @endif

        {{-- Header Banner --}}
        <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-md border border-slate-800 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-white flex items-center gap-2">
                    <i class="fas fa-id-card text-amber-400"></i> Multi-Level KYC Verifications
                </h1>
                <p class="text-xs text-slate-400 mt-1">Inspect and validate user verification documents across Identity Verification (50%) and Address Verification (100%).</p>
            </div>
            <div class="flex items-center gap-3">
                @if($pendingCount > 0)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-extrabold bg-rose-500/20 text-rose-300 border border-rose-500/40 animate-pulse">
                        <i class="fas fa-bell mr-1.5"></i> {{ $pendingCount }} PENDING REVIEWS
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                        <i class="fas fa-check-circle mr-1.5"></i> ALL REVIEWS UP TO DATE
                    </span>
                @endif
            </div>
        </div>

        {{-- Admin Configurable Non-KYC Fee Card --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2 border-b pb-2">
                <i class="fas fa-sliders text-indigo-600"></i> Non-KYC Fee Setting (Admin Configurable)
            </h3>
            <form method="POST" action="{{ route('admin.kyc.non-kyc-fee') }}" class="flex items-end gap-3 flex-wrap">
                @csrf
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Fee Amount for Unverified Accounts ($ USD)</label>
                    <input type="number" name="non_kyc_fee_amount" min="0" step="0.01" value="{{ old('non_kyc_fee_amount', $nonKycFee) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold font-mono text-slate-900 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-extrabold px-4 py-2 rounded-xl text-xs shadow-sm transition flex items-center gap-1.5">
                    <i class="fas fa-save"></i> Save Non-KYC Fee
                </button>
            </form>
            <p class="text-[11px] text-slate-500 mt-2">This fee applies to withdrawal requests if a user has not reached 100% Level 3 KYC approval.</p>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-bold">
            <a href="{{ route('admin.kyc.index') }}" class="px-3.5 py-2 rounded-xl border transition {{ !request('filter') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                All KYC Accounts
            </a>
            <a href="{{ route('admin.kyc.index', ['filter' => 'pending']) }}" class="px-3.5 py-2 rounded-xl border transition {{ request('filter') === 'pending' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                Pending Reviews
            </a>
            <a href="{{ route('admin.kyc.index', ['filter' => 'level2']) }}" class="px-3.5 py-2 rounded-xl border transition {{ request('filter') === 'level2' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                Identity Pending (50%)
            </a>
            <a href="{{ route('admin.kyc.index', ['filter' => 'level3']) }}" class="px-3.5 py-2 rounded-xl border transition {{ request('filter') === 'level3' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                Address Pending (100%)
            </a>
            <a href="{{ route('admin.kyc.index', ['filter' => 'approved']) }}" class="px-3.5 py-2 rounded-xl border transition {{ request('filter') === 'approved' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                100% Fully Approved
            </a>
        </div>

        {{-- Verification List Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-x-auto p-6">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b">
                    <tr>
                        <th class="py-3.5 px-4">User</th>
                        <th class="py-3.5 px-4">Progress</th>
                        <th class="py-3.5 px-4">Identity Verification (50%)</th>
                        <th class="py-3.5 px-4">Address Verification (100%)</th>
                        <th class="py-3.5 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($verifications as $v)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-4 font-bold text-slate-900">
                                @if($v->user)
                                    <div>@ {{ $v->user->user }}</div>
                                    <div class="text-[11px] text-slate-400 font-normal">{{ $v->user->name }} · {{ $v->user->email }}</div>
                                @else
                                    User #{{ $v->user_id }}
                                @endif
                            </td>

                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold {{ $v->overall_percentage === 100 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($v->overall_percentage > 0 ? 'bg-sky-100 text-sky-800 border border-sky-200' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $v->overall_percentage }}% Verified
                                </span>
                            </td>

                            <td class="py-4 px-4">
                                @if($v->level_2_status === 'approved')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800"><i class="fas fa-check-circle mr-1"></i> Approved</span>
                                @elseif($v->level_2_status === 'pending')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 animate-pulse"><i class="fas fa-hourglass-half mr-1"></i> Pending</span>
                                @elseif($v->level_2_status === 'rejected')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800"><i class="fas fa-times-circle mr-1"></i> Rejected</span>
                                @else
                                    <span class="text-slate-400 italic">Unsubmitted</span>
                                @endif
                            </td>

                            <td class="py-4 px-4">
                                @if($v->level_3_status === 'approved')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800"><i class="fas fa-check-circle mr-1"></i> Approved</span>
                                @elseif($v->level_3_status === 'pending')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 animate-pulse"><i class="fas fa-hourglass-half mr-1"></i> Pending</span>
                                @elseif($v->level_3_status === 'rejected')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800"><i class="fas fa-times-circle mr-1"></i> Rejected</span>
                                @else
                                    <span class="text-slate-400 italic">Unsubmitted</span>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('admin.kyc.show', $v->id) }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs transition inline-flex items-center gap-1">
                                    <i class="fas fa-search"></i> Inspect &amp; Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                <i class="fas fa-id-card text-3xl mb-2 text-slate-300 block"></i>
                                No KYC verification records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($verifications->hasPages())
                <div class="p-3 d-flex justify-content-center border-t mt-4">
                    {{ $verifications->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
