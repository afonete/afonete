<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * FC VIP Streamline Ranks (Silver / Gold / Diamond / Ambassador).
     *
     * Lifecycle per user per rank:
     *   locked        -> initial state until RANK ACTIVATION threshold is met
     *   active        -> activation threshold met; 65-day challenge PERIOD has
     *                     started automatically; user must hit OWN FC + TEAM CLUB
     *   pending_admin -> user claims/auto-detects completion; admin must verify
     *                     and confirm before reward is paid
     *   completed     -> admin confirmed; reward ($) added to COMMISSION cashout,
     *                     TOKEN added to AVAILABLE_TOKEN; pin awarded
     *   expired       -> 65-day period elapsed without hitting OWN FC+TEAM CLUB;
     *                     user is permanently locked out of THIS rank (cannot
     *                     re-challenge this tier), but can still progress to
     *                     higher ranks that do NOT require this pin if the
     *                     higher rank's own activation is met? Per spec ranks
     *                     are sequential (Silver → Gold → Diamond → Ambassador)
     *                     so expiring Silver blocks all higher ranks too.
     */
    public function up(): void
    {
        if (!Schema::hasTable('fc_streamline_ranks')) {
            Schema::create('fc_streamline_ranks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedTinyInteger('rank_level')
                    ->comment('1=Silver, 2=Gold, 3=Diamond, 4=Ambassador');
                $table->string('rank_pin', 20)->default('silver')
                    ->comment('silver / gold / diamond / ambassador');
                $table->string('status', 20)->default('locked')
                    ->comment('locked | active | pending_admin | completed | expired');

                // Activation — auto-set when RANK ACTIVATION threshold is hit.
                $table->timestamp('activated_at')->nullable()
                    ->comment('When the 65-day challenge period began.');
                $table->timestamp('deadline_at')->nullable()
                    ->comment('activated_at + 65 days.');

                // Completion — when OWN FC + TEAM CLUB targets were both met
                // (captured for admin review evidence).
                $table->timestamp('completed_at')->nullable();
                $table->unsignedInteger('direct_fc_at_completion')->default(0);
                $table->unsignedInteger('team_fc_at_completion')->default(0);
                $table->unsignedInteger('required_pins_at_completion')->default(0);

                // Admin verification.
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->text('admin_notes')->nullable();

                // Reward snapshot (what was credited on verification).
                $table->decimal('reward_usd', 12, 2)->default(0);
                $table->unsignedInteger('reward_tokens')->default(0);

                // Forfeiture tracking.
                $table->timestamp('expired_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'rank_level']);
                $table->index(['status']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fc_streamline_ranks');
    }
};
