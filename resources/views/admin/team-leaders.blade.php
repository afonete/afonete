@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl" x-data="{ tab: 'pending', openModal: null }">

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
                                <th class="px-6 py-3 text-left">Date Applied</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($pending as $leader)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $leader->Names }}</div>
                                        <div class="text-xs font-mono text-gray-500 mt-0.5">&#64;{{ $leader->User_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-700">{{ $leader->Email }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $leader->Phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $leader->Country }}</td>
                                    <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $leader->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        {{-- Trigger Approval Modal --}}
                                        <button type="button" @click="openModal = '{{ $leader->id }}'" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                            <i class="fas fa-check mr-1"></i> Approve
                                        </button>
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
                                <th class="px-6 py-3 text-left">Date Confirmed</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($confirmed as $leader)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $leader->Names }}</div>
                                        <div class="text-xs font-mono text-gray-500 mt-0.5">&#64;{{ $leader->User_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-700">{{ $leader->Email }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $leader->Phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $leader->Country }}</td>
                                    <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $leader->updated_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-right">
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
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-700">{{ $leader->Email }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $leader->Phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $leader->Country }}</td>
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
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-700">{{ $leader->Email }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $leader->Phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $leader->Country }}</td>
                                    <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $leader->updated_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        {{-- Trigger Approval Modal --}}
                                        <button type="button" @click="openModal = '{{ $leader->id }}'" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs transition duration-150">
                                            <i class="fas fa-undo mr-1"></i> Re-Approve
                                        </button>
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
