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
            <div class="bg-emerald-100 text-emerald-600 p-3 rounded-lg text-xl"><i class="fas fa-trophy"></i></div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">FOM Incentive Program</h1>
                <p class="text-xs text-slate-500 mt-0.5">Direct-referral investment achievements within a window from the user's latest FOM activation → instant cashout bonus.</p>
            </div>
        </div>
        <div class="flex gap-3 text-sm">
            <div class="bg-emerald-50 border border-emerald-100 rounded-lg px-4 py-2"><span class="block text-[11px] font-bold uppercase text-emerald-500">Achievers</span><span class="font-extrabold text-emerald-700">{{ $totals['awards_count'] }}</span></div>
            <div class="bg-amber-50 border border-amber-100 rounded-lg px-4 py-2"><span class="block text-[11px] font-bold uppercase text-amber-500">Bonus Paid</span><span class="font-extrabold text-amber-700">${{ number_format($totals['bonus_paid'], 2) }}</span></div>
        </div>
    </div>

    {{-- Tier configuration --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-5 py-3 border-b border-slate-100"><h2 class="font-bold text-slate-700"><i class="fas fa-sliders-h mr-1 text-blue-500"></i> Incentive Tiers (editable)</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr><th class="px-4 py-3 text-right">Achievement (USD)</th><th class="px-4 py-3 text-center">Duration (days)</th><th class="px-4 py-3 text-right">Bonus (USD)</th><th class="px-4 py-3 text-center">Order</th><th class="px-4 py-3 text-center">Active</th><th class="px-4 py-3 text-right">Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($tiers as $tier)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <form action="{{ route('admin.fom-incentive.update', $tier->id) }}" method="POST">
                            @csrf @method('PUT')
                            <td class="px-4 py-2 text-right"><input type="number" step="0.01" name="achievement" value="{{ $tier->achievement }}" class="w-36 bg-slate-50 border border-slate-300 rounded px-2 py-1 text-right"></td>
                            <td class="px-4 py-2 text-center"><input type="number" name="duration_days" value="{{ $tier->duration_days }}" class="w-20 bg-slate-50 border border-slate-300 rounded px-2 py-1 text-center"></td>
                            <td class="px-4 py-2 text-right"><input type="number" step="0.01" name="bonus" value="{{ $tier->bonus }}" class="w-32 bg-slate-50 border border-slate-300 rounded px-2 py-1 text-right"></td>
                            <td class="px-4 py-2 text-center"><input type="number" name="sort_order" value="{{ $tier->sort_order }}" class="w-16 bg-slate-50 border border-slate-300 rounded px-2 py-1 text-center"></td>
                            <td class="px-4 py-2 text-center"><input type="checkbox" name="is_active" value="1" {{ $tier->is_active ? 'checked' : '' }}></td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg">Save</button>
                            </form>
                                <form action="{{ route('admin.fom-incentive.destroy', $tier->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this tier? Past awards are kept.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    {{-- Add new tier --}}
                    <tr class="bg-emerald-50/50">
                        <form action="{{ route('admin.fom-incentive.store') }}" method="POST">
                        @csrf
                        <td class="px-4 py-2 text-right"><input type="number" step="0.01" name="achievement" placeholder="e.g. 20000" class="w-36 bg-white border border-emerald-300 rounded px-2 py-1 text-right"></td>
                        <td class="px-4 py-2 text-center"><input type="number" name="duration_days" placeholder="days" class="w-20 bg-white border border-emerald-300 rounded px-2 py-1 text-center"></td>
                        <td class="px-4 py-2 text-right"><input type="number" step="0.01" name="bonus" placeholder="bonus" class="w-32 bg-white border border-emerald-300 rounded px-2 py-1 text-right"></td>
                        <td class="px-4 py-2 text-center"><input type="number" name="sort_order" placeholder="#" class="w-16 bg-white border border-emerald-300 rounded px-2 py-1 text-center"></td>
                        <td class="px-4 py-2 text-center"><input type="checkbox" name="is_active" value="1" checked></td>
                        <td class="px-4 py-2 text-right"><button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-1.5 px-3 rounded-lg"><i class="fas fa-plus mr-1"></i>Add Tier</button></td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Achievers list --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100"><h2 class="font-bold text-slate-700"><i class="fas fa-medal mr-1 text-amber-500"></i> Users Who Achieved Incentives</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr><th class="px-4 py-3 text-left">Awarded</th><th class="px-4 py-3 text-left">User</th><th class="px-4 py-3 text-right">Achievement Target</th><th class="px-4 py-3 text-right">Achieved</th><th class="px-4 py-3 text-center">Window</th><th class="px-4 py-3 text-right">Bonus Paid</th></tr>
                </thead>
                <tbody>
                    @forelse($awards as $a)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-4 py-3 text-xs text-slate-500">{{ \Carbon\Carbon::parse($a->awarded_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3"><span class="font-bold text-slate-800 block">{{ $a->user->name ?? '#' . $a->user_id }}</span><span class="text-xs text-slate-400">@ {{ $a->user->user ?? '—' }}</span></td>
                            <td class="px-4 py-3 text-right font-semibold">${{ number_format($a->achievement) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-600">${{ number_format($a->achieved_amount, 2) }}</td>
                            <td class="px-4 py-3 text-center text-xs text-slate-500">{{ \Carbon\Carbon::parse($a->window_start)->format('m-d') }} → {{ \Carbon\Carbon::parse($a->window_end)->format('m-d') }} ({{ $a->duration_days }}d)</td>
                            <td class="px-4 py-3 text-right font-extrabold text-amber-600">${{ number_format($a->bonus, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">No incentive achievers yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($awards->hasPages())
            <div class="px-5 py-3 border-t border-slate-100">{{ $awards->links('pagination::bootstrap-4') }}</div>
        @endif
    </div>

</div>
@endsection
