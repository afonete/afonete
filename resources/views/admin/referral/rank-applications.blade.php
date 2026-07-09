@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    {{-- Header & Back Navigation --}}
    <div class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <a href="{{ route('admin.referral.bonuses') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm transition dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:text-white">
            <i class="fas fa-arrow-left text-xs"></i> Back to Referral Bonuses
        </a>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200">
            <i class="fas fa-robot mr-1.5"></i> Daily Auto-Detection Active
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
            <span class="p-2.5 bg-blue-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center"><i class="fas fa-trophy text-xl"></i></span>
            Rank Advancement Applications Queue
        </h1>
        <p class="text-slate-600 text-sm mt-2 font-medium max-w-2xl dark:text-gray-300">
            Audit user rank promotion applications, verify downline team volume milestones, and upload celebration banner badges.
        </p>
    </header>

    {{-- SECTION 1: PENDING REVIEW TABLE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-10 dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-amber-500 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-hourglass-half"></i> Pending Rank Applications</span>
            <span class="text-xs bg-amber-600 text-white px-3 py-1 rounded-full font-semibold">Pending Review: {{ $pending->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">User / Account</th>
                        <th class="py-4 px-6">Requested Rank</th>
                        <th class="py-4 px-6">Reward Benefit</th>
                        <th class="py-4 px-6">Detected Date</th>
                        <th class="py-4 px-6 text-center min-w-[340px]">Admin Review &amp; Banner Upload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($pending as $ur)
                        <tr class="bg-white hover:bg-amber-50/30 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                <div>{{ $ur->user->name ?? 'Unknown User' }}</div>
                                <div class="text-xs font-normal text-gray-500 font-mono">{{ $ur->user->email ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 text-xs font-extrabold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 inline-flex items-center gap-1">
                                    <i class="fas fa-trophy text-amber-500"></i> {{ $ur->rank_name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-mono font-extrabold text-emerald-600 dark:text-emerald-400 text-xs">
                                {{ $ur->rank ? $ur->rank->rewardLabel() : '—' }}
                            </td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-500">
                                {{ optional($ur->detected_at)->format('d M Y, H:i') ?? '—' }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-3 flex-wrap">
                                    <form method="POST" action="{{ route('admin.rank.approve', $ur->id) }}" enctype="multipart/form-data" class="flex flex-col gap-2 bg-gray-50 p-3 rounded-xl border border-gray-200 dark:bg-gray-700/50 dark:border-gray-600 flex-1">
                                        @csrf
                                        <div class="flex items-center gap-2">
                                            <input type="file" name="congratulation_image" accept="image/*" required class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300">
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="text" name="admin_notes" class="bg-white border border-gray-300 text-gray-800 text-xs rounded-lg px-2.5 py-1.5 flex-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Congratulation message">
                                            <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs shadow-sm transition inline-flex items-center gap-1 flex-shrink-0"><i class="fas fa-check"></i> Approve + Upload</button>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('admin.rank.reject', $ur->id) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-sm transition inline-flex items-center gap-1" onclick="return confirm('Reject this rank application?')"><i class="fas fa-times"></i> Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-gray-400">No pending rank advancement applications found in queue.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 2: APPROVED HISTORY TABLE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-10 dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-slate-800 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-history text-emerald-400"></i> Approved Rank History</span>
            <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full font-semibold">Total Approved: {{ method_exists($approved, 'total') ? $approved->total() : $approved->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">User / Account</th>
                        <th class="py-4 px-6">Rank Title</th>
                        <th class="py-4 px-6 text-right">Reward Amount ($)</th>
                        <th class="py-4 px-6">Reviewed Date</th>
                        <th class="py-4 px-6 text-center">Celebration Banner</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($approved as $ur)
                        <tr class="bg-white hover:bg-blue-50/40 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                <div>{{ $ur->user->name ?? 'Unknown User' }}</div>
                                <div class="text-xs font-normal text-gray-500 font-mono">{{ $ur->user->email ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 text-xs font-extrabold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 inline-flex items-center gap-1">
                                    <i class="fas fa-star text-amber-500"></i> {{ $ur->rank_name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-extrabold text-emerald-600 dark:text-emerald-400 text-base">${{ number_format($ur->reward_amount, 2) }}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-500">{{ optional($ur->reviewed_at)->format('d M Y, H:i') ?? '—' }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($ur->congratulation_image)
                                    <a href="{{ asset('storage/' . $ur->congratulation_image) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-bold border border-blue-200 transition dark:bg-blue-900/30 dark:text-blue-300"><i class="fas fa-image"></i> View Banner</a>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-gray-400">No approved rank advancements recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($approved, 'links') && $approved->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">{{ $approved->links() }}</div>
        @endif
    </div>

    {{-- SECTION 3: REJECTED HISTORY TABLE --}}
    @if($rejected->isNotEmpty())
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-rose-800 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-times-circle text-rose-300"></i> Rejected Applications History</span>
            <span class="text-xs bg-rose-900 text-rose-200 px-3 py-1 rounded-full font-semibold">Total Rejected: {{ method_exists($rejected, 'total') ? $rejected->total() : $rejected->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">User / Account</th>
                        <th class="py-4 px-6">Requested Rank</th>
                        <th class="py-4 px-6">Reviewed Date</th>
                        <th class="py-4 px-6">Rejection Notes / Admin Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($rejected as $ur)
                        <tr class="bg-white hover:bg-rose-50/30 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                <div>{{ $ur->user->name ?? 'Unknown User' }}</div>
                                <div class="text-xs font-normal text-gray-500 font-mono">{{ $ur->user->email ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300 border border-rose-200">
                                    {{ $ur->rank_name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-500">{{ optional($ur->reviewed_at)->format('d M Y, H:i') ?? '—' }}</td>
                            <td class="py-4 px-6 text-xs text-rose-600 dark:text-rose-400 font-medium">{{ $ur->admin_notes ?? 'Rejected by admin' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(method_exists($rejected, 'links') && $rejected->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">{{ $rejected->links() }}</div>
        @endif
    </div>
    @endif

</div>
@endsection
