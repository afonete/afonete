<div class="grid grid-cols-2 bg-white p-4 rounded-lg mb-6 space-x-2 md:space-x-4">

    <div class="flex flex-col sm:flex-row justify-around gap-2">
        <a href="{{ route('teambuilding') }}" class="{{ request()->routeIs('teambuilding') ? 'bg-purple-500 px-2 rounded py-1
         text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }} self-start" style="color:white">
            <i class="fas fa-home text-white" ></i>
        </a>

        <a href="{{ route('team.structure') }}" class="{{ request()->routeIs('team.structure') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Team Structure</a>
        <a href="{{ route('team.genealogy') }}" class="{{ request()->routeIs('team.genealogy') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Team Genealogy</a>
        <a href="{{ route('downline') }}" class="{{ request()->routeIs('downline') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Downline</a>
        <a href="{{ route('volume.points') }}" class="{{ request()->routeIs('volume.points') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Volume Points</a>
        <a href="{{ route('team.ranking') }}" class="{{ request()->routeIs('team.ranking') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Team Ranking</a>

    </div>
    <div  class="flex flex-col sm:flex-row justify-around gap-2">
    <a href="{{ route('teams.groups') }}" class="{{ request()->routeIs('teams.groups') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Teams & Groups</a>
    <a href="{{ route('my.awards') }}" class="{{ request()->routeIs('my.awards') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">My Awards</a>
    <a href="{{ route('commission') }}" class="{{ request()->routeIs('commission') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Commission</a>
    <a href="{{ route('focoin.point') }}" class="{{ request()->routeIs('focoin.point') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Focoin Point</a>
    <a href="{{ route('fone.commission') }}" class="{{ request()->routeIs('fone.commission') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Fone Commission</a>
    <a href="{{ route('fomo.commission') }}" class="{{ request()->routeIs('fomo.commission') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">FOMO Commission</a>
    <a href="{{ route('merchant') }}" class="{{ request()->routeIs('merchant') ? 'bg-blue-200 px-2 rounded py-1  text-white font-semibold' : 'text-blue-600 font-semibold hover:text-blue-800' }}">Merchant</a>

    </div>

</div>
