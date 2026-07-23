@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl" x-data="{ tab: 'pending', openModal: null, openEvent: null, openProof: null }">

    {{-- Flash Messages --}}
    @if(session('message'))
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
            <i class="fas fa-check-circle text-lg mr-3 text-green-600"></i>
            <span class="font-medium flex-1">{{ session('message') }}</span>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl uppercase text-slate-800 font-extrabold tracking-tight flex items-center">
                    <span class="p-2.5 bg-indigo-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center">
                        <i class="fas fa-users-cog text-xl"></i>
                    </span>
                    Manage Team Leaders
                </h1>
                <p class="text-slate-600 text-sm mt-2 font-medium max-w-2xl">
                    Review applications, audit scheduled events/meetings, verify marketing performance proofs, and approve social media ambassador channels.
                </p>
            </div>
        </div>
    </header>

    {{-- ── TABS ── --}}
    <div class="space-y-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex flex-wrap gap-x-6 gap-y-2" aria-label="Tabs">
                {{-- Profile Applications TABS --}}
                <button @click="tab = 'pending'" :class="tab === 'pending' ? 'border-blue-500 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-clock"></i> Profiles Pending 
                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($pending) }}</span>
                </button>
                <button @click="tab = 'confirmed'" :class="tab === 'confirmed' ? 'border-blue-500 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-check-circle"></i> Confirmed
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($confirmed) }}</span>
                </button>
                
                {{-- Dynamic Auditing TABS --}}
                <button @click="tab = 'events'" :class="tab === 'events' ? 'border-blue-500 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-calendar-alt"></i> Events Audit
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($pendingEvents) }}</span>
                </button>
                <button @click="tab = 'proofs'" :class="tab === 'proofs' ? 'border-blue-500 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-camera-retro"></i> Proofs Audit
                    <span class="bg-teal-100 text-teal-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($pendingProofs) }}</span>
                </button>
                <button @click="tab = 'socials'" :class="tab === 'socials' ? 'border-blue-500 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-bullhorn"></i> Ambassadors Audit
                    <span class="bg-pink-100 text-pink-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($pendingSocials) }}</span>
                </button>
                <button @click="tab = 'approvedAmb'" :class="tab === 'approvedAmb' ? 'border-blue-500 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-badge-check"></i> Confirmed Ambassadors
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($approvedSocials) }}</span>
                </button>

                {{-- Other Profiles --}}
                <button @click="tab = 'suspended'" :class="tab === 'suspended' ? 'border-blue-500 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-ban"></i> Suspended
                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($suspended) }}</span>
                </button>
                <button @click="tab = 'rejected'" :class="tab === 'rejected' ? 'border-blue-500 text-blue-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-sm flex items-center gap-2 transition duration-150">
                    <i class="fas fa-times-circle"></i> Rejected
                    <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ count($rejected) }}</span>
                </button>
            </nav>
        </div>

        {{-- ───────────────── PROFILES PENDING TAB ───────────────── --}}
        <div x-show="tab === 'pending'" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            @if($pending->isEmpty())
                <p class="text-gray-500 text-sm py-8 text-center">No pending applications found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Leader Profile</th>
                                <th class="px-6 py-3 text-left">Contact Info</th>
                                <th class="px-6 py-3 text-left">Country</th>
                                <th class="px-6 py-3 text-left">Social Group Links <span class="text-red-500">*</span></th>
                                <th class="px-6 py-3 text-left">Date Applied</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($pending as $leader)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.team-leaders.show', $leader->id) }}" class="font-bold text-gray-900 hover:text-blue-600 transition duration-150">
                                            {{ $leader->Names }} <i class="fas fa-external-link-alt text-[10px] text-gray-400 ml-1"></i>
                                        </a>
                                        <div class="text-xs font-mono text-gray-500 mt-0.5">&#64;{{ $leader->User_name }}</div>
                                        <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ ($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $leader->leadership_level ?? 'TEAM_LEADER' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-700">{{ $leader->Email }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $leader->Phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $leader->Country }}</td>
                                    <td class="px-6 py-4">
                                        @if($leader->whatsapp || $leader->instagram)
                                            <div class="space-y-1.5">
                                                @if($leader->whatsapp)
                                                    <a href="{{ $leader->whatsapp }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs bg-green-50 text-green-700 font-bold px-2.5 py-1 rounded-lg border border-green-200 hover:bg-green-100 transition duration-150">
                                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                                    </a>
                                                @endif
                                                @if($leader->instagram)
                                                    <a href="{{ $leader->instagram }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200 hover:bg-blue-100 transition duration-150">
                                                        <i class="fab fa-telegram"></i> Telegram
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs bg-red-50 text-red-600 font-bold px-2.5 py-1 rounded-lg border border-red-200">
                                                <i class="fas fa-exclamation-triangle"></i> Links Not Submitted
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $leader->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('admin.team-leaders.show', $leader->id) }}" class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                        @if($leader->whatsapp && $leader->instagram)
                                            {{-- Trigger Approval Modal --}}
                                            <button type="button" @click="openModal = '{{ $leader->id }}'" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                        @else
                                            <button type="button" disabled class="bg-gray-300 text-gray-500 font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs cursor-not-allowed" title="Cannot approve until WhatsApp & Telegram links are submitted">
                                                <i class="fas fa-lock mr-1"></i> Approve
                                            </button>
                                        @endif
                                        <form action="{{ route('admin.team-leaders.reject', $leader->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150" onclick="return confirm('Are you sure you want to reject this team leader application?')">
                                                <i class="fas fa-times mr-1"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- ─── Approval Modal (Sleek Flowbite/Tailwind Form) ─── --}}
                                <div x-cloak x-show="openModal === '{{ $leader->id }}'" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                                    <div class="bg-white p-6 rounded-2xl max-w-lg w-full shadow-2xl border border-gray-100 text-left" @click.away="openModal = null">
                                        <h4 class="font-bold text-lg text-slate-800 mb-4 border-b pb-2 flex items-center gap-2">
                                            <i class="fas fa-user-check text-green-600"></i> Approve Team Leader: {{ $leader->Names }}
                                        </h4>
                                        <form action="{{ route('admin.team-leaders.approve', $leader->id) }}" method="POST" class="space-y-4">
                                            @csrf
                                            
                                            {{-- 1. Unique Activation Code --}}
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Unique Activation Code <span class="text-red-500">*</span></label>
                                                @php $randomCode = 'TL-' . strtoupper(\Illuminate\Support\Str::random(8)); @endphp
                                                <input type="text" name="activation_code" required value="{{ $randomCode }}" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-mono font-bold uppercase tracking-wider">
                                                <small class="text-gray-400 text-xs mt-1 block">Default code generated. You can customize this code.</small>
                                            </div>

                                            {{-- 1b. Price --}}
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Price ($) <span class="text-red-500">*</span></label>
                                                <input type="number" name="price" min="0" step="0.01" required value="0" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                                                <small class="text-gray-400 text-xs mt-1 block">Correspondence price for the activation code.</small>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                {{-- 2. Duration --}}
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Duration (Days) <span class="text-red-500">*</span></label>
                                                    <input type="number" name="duration" min="1" required value="60" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                                                    <small class="text-gray-400 text-xs mt-1 block">Default is 60 days.</small>
                                                </div>

                                                {{-- 3. Token Reward Amount --}}
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Token Reward Amount <span class="text-red-500">*</span></label>
                                                    <input type="number" name="tokens" min="0" required value="1000" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                                                    <small class="text-gray-400 text-xs mt-1 block">Amount of tokens to credit upon activation.</small>
                                                </div>
                                            </div>

                                            {{-- 4. Tasks --}}
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Assigned Tasks <span class="text-red-500">*</span></label>
                                                <textarea name="tasks" rows="4" required class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Describe the specific tasks the Team Leader must do..."></textarea>
                                                <small class="text-gray-400 text-xs mt-1 block">Write clear tasks for the team leader to achieve in the timeline.</small>
                                            </div>

                                            {{-- Credits — SUPER LEADER only --}}
                                            @if(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER')
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3">
                                                <label class="block text-xs font-bold text-yellow-800 uppercase mb-1"><i class="fas fa-credit-card mr-1"></i> Credit Amount ($) <span class="text-red-500">*</span></label>
                                                <input type="number" name="credit_amount" min="0" step="0.01" value="0" class="w-full bg-white border border-yellow-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 font-bold">
                                                <small class="text-yellow-600 text-xs mt-1 block">SUPER LEADER credit wallet amount.</small>
                                            </div>
                                            @endif

                                            <div class="flex justify-end gap-3 pt-3 border-t">
                                                <button type="button" @click="openModal = null" class="bg-gray-100 hover:bg-gray-200 text-slate-700 font-bold py-2 px-4 rounded-xl text-xs transition duration-150">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-xl text-xs transition duration-150 shadow-md">
                                                    Confirm Approve &amp; Issue Code
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ───────────────── CONFIRMED LEADERS TAB ───────────────── --}}
        <div x-show="tab === 'confirmed'" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            @if($confirmed->isEmpty())
                <p class="text-gray-500 text-sm py-8 text-center">No confirmed leaders found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Leader Profile</th>
                                <th class="px-6 py-3 text-left">Contact Info</th>
                                <th class="px-6 py-3 text-left">Country</th>
                                <th class="px-6 py-3 text-left">Social Links</th>
                                <th class="px-6 py-3 text-left">Referrals</th>
                                <th class="px-6 py-3 text-left">Duration</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($confirmed as $leader)
                                @php
                                    $cUser = \App\Models\User::where('user', $leader->User_name)->first();
                                    $cReferrals = $cUser ? $cUser->referrals()->count() : 0;
                                    $cActivation = \App\Models\Activations::where('email', $leader->Email)->whereIn('package', ['TEAM_LEADER','SUPER_LEADER'])->first();
                                    $cPeriod = $cActivation ? (int)($cActivation->period ?? 60) : 60;
                                    $cStart = $cActivation ? \Carbon\Carbon::parse($cActivation->updated_at) : null;
                                    $cDaysLeft = $cStart ? max(0, \Carbon\Carbon::now()->diffInDays($cStart->copy()->addDays($cPeriod), false)) : 0;
                                    $cExpired = $cStart ? \Carbon\Carbon::now()->greaterThan($cStart->copy()->addDays($cPeriod)) : false;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.team-leaders.show', $leader->id) }}" class="font-bold text-gray-900 hover:text-blue-600 transition duration-150">
                                            {{ $leader->Names }} <i class="fas fa-external-link-alt text-[10px] text-gray-400 ml-1"></i>
                                        </a>
                                        <div class="text-xs font-mono text-gray-500 mt-0.5">&#64;{{ $leader->User_name }}</div>
                                        <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ ($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $leader->leadership_level ?? 'TEAM_LEADER' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-700">{{ $leader->Email }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $leader->Phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $leader->Country }}</td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1.5">
                                            @if($leader->whatsapp)
                                                <a href="{{ $leader->whatsapp }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs bg-green-50 text-green-700 font-bold px-2.5 py-1 rounded-lg border border-green-200 hover:bg-green-100 transition duration-150">
                                                    <i class="fab fa-whatsapp"></i> WA
                                                </a>
                                            @endif
                                            @if($leader->instagram)
                                                <a href="{{ $leader->instagram }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200 hover:bg-blue-100 transition duration-150">
                                                    <i class="fab fa-telegram"></i> TG
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block bg-green-100 text-green-800 font-extrabold text-sm px-3 py-1 rounded-full">{{ $cReferrals }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($cExpired)
                                            <span class="inline-block bg-red-100 text-red-700 font-bold text-xs px-2.5 py-1 rounded-full">EXPIRED</span>
                                        @else
                                            <span class="inline-block bg-green-100 text-green-700 font-bold text-xs px-2.5 py-1 rounded-full">{{ $cDaysLeft }}d left</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('admin.team-leaders.show', $leader->id) }}" class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                        @if(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER')
                                            <a href="{{ route('admin.team-leaders.show', $leader->id) }}#credit-panel" class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                                <i class="fas fa-credit-card mr-1"></i> Credits
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.team-leaders.suspend', $leader->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150" onclick="return confirm('Are you sure you want to suspend this team leader account?')">
                                                <i class="fas fa-pause mr-1"></i> Suspend
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ───────────────── EVENTS AUDIT TAB (Page 1) ───────────────── --}}
        <div x-show="tab === 'events'" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            @if($pendingEvents->isEmpty())
                <p class="text-gray-500 text-sm py-8 text-center">No pending events / Zoom meetings waiting for approval.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Team Leader</th>
                                <th class="px-6 py-3 text-left">Event Title</th>
                                <th class="px-6 py-3 text-left">Type &amp; Destination</th>
                                <th class="px-6 py-3 text-left">Scheduled Time</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($pendingEvents as $event)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $event->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500 font-mono">&#64;{{ $event->user->user ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-700">{{ $event->title }}</td>
                                    <td class="px-6 py-4">
                                        @if($event->type === 'zoom')
                                            <span class="badge bg-blue-100 text-blue-800 font-bold px-2.5 py-1 text-xs uppercase">Zoom</span>
                                            <small class="text-xs text-info d-block mt-1 font-mono truncate max-w-[200px]"><a href="{{ $event->zoom_link }}" target="_blank">{{ $event->zoom_link }}</a></small>
                                        @else
                                            <span class="badge bg-indigo-100 text-indigo-800 font-bold px-2.5 py-1 text-xs uppercase">Physical</span>
                                            <small class="text-xs text-gray-500 d-block mt-1 truncate max-w-[200px]">{{ $event->location }}</small>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-600 text-xs">{{ $event->event_time->format('d M Y, h:i A') }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button type="button" @click="openEvent = '{{ $event->id }}'" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                            <i class="fas fa-info-circle mr-1"></i> Details
                                        </button>
                                        <form action="{{ route('admin.team-leaders.events.approve', $event->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.team-leaders.events.reject', $event->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150" onclick="return confirm('Are you sure you want to reject this event planning?')">
                                                <i class="fas fa-times mr-1"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- ─── Event Full Details Modal ─── --}}
                                <div x-cloak x-show="openEvent === '{{ $event->id }}'" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="display:none;">
                                    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto" @click.away="openEvent = null">
                                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between sticky top-0 z-10">
                                            <h4 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                                                <i class="fas fa-calendar-check text-indigo-600"></i> Event Full Details
                                            </h4>
                                            <button type="button" @click="openEvent = null" class="text-gray-400 hover:text-gray-700">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>

                                        <div class="p-6 space-y-5">
                                            @php
                                                $evLeader = $event->user ? \App\Models\TeamLeader::where('User_name', $event->user->user)->first() : null;
                                                $evImgs = [];
                                                if (!empty($event->event_image_1)) { $evImgs[] = $event->event_image_1; }
                                                if (!empty($event->event_image_2)) {
                                                    foreach (explode(',', $event->event_image_2) as $im) {
                                                        $im = trim($im);
                                                        if ($im !== '') { $evImgs[] = $im; }
                                                    }
                                                }
                                            @endphp

                                            {{-- Leader --}}
                                            <div class="flex items-center gap-3 bg-indigo-50 rounded-xl p-3 border border-indigo-100">
                                                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    @if($evLeader)
                                                        <a href="{{ route('admin.team-leaders.show', $evLeader->id) }}" class="font-bold text-gray-900 hover:text-indigo-600">{{ $event->user->name ?? 'Unknown Leader' }}</a>
                                                    @else
                                                        <span class="font-bold text-gray-900">{{ $event->user->name ?? 'Unknown Leader' }}</span>
                                                    @endif
                                                    <div class="text-xs text-gray-500 font-mono">&#64;{{ $event->user->user ?? '—' }}</div>
                                                </div>
                                            </div>

                                            {{-- Field grid --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Event Title</p>
                                                    <p class="font-bold text-slate-800">{{ $event->title ?? '—' }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Category</p>
                                                    <p class="font-bold text-slate-800 uppercase">{{ $event->event_type ?? $event->type ?? '—' }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Meeting Type</p>
                                                    <p class="font-bold {{ $event->type === 'zoom' ? 'text-blue-700' : 'text-indigo-700' }}">{{ ucfirst($event->type ?? '—') }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Approval Status</p>
                                                    <p class="font-bold">{{ ucfirst($event->status ?? '—') }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Scheduled Time</p>
                                                    <p class="font-bold text-slate-800">{{ $event->event_time ? $event->event_time->format('d M Y, h:i A') : '—' }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Event Done On</p>
                                                    <p class="font-bold text-slate-800">{{ $event->event_done_on ? $event->event_done_on->format('d M Y') : '—' }}</p>
                                                </div>
                                            </div>

                                            {{-- Destination / venue details --}}
                                            @if($event->type === 'zoom')
                                                <div class="bg-blue-50 rounded-xl p-3 border border-blue-100 space-y-1 text-sm">
                                                    <p class="text-[10px] text-blue-400 font-bold uppercase mb-1"><i class="fas fa-video mr-1"></i> Zoom Details</p>
                                                    @if($event->zoom_link)
                                                        <p><span class="text-gray-500">Link:</span> <a href="{{ $event->zoom_link }}" target="_blank" rel="noopener" class="text-blue-700 font-bold break-all">{{ $event->zoom_link }}</a></p>
                                                    @endif
                                                    <p><span class="text-gray-500">Country:</span> <span class="font-semibold text-slate-700">{{ $event->country ?? '—' }}</span></p>
                                                    <p><span class="text-gray-500">Place:</span> <span class="font-semibold text-slate-700">{{ $event->place ?? '—' }}</span></p>
                                                    @if($event->event_date)<p><span class="text-gray-500">Date:</span> <span class="font-semibold text-slate-700">{{ $event->event_date->format('d M Y') }}</span></p>@endif
                                                </div>
                                            @else
                                                <div class="bg-indigo-50 rounded-xl p-3 border border-indigo-100 space-y-1 text-sm">
                                                    <p class="text-[10px] text-indigo-400 font-bold uppercase mb-1"><i class="fas fa-map-marker-alt mr-1"></i> Venue Details</p>
                                                    <p><span class="text-gray-500">Location:</span> <span class="font-semibold text-slate-700">{{ $event->location ?? '—' }}</span></p>
                                                    <p><span class="text-gray-500">Hotel / Venue:</span> <span class="font-semibold text-slate-700">{{ $event->hotel_location ?? '—' }}</span></p>
                                                </div>
                                            @endif

                                            {{-- Uploaded event photos --}}
                                            @if(!empty($evImgs))
                                                <div>
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-2"><i class="fas fa-images mr-1"></i> Uploaded Photos</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($evImgs as $im)
                                                            <a href="{{ asset('storage/' . $im) }}" target="_blank" rel="noopener">
                                                                <img src="{{ asset('storage/' . $im) }}" class="w-24 h-20 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Proof summary --}}
                                            <div class="bg-gray-50 rounded-xl p-3 border text-sm">
                                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Proof Status</p>
                                                <p>
                                                    Submitted:
                                                    @if($event->proof_submitted)
                                                        <span class="text-green-700 font-bold">Yes</span>
                                                    @else
                                                        <span class="text-gray-500 font-bold">No</span>
                                                    @endif
                                                    &middot; Status: <span class="font-bold">{{ ucfirst($event->proof_status ?? '—') }}</span>
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Footer actions --}}
                                        <div class="px-6 py-4 border-t flex flex-wrap justify-end items-center gap-2 bg-gray-50 sticky bottom-0">
                                            <button type="button" @click="openEvent = null" class="bg-gray-200 hover:bg-gray-300 text-slate-700 font-bold py-2 px-4 rounded-xl text-xs transition duration-150">
                                                Close
                                            </button>
                                            <form action="{{ route('admin.team-leaders.events.reject', $event->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition duration-150" onclick="return confirm('Reject this event?')">
                                                    <i class="fas fa-times mr-1"></i> Reject
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.team-leaders.events.approve', $event->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition duration-150">
                                                    <i class="fas fa-check mr-1"></i> Approve
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ───────────────── PROOFS AUDIT TAB (Page 2) ───────────────── --}}
        <div x-show="tab === 'proofs'" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            @if($pendingProofs->isEmpty())
                <p class="text-gray-500 text-sm py-8 text-center">No pending performance proofs waiting for audit.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Team Leader</th>
                                <th class="px-6 py-3 text-left">Meeting / Event Title</th>
                                <th class="px-6 py-3 text-left">Uploaded Attachment</th>
                                <th class="px-6 py-3 text-left">Performance Notes</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($pendingProofs as $event)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $event->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500 font-mono">&#64;{{ $event->user->user ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-700">{{ $event->title }}</td>
                                    <td class="px-6 py-4">
                                        @if($event->proof_files)
                                            <a href="{{ asset('storage/' . $event->proof_files) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 font-bold px-3 py-1.5 rounded-xl border border-blue-200 hover:bg-blue-100 transition duration-150">
                                                <i class="fas fa-file-download"></i> View Proof Asset
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No attachment file</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-600 text-xs max-w-[200px] truncate" title="{{ $event->proof_notes }}">{{ $event->proof_notes }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button type="button" @click="openProof = '{{ $event->id }}'" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                            <i class="fas fa-info-circle mr-1"></i> Details
                                        </button>
                                        <form action="{{ route('admin.team-leaders.proofs.approve', $event->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.team-leaders.proofs.reject', $event->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150" onclick="return confirm('Are you sure you want to reject this event proof?')">
                                                <i class="fas fa-times mr-1"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- ─── Proof Full Details Modal ─── --}}
                                <div x-cloak x-show="openProof === '{{ $event->id }}'" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="display:none;">
                                    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto" @click.away="openProof = null">
                                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between sticky top-0 z-10">
                                            <h4 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                                                <i class="fas fa-camera-retro text-teal-600"></i> Proof Full Details
                                            </h4>
                                            <button type="button" @click="openProof = null" class="text-gray-400 hover:text-gray-700">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>

                                        <div class="p-6 space-y-5">
                                            @php
                                                $prLeader = $event->user ? \App\Models\TeamLeader::where('User_name', $event->user->user)->first() : null;
                                                $prImgs = [];
                                                if (!empty($event->event_image_1)) { $prImgs[] = $event->event_image_1; }
                                                if (!empty($event->event_image_2)) {
                                                    foreach (explode(',', $event->event_image_2) as $im) {
                                                        $im = trim($im);
                                                        if ($im !== '') { $prImgs[] = $im; }
                                                    }
                                                }
                                            @endphp

                                            {{-- Leader --}}
                                            <div class="flex items-center gap-3 bg-teal-50 rounded-xl p-3 border border-teal-100">
                                                <div class="w-10 h-10 bg-teal-600 text-white rounded-full flex items-center justify-center font-bold">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    @if($prLeader)
                                                        <a href="{{ route('admin.team-leaders.show', $prLeader->id) }}" class="font-bold text-gray-900 hover:text-teal-600">{{ $event->user->name ?? 'Unknown Leader' }}</a>
                                                    @else
                                                        <span class="font-bold text-gray-900">{{ $event->user->name ?? 'Unknown Leader' }}</span>
                                                    @endif
                                                    <div class="text-xs text-gray-500 font-mono">&#64;{{ $event->user->user ?? '—' }}</div>
                                                </div>
                                            </div>

                                            {{-- Field grid --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Meeting / Event Title</p>
                                                    <p class="font-bold text-slate-800">{{ $event->title ?? '—' }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Category</p>
                                                    <p class="font-bold text-slate-800 uppercase">{{ $event->event_type ?? $event->type ?? '—' }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Proof Submitted</p>
                                                    <p class="font-bold {{ $event->proof_submitted ? 'text-green-700' : 'text-gray-500' }}">{{ $event->proof_submitted ? 'Yes' : 'No' }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Audit Status</p>
                                                    <p class="font-bold">{{ ucfirst($event->proof_status ?? '—') }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Event Done On</p>
                                                    <p class="font-bold text-slate-800">{{ $event->event_done_on ? $event->event_done_on->format('d M Y') : '—' }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-xl p-3 border">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Scheduled Time</p>
                                                    <p class="font-bold text-slate-800">{{ $event->event_time ? $event->event_time->format('d M Y, h:i A') : '—' }}</p>
                                                </div>
                                            </div>

                                            {{-- Hotel / venue --}}
                                            @if($event->hotel_location)
                                                <div class="bg-indigo-50 rounded-xl p-3 border border-indigo-100 text-sm">
                                                    <p class="text-[10px] text-indigo-400 font-bold uppercase mb-1"><i class="fas fa-map-marker-alt mr-1"></i> Hotel / Venue</p>
                                                    <p class="font-semibold text-slate-700">{{ $event->hotel_location }}</p>
                                                </div>
                                            @endif

                                            {{-- Performance notes (full text) --}}
                                            <div class="bg-gray-50 rounded-xl p-3 border text-sm">
                                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1"><i class="fas fa-align-left mr-1"></i> Performance Notes</p>
                                                <p class="text-slate-700 whitespace-pre-wrap">{{ $event->proof_notes ?: 'No notes provided.' }}</p>
                                            </div>

                                            {{-- Proof attachment file(s) --}}
                                            @php
                                                $proofAttach = !empty($event->proof_files) ? array_filter(array_map('trim', explode(',', $event->proof_files))) : [];
                                            @endphp
                                            <div class="bg-blue-50 rounded-xl p-3 border border-blue-100 text-sm">
                                                <p class="text-[10px] text-blue-400 font-bold uppercase mb-2"><i class="fas fa-paperclip mr-1"></i> Proof Attachment(s) — {{ count($proofAttach) }} file(s)</p>
                                                @if(!empty($proofAttach))
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($proofAttach as $pa)
                                                            @php
                                                                $paUrl = asset('storage/'.$pa);
                                                                $paExt = strtolower(pathinfo($pa, PATHINFO_EXTENSION));
                                                                $paIsImg = in_array($paExt, ['jpg','jpeg','png','gif','webp','bmp']);
                                                            @endphp
                                                            @if($paIsImg)
                                                                <a href="{{ $paUrl }}" target="_blank" rel="noopener">
                                                                    <img src="{{ $paUrl }}" class="w-20 h-16 object-cover rounded-lg border border-blue-200 hover:opacity-80 transition">
                                                                </a>
                                                            @else
                                                                <a href="{{ $paUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs bg-white text-blue-700 font-bold px-3 py-1.5 rounded-lg border border-blue-200 hover:bg-blue-100 transition duration-150">
                                                                    <i class="fas fa-file-download"></i> {{ strtoupper($paExt) }}
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">No attachment file uploaded.</span>
                                                @endif
                                            </div>

                                            {{-- Uploaded event photos --}}
                                            @if(!empty($prImgs))
                                                <div>
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-2"><i class="fas fa-images mr-1"></i> Uploaded Photos</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($prImgs as $im)
                                                            <a href="{{ asset('storage/' . $im) }}" target="_blank" rel="noopener">
                                                                <img src="{{ asset('storage/' . $im) }}" class="w-24 h-20 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Footer actions --}}
                                        <div class="px-6 py-4 border-t flex flex-wrap justify-end items-center gap-2 bg-gray-50 sticky bottom-0">
                                            <button type="button" @click="openProof = null" class="bg-gray-200 hover:bg-gray-300 text-slate-700 font-bold py-2 px-4 rounded-xl text-xs transition duration-150">
                                                Close
                                            </button>
                                            <form action="{{ route('admin.team-leaders.proofs.reject', $event->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition duration-150" onclick="return confirm('Reject this proof?')">
                                                    <i class="fas fa-times mr-1"></i> Reject
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.team-leaders.proofs.approve', $event->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition duration-150">
                                                    <i class="fas fa-check mr-1"></i> Approve
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ───────────────── AMBASSADORS AUDIT TAB (Page 3) ───────────────── --}}
        <div x-show="tab === 'socials'" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            @if($pendingSocials->isEmpty())
                <p class="text-gray-500 text-sm py-8 text-center">No pending ambassador profiles waiting for audit.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Team Leader</th>
                                <th class="px-6 py-3 text-left">Platform</th>
                                <th class="px-6 py-3 text-left">Ambassador Profile Link</th>
                                <th class="px-6 py-3 text-right">Avg Impressions</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($pendingSocials as $social)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $social->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500 font-mono">&#64;{{ $social->user->user ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="badge bg-pink-100 text-pink-800 font-bold px-2.5 py-1 text-xs text-uppercase border border-pink-200">
                                            {{ $social->platform }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-info text-xs font-mono"><a href="{{ $social->profile_link }}" target="_blank">{{ $social->profile_link }}</a></td>
                                    <td class="px-6 py-4 text-right font-extrabold text-slate-700">{{ number_format($social->views_count, 0) }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <form action="{{ route('admin.team-leaders.socials.approve', $social->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.team-leaders.socials.reject', $social->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150" onclick="return confirm('Are you sure you want to reject this social channel?')">
                                                <i class="fas fa-times mr-1"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ───────────────── CONFIRMED AMBASSADORS TAB ───────────────── --}}
        <div x-show="tab === 'approvedAmb'" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            @if($approvedSocials->isEmpty())
                <p class="text-gray-500 text-sm py-8 text-center">No confirmed ambassadors yet. Approved applications will appear here.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Team Leader</th>
                                <th class="px-6 py-3 text-left">Platform</th>
                                <th class="px-6 py-3 text-left">Ambassador Profile Link</th>
                                <th class="px-6 py-3 text-right">Avg Impressions</th>
                                <th class="px-6 py-3 text-left">Confirmed On</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($approvedSocials as $social)
                                @php
                                    $ambLeader = $social->user ? \App\Models\TeamLeader::where('User_name', $social->user->user)->first() : null;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        @if($ambLeader)
                                            <a href="{{ route('admin.team-leaders.show', $ambLeader->id) }}" class="font-bold text-gray-900 hover:text-blue-600 transition duration-150">
                                                {{ $social->user->name ?? 'Unknown' }} <i class="fas fa-external-link-alt text-[10px] text-gray-400 ml-1"></i>
                                            </a>
                                        @else
                                            <div class="font-bold text-gray-900">{{ $social->user->name ?? 'Unknown' }}</div>
                                        @endif
                                        <div class="text-xs text-gray-500 font-mono">&#64;{{ $social->user->user ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="badge bg-green-100 text-green-800 font-bold px-2.5 py-1 text-xs uppercase border border-green-200">
                                            {{ $social->platform }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono"><a href="{{ $social->profile_link }}" target="_blank" rel="noopener" class="text-blue-700 font-bold hover:underline break-all">{{ $social->profile_link }}</a></td>
                                    <td class="px-6 py-4 text-right font-extrabold text-slate-700">{{ number_format($social->views_count, 0) }}</td>
                                    <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $social->updated_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        @if($ambLeader)
                                            <a href="{{ route('admin.team-leaders.show', $ambLeader->id) }}" class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                                <i class="fas fa-eye mr-1"></i> View Leader
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ───────────────── SUSPENDED TAB ───────────────── --}}
        <div x-show="tab === 'suspended'" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            @if($suspended->isEmpty())
                <p class="text-gray-500 text-sm py-8 text-center">No suspended leaders found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Leader Profile</th>
                                <th class="px-6 py-3 text-left">Contact Info</th>
                                <th class="px-6 py-3 text-left">Country</th>
                                <th class="px-6 py-3 text-left">Social Group Links</th>
                                <th class="px-6 py-3 text-left">Date Suspended</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($suspended as $leader)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $leader->Names }}</div>
                                        <div class="text-xs font-mono text-gray-500 mt-0.5">&#64;{{ $leader->User_name }}</div>
                                        <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ ($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $leader->leadership_level ?? 'TEAM_LEADER' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-700">{{ $leader->Email }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $leader->Phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $leader->Country }}</td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1.5">
                                            @if($leader->whatsapp)
                                                <a href="{{ $leader->whatsapp }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs bg-green-50 text-green-700 font-bold px-2.5 py-1 rounded-lg border border-green-200 hover:bg-green-100 transition duration-150">
                                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                                </a>
                                            @endif
                                            @if($leader->instagram)
                                                <a href="{{ $leader->instagram }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200 hover:bg-blue-100 transition duration-150">
                                                    <i class="fab fa-telegram"></i> Telegram
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $leader->updated_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.team-leaders.reactivate', $leader->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                                <i class="fas fa-play mr-1"></i> Reactivate
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ───────────────── REJECTED TAB ───────────────── --}}
        <div x-show="tab === 'rejected'" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
            @if($rejected->isEmpty())
                <p class="text-gray-500 text-sm py-8 text-center">No rejected applications found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Leader Profile</th>
                                <th class="px-6 py-3 text-left">Contact Info</th>
                                <th class="px-6 py-3 text-left">Country</th>
                                <th class="px-6 py-3 text-left">Social Group Links</th>
                                <th class="px-6 py-3 text-left">Date Rejected</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($rejected as $leader)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $leader->Names }}</div>
                                        <div class="text-xs font-mono text-gray-500 mt-0.5">&#64;{{ $leader->User_name }}</div>
                                        <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ ($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $leader->leadership_level ?? 'TEAM_LEADER' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-700">{{ $leader->Email }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $leader->Phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $leader->Country }}</td>
                                    <td class="px-6 py-4">
                                        @if($leader->whatsapp || $leader->instagram)
                                            <div class="space-y-1.5">
                                                @if($leader->whatsapp)
                                                    <a href="{{ $leader->whatsapp }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs bg-green-50 text-green-700 font-bold px-2.5 py-1 rounded-lg border border-green-200 hover:bg-green-100 transition duration-150">
                                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                                    </a>
                                                @endif
                                                @if($leader->instagram)
                                                    <a href="{{ $leader->instagram }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg border border-blue-200 hover:bg-blue-100 transition duration-150">
                                                        <i class="fab fa-telegram"></i> Telegram
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs bg-red-50 text-red-600 font-bold px-2.5 py-1 rounded-lg border border-red-200">
                                                <i class="fas fa-exclamation-triangle"></i> Links Not Submitted
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $leader->updated_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if($leader->whatsapp && $leader->instagram)
                                            <button type="button" @click="openModal = '{{ $leader->id }}'" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                                <i class="fas fa-undo mr-1"></i> Re-Approve
                                            </button>
                                        @else
                                            <button type="button" disabled class="bg-gray-300 text-gray-500 font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs cursor-not-allowed" title="Cannot re-approve until WhatsApp & Telegram links are submitted">
                                                <i class="fas fa-lock mr-1"></i> Re-Approve
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Approval Modal for Rejected Applications --}}
                                <div x-cloak x-show="openModal === '{{ $leader->id }}'" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                                    <div class="bg-white p-6 rounded-2xl max-w-lg w-full shadow-2xl border border-gray-100 text-left" @click.away="openModal = null">
                                        <h4 class="font-bold text-lg text-slate-800 mb-4 border-b pb-2 flex items-center gap-2">
                                            <i class="fas fa-user-check text-green-600"></i> Approve Team Leader: {{ $leader->Names }}
                                        </h4>
                                        <form action="{{ route('admin.team-leaders.approve', $leader->id) }}" method="POST" class="space-y-4">
                                            @csrf
                                            
                                            {{-- 1. Unique Activation Code --}}
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Unique Activation Code <span class="text-red-500">*</span></label>
                                                @php $randomCode = 'TL-' . strtoupper(\Illuminate\Support\Str::random(8)); @endphp
                                                <input type="text" name="activation_code" required value="{{ $randomCode }}" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-mono font-bold uppercase tracking-wider">
                                                <small class="text-gray-400 text-xs mt-1 block">Default code generated. You can customize this code.</small>
                                            </div>

                                            {{-- 1b. Price --}}
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Price ($) <span class="text-red-500">*</span></label>
                                                <input type="number" name="price" min="0" step="0.01" required value="0" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                                                <small class="text-gray-400 text-xs mt-1 block">Correspondence price for the activation code.</small>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                {{-- 2. Duration --}}
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Duration (Days) <span class="text-red-500">*</span></label>
                                                    <input type="number" name="duration" min="1" required value="60" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                                                    <small class="text-gray-400 text-xs mt-1 block">Default is 60 days.</small>
                                                </div>

                                                {{-- 3. Token Reward Amount --}}
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Token Reward Amount <span class="text-red-500">*</span></label>
                                                    <input type="number" name="tokens" min="0" required value="1000" class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold">
                                                    <small class="text-gray-400 text-xs mt-1 block">Amount of tokens to credit upon activation.</small>
                                                </div>
                                            </div>

                                            {{-- 4. Tasks --}}
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Assigned Tasks <span class="text-red-500">*</span></label>
                                                <textarea name="tasks" rows="4" required class="w-full bg-gray-50 border border-gray-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Describe the specific tasks the Team Leader must do..."></textarea>
                                                <small class="text-gray-400 text-xs mt-1 block">Write clear tasks for the team leader to achieve in the timeline.</small>
                                            </div>

                                            {{-- Credits — SUPER LEADER only --}}
                                            @if(($leader->leadership_level ?? 'TEAM_LEADER') === 'SUPER_LEADER')
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3">
                                                <label class="block text-xs font-bold text-yellow-800 uppercase mb-1"><i class="fas fa-credit-card mr-1"></i> Credit Amount ($) <span class="text-red-500">*</span></label>
                                                <input type="number" name="credit_amount" min="0" step="0.01" value="0" class="w-full bg-white border border-yellow-300 text-slate-900 rounded-xl p-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 font-bold">
                                                <small class="text-yellow-600 text-xs mt-1 block">SUPER LEADER credit wallet amount.</small>
                                            </div>
                                            @endif

                                            <div class="flex justify-end gap-3 pt-3 border-t">
                                                <button type="button" @click="openModal = null" class="bg-gray-100 hover:bg-gray-200 text-slate-700 font-bold py-2 px-4 rounded-xl text-xs transition duration-150">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-xl text-xs transition duration-150 shadow-md">
                                                    Confirm Approve &amp; Issue Code
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
