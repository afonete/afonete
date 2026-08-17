@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 font-semibold"><i class="fas fa-check-circle mr-1"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 font-semibold"><i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="p-4 mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            <ul class="list-disc pl-5 space-y-1 font-semibold">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="bg-cyan-100 text-cyan-600 p-3 rounded-lg text-xl"><i class="fas fa-exchange-alt"></i></div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Volume Point Redemption</h1>
                <p class="text-xs text-slate-500 mt-0.5">Configure the USDT boxes on /user/rewards and audit who redeemed volume points.</p>
            </div>
        </div>
        <div class="flex gap-3 text-sm">
            <div class="bg-cyan-50 border border-cyan-100 rounded-lg px-4 py-2"><span class="block text-[11px] font-bold uppercase text-cyan-500">Redemptions</span><span class="font-extrabold text-cyan-700">{{ $totals['redemptions'] }}</span></div>
            <div class="bg-red-50 border border-red-100 rounded-lg px-4 py-2"><span class="block text-[11px] font-bold uppercase text-red-400">Points Spent</span><span class="font-extrabold text-red-600">{{ number_format($totals['points_spent']) }}</span></div>
            <div class="bg-emerald-50 border border-emerald-100 rounded-lg px-4 py-2"><span class="block text-[11px] font-bold uppercase text-emerald-500">USDT Paid</span><span class="font-extrabold text-emerald-700">${{ number_format($totals['usdt_paid'], 2) }}</span></div>
        </div>
    </div>

    {{-- Options configuration --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-5 py-3 border-b border-slate-100"><h2 class="font-bold text-slate-700"><i class="fas fa-sliders-h mr-1 text-blue-500"></i> Redemption Options (the boxes users see)</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr><th class="px-4 py-3 text-right">USDT Amount</th><th class="px-4 py-3 text-right">Points Required</th><th class="px-4 py-3 text-center">Order</th><th class="px-4 py-3 text-center">Active</th><th class="px-4 py-3 text-right">Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($options as $opt)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <form action="{{ route('admin.fom-redeem.update', $opt->id) }}" method="POST">
                            @csrf @method('PUT')
                            <td class="px-4 py-2 text-right"><input type="number" step="0.01" name="usdt_amount" value="{{ $opt->usdt_amount }}" class="w-32 bg-slate-50 border border-slate-300 rounded px-2 py-1 text-right"></td>
                            <td class="px-4 py-2 text-right"><input type="number" step="1" name="points_required" value="{{ (int) $opt->points_required }}" class="w-36 bg-slate-50 border border-slate-300 rounded px-2 py-1 text-right"></td>
                            <td class="px-4 py-2 text-center"><input type="number" name="sort_order" value="{{ $opt->sort_order }}" class="w-16 bg-slate-50 border border-slate-300 rounded px-2 py-1 text-center"></td>
                            <td class="px-4 py-2 text-center"><input type="checkbox" name="is_active" value="1" {{ $opt->is_active ? 'checked' : '' }}></td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg">Save</button>
                            </form>
                                <form action="{{ route('admin.fom-redeem.destroy', $opt->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this option? Past redemptions are kept.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-cyan-50/50">
                        <form action="{{ route('admin.fom-redeem.store') }}" method="POST">
                        @csrf
                        <td class="px-4 py-2 text-right"><input type="number" step="0.01" name="usdt_amount" placeholder="e.g. 7" class="w-32 bg-white border border-cyan-300 rounded px-2 py-1 text-right"></td>
                        <td class="px-4 py-2 text-right"><input type="number" step="1" name="points_required" placeholder="e.g. 700" class="w-36 bg-white border border-cyan-300 rounded px-2 py-1 text-right"></td>
                        <td class="px-4 py-2 text-center"><input type="number" name="sort_order" placeholder="#" class="w-16 bg-white border border-cyan-300 rounded px-2 py-1 text-center"></td>
                        <td class="px-4 py-2 text-center"><input type="checkbox" name="is_active" value="1" checked></td>
                        <td class="px-4 py-2 text-right"><button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold py-1.5 px-3 rounded-lg"><i class="fas fa-plus mr-1"></i>Add Option</button></td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Redemption audit --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100"><h2 class="font-bold text-slate-700"><i class="fas fa-users mr-1 text-amber-500"></i> Users Who Redeemed Volume Points</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr><th class="px-4 py-3 text-left">Redeemed At</th><th class="px-4 py-3 text-left">User</th><th class="px-4 py-3 text-right">Points Spent</th><th class="px-4 py-3 text-right">USDT Received</th></tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-4 py-3 text-xs text-slate-500">{{ \Carbon\Carbon::parse($log->redeemed_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3"><span class="font-bold text-slate-800 block">{{ $log->user->name ?? '#' . $log->user_id }}</span><span class="text-xs text-slate-400">@ {{ $log->user->user ?? '—' }}</span></td>
                            <td class="px-4 py-3 text-right font-bold text-red-500">-{{ number_format((float) $log->points_spent) }}</td>
                            <td class="px-4 py-3 text-right font-extrabold text-emerald-600">+${{ number_format((float) $log->usdt_received, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">No redemptions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-5 py-3 border-t border-slate-100">{{ $logs->links('pagination::bootstrap-4') }}</div>
        @endif
    </div>

</div>
@endsection
