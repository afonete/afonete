@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl" x-data="{ tab: 'pending', openApproveModal: null, openRejectModal: null }">

    {{-- Flash Messages --}}
    @if(session('message'))
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
            <i class="fas fa-check-circle text-lg mr-3 text-green-600"></i>
            <span class="font-medium flex-1">{{ session('message') }}</span>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex items-center justify-center h-8 w-8" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <i class="fas fa-exclamation-circle text-lg mr-3 text-red-600"></i>
            <span class="font-medium flex-1">{{ session('error') }}</span>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-100 inline-flex items-center justify-center h-8 w-8" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <header class="bg-gradient-to-r from-amber-50 via-indigo-50 to-blue-50 border border-amber-200/60 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl uppercase text-slate-800 font-extrabold tracking-tight flex items-center">
                    <span class="p-2.5 bg-amber-500 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center">
                        <i class="fas fa-coins text-xl"></i>
                    </span>
                    Team Leader Token Releases
                </h1>
                <p class="text-slate-600 text-sm mt-2 font-medium max-w-2xl">
                    Review Team Leaders whose timeline/duration is completed, inspect their performance (tasks, events, referrals), and manually confirm token releases to Available Tokens.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.team-leaders.index') }}" class="inline-flex items-center gap-2 bg-white text-slate-700 hover:bg-slate-50 font-bold px-4 py-2.5 rounded-xl border border-slate-200 text-xs shadow-sm transition">
                    <i class="fas fa-users-cog text-indigo-600"></i> All Team Leaders
                </a>
            </div>
        </div>
    </header>

    {{-- Tabs Filter --}}
    <div class="space-y-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex flex-wrap gap-x-6 gap-y-2" aria-label="Tabs">
                <button @click="tab = 'pending'" :class="tab === 'pending' ? 'border-amber-500 text-amber-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-hourglass-half"></i> Pending Releases
                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($pendingReleases) }}</span>
                </button>
                <button @click="tab = 'approved'" :class="tab === 'approved' ? 'border-green-500 text-green-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-check-circle"></i> Approved &amp; Released
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($approvedReleases) }}</span>
                </button>
                <button @click="tab = 'rejected'" :class="tab === 'rejected' ? 'border-red-500 text-red-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-times-circle"></i> Rejected / Held
                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($rejectedReleases) }}</span>
                </button>
                <button @click="tab = 'all'" :class="tab === 'all' ? 'border-blue-500 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-list"></i> All ({ count($releases) })
                </button>
            </nav>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-semibold text-xs border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Leader / Account</th>
                            <th class="py-3.5 px-4">Locked Tokens</th>
                            <th class="py-3.5 px-4">Timeline / Duration</th>
                            <th class="py-3.5 px-4">Performance Snapshot</th>
                            <th class="py-3.5 px-4">Release Status</th>
                            <th class="py-3.5 px-4 text-right">Admin Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($releases as $item)
                            @php
                                $rel = $item['release'];
                                $lead = $item['leader'];
                                $usr = $item['user'];
                                $perf = $item['performance'];
                            @endphp
                            <tr x-show="tab === 'all' || tab === '{{ $rel->status }}'" class="hover:bg-slate-50/80 transition duration-150">
                                {{-- Leader Info --}}
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-sm border border-amber-200">
                                            {{ strtoupper(substr($lead->User_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.team-leaders.show', $lead->id) }}" class="font-bold text-slate-900 hover:text-indigo-600 flex items-center gap-1.5">
                                                {{ $lead->User_name }}
                                                <i class="fas fa-external-link-alt text-xs text-slate-400"></i>
                                            </a>
                                            <div class="text-xs text-slate-500 font-medium">{{ $usr ? $usr->email : $lead->Email }}</div>
                                            <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-extrabold rounded-md uppercase tracking-wider {{ ($lead->leadership_level ?? '') === 'SUPER_LEADER' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                                {{ $lead->leadership_level ?? 'TEAM_LEADER' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tokens Info --}}
                                <td class="py-4 px-4">
                                    <div class="font-extrabold text-slate-900 text-base">
                                        {{ number_format($rel->total_locked_tokens, 0) }} <span class="text-xs text-amber-600">TOKENS</span>
                                    </div>
                                    @if($rel->released_tokens > 0)
                                        <div class="text-xs text-green-600 font-semibold mt-0.5">
                                            Released: {{ number_format($rel->released_tokens, 0) }}
                                        </div>
                                    @endif
                                    <div class="text-xs text-slate-400 font-medium mt-0.5">
                                        Unreleased: {{ number_format($rel->remaining_locked_tokens, 0) }}
                                    </div>
                                </td>

                                {{-- Timeline / Duration --}}
                                <td class="py-4 px-4">
                                    <div class="font-semibold text-slate-800 text-xs">
                                        Duration: {{ $rel->duration_days }} Days
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        Activated: {{ $rel->activated_at ? $rel->activated_at->format('M d, Y') : 'N/A' }}
                                    </div>
                                    <div class="mt-1.5">
                                        @if($item['is_duration_over'])
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-check-circle mr-1 text-green-600"></i> Duration Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <i class="fas fa-spinner fa-spin mr-1 text-blue-500"></i> In Timeline
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Performance Snapshot --}}
                                <td class="py-4 px-4">
                                    <div class="grid grid-cols-2 gap-1.5 text-xs">
                                        <div class="bg-slate-50 p-2 rounded-lg border border-slate-200/60">
                                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Tasks</span>
                                            <span class="font-extrabold text-slate-800">{{ $perf['tasks_completed'] }} / {{ $perf['tasks_total'] }}</span>
                                        </div>
                                        <div class="bg-slate-50 p-2 rounded-lg border border-slate-200/60">
                                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Events</span>
                                            <span class="font-extrabold text-slate-800">{{ $perf['events_approved'] }} Approved</span>
                                        </div>
                                        <div class="bg-slate-50 p-2 rounded-lg border border-slate-200/60 col-span-2">
                                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Referrals</span>
                                            <span class="font-extrabold text-slate-800">{{ $perf['referrals_count'] }} Total ({{ $perf['active_referrals'] }} Active)</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Release Status --}}
                                <td class="py-4 px-4">
                                    @if($rel->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300">
                                            <i class="fas fa-check-circle mr-1.5 text-green-600"></i> Released to Available
                                        </span>
                                        <div class="text-[11px] text-slate-500 mt-1">
                                            {{ $rel->approved_at ? $rel->approved_at->format('M d, Y H:i') : '' }}
                                        </div>
                                    @elseif($rel->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                                            <i class="fas fa-times-circle mr-1.5 text-red-600"></i> Release Held / Rejected
                                        </span>
                                        <div class="text-[11px] text-slate-500 mt-1 max-w-xs truncate" title="{{ $rel->admin_notes }}">
                                            {{ $rel->admin_notes }}
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                            <i class="fas fa-hourglass-half mr-1.5 text-amber-600"></i> Pending Review
                                        </span>
                                    @endif
                                </td>

                                {{-- Admin Actions --}}
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($rel->status !== 'approved')
                                            <button @click="openApproveModal = {{ $rel->id }}" class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs shadow-sm transition">
                                                <i class="fas fa-check"></i> Approve &amp; Release
                                            </button>
                                            <button @click="openRejectModal = {{ $rel->id }}" class="inline-flex items-center gap-1.5 bg-red-100 hover:bg-red-200 text-red-700 font-bold px-3 py-1.5 rounded-lg text-xs transition">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        @else
                                            <span class="text-xs font-semibold text-green-700 bg-green-50 px-2.5 py-1 rounded-md border border-green-200">
                                                <i class="fas fa-lock-open mr-1"></i> Tokens Credited
                                            </span>
                                        @endif
                                    </div>

                                    {{-- APPROVE MODAL --}}
                                    <div x-show="openApproveModal === {{ $rel->id }}" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-100">
                                            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                                    <i class="fas fa-coins text-amber-500"></i> Approve Token Release
                                                </h3>
                                                <button @click="openApproveModal = null" class="text-slate-400 hover:text-slate-600">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.team-leaders.token-releases.approve', $rel->id) }}" class="mt-4 space-y-4">
                                                @csrf
                                                <div class="bg-amber-50 p-3 rounded-xl border border-amber-200 text-xs text-amber-900">
                                                    Releasing <strong>{{ number_format($rel->total_locked_tokens, 0) }} tokens</strong> to <strong>{{ $usr ? $usr->name : $lead->Names }}</strong>'s <strong>Available Token</strong> account.
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Release Amount (Tokens)</label>
                                                    <input type="number" name="release_amount" value="{{ (int)$rel->total_locked_tokens }}" min="1" step="1" required class="w-full rounded-xl border-slate-300 text-sm font-bold text-slate-800 focus:ring-amber-500 focus:border-amber-500">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Admin Notes / Audit Confirmation</label>
                                                    <textarea name="notes" rows="3" placeholder="Performance verified. Tokens approved for release." class="w-full rounded-xl border-slate-300 text-xs text-slate-800 focus:ring-amber-500 focus:border-amber-500"></textarea>
                                                </div>

                                                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                                                    <button type="button" @click="openApproveModal = null" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                                                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-green-600 hover:bg-green-700 rounded-xl shadow-sm">Confirm &amp; Credit Available Tokens</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- REJECT MODAL --}}
                                    <div x-show="openRejectModal === {{ $rel->id }}" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-100">
                                            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                                    <i class="fas fa-times-circle text-red-500"></i> Reject / Hold Token Release
                                                </h3>
                                                <button @click="openRejectModal = null" class="text-slate-400 hover:text-slate-600">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.team-leaders.token-releases.reject', $rel->id) }}" class="mt-4 space-y-4">
                                                @csrf
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reason for Rejection / Delay</label>
                                                    <textarea name="notes" rows="3" required placeholder="Performance criteria not met. Required tasks or events incomplete." class="w-full rounded-xl border-slate-300 text-xs text-slate-800 focus:ring-red-500 focus:border-red-500"></textarea>
                                                </div>

                                                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                                                    <button type="button" @click="openRejectModal = null" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                                                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm">Reject / Hold Release</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    <i class="fas fa-coins text-3xl mb-2 text-slate-300 block"></i>
                                    No Team Leader token release records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
