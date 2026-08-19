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

    {{-- Header + week selector --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900"><i class="fas fa-trophy text-amber-500 mr-2"></i>Weekly Leaderboard</h1>
            <p class="text-sm text-slate-500">
                Top 10 earners (UVP / FOM Licence Miner) on the user dashboard — regenerated every Monday.
                Pin any user at any list number; system entries re-flow around your pins.
            </p>
        </div>
        <form method="GET" action="{{ route('admin.fom-leaderboard.index') }}">
            <select name="week" onchange="this.form.submit()"
                    class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg p-2">
                @foreach($weeks as $w)
                    <option value="{{ $w['value'] }}" {{ $week->toDateString() === $w['value'] ? 'selected' : '' }}>{{ $w['label'] }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Board --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                <h2 class="text-lg font-bold text-slate-900">Week {{ $weekLabel }}</h2>
                <form method="POST" action="{{ route('admin.fom-leaderboard.regenerate') }}">
                    @csrf
                    <input type="hidden" name="week" value="{{ $week->toDateString() }}">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-xs px-3 py-2">
                        <i class="fas fa-sync-alt mr-1"></i> Regenerate system entries
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Username</th>
                            <th class="px-4 py-3">Total Earned</th>
                            <th class="px-4 py-3">Earned From</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $e)
                            <tr class="border-b {{ $e->is_manual ? 'bg-amber-50' : 'bg-white' }}">
                                <td class="px-4 py-3 font-bold text-slate-900">{{ $e->position }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $e->display_name }}</td>
                                <td class="px-4 py-3 font-bold text-emerald-600">${{ number_format((float) $e->total_earned, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-bold px-2 py-1 rounded {{ $e->earned_from === 'UVP' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $e->earned_from }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($e->is_manual)
                                        <span class="text-xs font-bold text-amber-600"><i class="fas fa-thumbtack mr-1"></i>Admin pin</span>
                                    @else
                                        <span class="text-xs text-slate-400">System</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if($e->is_manual)
                                        <form method="POST" action="{{ route('admin.fom-leaderboard.unpin', $e->id) }}"
                                              onsubmit="return confirm('Remove this pin? System entries will re-flow.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-semibold text-red-600 hover:underline text-xs">Remove pin</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white">
                                <td colspan="6" class="px-4 py-6 text-center text-slate-400">
                                    No earnings recorded for this week yet — the board fills as users earn UVP/FOM bonuses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pin form --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 h-fit">
            <h2 class="text-lg font-bold text-slate-900 mb-1"><i class="fas fa-thumbtack text-amber-500 mr-1"></i> Pin a user</h2>
            <p class="text-xs text-slate-500 mb-4">
                Place any user at any list number for this week. Example: pin <strong>John Doe</strong> at #1 —
                the system's #1 automatically becomes #2 (system entries shift around your pins).
            </p>
            <form method="POST" action="{{ route('admin.fom-leaderboard.pin') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="week" value="{{ $week->toDateString() }}">
                <div>
                    <label class="block mb-1 text-sm font-semibold text-slate-700">Username / email / user ID</label>
                    <input type="text" name="username" required placeholder="e.g. johndoe"
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-semibold text-slate-700">List number (1–10)</label>
                    <select name="position" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg block w-full p-2.5">
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">#{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-semibold text-slate-700">Total Earned override <span class="text-slate-400 font-normal">(optional — blank = real earnings)</span></label>
                    <input type="number" step="0.01" min="0" name="amount" placeholder="auto from this week's earnings"
                           class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-semibold text-slate-700">Earned From <span class="text-slate-400 font-normal">(optional — blank = auto)</span></label>
                    <select name="earned_from" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg block w-full p-2.5">
                        <option value="">Auto (from real earnings)</option>
                        <option value="UVP">UVP</option>
                        <option value="FOM Licence Miner">FOM Licence Miner</option>
                    </select>
                </div>
                <button type="submit" class="w-full text-white bg-amber-500 hover:bg-amber-600 font-bold rounded-lg text-sm px-5 py-2.5">
                    Pin to leaderboard
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
