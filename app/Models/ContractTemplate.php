<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Admin-managed contract content:
 *  - body_content:    the full agreement shown on /user/contract (the page
 *                     users read and sign). When NULL/empty the view falls
 *                     back to the original hardcoded agreement text, so the
 *                     page keeps working before the admin ever saves.
 *  - summary_content: the short contract summary shown on
 *                     /user/contracts/bifonex.
 *
 * Follows the same pattern as AffiliateTerm (admin-editable rich text with
 * runtime table self-heal).
 */
class ContractTemplate extends Model
{
    use HasFactory;

    protected $table = 'contract_templates';

    protected $fillable = [
        'body_content',
        'summary_content',
        'is_active',
        'updated_by',
    ];

    /** Per-request memo. */
    protected static $ensured = false;

    /**
     * HTML-aware blank check. Rich-text editors (Trix) submit an "empty"
     * editor as markup like <div><br></div> or <p>&nbsp;</p>; a plain
     * trim() would treat that as real content and the original contract
     * would never be restored. Strips tags and whitespace entities first.
     */
    public static function isBlankHtml($html): bool
    {
        if ($html === null) {
            return true;
        }
        $text = strip_tags((string) $html);
        $text = str_replace(["\u{00A0}", '&nbsp;'], ' ', $text);
        return trim($text) === '';
    }

    public static function ensureTableAndData()
    {
        if (static::$ensured) {
            return;
        }
        static::$ensured = true;

        try {
            if (!Schema::hasTable('contract_templates')) {
                Schema::create('contract_templates', function (Blueprint $table) {
                    $table->id();
                    $table->longText('body_content')->nullable();
                    $table->longText('summary_content')->nullable();
                    $table->boolean('is_active')->default(true);
                    $table->unsignedBigInteger('updated_by')->nullable();
                    $table->timestamps();
                });
            }

            if (self::count() === 0) {
                self::create([
                    // Seed with the project's original full agreement so the
                    // admin can start from it and modify as needed.
                    'body_content'    => self::defaultFullContract(),
                    'summary_content' => self::defaultSummary(),
                    'is_active'       => true,
                ]);
            } else {
                // Backfill installs created before the full-contract seed:
                // put the original agreement into the editor when empty.
                $tpl = self::first();
                if ($tpl && self::isBlankHtml($tpl->body_content)) {
                    $default = self::defaultFullContract();
                    if ($default !== null) {
                        $tpl->body_content = $default;
                        $tpl->save();
                    }
                }

                // One-time heal: the original agreement contained an orphan
                // <td> (no surrounding <table>) which crashes dompdf when the
                // DB body is rendered into the full-contract PDF. Fix rows
                // that still carry it (seeded before the seed file was fixed).
                if ($tpl && str_contains((string) $tpl->body_content, '<td> <span>Holding Id</span>  </td>')) {
                    $tpl->body_content = str_replace(
                        '<td> <span>Holding Id</span>  </td>',
                        '<span>Holding Id</span>',
                        $tpl->body_content
                    );
                    $tpl->save();
                }

                // Upgrade an un-edited OLD default summary (pre-placeholder
                // version) to the new per-user placeholder default. A summary
                // the admin customised (differs from the old default) is
                // never touched.
                if ($tpl) {
                    $summary = trim((string) $tpl->summary_content);
                    $isOldDefault = !self::isBlankHtml($summary)
                        && !str_contains($summary, '{USER_NAME}')
                        && str_contains($summary, 'Contract Summary — Independent Marketing Affiliate')
                        && str_contains($summary, '<strong>Parties:</strong> You (the Independent Marketing Affiliate) and Bifonex');
                    if (self::isBlankHtml($summary) || $isOldDefault) {
                        $tpl->summary_content = self::defaultSummary();
                        $tpl->save();
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("ContractTemplate ensureTableAndData error: " . $e->getMessage());
        }
    }

    /**
     * The full agreement body configured by the admin, or null when the
     * hardcoded fallback should be used.
     */
    public static function currentBody(): ?string
    {
        self::ensureTableAndData();

        try {
            $tpl = self::where('is_active', true)->latest('updated_at')->first();
            return ($tpl && !self::isBlankHtml($tpl->body_content)) ? $tpl->body_content : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * The short contract summary (always returns displayable content).
     */
    public static function currentSummary(): string
    {
        self::ensureTableAndData();

        try {
            $tpl = self::where('is_active', true)->latest('updated_at')->first();
            return ($tpl && !self::isBlankHtml($tpl->summary_content)) ? $tpl->summary_content : self::defaultSummary();
        } catch (\Throwable $e) {
            return self::defaultSummary();
        }
    }

    /**
     * Placeholders replaced with the viewing user's info at render time.
     * {USER_NAME}, {USER_COUNTRY}, {USER_USERNAME}, {USER_ID}, {COMPANY_NAME}
     */
    public static function renderForUser(?string $html, $user): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $company = 'Bifonex';
        try {
            $setting = \App\Models\TokenSetting::first();
            if ($setting && !empty($setting->company_name)) {
                $company = $setting->company_name;
            }
        } catch (\Throwable $e) {
        }

        $map = [
            '{USER_NAME}'     => $user->name ?? '',
            '{USER_COUNTRY}'  => $user->country ?? '',
            '{USER_USERNAME}' => $user->user ?? '',
            '{USER_ID}'       => ($user && method_exists($user, 'getTransferCode')) ? $user->getTransferCode() : ($user->transfer_code ?? ''),
            '{COMPANY_NAME}'  => $company,
        ];

        return strtr($html, $map);
    }

    /**
     * The project's original full agreement (extracted from the previously
     * hardcoded /user/contract view) with per-user placeholders. Loaded from
     * resources/contracts/default-full-contract.html.
     */
    public static function defaultFullContract(): ?string
    {
        $candidates = [];
        try {
            if (function_exists('resource_path')) {
                $candidates[] = resource_path('contracts/default-full-contract.html');
            }
        } catch (\Throwable $e) {
        }
        // Fallback that works without the Laravel app container (tests, CLI).
        $candidates[] = dirname(__DIR__, 2) . '/resources/contracts/default-full-contract.html';

        foreach ($candidates as $path) {
            try {
                if (is_file($path)) {
                    $html = file_get_contents($path);
                    if ($html !== false && trim($html) !== '') {
                        return $html;
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        return null;
    }

    public static function defaultSummary(): string
    {
        return "<h4>Contract Summary — Independent Marketing Affiliate (IMA) Agreement</h4>"
            . "<p><strong>{COMPANY_NAME}</strong> (the \"COMPANY\" / Disclosing Party)<br>"
            . "<strong>AND</strong><br>"
            . "Mr./ Mrs. / Ms. <strong>{USER_NAME}</strong><br>"
            . "Nationality, <strong>{USER_COUNTRY}</strong></p>"
            . "<ul>"
            . "<li><strong>Parties:</strong> {USER_NAME} (the Independent Marketing Affiliate) and {COMPANY_NAME} (the Company).</li>"
            . "<li><strong>Scope:</strong> The IMA Agreement, the Compensation Plan and the General Terms &amp; Conditions together form the entire agreement governing your relationship with the Company.</li>"
            . "<li><strong>Your commitments:</strong> Read, understand and comply with all terms; provide accurate information; act lawfully and ethically when promoting the Company.</li>"
            . "<li><strong>Confidentiality:</strong> Business, financial and member information you access is confidential and may not be disclosed or used outside the program.</li>"
            . "<li><strong>Compensation:</strong> Earnings are governed exclusively by the official Compensation Plan; no income is guaranteed.</li>"
            . "<li><strong>Changes:</strong> The Company may revise the Agreement from time to time at its sole discretion; continued participation constitutes acceptance.</li>"
            . "<li><strong>Signature:</strong> By signing, you confirm you read the full agreement and accept it with free will and consent.</li>"
            . "</ul>"
            . "<p><em>This summary is provided for convenience only — the full signed agreement below prevails.</em></p>";
    }
}
