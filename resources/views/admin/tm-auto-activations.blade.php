@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl" x-data="{ level: 'TEAM_LEADER', code: '' }">

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
    <header class="bg-gradient-to-r from-amber-500 via-amber-600 to-indigo-600 py-6 px-6 rounded-2xl mb-8 shadow-md text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl uppercase font-extrabold tracking-tight flex items-center">
                    <span class="p-2.5 bg-white/20 rounded-xl shadow-inner mr-3 inline-flex items-center justify-center">
                        <i class="fas fa-bolt text-2xl text-amber-200"></i>
                    </span>
                    TM Auto Activation Code Generator
                </h1>
                <p class="text-amber-100 text-sm mt-2 font-medium max-w-2xl">
                    Generate unique activation codes for Team Leaders and Super Leaders. Anyone who holds an unused code can redeem it directly to activate their account and bypass all social media approval steps.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.team-leaders.index') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold px-4 py-2.5 rounded-xl border border-white/20 text-xs transition backdrop-blur-sm">
                    <i class="fas fa-users-cog"></i> Manage Team Leaders
                </a>
            </div>
        </div>
    </header>

    {{-- Generator Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
            <i class="fas fa-plus-circle text-amber-500"></i> Generate TM Auto Activation Code
        </h2>

        <form method="POST" action="{{ route('admin.tm-auto-activations.store') }}" class="space-y-6">
            @csrf

            {{-- Row 1: Code & Level --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Activation Code <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="activation_code" x-model="code" placeholder="e.g. TM-AUTO-9F82A" required class="flex-1 rounded-xl border-slate-300 text-sm font-mono font-bold uppercase text-slate-900 focus:ring-amber-500 focus:border-amber-500">
                        <button type="button" @click="code = 'TMAUTO-' + Math.random().toString(36).substring(2, 8).toUpperCase()" class="bg-slate-800 hover:bg-slate-900 text-white font-bold px-3 py-2 rounded-xl text-xs flex items-center gap-1.5 transition">
                            <i class="fas fa-magic text-amber-400"></i> Auto-Generate
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Unique uppercase string. Will be given to the applicant.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Leadership Level <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50" :class="level === 'TEAM_LEADER' ? 'border-amber-500 bg-amber-50/50 ring-2 ring-amber-500/20' : ''">
                            <input type="radio" name="leadership_level" value="TEAM_LEADER" x-model="level" class="text-amber-500 focus:ring-amber-500">
                            <span class="ml-2 font-bold text-xs text-slate-800">TEAM LEADER</span>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50" :class="level === 'SUPER_LEADER' ? 'border-indigo-500 bg-indigo-50/50 ring-2 ring-indigo-500/20' : ''">
                            <input type="radio" name="leadership_level" value="SUPER_LEADER" x-model="level" class="text-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 font-bold text-xs text-slate-800">SUPER LEADER</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Row 2: Duration, Tokens, Price --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Duration (Days)</label>
                    <input type="number" name="duration" value="60" min="1" required class="w-full rounded-xl border-slate-300 text-sm font-bold text-slate-800 focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Locked Tokens</label>
                    <input type="number" name="tokens" value="20000" min="0" required class="w-full rounded-xl border-slate-300 text-sm font-bold text-slate-800 focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Price ($)</label>
                    <input type="number" name="price" value="0" min="0" step="0.01" required class="w-full rounded-xl border-slate-300 text-sm font-bold text-slate-800 focus:ring-amber-500 focus:border-amber-500">
                </div>
            </div>

            {{-- Row 3: SUPER LEADER CREDIT CONDITIONS --}}
            <div x-show="level === 'SUPER_LEADER'" x-cloak class="p-5 rounded-2xl bg-indigo-50/60 border border-indigo-200/80 space-y-4">
                <div class="flex items-center gap-2 border-b border-indigo-200/60 pb-2">
                    <i class="fas fa-gift text-indigo-600 text-base"></i>
                    <h3 class="text-xs font-extrabold uppercase text-indigo-900 tracking-wider">Super Leader Credit Conditions (Same as Normal Way)</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1">Credit Amount ($)</label>
                        <input type="number" name="credit_amount" value="1000" min="0" step="0.01" class="w-full rounded-xl border-indigo-300 text-xs font-bold text-indigo-950 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1">Sales Turnover Target ($)</label>
                        <input type="number" name="sales_turnover_target" value="10000" min="0" step="0.01" class="w-full rounded-xl border-indigo-300 text-xs font-bold text-indigo-950 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1">Turnover Target %</label>
                        <input type="number" name="turnover_target_percent" value="1" min="0" max="100" step="0.01" class="w-full rounded-xl border-indigo-300 text-xs font-bold text-indigo-950 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1">Turnover Reward %</label>
                        <input type="number" name="turnover_reward_percent" value="0.5" min="0" max="100" step="0.01" class="w-full rounded-xl border-indigo-300 text-xs font-bold text-indigo-950 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1">Auto Withdrawal %</label>
                        <input type="number" name="auto_withdrawal_percent" value="20" min="0" max="100" step="0.01" class="w-full rounded-xl border-indigo-300 text-xs font-bold text-indigo-950 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    </div>
                </div>
            </div>

            {{-- Row 4: Tasks & Memo --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Assigned Tasks List</label>
                    <textarea name="tasks" rows="3" required class="w-full rounded-xl border-slate-300 text-xs text-slate-800 focus:ring-amber-500 focus:border-amber-500">Create weekly Zoom reports
Post official marketing creatives
Guide downline members</textarea>
                    <p class="text-[11px] text-slate-400 mt-0.5">Separate multiple tasks with newlines.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Memo / Recipient Note (Optional)</label>
                    <textarea name="memo" rows="3" placeholder="e.g. Reserved for VIP Partner John Doe" class="w-full rounded-xl border-slate-300 text-xs text-slate-800 focus:ring-amber-500 focus:border-amber-500"></textarea>
                    <p class="text-[11px] text-slate-400 mt-0.5">Internal note or recipient name for admin tracking.</p>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-6 py-3 rounded-xl shadow-md text-xs uppercase tracking-wider flex items-center gap-2 transition">
                    <i class="fas fa-bolt text-amber-200"></i> Generate TM Auto Activation Code
                </button>
            </div>
        </form>
    </div>

    {{-- List Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Generated TM Auto Activation Codes</h2>
                <p class="text-xs text-slate-500 font-medium">List of all Team Leader and Super Leader auto activation codes generated by admin.</p>
            </div>
            <span class="bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-full">
                Total: {{ count($activations) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-semibold text-xs border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Activation Code</th>
                        <th class="py-3.5 px-4">Level</th>
                        <th class="py-3.5 px-4">Price &amp; Tokens</th>
                        <th class="py-3.5 px-4">Credit Conditions</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Redeemed By / Note</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activations as $act)
                        @php
                            $cond = !empty($act->credit_conditions) ? json_decode($act->credit_conditions, true) : null;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            {{-- Code --}}
                            <td class="py-4 px-4 font-mono font-bold text-slate-900">
                                <div class="flex items-center gap-2">
                                    <span class="text-base text-indigo-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                        {{ $act->code }}
                                    </span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $act->code }}'); alert('Code {{ $act->code }} copied!')" class="text-slate-400 hover:text-amber-600 text-xs" title="Copy Code">
                                        <i class="far fa-copy text-base"></i>
                                    </button>
                                </div>
                            </td>

                            {{-- Level --}}
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 text-xs font-extrabold rounded-md uppercase tracking-wider {{ $act->package === 'SUPER_LEADER' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                    {{ $act->package }}
                                </span>
                            </td>

                            {{-- Price & Tokens --}}
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900">${{ number_format($act->price, 2) }}</div>
                                <div class="text-xs text-amber-600 font-semibold">{{ number_format($act->token, 0) }} Tokens</div>
                                <div class="text-[11px] text-slate-400 font-medium">{{ $act->period }} Days</div>
                            </td>

                            {{-- Credit Conditions (Super Leader) --}}
                            <td class="py-4 px-4 text-xs">
                                @if($act->package === 'SUPER_LEADER' && $cond)
                                    <div class="space-y-0.5 font-medium">
                                        <div class="text-indigo-900 font-bold"><i class="fas fa-gift text-indigo-500 mr-1"></i> Credit: ${{ number_format($cond['credit_amount'] ?? 0, 2) }}</div>
                                        <div class="text-slate-600">Target: ${{ number_format($cond['sales_turnover_target'] ?? 0, 0) }} ({{ $cond['turnover_target_percent'] ?? 0 }}%)</div>
                                        <div class="text-slate-600">Reward: {{ $cond['turnover_reward_percent'] ?? 0 }}% | Auto WD: {{ $cond['auto_withdrawal_percent'] ?? 0 }}%</div>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">N/A (Standard)</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="py-4 px-4">
                                @if($act->stutus === 'used')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                        <i class="fas fa-check-double mr-1 text-slate-500"></i> Used / Redeemed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300">
                                        <i class="fas fa-bolt mr-1 text-green-600"></i> Unused / Ready
                                    </span>
                                @endif
                            </td>

                            {{-- Redeemed By / Memo --}}
                            <td class="py-4 px-4 text-xs">
                                @if($act->stutus === 'used')
                                    <div class="font-bold text-slate-800">{{ $act->email }}</div>
                                    <div class="text-slate-400 text-[11px]">{{ $act->updated_at ? $act->updated_at->format('M d, Y H:i') : '' }}</div>
                                @else
                                    <div class="text-slate-500 italic">{{ $act->email ?: 'No memo note' }}</div>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="py-4 px-4 text-right">
                                @if($act->stutus !== 'used')
                                    <form method="POST" action="{{ route('admin.tm-auto-activations.delete', $act->id) }}" onsubmit="return confirm('Delete this unused TM Auto Activation Code?');" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 text-xs italic">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                <i class="fas fa-bolt text-3xl mb-2 text-slate-300 block"></i>
                                No TM Auto Activation Codes generated yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
