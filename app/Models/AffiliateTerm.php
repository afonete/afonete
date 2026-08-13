<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AffiliateTerm extends Model
{
    use HasFactory;

    protected $table = 'affiliate_terms';

    protected $fillable = [
        'terms_content',
        'is_active',
        'updated_by',
    ];

    /**
     * Ensures table exists and seeds default affiliate terms if empty.
     */
    public static function ensureTableAndData()
    {
        try {
            if (!Schema::hasTable('affiliate_terms')) {
                Schema::create('affiliate_terms', function (Blueprint $table) {
                    $table->id();
                    $table->longText('terms_content')->nullable();
                    $table->boolean('is_active')->default(true);
                    $table->unsignedBigInteger('updated_by')->nullable();
                    $table->timestamps();
                });
            }

            if (self::count() === 0) {
                self::create([
                    'terms_content' => "<h3>Affiliate Partner Program Terms & Conditions</h3>
<p>By becoming an Affiliate Partner with FOM Licence Miner / Bifonex, you agree to the following terms:</p>
<ul>
    <li><strong>1. Compliance:</strong> You agree to represent the platform accurately and adhere to all anti-spam and ethical referral guidelines.</li>
    <li><strong>2. Binary & Commission Network:</strong> Binary status activation enables placement and binary commission tracking across your referral network.</li>
    <li><strong>3. Package Terms:</strong> Commission earnings and volume calculations are governed by active package tiers and system compensation rules.</li>
    <li><strong>4. Account Integrity:</strong> Misrepresentation, fraudulent referral creation, or unethical manipulation will result in affiliate termination.</li>
</ul>
<p>Check the acceptance box below to confirm your agreement and activate your Binary Status.</p>",
                    'is_active' => true,
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AffiliateTerm ensureTableAndData error: " . $e->getMessage());
        }
    }

    public static function currentTerms(): string
    {
        self::ensureTableAndData();
        $term = self::where('is_active', true)->first();
        return $term && !empty($term->terms_content)
            ? $term->terms_content
            : "<h3>Affiliate Partner Program Terms & Conditions</h3><p>By becoming an Affiliate Partner, you agree to represent the platform accurately and adhere to ethical referral guidelines. Accepting these terms activates your Binary Status.</p>";
    }
}
