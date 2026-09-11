@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    @if(session('message'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200 font-semibold">
            <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 font-semibold">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Page header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between flex-wrap gap-3"
         style="border-left: 5px solid #d4af37;">
        <div>
            <h1 class="text-xl font-bold text-slate-900">
                <i class="fas fa-crown text-yellow-500 mr-2"></i>FC VIP Streamline Ranks
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Review pending rank completions, monitor active 65-day challenges, and credit rewards ($cash + AVAILABLE_TOKEN).
                Ranks unlock in strict order <span class="font-semibold">Silver → Gold → Diamond → Ambassador</span>.
            </p>
        </div>
        <div class="text-xs text-slate-500 text-right">
            <div class="font-bold text-slate-700">{{ number_format($counts['pending'] + $counts['active'] + $counts['completed'] + $counts['expired']) }}</div>
            <div>rank records total</div>
        </div>
    </div>

    {{-- Summary stat cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="?tab=pending" class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center hover:shadow-md transition {{ $tab==='pending' ? 'ring-2 ring-amber-400' : '' }}">
            <div class="text-2xl font-extrabold text-amber-500">{{ $counts['pending'] }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase mt-1">Pending review</div>
        </a>
        <a href="?tab=active" class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center hover:shadow-md transition {{ $tab==='active' ? 'ring-2 ring-sky-400' : '' }}">
            <div class="text-2xl font-extrabold text-sky-600">{{ $counts['active'] }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase mt-1">Active challenges</div>
        </a>
        <a href="?tab=completed" class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center hover:shadow-md transition {{ $tab==='completed' ? 'ring-2 ring-emerald-400' : '' }}">
            <div class="text-2xl font-extrabold text-emerald-600">{{ $counts['completed'] }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase mt-1">Completed</div>
        </a>
        <a href="?tab=expired" class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center hover:shadow-md transition {{ $tab==='expired' ? 'ring-2 ring-rose-400' : '' }}">
            <div class="text-2xl font-extrabold text-rose-600">{{ $counts['expired'] }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase mt-1">Expired / forfeited</div>
        </a>
    </div>

    {{-- Tabs (Tailwind pill style) --}}
    <div class="flex flex-wrap gap-2 mb-5 border-b border-slate-200 pb-3">
        @php
            $tabs = [
                'pending'   => ['label' => 'Pending verification', 'count' => $counts['pending'], 'color' => 'amber'],
                'active'    => ['label' => 'Active challenges',   'count' => $counts['active'],  'color' => 'sky'],
                'completed' => ['label' => 'Completed',          'count' => $counts['completed'], 'color' => 'emerald'],
                'expired'   => ['label' => 'Expired',            'count' => $counts['expired'],  'color' => 'rose'],
            ];
        @endphp
        @foreach($tabs as $k => $t)
            @php $active = ($tab === $k); @endphp
            <a href="?tab={{ $k }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition
                      {{ $active
                         ? 'bg-slate-900 text-white shadow-sm'
                         : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                <span>{{ $t['label'] }}</span>
                @if($t['count'] > 0)
                    <span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full text-[10px] font-bold
                                 {{ $active ? 'bg-white/20 text-white' : 'bg-'.$t['color'].'-100 text-'.$t['color'].'-700' }}">
                        {{ $t['count'] }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Ranks table --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Rank</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Direct FC</th>
                        <th class="px-4 py-3">Team FC</th>
                        @if($tab === 'active')
                            <th class="px-4 py-3">Deadline</th>
                            <th class="px-4 py-3">Days left</th>
                        @elseif($tab === 'pending')
                            <th class="px-4 py-3">Completed At</th>
                            <th class="px-4 py-3">Targets</th>
                        @elseif($tab === 'completed')
                            <th class="px-4 py-3">Verified</th>
                            <th class="px-4 py-3">Reward</th>
                        @else
                            <th class="px-4 py-3">Expired</th>
                        @endif
                        @if(in_array($tab, ['pending', 'active']))
                            <th class="px-4 py-3 text-right">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @forelse($rows as $row)
                    @php
                        $def = $row->rankDefinition();
                        $pinColors = ['silver' => '#c0c0c0', 'gold' => '#d4af37', 'diamond' => '#b9f2ff', 'ambassador' => '#9c27b0'];
                        $pinBg = $pinColors[$row->rank_pin] ?? '#94a3b8';
                    @endphp
                    <tr class="bg-white border-b hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-slate-400 font-mono text-xs">{{ $row->id }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ url('/admin/users/' . $row->user_id) }}" class="hover:underline">
                                <div class="font-bold text-slate-900">#{{ $row->user_id }}</div>
                                <div class="text-xs text-slate-500">{{ optional($row->user)->email ?: '—' }}</div>
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold text-black shadow-sm"
                                  style="background: {{ $pinBg }};">
                                <i class="fas fa-medal"></i>{{ $def['title'] ?? ucfirst($row->rank_pin) }}
                            </span>
                            <div class="text-xs text-slate-400 mt-1">{{ ucfirst($row->rank_pin) }} pin</div>
                        </td>
                        <td class="px-4 py-3">
                            @if($row->status === 'pending_admin')
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800"><i class="fas fa-hourglass-half mr-1"></i>Pending review</span>
                            @elseif($row->status === 'active')
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800"><i class="fas fa-play mr-1"></i>Active</span>
                            @elseif($row->status === 'completed')
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800"><i class="fas fa-check mr-1"></i>Completed</span>
                            @elseif($row->status === 'expired')
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800"><i class="fas fa-ban mr-1"></i>Expired</span>
                            @else
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-700">{{ ucfirst($row->status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-700">
                            @if($tab === 'pending')
                                {{ $row->direct_fc_at_completion }} <span class="text-slate-400 font-normal">/ {{ $def['own_fc_direct'] }}</span>
                            @elseif($tab === 'active')
                                {{ \App\Services\FcStreamlineRankService::countDirectFcReferrals($row->user_id) }} <span class="text-slate-400 font-normal">/ {{ $def['own_fc_direct'] }}</span>
                            @else
                                {{ $row->direct_fc_at_completion ?: '—' }}
                            @endif
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-700">
                            @if($tab === 'pending')
                                {{ number_format($row->team_fc_at_completion) }} <span class="text-slate-400 font-normal">/ {{ number_format($def['team_club']) }}</span>
                            @elseif($tab === 'active')
                                {{ number_format(\App\Services\FcStreamlineRankService::countTeamClubFc($row->user_id)) }} <span class="text-slate-400 font-normal">/ {{ number_format($def['team_club']) }}</span>
                            @else
                                {{ $row->team_fc_at_completion ? number_format($row->team_fc_at_completion) : '—' }}
                            @endif
                        </td>

                        @if($tab === 'active')
                            @php $d = $row->daysRemaining(); @endphp
                            <td class="px-4 py-3 text-slate-600 text-xs">
                                {{ $row->deadline_at ? $row->deadline_at->format('Y-m-d H:i') : '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold
                                    {{ $d <= 7 ? 'bg-rose-100 text-rose-800' : ($d <= 20 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    {{ $d }} days
                                </span>
                            </td>
                        @elseif($tab === 'pending')
                            <td class="px-4 py-3 text-slate-600 text-xs">
                                {{ $row->completed_at ? $row->completed_at->format('Y-m-d H:i') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600 leading-relaxed">
                                <div>Own FC: <strong>{{ $def['own_fc_direct'] }}</strong> direct</div>
                                <div>Team: <strong>{{ number_format($def['team_club']) }}</strong></div>
                                @if($def['activation_pins'])
                                    <div>{{ ucfirst($def['activation_pin_type']) }} pins: <strong>{{ $row->required_pins_at_completion }}/{{ $def['activation_pins'] }}</strong></div>
                                @endif
                            </td>
                        @elseif($tab === 'completed')
                            <td class="px-4 py-3 text-slate-600 text-xs">{{ $row->verified_at ? $row->verified_at->format('Y-m-d') : '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-emerald-600">${{ number_format($row->reward_usd, 2) }}</div>
                                <div class="text-xs text-sky-600 font-semibold">{{ number_format($row->reward_tokens) }} tokens</div>
                            </td>
                        @else
                            <td class="px-4 py-3 text-slate-600 text-xs">{{ $row->expired_at ? $row->expired_at->format('Y-m-d') : '—' }}</td>
                        @endif

                        @if(in_array($tab, ['pending', 'active']))
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                @if($row->status === 'pending_admin')
                                    <form method="POST" action="{{ route('admin.fc-ranks.approve', $row) }}" class="inline-block"
                                          onsubmit="return confirm('Approve this rank? ${{ number_format($def['reward_usd'],2) }} + {{ number_format($def['reward_tokens']) }} tokens will be credited.')">
                                        @csrf
                                        <button class="inline-flex items-center text-white bg-emerald-600 hover:bg-emerald-700 font-semibold rounded-lg text-xs px-3 py-1.5 mr-1">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                    </form>
                                    <button type="button"
                                            class="inline-flex items-center text-rose-600 border border-rose-300 hover:bg-rose-50 font-semibold rounded-lg text-xs px-3 py-1.5"
                                            data-toggle="modal" data-target="#rejectModal{{ $row->id }}">
                                        <i class="fas fa-times mr-1"></i>Reject
                                    </button>

                                    {{-- Reject modal (Bootstrap JS still loaded globally by admin.sidebar) --}}
                                    <div class="modal fade" id="rejectModal{{ $row->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <form method="POST" action="{{ route('admin.fc-ranks.reject', $row) }}" class="modal-content bg-white rounded-xl shadow-2xl">
                                                @csrf
                                                <div class="modal-header border-b border-slate-200 px-5 py-3">
                                                    <h5 class="modal-title font-bold text-slate-900">Reject rank completion</h5>
                                                    <button type="button" class="text-slate-400 hover:text-slate-700 text-2xl leading-none" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body px-5 py-4 text-sm text-slate-700">
                                                    <p class="mb-1">User <strong>#{{ $row->user_id }}</strong></p>
                                                    <p class="mb-3">Rank: <strong>{{ $def['title'] }} ({{ ucfirst($row->rank_pin) }})</strong></p>
                                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Reason (visible on the user timeline)</label>
                                                    <textarea name="notes" rows="3" class="w-full border border-slate-300 rounded-lg p-2 text-sm"
                                                              placeholder="e.g. Direct referral count could not be verified..."></textarea>
                                                </div>
                                                <div class="modal-footer border-t border-slate-200 px-5 py-3 flex justify-end gap-2">
                                                    <button type="button" class="px-4 py-2 text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50" data-dismiss="modal">Cancel</button>
                                                    <button class="px-4 py-2 text-sm font-semibold rounded-lg bg-rose-600 text-white hover:bg-rose-700">Reject &amp; return to active</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($row->status === 'active')
                                    <span class="text-xs text-slate-500 italic">Awaiting targets — auto-detected on completion.</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="20" class="px-4 py-10 text-center">
                            <div class="inline-flex flex-col items-center text-slate-400">
                                <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                                <div class="text-sm font-semibold">No ranks in this tab.</div>
                                <div class="text-xs mt-1">When users hit rank thresholds they will appear here automatically.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($rows,'links'))
            <div class="px-4 py-3 border-t border-slate-200 flex justify-center">
                {{ $rows->appends(['tab' => $tab])->links() }}
            </div>
        @endif
    </div>

    {{-- Legend / how it works --}}
    <div class="mt-6 bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 mb-2"><i class="fas fa-info-circle text-sky-500 mr-1"></i>How FC Streamline Ranks work</h3>
        <ul class="text-xs text-slate-600 leading-relaxed list-disc pl-5 space-y-1">
            <li><strong>Auto-activation:</strong> the 65-day challenge starts automatically the moment a user hits the activation threshold (5 direct FC for Silver; pin counts for higher ranks). No opt-in required.</li>
            <li><strong>Targets:</strong> OWN FC = direct FC referrals; TEAM CLUB = all FC-paid users anywhere in the downline (any depth). Both must be met within 65 days.</li>
            <li><strong>Admin approval:</strong> when targets are met the rank moves to <em>Pending review</em>. Approve credits $reward to COMMISSION (Monday cashout) and tokens to AVAILABLE_TOKEN (unlocked).</li>
            <li><strong>Expiry:</strong> failing to meet both targets within 65 days permanently forfeits that rank and locks all higher ranks (sequential progression).</li>
        </ul>
    </div>
</div>
@endsection
