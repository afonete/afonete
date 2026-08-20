<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * Residual Income Matching Bonus — level configuration (admin-modifiable).
 *
 * L1: first 4 ranked downlines → 10% of their weekly FOM Affiliate Bonus.
 * L2..L6: each member of the previous level needs `per_parent` (2) ranked
 * downlines → required_members doubles (8, 16, 32, 64, 128); income %
 * 5 / 2 / 1 / 0.5 / 0.2.
 */
class FomResidualLevel extends Model
{
    protected $table = 'fom_residual_levels';

    protected $fillable = ['level', 'required_members', 'per_parent', 'income_percent', 'is_active'];

    protected $casts = [
        'income_percent' => 'decimal:3',
        'is_active'      => 'boolean',
    ];

    public static function ensureTableAndData(): void
    {
        try {
            if (!Schema::hasTable('fom_residual_levels')) {
                Schema::create('fom_residual_levels', function ($table) {
                    $table->id();
                    $table->unsignedInteger('level')->unique();
                    $table->unsignedInteger('required_members');
                    $table->unsignedInteger('per_parent')->default(2);
                    $table->decimal('income_percent', 8, 3);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }
            if (self::count() === 0) {
                self::seedDefaults();
            }
        } catch (\Throwable $e) {
            Log::error('FomResidualLevel ensureTableAndData failed: ' . $e->getMessage());
        }
    }

    public static function seedDefaults(): void
    {
        foreach ([
            [1, 4,   4, 10.0],
            [2, 8,   2, 5.0],
            [3, 16,  2, 2.0],
            [4, 32,  2, 1.0],
            [5, 64,  2, 0.5],
            [6, 128, 2, 0.2],
        ] as [$level, $required, $perParent, $pct]) {
            self::updateOrCreate(['level' => $level], [
                'required_members' => $required,
                'per_parent'       => $perParent,
                'income_percent'   => $pct,
                'is_active'        => true,
            ]);
        }
    }

    public static function ordered()
    {
        return self::where('is_active', true)->orderBy('level');
    }
}
