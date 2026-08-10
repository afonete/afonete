<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycVerification extends Model
{
    use HasFactory;

    protected $table = 'kyc_verifications';

    protected $fillable = [
        'user_id',
        'overall_percentage',
        'status',

        // Level 1: Basic (25%)
        'level_1_status',
        'phone_number',
        'level_1_submitted_at',
        'level_1_approved_at',
        'level_1_admin_notes',

        // Level 2: Identity (75%)
        'level_2_status',
        'id_type',
        'id_front_path',
        'id_back_path',
        'selfie_path',
        'date_of_birth',
        'level_2_submitted_at',
        'level_2_approved_at',
        'level_2_admin_notes',

        // Level 3: Address (100%)
        'level_3_status',
        'address_doc_type',
        'address_doc_path',
        'full_address',
        'city',
        'country',
        'level_3_submitted_at',
        'level_3_approved_at',
        'level_3_admin_notes',

        'reviewed_by',
    ];

    protected $casts = [
        'date_of_birth'        => 'date',
        'level_1_submitted_at' => 'datetime',
        'level_1_approved_at'  => 'datetime',
        'level_2_submitted_at' => 'datetime',
        'level_2_approved_at'  => 'datetime',
        'level_3_submitted_at' => 'datetime',
        'level_3_approved_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get or create the KycVerification record for a user.
     */
    public static function forUser(User $user): self
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('kyc_verifications')) {
            try {
                \Illuminate\Support\Facades\Schema::create('kyc_verifications', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->unique();

                    $table->integer('overall_percentage')->default(0);
                    $table->string('status')->default('unsubmitted');

                    $table->string('level_1_status')->default('unsubmitted');
                    $table->string('phone_number')->nullable();
                    $table->timestamp('level_1_submitted_at')->nullable();
                    $table->timestamp('level_1_approved_at')->nullable();
                    $table->text('level_1_admin_notes')->nullable();

                    $table->string('level_2_status')->default('unsubmitted');
                    $table->string('id_type')->nullable();
                    $table->string('id_front_path')->nullable();
                    $table->string('id_back_path')->nullable();
                    $table->string('selfie_path')->nullable();
                    $table->date('date_of_birth')->nullable();
                    $table->timestamp('level_2_submitted_at')->nullable();
                    $table->timestamp('level_2_approved_at')->nullable();
                    $table->text('level_2_admin_notes')->nullable();

                    $table->string('level_3_status')->default('unsubmitted');
                    $table->string('address_doc_type')->nullable();
                    $table->string('address_doc_path')->nullable();
                    $table->text('full_address')->nullable();
                    $table->string('city')->nullable();
                    $table->string('country')->nullable();
                    $table->timestamp('level_3_submitted_at')->nullable();
                    $table->timestamp('level_3_approved_at')->nullable();
                    $table->text('level_3_admin_notes')->nullable();

                    $table->unsignedBigInteger('reviewed_by')->nullable();
                    $table->timestamps();

                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Throwable $e) {}
        }

        $record = self::where('user_id', $user->id)->first();
        if (!$record) {
            $record = self::create([
                'user_id'            => $user->id,
                'overall_percentage' => 0,
                'status'             => 'unsubmitted',
                'phone_number'       => $user->phone ?? '',
            ]);
        }
        return $record;
    }

    /**
     * Recalculate overall completion percentage & status based on level approvals.
     * Identity Verification (Level 2 Approved) = 50%
     * Address Verification (Level 3 Approved)  = 100%
     */
    public function recalculateProgress(): void
    {
        $pct = 0;
        if ($this->level_2_status === 'approved') {
            $pct = 50;
        }
        if ($this->level_2_status === 'approved' && $this->level_3_status === 'approved') {
            $pct = 100;
        }

        $hasPending  = in_array('pending', [$this->level_2_status, $this->level_3_status], true);
        $hasRejected = in_array('rejected', [$this->level_2_status, $this->level_3_status], true);

        if ($pct === 100) {
            $status = 'approved';
        } elseif ($hasPending) {
            $status = 'pending';
        } elseif ($hasRejected && $pct === 0) {
            $status = 'rejected';
        } elseif ($pct > 0) {
            $status = 'partially_approved';
        } else {
            $status = 'unsubmitted';
        }

        $this->update([
            'overall_percentage' => $pct,
            'status'             => $status,
        ]);
    }
}
