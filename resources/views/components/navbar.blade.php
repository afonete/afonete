<div class="flex flex-wrap items-center justify-center gap-2 bg-white p-3 sm:p-4 rounded-2xl shadow-sm border border-slate-200 text-xs font-bold">
    <a href="{{ route('teambuilding') }}" class="px-3 py-1.5 rounded-xl transition flex items-center gap-1.5 {{ request()->routeIs('teambuilding') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
        <i class="fas fa-home"></i> <span>Overview</span>
    </a>
    <a href="{{ route('team.structure') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('team.structure') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Team Structure</a>
    <a href="{{ route('team.genealogy') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('team.genealogy') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Team Genealogy</a>
    <a href="{{ route('downline') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('downline') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Downline</a>
    <a href="{{ route('volume.points') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('volume.points') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Volume Points</a>
    <a href="{{ route('team.ranking') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('team.ranking') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Team Ranking</a>
    <a href="{{ route('teams.groups') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('teams.groups') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Teams &amp; Groups</a>
    <a href="{{ route('my.awards') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('my.awards') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">My Awards</a>
    <a href="{{ route('commission') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('commission') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Commission</a>
    <a href="{{ route('focoin.point') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('focoin.point') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Focoin Point</a>
    <a href="{{ route('fone.commission') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('fone.commission') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Fone Commission</a>
    <a href="{{ route('fomo.commission') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('fomo.commission') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">FOMO Commission</a>
    <a href="{{ route('merchant') }}" class="px-3 py-1.5 rounded-xl transition {{ request()->routeIs('merchant') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Merchant</a>
</div>
