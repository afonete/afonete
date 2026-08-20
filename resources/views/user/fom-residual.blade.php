<div class="wrapper">
@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <h3 class="font-weight-bold mb-1"><i class="fas fa-sitemap text-primary mr-2"></i> Residual Income Matching Bonus</h3>
    <p class="text-muted mb-3" style="font-size: 0.9rem;">
        A unique affiliate program for leaders. Hold a FOM rank (at least Trainee), build ranked downlines —
        L1 needs 4 with ranks, then each member needs 2 ranked downlines per level up to L6 —
        and earn a weekly % of their FOM Affiliate Bonus every Monday.
    </p>

    {{-- Rank gate --}}
    @if(!$hasRank)
        <div class="alert alert-warning shadow-sm font-weight-bold" style="border-radius: 10px;">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            You need a FOM rank (at least Trainee) to earn Level 1.
            <a href="{{ route('user.fom-rank') }}" class="alert-link">See your rank progress →</a>
        </div>
    @else
        <div class="alert alert-success shadow-sm font-weight-bold" style="border-radius: 10px;">
            <i class="fas fa-check-circle mr-1"></i>
            Rank gate passed — you are eligible up to
            <span class="badge badge-dark">{{ ($matrix['eligible_level'] ?? 0) > 0 ? 'Level ' . $matrix['eligible_level'] : 'no level yet (build 4 ranked downlines)' }}</span>
        </div>
    @endif

    {{-- Referral structure / matrix --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-project-diagram mr-1"></i> My matching structure</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="font-size: 0.85rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>Level</th>
                            <th>Rank criteria</th>
                            <th>Income</th>
                            <th>My progress</th>
                            <th>Members (ranked downlines)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($levels as $lv)
                            @php($info = $matrix['levels'][$lv->level] ?? null)
                            <tr class="{{ ($matrix['eligible_level'] ?? 0) >= $lv->level ? 'table-success' : '' }}">
                                <td class="font-weight-bold">L{{ $lv->level }}</td>
                                <td>
                                    {{ $lv->required_members }} downlines with ranks
                                    @if($lv->level > 1)<small class="text-muted d-block">(each previous member: {{ $lv->per_parent }})</small>@endif
                                </td>
                                <td class="font-weight-bold text-primary">{{ rtrim(rtrim(number_format((float) $lv->income_percent, 3), '0'), '.') }}% of FOM Affiliate Bonus</td>
                                <td>{{ $info ? count($info['members']) : 0 }} / {{ $lv->required_members }}</td>
                                <td style="max-width: 320px;">
                                    <small class="text-muted">{{ implode(', ', array_slice($memberNames[$lv->level] ?? [], 0, 12)) ?: '—' }}{{ count($memberNames[$lv->level] ?? []) > 12 ? '…' : '' }}</small>
                                </td>
                                <td>
                                    @php($appr = ($approvals ?? collect())->get($lv->level))
                                    @if($appr && $appr->status === 'approved')
                                        <span class="badge badge-success">APPROVED — PAYING</span>
                                    @elseif($appr && $appr->status === 'pending')
                                        <span class="badge badge-warning text-dark">AWAITING ADMIN APPROVAL</span>
                                    @elseif($appr && $appr->status === 'rejected')
                                        <span class="badge badge-danger">REJECTED</span>
                                    @elseif($info && $info['complete'])
                                        <span class="badge badge-info">COMPLETE</span>
                                    @else
                                        <span class="badge badge-secondary">NOT YET</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Interactive Team Tree --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-header bg-light font-weight-bold d-flex justify-content-between align-items-center">
            <span><i class="fas fa-network-wired mr-1"></i> Interactive Team Tree</span>
            <span>
                <button type="button" class="btn btn-sm btn-outline-primary py-0" onclick="fomTreeToggleAll(true)" style="font-size: 0.72rem;">Expand all</button>
                <button type="button" class="btn btn-sm btn-outline-secondary py-0" onclick="fomTreeToggleAll(false)" style="font-size: 0.72rem;">Collapse all</button>
            </span>
        </div>
        <div class="card-body" id="fom-team-tree" style="overflow-x: auto;">
            <small class="text-muted d-block mb-2">Click a member to expand/collapse their branch. Colors show the matching level each ranked member fills.</small>
        </div>
    </div>
    <script>
        (function () {
            var treeData = {!! json_encode($teamTree ?? ['children' => []]) !!};
            var levelColors = ['#343a40', '#007bff', '#28a745', '#17a2b8', '#ffc107', '#fd7e14', '#dc3545'];

            function renderNode(node) {
                var li = document.createElement('li');
                li.style.cssText = 'list-style:none; margin:4px 0;';
                var hasKids = node.children && node.children.length > 0;

                var badge = document.createElement('span');
                var color = levelColors[node.level] || '#6c757d';
                badge.style.cssText = 'display:inline-block; padding:4px 10px; border-radius:16px; color:#fff; font-size:0.78rem; font-weight:700; cursor:' + (hasKids ? 'pointer' : 'default') + '; background:' + color + ';';
                badge.innerHTML = (hasKids ? '<i class="fas fa-caret-down mr-1"></i>' : '')
                    + (node.level > 0 ? 'L' + node.level + ' · ' : '')
                    + node.name + ' <small style="opacity:.85;">(' + node.rank + ')</small>';
                li.appendChild(badge);

                if (hasKids) {
                    var ul = document.createElement('ul');
                    ul.style.cssText = 'margin:4px 0 4px 22px; padding-left:12px; border-left:2px dashed #dee2e6;';
                    node.children.forEach(function (c) { ul.appendChild(renderNode(c)); });
                    li.appendChild(ul);
                    badge.addEventListener('click', function () {
                        var hidden = ul.style.display === 'none';
                        ul.style.display = hidden ? '' : 'none';
                        badge.querySelector('i').className = hidden ? 'fas fa-caret-down mr-1' : 'fas fa-caret-right mr-1';
                    });
                }
                return li;
            }

            var rootUl = document.createElement('ul');
            rootUl.style.cssText = 'margin:0; padding:0;';
            rootUl.appendChild(renderNode(treeData));
            document.getElementById('fom-team-tree').appendChild(rootUl);

            window.fomTreeToggleAll = function (open) {
                document.querySelectorAll('#fom-team-tree ul ul').forEach(function (ul) {
                    ul.style.display = open ? '' : 'none';
                });
                document.querySelectorAll('#fom-team-tree i.fas').forEach(function (i) {
                    i.className = open ? 'fas fa-caret-down mr-1' : 'fas fa-caret-right mr-1';
                });
            };
        })();
    </script>

    {{-- My weekly payouts --}}
    @if($myPayouts->isNotEmpty())
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
            <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> My matching income</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light"><tr><th>Week</th><th>Eligible Level</th><th>Income</th><th>Breakdown</th></tr></thead>
                        <tbody>
                            @foreach($myPayouts as $p)
                                <tr>
                                    <td>{{ $p->parsed['week'] ?? '—' }}</td>
                                    <td class="font-weight-bold">L{{ $p->parsed['eligible_level'] ?? '?' }}</td>
                                    <td class="font-weight-bold text-success">${{ number_format((float) ($p->parsed['total'] ?? 0), 4) }}</td>
                                    <td>
                                        @foreach((array) ($p->parsed['breakdown'] ?? []) as $b)
                                            <small class="badge badge-light border mr-1">L{{ $b['level'] }}: {{ $b['percent'] }}% of ${{ number_format((float) $b['members_bonus'], 2) }}</small>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
</div>
@include('user.footer')
</div>
