<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TokenSaving;
use App\Models\TokenSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Admin viewer for the Token Saving Wallet feature.
 *
 * Lists every user who has moved tokens into the SAVING_TOKEN wallet,
 * with per-record status (locked / matured / withdrawn), totals, and
 * an optional user filter so admins can audit the scheme.
 */
class TokenSavingAdminController extends Controller
{
    public function index(Request $request)
    {
        $symbol = TokenSetting::currentSymbol() ?: 'FOCOIN';

        // ── Summary KPI cards ──
        $totalSavers      = TokenSaving::distinct('user_id')->count('user_id');
        $totalRecords     = TokenSaving::count();
        $totalDeposited   = (float) TokenSaving::sum('amount');
        $totalWithdrawn   = (float) TokenSaving::where('status', 'withdrawn')->sum('withdrawn_amount');
        $lockedNow        = (float) TokenSaving::whereIn('status', ['active','matured'])->sum('amount')
                              - $totalWithdrawn;
        $maturedCount      = TokenSaving::where('status', 'matured')
                              ->orWhere(function ($q) {
                                  $q->where('mature_date', '<=', Carbon::now()->startOfDay())
                                    ->where('status', '!=', 'withdrawn');
                              })->count();
        $activeCount       = TokenSaving::where('status', 'active')
                              ->where('mature_date', '>', Carbon::now()->startOfDay())->count();
        $withdrawnCount    = TokenSaving::where('status', 'withdrawn')->count();

        // ── Records table (paginated, filterable) ──
        $query = TokenSaving::with('user')
            ->orderByDesc('created_at');

        $search = trim((string) $request->input('q', ''));
        $status = trim((string) $request->input('status', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%");
            });
        }
        if (in_array($status, ['active','matured','withdrawn'], true)) {
            if ($status === 'matured') {
                $query->where(function ($q) {
                    $q->where('status', 'matured')
                      ->orWhere(function ($q2) {
                          $q2->where('mature_date', '<=', Carbon::now()->startOfDay())
                             ->where('status', '!=', 'withdrawn');
                      });
                });
            } else {
                $query->where('status', $status);
            }
        }

        $records = $query->paginate(25)->withQueryString();

        // Derive a display status for each row (avoids re-querying in Blade)
        $records->getCollection()->transform(function ($r) {
            if ($r->status === 'withdrawn') {
                $r->display_status = 'withdrawn';
            } elseif ($r->status === 'matured' || ($r->mature_date && Carbon::parse($r->mature_date)->lte(Carbon::now()->startOfDay()))) {
                $r->display_status = 'matured';
            } else {
                $r->display_status = 'active';
            }
            return $r;
        });

        return view('admin.token-savings', compact(
            'symbol',
            'totalSavers','totalRecords','totalDeposited','totalWithdrawn','lockedNow',
            'maturedCount','activeCount','withdrawnCount',
            'records','search','status'
        ));
    }
}
