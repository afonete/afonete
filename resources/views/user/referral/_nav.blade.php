@php
    $tabs = [
        ['route' => 'user.referral.bonus',    'label' => 'Bonus & Withdraw'],
        ['route' => 'user.referral.downline',  'label' => 'Downline'],
        ['route' => 'user.referral.rank',      'label' => 'Ranks & Rewards'],
        ['route' => 'user.fc-leadership',      'label' => 'FC Leadership'],
        ['route' => 'user.fc-streamline-ranks','label' => 'FC Streamline Ranks'],
    ];
@endphp
<ul class="nav nav-pills mb-3">
    @foreach($tabs as $tab)
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs($tab['route']) ? 'active bg-primary text-white' : 'text-primary' }}"
               href="{{ route($tab['route']) }}">
                {{ $tab['label'] }}
            </a>
        </li>
    @endforeach
</ul>
