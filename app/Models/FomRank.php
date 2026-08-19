<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * FOM Licence Miner rank ladder — 17 ranks in 5 groups
 * (Chief Promoter, Chief Manager, Chief Supervisor, Executive Director,
 * Chief Regional Supervisor), Trainee → Royal Crown Diamond.
 *
 * criteria_json types:
 *   {"type":"bonus","amount":100}                                — FOM referral bonus earned ≥ $
 *   {"type":"active_directs","count":4}                          — direct referrals with active FOM payment
 *   {"type":"ranks","requires":[{"rank":"Builder","count":2,"distinct_lines":true,"both_sides":true}, …]}
 *       — N qualified holders anywhere in the downline; distinct_lines →
 *         they must sit under different direct-referral branches;
 *         both_sides → branches on BOTH the LEFT and RIGHT leg.
 */
class FomRank extends Model
{
    protected $table = 'fom_ranks';

    protected $fillable = [
        'group_name', 'name', 'level', 'vb_required', 'team_turnover',
        'personal_turnover', 'criteria_label', 'criteria_json',
        'licence_min', 'reward', 'extra_bonuses', 'is_active',
    ];

    protected $casts = [
        'vb_required'       => 'decimal:4',
        'team_turnover'     => 'decimal:2',
        'personal_turnover' => 'decimal:2',
        'reward'            => 'decimal:2',
        'criteria_json'     => 'array',
        'is_active'         => 'boolean',
    ];

    /** FOM licence ladder for "FOM Licence Taken At Least". */
    public const LICENCE_ORDER = [
        'BASIC' => 1, 'STARTER' => 2, 'LIGHT' => 3, 'PRO' => 4, 'ADVANCED' => 5,
        'PREMIUM' => 6, 'TYCOON' => 7, 'MASTER' => 8, 'PRO MASTER' => 9, 'SUPER' => 10,
    ];

    public static function ensureTableAndData(): void
    {
        try {
            if (!Schema::hasTable('fom_ranks')) {
                Schema::create('fom_ranks', function ($table) {
                    $table->id();
                    $table->string('group_name', 60);
                    $table->string('name', 60)->unique();
                    $table->unsignedInteger('level')->unique();
                    $table->decimal('vb_required', 20, 4)->default(0);
                    $table->decimal('team_turnover', 20, 2)->default(0);
                    $table->decimal('personal_turnover', 20, 2)->default(0);
                    $table->string('criteria_label')->nullable();
                    $table->text('criteria_json')->nullable();
                    $table->string('licence_min', 30)->default('BASIC');
                    $table->decimal('reward', 20, 2)->default(0);
                    $table->string('extra_bonuses')->nullable();
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }
            if (self::count() === 0) {
                self::seedDefaults();
            }
        } catch (\Throwable $e) {
            Log::error('FomRank ensureTableAndData failed: ' . $e->getMessage());
        }
    }

    public static function seedDefaults(): void
    {
        $r = function ($group, $name, $level, $vb, $tt, $pt, $label, $json, $lic, $reward, $extras) {
            self::updateOrCreate(['name' => $name], [
                'group_name' => $group, 'level' => $level, 'vb_required' => $vb,
                'team_turnover' => $tt, 'personal_turnover' => $pt,
                'criteria_label' => $label, 'criteria_json' => $json,
                'licence_min' => $lic, 'reward' => $reward, 'extra_bonuses' => $extras,
                'is_active' => true,
            ]);
        };

        // ── 1. Chief Promoter ──
        $r('Chief Promoter', 'Trainee', 1, 200, 5000, 1000,
            'Bonus $100 (FOM Licence referral bonus earned)',
            ['type' => 'bonus', 'amount' => 100], 'BASIC', 50, 'None');
        $r('Chief Promoter', 'Builder', 2, 500, 10000, 2000,
            '4 Active Direct referral',
            ['type' => 'active_directs', 'count' => 4], 'BASIC', 200, 'PIN');
        $r('Chief Promoter', 'Junior', 3, 1200, 50000, 5000,
            '2 Builders (both sides left and right side)',
            ['type' => 'ranks', 'requires' => [['rank' => 'Builder', 'count' => 2, 'distinct_lines' => true, 'both_sides' => true]]],
            'BASIC', 1000, 'PIN');
        $r('Chief Promoter', 'Promoter', 4, 3000, 200000, 10000,
            '3 Juniors',
            ['type' => 'ranks', 'requires' => [['rank' => 'Junior', 'count' => 3]]],
            'STARTER', 2000, 'PIN');
        $r('Chief Promoter', 'Consultant', 5, 6000, 500000, 15000,
            '2 Promoters (both sides left and right side)',
            ['type' => 'ranks', 'requires' => [['rank' => 'Promoter', 'count' => 2, 'distinct_lines' => true, 'both_sides' => true]]],
            'STARTER', 7500, 'PIN');

        // ── 2. Chief Manager ──
        $r('Chief Manager', 'Chief Partner', 6, 15000, 1000000, 20000,
            '3 Consultants + 2 Juniors (both sides left and right side)',
            ['type' => 'ranks', 'requires' => [
                ['rank' => 'Consultant', 'count' => 3, 'distinct_lines' => true, 'both_sides' => true],
                ['rank' => 'Junior', 'count' => 2, 'distinct_lines' => true, 'both_sides' => true],
            ]], 'STARTER', 15000, 'Global Event Ticket, PIN');
        $r('Chief Manager', 'Bronze', 7, 30000, 5000000, 50000,
            '3 Consultants + 2 Juniors (both sides left and right side)',
            ['type' => 'ranks', 'requires' => [
                ['rank' => 'Consultant', 'count' => 3, 'distinct_lines' => true, 'both_sides' => true],
                ['rank' => 'Junior', 'count' => 2, 'distinct_lines' => true, 'both_sides' => true],
            ]], 'STARTER', 75000, 'Global Event Ticket, PIN');
        $r('Chief Manager', 'Team Leader', 8, 62000, 10000000, 100000,
            '2 Bronze (both sides) + 3 Team Leaders',
            ['type' => 'ranks', 'requires' => [
                ['rank' => 'Bronze', 'count' => 2, 'distinct_lines' => true, 'both_sides' => true],
                ['rank' => 'Team Leader', 'count' => 3],
            ]], 'LIGHT', 150000, 'Global Event Ticket, PIN');
        $r('Chief Manager', 'Sapphire', 9, 150000, 20000000, 500000,
            '2 Bronze (both sides) + 3 Team Leaders',
            ['type' => 'ranks', 'requires' => [
                ['rank' => 'Bronze', 'count' => 2, 'distinct_lines' => true, 'both_sides' => true],
                ['rank' => 'Team Leader', 'count' => 3],
            ]], 'PRO', 300000, 'Global Event Ticket, PIN');

        // ── 3. Chief Supervisor ──
        $r('Chief Supervisor', 'Ruby', 10, 300000, 40000000, 1000000,
            '2 Sapphire',
            ['type' => 'ranks', 'requires' => [['rank' => 'Sapphire', 'count' => 2]]],
            'ADVANCED', 600000, 'Global Event Ticket, PIN');
        $r('Chief Supervisor', 'Supervisor', 11, 650000, 80000000, 2000000,
            '2 Ruby (both sides)',
            ['type' => 'ranks', 'requires' => [['rank' => 'Ruby', 'count' => 2, 'distinct_lines' => true, 'both_sides' => true]]],
            'PREMIUM', 1000000, 'Global Event Ticket, PIN');
        $r('Chief Supervisor', 'Emerald', 12, 1300000, 120000000, 5000000,
            '2 Supervisors (different line)',
            ['type' => 'ranks', 'requires' => [['rank' => 'Supervisor', 'count' => 2, 'distinct_lines' => true]]],
            'TYCOON', 1500000, 'Latest Smart Watch, Global Event Ticket, PIN');

        // ── 4. Executive Director ──
        $r('Executive Director', 'Diamond', 13, 3500000, 150000000, 10000000,
            '3 Emerald (different line)',
            ['type' => 'ranks', 'requires' => [['rank' => 'Emerald', 'count' => 3, 'distinct_lines' => true]]],
            'MASTER', 3000000, 'Latest Smart iPhone, Global Event Ticket, PIN');
        $r('Executive Director', 'Blue Diamond', 14, 6500000, 200000000, 15000000,
            '2 Diamond (different line)',
            ['type' => 'ranks', 'requires' => [['rank' => 'Diamond', 'count' => 2, 'distinct_lines' => true]]],
            'PRO MASTER', 4000000, '2-star trip to Dubai for 5 days, Global Event Ticket, PIN');
        $r('Executive Director', 'Black Diamond', 15, 12000000, 250000000, 20000000,
            '2 Blue Diamond (different line)',
            ['type' => 'ranks', 'requires' => [['rank' => 'Blue Diamond', 'count' => 2, 'distinct_lines' => true]]],
            'PRO MASTER', 6000000, '5-star trip to Dubai for 2 days, Global Event Ticket, PIN');

        // ── 5. Chief Regional Supervisor ──
        $r('Chief Regional Supervisor', 'Crown Diamond', 16, 24000000, 350000000, 50000000,
            '2 Black Diamond',
            ['type' => 'ranks', 'requires' => [['rank' => 'Black Diamond', 'count' => 2]]],
            'SUPER', 10000000, '5-star trip to Dubai for 4 days, Global Event Ticket, PIN');
        $r('Chief Regional Supervisor', 'Royal Crown Diamond', 17, 50000000, 550000000, 100000000,
            '2 Crown Diamond',
            ['type' => 'ranks', 'requires' => [['rank' => 'Crown Diamond', 'count' => 2]]],
            'SUPER', 15000000, 'Mercedes-Benz, Global Event Ticket, PIN');
    }

    public static function ordered()
    {
        return self::where('is_active', true)->orderBy('level');
    }
}
