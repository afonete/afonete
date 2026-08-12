<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFomLicenceMinersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fom_licence_miners')) {
            Schema::create('fom_licence_miners', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('price', 12, 2)->default(0.00);
                $table->string('display_price')->nullable();
                $table->decimal('tokens', 20, 0)->default(0);
                $table->integer('duration_days')->default(600);
                $table->decimal('token_bonus', 8, 2)->default(0.00);
                $table->decimal('direct_sponsors', 8, 2)->default(0.00);
                $table->decimal('affiliate_vbonus', 8, 2)->default(10.00);
                $table->string('space_shop_limit')->nullable()->default('Space Shop Room Limit');
                $table->decimal('volume_point', 12, 0)->default(0);
                $table->string('unlocked_per_week')->default('YES');
                $table->string('allowed_loan')->nullable();
                $table->string('investment_option')->nullable();
                $table->decimal('total_return', 20, 0)->default(0);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fom_licence_miners');
    }
}
