@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg text-xl"><i class="fas fa-user-check"></i></div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">FOM Referral Audit — {{ $user->name }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">@ {{ $user->user }} · {{ $user->email }} ·
                    @if($eligible)
                        <span class="text-emerald-600 font-bold">Affiliate terms accepted</span>
                    @else
                        <span class="text-red-500 font-bold">Terms NOT accepted — earns nothing until accepted</span>
                    @endif
                </p>
            </div>
        </div>
        <a href="{{ route('admin.fom-referral.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded-lg text-sm">← Back</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Left Volume</span><span class="text-lg font-extrabold text-blue-600">{{ number_format($left, 2) }}</span></div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Right Volume</span><span class="text-lg font-extrabold text-violet-600">{{ number_format($right, 2) }}</span></div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Weaker Side</span><span class="text-lg font-extrabold text-slate-700">{{ number_format(min($left, $right), 2) }}</span></div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Volume Points</span><span class="text-lg font-extrabold text-emerald-600">{{ number_format($volumePoints) }}</span></div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100"><h2 class="font-bold text-slate-700">All FOM Bonus Rows</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">From Buyer</th>
                        <th class="px-4 py-3 text-center">Level</th>
                        <th class="px-4 py-3 text-right">Pkg Price</th>
                        <th class="px-4 py-3 text-right">Direct Bonus</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-left">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-700">{{ $r->sourceUser->user ?? '#' . $r->source_user_id }}</td>
                            <td class="px-4 py-3 text-center font-bold text-amber-600">L{{ $r->level }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($r->source_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ $r->level == 1 ? '$' . number_format($r->bonus_amount, 2) : '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($r->status === 'paid') <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-2 py-1 rounded">PAID</span>
                                @elseif($r->status === 'accrued') <span class="bg-amber-50 text-amber-700 text-xs font-bold px-2 py-1 rounded">ACCRUED</span>
                                @elseif($r->status === 'ineligible') <span class="bg-red-50 text-red-600 text-xs font-bold px-2 py-1 rounded">FORFEITED</span>
                                @else <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2 py-1 rounded">{{ strtoupper($r->status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400 max-w-md truncate" title="{{ $r->notes }}">{{ $r->notes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">No FOM bonus rows for this user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rows->hasPages())
            <div class="px-5 py-3 border-t border-slate-100">{{ $rows->links('pagination::bootstrap-4') }}</div>
        @endif
    </div>

</div>
@endsection
