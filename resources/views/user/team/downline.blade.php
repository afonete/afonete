{{-- pkl-v1 (§76 FOM-aware package labels + navbar) --}}
<div class="wrapper">
    @include('user.user-dashboard-base')
    <title>Overview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="content-wrapper bg-slate-50 min-h-screen py-4 px-4">
        <div class="container-fluid max-w-7xl mx-auto flex flex-col gap-6">

            {{-- Navigation Bar Component (§76 — same navbar as team-structure) --}}
            <x-navbar/>

            <!-- Stats Overview Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Team Members Card: Total Direct & Indirect Referrals -->
                <div class="bg-slate-800 text-white shadow-md rounded-xl p-4 border border-slate-700 col-span-1">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Team Members</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-extrabold text-white">{{ $team_members }}</p>
                            <small class="text-slate-400 text-[11px] font-medium block mt-1">Direct &amp; Indirect Referral</small>
                        </div>
                        <div class="mr-1">
                            <i class="fa fa-users text-slate-400" style="font-size: 2.2em"></i>
                        </div>
                    </div>
                </div>

                <!-- Person Members Card: Total Network Investment -->
                <div class="bg-white shadow-md rounded-xl p-4 border border-slate-200 col-span-1">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-2">Network Investment</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-extrabold text-slate-900">{{ $person_members }}</p>
                            <small class="text-slate-500 text-[11px] font-medium block mt-1">Network Investment (Direct &amp; Indirect)</small>
                        </div>
                        <div class="mr-1">
                            <i class="fa fa-chart-line text-emerald-600" style="font-size: 2.2em"></i>
                        </div>
                    </div>
                </div>

                <!-- Referrals Navigation Links -->
                <div class="bg-slate-900 text-white shadow-md rounded-xl p-4 col-span-1 sm:col-span-2 lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3 items-center border border-slate-800">
                    <a href="{{ route('referrals.free') }}" class="flex items-center justify-between bg-slate-800 hover:bg-slate-700 text-white p-3 rounded-lg font-bold text-xs transition border border-slate-700">
                        <div>
                            <span class="block text-amber-400 uppercase font-extrabold">Free Referrals</span>
                            <span class="text-[11px] text-slate-300 font-normal">View Free Accounts</span>
                        </div>
                        <i class="fa fa-arrow-right text-amber-400 text-sm"></i>
                    </a>

                    <a href="{{ route('referrals.paid') }}" class="flex items-center justify-between bg-slate-800 hover:bg-slate-700 text-white p-3 rounded-lg font-bold text-xs transition border border-slate-700">
                        <div>
                            <span class="block text-emerald-400 uppercase font-extrabold">Paid Referrals</span>
                            <span class="text-[11px] text-slate-300 font-normal">View Active Packages</span>
                        </div>
                        <i class="fa fa-arrow-right text-emerald-400 text-sm"></i>
                    </a>
                </div>

                <!-- Person Customers Card: Total Direct Referral Count -->
                <div class="bg-white shadow-md rounded-xl p-4 border border-slate-200 col-span-1">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-2">Person Customers</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-extrabold text-slate-900">{{ $person_customers }}</p>
                            <small class="text-slate-500 text-[11px] font-medium block mt-1">Total Direct Referral</small>
                        </div>
                        <div class="mr-1">
                            <i class="fa fa-user-friends text-blue-600" style="font-size: 2.2em"></i>
                        </div>
                    </div>
                </div>

                <!-- Merchants Card: Total Direct Referral Investment -->
                <div class="bg-white shadow-md rounded-xl p-4 border border-slate-200 col-span-1">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 mb-2">Direct Investment</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-extrabold text-slate-900">{{ $merchants }}</p>
                            <small class="text-slate-500 text-[11px] font-medium block mt-1">Total Direct Ref Investment</small>
                        </div>
                        <div class="mr-1">
                            <i class="fa fa-wallet text-amber-500" style="font-size: 2.2em"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Information Summary Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Total Members Card --}}
                <div class="bg-white shadow-md rounded-xl p-4 flex items-center gap-4 border border-slate-200">
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold uppercase text-slate-500 tracking-wider">TOTAL</h4>
                        <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $total_members }}</div>
                        <small class="text-slate-500 text-xs">Total Referral (Direct &amp; Indirect)</small>
                    </div>
                </div>

                {{-- Left Team Card --}}
                <div class="bg-white shadow-md rounded-xl p-4 flex items-center gap-4 border border-slate-200">
                    <div class="p-3 bg-sky-50 rounded-xl text-sky-600">
                        <i class="fas fa-arrow-left text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold uppercase text-slate-500 tracking-wider">LEFT TEAM</h4>
                        <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $left_team }}</div>
                        <small class="text-slate-500 text-xs">Total Referral on Left Side (Direct &amp; Indirect)</small>
                    </div>
                </div>

                {{-- Right Team Card --}}
                <div class="bg-white shadow-md rounded-xl p-4 flex items-center gap-4 border border-slate-200">
                    <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                        <i class="fas fa-arrow-right text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold uppercase text-slate-500 tracking-wider">RIGHT TEAM</h4>
                        <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $right_team }}</div>
                        <small class="text-slate-500 text-xs">Total Referral on Right Side (Direct &amp; Indirect)</small>
                    </div>
                </div>
            </div>

            <!-- Team Members Table -->
            <div class="bg-white shadow-md rounded-xl p-6 overflow-x-auto border border-slate-200">
                <header class="mb-4 pb-3 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h4 class="text-slate-900 font-extrabold text-lg flex items-center gap-2">
                            <i class="fas fa-users text-indigo-600"></i> TEAM MEMBERS
                        </h4>
                        <p class="text-xs text-slate-500 mt-0.5">Complete list of all direct and indirect referrals in your downline network.</p>
                    </div>
                    <span class="bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-full">
                        Total: {{ count($members) }}
                    </span>
                </header>

                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-xs border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Username</th>
                            <th class="py-3 px-4">Activation Date</th>
                            <th class="py-3 px-4">Package</th>
                            <th class="py-3 px-4">Country</th>
                            <th class="py-3 px-4">Signup Date</th>
                            <th class="py-3 px-4">Leadership Rank</th>
                            <th class="py-3 px-4">Sponsored By</th>
                            <th class="py-3 px-4">Team Side</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($members as $member)
                            @php
                                $side = $member->teamSide ? $member->teamSide->side : null;
                                if (!$side) {
                                    $side = $member->referee_id == Auth::id() ? 'DIRECT' : 'INDIRECT';
                                }
                                $hasPkg = $member->hasAnyPackage(); // §76: FOM-aware (Royal promos / legacy FOM won't show FREE)
                                $rank = $member->currentRank();
                                $sponsor = $member->referrer;
                                $activationObj = $member->have_activation_code;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-400 text-xs">{{ $loop->iteration + ($members->currentPage() - 1) * $members->perPage() }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    @ {{ $member->user }}
                                </td>
                                <td class="py-3.5 px-4 text-xs font-medium">
                                    @if($hasPkg && $activationObj && $activationObj->created_at)
                                        <span class="text-slate-800 font-semibold">{{ \Carbon\Carbon::parse($activationObj->created_at)->format('d M Y') }}</span>
                                    @elseif($hasPkg)
                                        <span class="text-slate-800 font-semibold">{{ $member->created_at ? $member->created_at->format('d M Y') : 'Active' }}</span>
                                    @else
                                        <span class="text-slate-400 italic">Not Activated Yet</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    <span class="px-2.5 py-1 font-extrabold rounded text-[11px] {{ $hasPkg ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $member->packageLabel() }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs font-medium text-slate-700">{{ $member->country ?: 'N/A' }}</td>
                                <td class="py-3.5 px-4 text-xs text-slate-500">{{ $member->created_at ? $member->created_at->format('d M Y') : 'N/A' }}</td>
                                <td class="py-3.5 px-4 text-xs font-bold text-amber-600">
                                    {{ $rank ? $rank->rank_name : 'No Rank' }}
                                </td>
                                <td class="py-3.5 px-4 text-xs font-bold text-indigo-600">
                                    {{ $sponsor ? '@' . $sponsor->user : 'Direct' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 text-[11px] font-extrabold rounded {{ $side === 'LEFT' ? 'bg-sky-600 text-white' : ($side === 'RIGHT' ? 'bg-indigo-600 text-white' : 'bg-slate-600 text-white') }}">
                                        {{ $side }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-slate-400">
                                    <i class="fas fa-users text-3xl mb-2 text-slate-300 block"></i>
                                    No team members in your downline network yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($members->hasPages())
                <div class="p-3 d-flex justify-content-center border-t border-slate-200">
                    {{ $members->links('pagination::bootstrap-4') }}
                </div>
            @endif

        </div>
    </div>
</div>
