<div class="wrapper">
    @include('user.user-dashboard-base')
    <title>Team Building Structure</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="content-wrapper bg-slate-50 min-h-screen py-4 px-4">
        <div class="container-fluid max-w-7xl mx-auto flex flex-col gap-6">

            {{-- Navigation Bar Component --}}
            <x-navbar/>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
                    <span><i class="fas fa-check-circle mr-2 text-emerald-600"></i>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
                    <span><i class="fas fa-exclamation-circle mr-2 text-rose-600"></i>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-2 text-rose-600"></i>{{ $errors->first() }}
                </div>
            @endif

            {{-- Header & Balance Overview --}}
            <div class="bg-slate-900 text-white rounded-2xl p-4 sm:p-6 shadow-md border border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-white flex items-center gap-2">
                        <i class="fas fa-sitemap text-amber-400"></i> Team Building Structure
                    </h1>
                    <p class="text-xs text-slate-400 mt-1">Register new downline members directly using funds from your Deposit Wallet.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                    <div class="bg-slate-800 px-4 py-2.5 rounded-xl border border-slate-700 text-left sm:text-right w-full sm:w-auto">
                        <span class="text-[10px] text-amber-400 font-bold uppercase tracking-wider block">Deposit Wallet Balance</span>
                        <span class="text-lg sm:text-xl font-extrabold text-white">${{ number_format($depositBalance ?? 0, 2) }}</span>
                    </div>

                    <button onclick="openModal()" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold py-3 px-5 rounded-xl text-xs uppercase tracking-wider shadow-md transition flex items-center justify-center gap-2 w-full sm:w-auto shrink-0">
                        <i class="fas fa-user-plus text-amber-200"></i> Add Team Member
                    </button>
                </div>
            </div>

            {{-- Sponsor Referral Links Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Left Referral Link</label>
                    <div class="flex items-center">
                        <input type="text" id="leftLink" value="{{ url('/register?referral=' . (auth()->user()->activation ?? auth()->user()->user ?? auth()->id()) . '&side=LEFT') }}" class="w-full min-w-0 px-3 py-2 text-xs border border-slate-300 rounded-l-xl font-mono text-slate-800" readonly>
                        <button onclick="copyToClipboard('leftLink')" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-r-xl text-xs font-bold transition shrink-0">
                            <i class="fa fa-copy mr-1"></i> Copy
                        </button>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Right Referral Link</label>
                    <div class="flex items-center">
                        <input type="text" id="rightLink" value="{{ url('/register?referral=' . (auth()->user()->activation ?? auth()->user()->user ?? auth()->id()) . '&side=RIGHT') }}" class="w-full min-w-0 px-3 py-2 text-xs border border-slate-300 rounded-l-xl font-mono text-slate-800" readonly>
                        <button onclick="copyToClipboard('rightLink')" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-r-xl text-xs font-bold transition shrink-0">
                            <i class="fa fa-copy mr-1"></i> Copy
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tree Structure Container --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
                <div class="flex items-center justify-between border-b pb-3 mb-6 flex-wrap gap-2">
                    <div>
                        <h4 class="text-slate-900 font-extrabold text-base flex items-center gap-2">
                            <i class="fas fa-network-wired text-indigo-600"></i> Interactive Team Tree
                        </h4>
                        <p class="text-xs text-slate-500 mt-0.5">Visual placement tree for your Left and Right downline teams.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="switchTreeView('visual')" id="btnVisualTree" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm">
                            <i class="fas fa-sitemap mr-1"></i> Visual Tree
                        </button>
                        <button type="button" onclick="switchTreeView('canvas')" id="btnCanvasTree" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200">
                            <i class="fas fa-project-diagram mr-1"></i> Diagram Canvas
                        </button>
                    </div>
                </div>

                {{-- Visual Cards Tree View --}}
                <div id="visualTreeView" class="space-y-6">
                    {{-- Root User Card --}}
                    <div class="flex justify-center">
                        <div class="bg-slate-900 text-white p-4 rounded-2xl border-2 border-amber-400 shadow-lg text-center max-w-xs w-full">
                            <div class="w-12 h-12 bg-amber-500/20 text-amber-400 rounded-full flex items-center justify-center mx-auto mb-2 text-xl font-bold border border-amber-400/40">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <span class="bg-amber-400 text-slate-950 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Root Sponsor (You)</span>
                            <h5 class="text-base font-extrabold text-white mt-1">@ {{ Auth::user()->user }}</h5>
                            <div class="text-xs text-slate-300 font-mono mt-0.5">Code: {{ Auth::user()->transfer_code }}</div>
                            <div class="mt-2">
                                <span class="bg-slate-800 text-amber-300 text-xs font-extrabold px-2.5 py-1 rounded-lg border border-slate-700">
                                    {{ strtoupper(Auth::user()->has_paid_package ?: 'FREE / STANDARD') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Connector Lines --}}
                    <div class="flex justify-center items-center text-slate-300 my-2">
                        <i class="fas fa-code-branch text-2xl text-indigo-500"></i>
                    </div>

                    {{-- Left & Right Teams Branches --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- LEFT TEAM BRANCH · ts-v2 marker (§75): plain div — NOT an
                             AdminLTE .card, so no CardWidget/collapse handler can bind
                             to this header. Only the Show More button toggles rows. --}}
                        <div class="bg-sky-50/60 rounded-2xl p-5 border border-sky-200">
                            <div class="flex items-center justify-between border-b border-sky-200 pb-3 mb-3">
                                <h5 class="text-sky-900 font-extrabold text-sm flex items-center gap-2">
                                    <i class="fas fa-arrow-left text-sky-600"></i> LEFT TEAM BRANCH
                                </h5>
                                <span class="bg-sky-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                    {{ count($leftMembers) }} {{ Str::plural('Member', count($leftMembers)) }}
                                </span>
                            </div>

                            @if(count($leftMembers) > 3)
                                <div class="mb-3">
                                    <input type="text" id="leftSearch" onkeyup="filterBranch('left')" placeholder="Search Left Team (username / code)..." class="w-full px-3 py-1.5 bg-white rounded-lg border border-sky-200 text-xs font-medium text-slate-800 outline-none focus:ring-1 focus:ring-sky-500">
                                </div>
                            @endif

                            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1" id="leftBranchContainer">
                                @forelse($leftMembers as $index => $m)
                                    <div class="left-member-item bg-white p-3.5 rounded-xl border border-sky-200 shadow-sm flex items-center justify-between gap-3 {{ $index >= 5 ? 'hidden left-extra-item' : '' }}" data-search="{{ strtolower($m['user'] . ' ' . $m['transfer_code'] . ' ' . $m['name']) }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-sky-100 text-sky-700 rounded-full flex items-center justify-center font-bold text-xs shrink-0">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="text-xs font-extrabold text-slate-900 flex items-center gap-1.5 flex-wrap">
                                                    <span>@ {{ $m['user'] }}</span>
                                                    @if($m['is_direct'])
                                                        <span class="bg-emerald-100 text-emerald-800 text-[9px] font-extrabold px-1.5 py-0.5 rounded border border-emerald-200">L1 Direct</span>
                                                    @else
                                                        <span class="bg-slate-100 text-slate-700 text-[9px] font-extrabold px-1.5 py-0.5 rounded border border-slate-200">L{{ $m['level'] }} Indirect</span>
                                                    @endif
                                                </div>
                                                <div class="text-[11px] text-slate-500 font-mono mt-0.5">Code: {{ $m['transfer_code'] }}</div>
                                                @if(!$m['is_direct'] && !empty($m['sponsor_user']))
                                                    <div class="text-[10px] text-indigo-600 font-medium">by @ {{ $m['sponsor_user'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="bg-sky-100 text-sky-800 text-[10px] font-extrabold px-2 py-0.5 rounded border border-sky-200 uppercase block mb-1">
                                                {{ $m['package'] }}
                                            </span>
                                            <small class="text-slate-400 text-[10px]">{{ $m['joined'] }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 border-2 border-dashed border-sky-200 rounded-xl bg-white/50">
                                        <i class="fas fa-user-plus text-sky-300 text-2xl mb-1 block"></i>
                                        <p class="text-xs text-sky-700 font-medium">No members on Left Team branch yet.</p>
                                    </div>
                                @endforelse
                            </div>

                            @if(count($leftMembers) > 5)
                                <button type="button" onclick="toggleBranchLimit('left', event)" id="btnLeftToggle" class="w-full mt-2.5 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-xs font-bold rounded-xl border border-sky-200 transition flex items-center justify-center gap-1.5">
                                    <i class="fas fa-chevron-down text-[10px]" id="iconLeftToggle"></i>
                                    <span id="textLeftToggle">Show More (+{{ count($leftMembers) - 5 }} Remaining)</span>
                                </button>
                            @endif

                            <button onclick="openModal('LEFT')" class="w-full mt-3 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-extrabold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> Add Team Member to LEFT
                            </button>
                        </div>

                        {{-- RIGHT TEAM BRANCH --}}
                        <div class="bg-indigo-50/60 rounded-2xl p-5 border border-indigo-200">
                            <div class="flex items-center justify-between border-b border-indigo-200 pb-3 mb-3">
                                <h5 class="text-indigo-900 font-extrabold text-sm flex items-center gap-2">
                                    <i class="fas fa-arrow-right text-indigo-600"></i> RIGHT TEAM BRANCH
                                </h5>
                                <span class="bg-indigo-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                    {{ count($rightMembers) }} {{ Str::plural('Member', count($rightMembers)) }}
                                </span>
                            </div>

                            @if(count($rightMembers) > 3)
                                <div class="mb-3">
                                    <input type="text" id="rightSearch" onkeyup="filterBranch('right')" placeholder="Search Right Team (username / code)..." class="w-full px-3 py-1.5 bg-white rounded-lg border border-indigo-200 text-xs font-medium text-slate-800 outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                            @endif

                            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1" id="rightBranchContainer">
                                @forelse($rightMembers as $index => $m)
                                    <div class="right-member-item bg-white p-3.5 rounded-xl border border-indigo-200 shadow-sm flex items-center justify-between gap-3 {{ $index >= 5 ? 'hidden right-extra-item' : '' }}" data-search="{{ strtolower($m['user'] . ' ' . $m['transfer_code'] . ' ' . $m['name']) }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-xs shrink-0">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="text-xs font-extrabold text-slate-900 flex items-center gap-1.5 flex-wrap">
                                                    <span>@ {{ $m['user'] }}</span>
                                                    @if($m['is_direct'])
                                                        <span class="bg-emerald-100 text-emerald-800 text-[9px] font-extrabold px-1.5 py-0.5 rounded border border-emerald-200">L1 Direct</span>
                                                    @else
                                                        <span class="bg-slate-100 text-slate-700 text-[9px] font-extrabold px-1.5 py-0.5 rounded border border-slate-200">L{{ $m['level'] }} Indirect</span>
                                                    @endif
                                                </div>
                                                <div class="text-[11px] text-slate-500 font-mono mt-0.5">Code: {{ $m['transfer_code'] }}</div>
                                                @if(!$m['is_direct'] && !empty($m['sponsor_user']))
                                                    <div class="text-[10px] text-indigo-600 font-medium">by @ {{ $m['sponsor_user'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="bg-indigo-100 text-indigo-800 text-[10px] font-extrabold px-2 py-0.5 rounded border border-indigo-200 uppercase block mb-1">
                                                {{ $m['package'] }}
                                            </span>
                                            <small class="text-slate-400 text-[10px]">{{ $m['joined'] }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 border-2 border-dashed border-indigo-200 rounded-xl bg-white/50">
                                        <i class="fas fa-user-plus text-indigo-300 text-2xl mb-1 block"></i>
                                        <p class="text-xs text-indigo-700 font-medium">No members on Right Team branch yet.</p>
                                    </div>
                                @endforelse
                            </div>

                            @if(count($rightMembers) > 5)
                                <button type="button" onclick="toggleBranchLimit('right', event)" id="btnRightToggle" class="w-full mt-2.5 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-800 text-xs font-bold rounded-xl border border-indigo-200 transition flex items-center justify-center gap-1.5">
                                    <i class="fas fa-chevron-down text-[10px]" id="iconRightToggle"></i>
                                    <span id="textRightToggle">Show More (+{{ count($rightMembers) - 5 }} Remaining)</span>
                                </button>
                            @endif

                            <button onclick="openModal('RIGHT')" class="w-full mt-3 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> Add Team Member to RIGHT
                            </button>
                        </div>
                    </div>
                </div>

                {{-- GoJS Canvas Diagram View --}}
                <div id="canvasTreeView" class="hidden">
                    <script src="https://cdn.jsdelivr.net/npm/gojs/release/go.js"></script>
                    <div id="myDiagramDiv" class="w-full h-[450px] bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-inner"></div>
                    <div class="mt-3 flex items-center justify-between text-xs text-slate-500 flex-wrap gap-2">
                        <span><i class="fas fa-info-circle text-amber-500 mr-1"></i> Click on any user node to view details or expand/collapse their downline tree below.</span>
                        <span><i class="fas fa-search-plus text-indigo-500 mr-1"></i> Scroll to zoom · Double-click canvas to reset view</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal: Add Team Member --}}
<div id="userModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-6 border md:w-[50%] w-full max-w-2xl shadow-2xl rounded-2xl bg-white text-slate-800 my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <h3 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fas fa-user-plus text-indigo-600"></i> Register &amp; Activate Team Member
            </h3>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Deposit Balance Banner --}}
        <div class="mt-4 p-3.5 bg-slate-900 text-white rounded-xl flex items-center justify-between flex-wrap gap-2 border border-slate-700">
            <div>
                <span class="text-xs text-amber-400 font-bold uppercase tracking-wider block"><i class="fas fa-wallet mr-1"></i> Your Deposit Wallet Balance</span>
                <span class="text-2xl font-extrabold text-white">${{ number_format($depositBalance ?? 0, 2) }}</span>
            </div>
            <div class="text-xs text-slate-300 max-w-xs text-right">
                Funds will be deducted directly from your Deposit Wallet to pay for and activate this new member's package.
            </div>
        </div>

        <form method="POST" action="{{ route('user.team.add-member') }}" class="space-y-4 mt-4 js-transaction-password-form">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Sponsor (Referrer)</label>
                    <input type="text" value="@ {{ Auth::user()->user }} (You)" readonly class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-100 text-xs font-bold text-slate-700">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Team Placement Side <span class="text-red-500">*</span></label>
                    <select name="side" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="LEFT" selected>LEFT Team</option>
                        <option value="RIGHT">RIGHT Team</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Select UVP Package Tier <span class="text-red-500">*</span></label>
                    <select name="package_id" id="pkgSelect" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:ring-indigo-500 focus:border-indigo-500" onchange="updatePkgAmount()">
                        @foreach($adventures as $adv)
                            <option value="{{ $adv->id }}" data-min="{{ $adv->min_amount }}" data-max="{{ $adv->max_amount }}">
                                {{ $adv->name ?: $adv->plan }} (Min ${{ number_format($adv->min_amount, 0) }} - Max ${{ number_format($adv->max_amount, 0) }}) · {{ $adv->percentage }}% Daily ROI
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Investment Amount ($ USD) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="pkgAmount" min="1" step="0.01" required placeholder="Enter amount to invest" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-[11px] text-slate-400 mt-1">Must be $\le$ your available Deposit Wallet balance (${{ number_format($depositBalance ?? 0, 2) }}).</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Member Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Full Name" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Member Username <span class="text-red-500">*</span></label>
                    <input type="text" name="user" required placeholder="Username (4-10 chars)" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required placeholder="Member Email" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" required placeholder="Phone Number" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Country <span class="text-red-500">*</span></label>
                    <input type="text" name="country" required placeholder="Country" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required placeholder="Min 6 characters" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required placeholder="Confirm Password" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-800">
                </div>
            </div>

            <input type="hidden" name="transaction_password" class="js-transaction-password-value">

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl text-xs shadow-md transition flex items-center gap-2">
                    <i class="fas fa-check"></i> Register &amp; Pay From Deposit
                </button>
            </div>
        </form>
    </div>
</div>

@include('user.components.transaction-password-modal')

<script>
function openModal(preselectedSide) {
    document.getElementById('userModal').classList.remove('hidden');
    if (preselectedSide) {
        const sideSelect = document.querySelector('select[name="side"]');
        if (sideSelect) sideSelect.value = preselectedSide;
    }
    updatePkgAmount();
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

function updatePkgAmount() {
    const sel = document.getElementById('pkgSelect');
    if (!sel) return;
    const opt = sel.options[sel.selectedIndex];
    if (!opt) return;
    const min = opt.getAttribute('data-min') || '1';
    const input = document.getElementById('pkgAmount');
    if (input) {
        input.min = min;
        if (!input.value || parseFloat(input.value) < parseFloat(min)) {
            input.value = min;
        }
    }
}

function copyToClipboard(id) {
    const el = document.getElementById(id);
    if (!el) return;
    navigator.clipboard.writeText(el.value).then(() => {
        alert('Referral link copied to clipboard!');
    });
}

function toggleBranchLimit(side, ev) {
    // §75 hardening: stop the click from bubbling into any global handler
    // (AdminLTE CardWidget / treeview listeners on the live host were
    // collapsing the whole branch container instead of toggling rows).
    if (ev) { ev.preventDefault(); ev.stopPropagation(); }
    const extras = document.querySelectorAll('.' + side + '-extra-item');
    const textEl = document.getElementById('text' + (side === 'left' ? 'Left' : 'Right') + 'Toggle');
    const iconEl = document.getElementById('icon' + (side === 'left' ? 'Left' : 'Right') + 'Toggle');
    if (!extras || extras.length === 0) return;

    const isHidden = extras[0].classList.contains('hidden');

    extras.forEach(function(el) {
        if (isHidden) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    });

    if (isHidden) {
        if (textEl) textEl.textContent = 'Show Less';
        if (iconEl) iconEl.className = 'fas fa-chevron-up text-[10px]';
    } else {
        if (textEl) textEl.textContent = 'Show More (+' + extras.length + ' Remaining)';
        if (iconEl) iconEl.className = 'fas fa-chevron-down text-[10px]';
    }
}

// §75 hardening: the dashboard base ships AdminLTE, whose CardWidget binds
// collapse handlers to [data-card-widget] / .card-header elements. If a stale
// compiled copy of this view (or a plugin) marks the branch headers that way
// on the LIVE host, clicking near "LEFT TEAM BRANCH" collapses the whole box.
// Strip any such attributes inside the branch containers at load time.
document.addEventListener('DOMContentLoaded', function () {
    ['leftBranchContainer', 'rightBranchContainer'].forEach(function (id) {
        var container = document.getElementById(id);
        if (!container) return;
        var box = container.closest('div.rounded-2xl') || container.parentElement;
        if (!box) return;
        box.querySelectorAll('[data-card-widget], [data-widget], [data-toggle="collapse"], [data-bs-toggle="collapse"]').forEach(function (el) {
            el.removeAttribute('data-card-widget');
            el.removeAttribute('data-widget');
            el.removeAttribute('data-toggle');
            el.removeAttribute('data-bs-toggle');
        });
        box.classList.remove('card', 'collapsed-card');
    });
});

function filterBranch(side) {
    const input = document.getElementById(side + 'Search');
    if (!input) return;
    const filter = input.value.toLowerCase().trim();
    const items = document.querySelectorAll('.' + side + '-member-item');
    items.forEach(function(item) {
        const text = item.getAttribute('data-search') || '';
        if (!filter || text.includes(filter)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function switchTreeView(view) {
    const v = document.getElementById('visualTreeView');
    const c = document.getElementById('canvasTreeView');
    const btnV = document.getElementById('btnVisualTree');
    const btnC = document.getElementById('btnCanvasTree');

    if (view === 'canvas') {
        v.classList.add('hidden');
        c.classList.remove('hidden');
        btnC.className = "px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm";
        btnV.className = "px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200";
        initGoJsDiagram();
    } else {
        c.classList.add('hidden');
        v.classList.remove('hidden');
        btnV.className = "px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm";
        btnC.className = "px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200";
    }
}

let goDiagram = null;
function initGoJsDiagram() {
    if (goDiagram || !window.go) return;
    const $ = go.GraphObject.make;

    goDiagram = $(go.Diagram, "myDiagramDiv", {
        "undoManager.isEnabled": true,
        "doubleClick": function(e) { e.diagram.zoomToFit(); },
        layout: $(go.TreeLayout, {
            angle: 90,
            layerSpacing: 50,
            nodeSpacing: 24,
            alignment: go.TreeLayout.AlignmentCenterChildren
        })
    });

    // Custom node template with expand/collapse button & click listener
    goDiagram.nodeTemplate =
        $(go.Node, "Auto",
            {
                cursor: "pointer",
                click: function(e, node) {
                    var data = node.data;
                    if (data && data.userData) {
                        alert("User Details:\nUsername: @" + data.userData.user + "\nTransfer Code: " + data.userData.transfer_code + "\nPackage: " + data.userData.package + "\nLevel: L" + data.userData.level + " (" + (data.userData.is_direct ? "Direct" : "Indirect by @" + data.userData.sponsor_user) + ")\nJoined: " + data.userData.joined);
                    }
                }
            },
            $(go.Shape, "RoundedRectangle",
                { fill: "#1e293b", stroke: "#6366f1", strokeWidth: 2 },
                new go.Binding("fill", "fill"),
                new go.Binding("stroke", "stroke")
            ),
            $(go.Panel, "Vertical",
                { margin: 10 },
                $(go.TextBlock,
                    { stroke: "#f59e0b", font: "bold 13px sans-serif", margin: new go.Margin(0, 0, 3, 0) },
                    new go.Binding("text", "name")),
                $(go.TextBlock,
                    { stroke: "#cbd5e1", font: "11px sans-serif", margin: new go.Margin(0, 0, 3, 0) },
                    new go.Binding("text", "title")),
                $(go.TextBlock,
                    { stroke: "#38bdf8", font: "bold 10px sans-serif" },
                    new go.Binding("text", "package"))
            ),
            // Tree expand/collapse button
            $("TreeExpanderButton",
                {
                    alignment: go.Spot.Bottom,
                    alignmentFocus: go.Spot.Top,
                    width: 16, height: 16
                }
            )
        );

    goDiagram.linkTemplate =
        $(go.Link,
            { routing: go.Link.Orthogonal, corner: 10 },
            $(go.Shape, { strokeWidth: 2, stroke: "#6366f1" })
        );

    const leftMembers  = @json($leftMembers);
    const rightMembers = @json($rightMembers);
    const rootUserId   = @json(Auth::id());

    const nodes = [
        { key: rootUserId, name: "@" + @json(Auth::user()->user) + " (You)", title: "Root Sponsor", package: @json(strtoupper(Auth::user()->has_paid_package ?: 'FREE / STANDARD')), fill: "#0f172a", stroke: "#f59e0b", userId: rootUserId },
        { key: "LEFT_BRANCH", parent: rootUserId, name: "LEFT TEAM", title: leftMembers.length + " Members", package: "LEFT BRANCH", fill: "#0369a1", stroke: "#38bdf8" },
        { key: "RIGHT_BRANCH", parent: rootUserId, name: "RIGHT TEAM", title: rightMembers.length + " Members", package: "RIGHT BRANCH", fill: "#4338ca", stroke: "#818cf8" }
    ];

    // Left Team members - parent = m.is_direct ? "LEFT_BRANCH" : m.sponsor_id
    leftMembers.forEach(function(m) {
        var parentKey = m.is_direct ? "LEFT_BRANCH" : m.sponsor_id;
        var levelText = m.is_direct ? "L1 Direct" : "L" + m.level + " Indirect (by @" + m.sponsor_user + ")";
        nodes.push({
            key: m.id,
            parent: parentKey,
            name: "@" + m.user,
            title: levelText + " · Code: " + m.transfer_code,
            package: m.package,
            fill: m.is_direct ? "#0f172a" : "#1e293b",
            stroke: m.is_direct ? "#10b981" : "#38bdf8",
            userId: m.id,
            userData: m
        });
    });

    // Right Team members - parent = m.is_direct ? "RIGHT_BRANCH" : m.sponsor_id
    rightMembers.forEach(function(m) {
        var parentKey = m.is_direct ? "RIGHT_BRANCH" : m.sponsor_id;
        var levelText = m.is_direct ? "L1 Direct" : "L" + m.level + " Indirect (by @" + m.sponsor_user + ")";
        nodes.push({
            key: m.id,
            parent: parentKey,
            name: "@" + m.user,
            title: levelText + " · Code: " + m.transfer_code,
            package: m.package,
            fill: m.is_direct ? "#0f172a" : "#1e293b",
            stroke: m.is_direct ? "#10b981" : "#818cf8",
            userId: m.id,
            userData: m
        });
    });

    goDiagram.model = new go.TreeModel(nodes);
}
</script>
