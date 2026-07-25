<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\TeamLeader;
use App\Models\User;

/**
 * Audits how `users` data correlates with `team_leaders` data.
 *
 * The two tables are linked by a SOFT string match:
 *     team_leaders.User_name  <->  users.user
 * There is NO foreign key, and users.user is nullable + not unique, so this
 * command surfaces orphans, mismatches, duplicate usernames, and status drift.
 *
 * Read-only — safe to run any time.  Usage:  php artisan teamleader:audit
 */
class AuditTeamLeaderCorrelation extends Command
{
    protected $signature = 'teamleader:audit';
    protected $description = 'Audit & validate the correlation between users and team_leaders';

    public function handle()
    {
        $this->info('Auditing team_leaders <-> users correlation…');
        $this->line(str_repeat('─', 60));

        // --- Build lookup indexes ---
        $usersByUser  = User::whereNotNull('user')->select(['id', 'user', 'email', 'has_paid_package'])->get()->groupBy('user');
        $usersByEmail = User::select(['id', 'user', 'email', 'has_paid_package'])->get()->keyBy(function ($u) {
            return strtolower($u->email);
        });
        $leaders      = TeamLeader::all();

        $totalLeaders = $leaders->count();
        $totalUsers   = User::count();
        $this->line("Total team_leaders rows : <fg=cyan>{$totalLeaders}</>");
        $this->line("Total users rows        : <fg=cyan>{$totalUsers}</>");
        $this->line(str_repeat('─', 60));

        // --- 1. Match each leader to a user via User_name == users.user ---
        $matched = 0;
        $orphanLeaders = 0;
        $emailMismatches = [];
        $ambiguous = 0;

        foreach ($leaders as $leader) {
            $matches = $usersByUser->get($leader->User_name);

            if (!$matches || $matches->isEmpty()) {
                $orphanLeaders++;
                $this->line("  <fg=red>ORPHAN leader</> #{$leader->id}  '{$leader->User_name}' ({$leader->Email}) — no user with users.user matching");
                continue;
            }

            $matched++;
            if ($matches->count() > 1) {
                $ambiguous++;
                $this->line("  <fg=yellow>AMBIGUOUS</> username '{$leader->User_name}' matches {$matches->count()} users");
            }

            foreach ($matches as $u) {
                if (strtolower($u->email) !== strtolower($leader->Email)) {
                    $emailMismatches[] = [
                        'leader'      => $leader->User_name,
                        'leader_email'=> $leader->Email,
                        'user_email'  => $u->email,
                    ];
                }
            }
        }

        $this->line("Leaders matched to a user        : <fg=green>{$matched}</>");
        $this->line("Orphan leaders (no matching user): <fg=red>{$orphanLeaders}</>");
        $this->line("Ambiguous usernames (>1 user)    : <fg=yellow>{$ambiguous}</>");
        $this->line("Leader/user email mismatches     : <fg=red>" . count($emailMismatches) . '</>');
        foreach (array_slice($emailMismatches, 0, 10) as $m) {
            $this->line("    • '{$m['leader']}': leader={$m['leader_email']}  vs  user={$m['user_email']}");
        }
        $this->line(str_repeat('─', 60));

        // --- 2. Users flagged as leaders but with no team_leaders row ---
        $leaderUsers = User::whereIn('has_paid_package', ['TEAM_LEADER', 'SUPER_LEADER'])->get();
        $missingRow = 0;
        foreach ($leaderUsers as $u) {
            if (!TeamLeader::where('User_name', $u->user)->exists()) {
                $missingRow++;
                $this->line("  <fg=red>USER #{$u->id}</> '{$u->user}' has_paid_package={$u->has_paid_package} but NO team_leaders row");
            }
        }
        $this->line("Users w/ leader package but no team_leaders row : <fg=red>{$missingRow}</>");
        $this->line(str_repeat('─', 60));

        // --- 3. Duplicate usernames in users.user (join ambiguity) ---
        $dups = DB::table('users')
            ->select('user', DB::raw('COUNT(*) as c'))
            ->whereNotNull('user')
            ->groupBy('user')
            ->having('c', '>', 1)
            ->get();
        $this->line("Duplicate usernames in users.user : <fg=yellow>{$dups->count()}</>");
        foreach ($dups->take(10) as $d) {
            $this->line("    • '{$d->user}' used by {$d->c} users");
        }
        $this->line(str_repeat('─', 60));

        // --- 4. Status / leadership_level drift between the two tables ---
        $statusDrift = 0;
        foreach ($leaders as $leader) {
            $u = $usersByUser->get($leader->User_name) ? $usersByUser->get($leader->User_name)->first() : null;
            if (!$u) {
                continue;
            }
            $expectedLevel = $leader->leadership_level ?: 'TEAM_LEADER';
            $isLeaderUser  = in_array($u->has_paid_package, ['TEAM_LEADER', 'SUPER_LEADER']);
            // leader confirmed but user not flagged as a leader package
            if ($leader->status === 'confirmed' && !$isLeaderUser) {
                $statusDrift++;
                $this->line("  <fg=yellow>DRIFT</> '{$leader->User_name}': leader confirmed but user.has_paid_package='{$u->has_paid_package}'");
            }
            // user is a leader but level disagrees
            if ($isLeaderUser && $u->has_paid_package !== $expectedLevel) {
                $statusDrift++;
                $this->line("  <fg=yellow>DRIFT</> '{$leader->User_name}': leader level {$expectedLevel} vs user {$u->has_paid_package}");
            }
        }
        $this->line("Status / level drift cases : <fg=yellow>{$statusDrift}</>");
        $this->line(str_repeat('─', 60));

        $this->info('Audit complete.');
        return 0;
    }
}
