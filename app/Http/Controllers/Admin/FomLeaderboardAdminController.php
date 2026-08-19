<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FomLeaderboardEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Admin: weekly LEADERBOARD control.
 *
 * The system generates the top-10 board from real UVP/FOM earnings every
 * week. Here the admin can view any week, regenerate it, PIN any user at
 * any position (system entries re-flow around the pins — e.g. pin someone
 * at #1 and the system's #1 shifts to #2/#3), and remove pins.
 */
class FomLeaderboardAdminController extends Controller
{
    private function resolveWeek(Request $request): Carbon
    {
        $raw = trim((string) $request->query('week', ''));
        try {
            return $raw !== ''
                ? Carbon::parse($raw)->startOfWeek()
                : FomLeaderboardEntry::currentWeekStart();
        } catch (\Throwable $e) {
            return FomLeaderboardEntry::currentWeekStart();
        }
    }

    public function index(Request $request)
    {
        FomLeaderboardEntry::ensureTable();
        $week = $this->resolveWeek($request);

        $entries = FomLeaderboardEntry::board($week);

        // Recent weeks selector (this week + previous 7)
        $weeks = [];
        for ($i = 0; $i < 8; $i++) {
            $w = FomLeaderboardEntry::currentWeekStart()->subWeeks($i);
            $weeks[] = [
                'value' => $w->toDateString(),
                'label' => $w->format('d M') . ' - ' . $w->copy()->addDays(6)->format('d M Y'),
            ];
        }

        return view('admin.fom-leaderboard.index', [
            'entries'   => $entries,
            'week'      => $week,
            'weekLabel' => $week->format('d M') . ' - ' . $week->copy()->addDays(6)->format('d M Y'),
            'weeks'     => $weeks,
        ]);
    }

    /** Pin a user at a position for the selected week. */
    public function pin(Request $request)
    {
        $request->validate([
            'week'          => 'required|date',
            'position'      => 'required|integer|min:1|max:10',
            'username'      => 'required|string|max:100',
            'amount'        => 'nullable|numeric|min:0',
            'earned_from'   => 'nullable|in:UVP,FOM Licence Miner',
        ]);

        $week = Carbon::parse($request->input('week'))->startOfWeek();

        // Look up by username (users.user), then email, then numeric id.
        $needle = trim((string) $request->input('username'));
        $user = User::where('user', $needle)->first()
            ?: User::where('email', $needle)->first()
            ?: (ctype_digit($needle) ? User::find((int) $needle) : null);

        if (!$user) {
            return back()->with('error', "User '{$needle}' not found (searched username, email and ID).");
        }

        $amount = $request->filled('amount') ? (float) $request->input('amount') : null;
        $source = $request->filled('earned_from') ? (string) $request->input('earned_from') : null;

        $row = FomLeaderboardEntry::pin($user->id, (int) $request->input('position'), $week, $amount, $source);

        if (!$row) {
            return back()->with('error', 'Could not pin the user — please try again.');
        }

        return redirect()->route('admin.fom-leaderboard.index', ['week' => $week->toDateString()])
            ->with('success', "Pinned {$row->display_name} at position #{$row->position} for week {$week->format('d M')}.");
    }

    /** Remove a pinned entry (system rows re-flow). */
    public function unpin(Request $request, int $id)
    {
        $entry = FomLeaderboardEntry::find($id);
        $week  = $entry ? Carbon::parse($entry->week_start)->toDateString() : null;

        if (!FomLeaderboardEntry::unpin($id)) {
            return back()->with('error', 'Only admin-pinned entries can be removed.');
        }

        return redirect()->route('admin.fom-leaderboard.index', array_filter(['week' => $week]))
            ->with('success', 'Pin removed — system entries re-flowed.');
    }

    /** Regenerate the system entries for the selected week (pins survive). */
    public function regenerate(Request $request)
    {
        $request->validate(['week' => 'required|date']);
        $week = Carbon::parse($request->input('week'))->startOfWeek();

        $written = FomLeaderboardEntry::generateForWeek($week);

        return redirect()->route('admin.fom-leaderboard.index', ['week' => $week->toDateString()])
            ->with('success', "Leaderboard regenerated — {$written} system entr" . ($written === 1 ? 'y' : 'ies') . ' written (admin pins kept).');
    }
}
