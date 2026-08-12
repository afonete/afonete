<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFomTokenStakingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fom_token_stakings')) {
            Schema::create('fom_token_stakings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->decimal('principal_amount', 20, 2)->default(0.00);
                $table->decimal('yield_percent', 8, 2)->default(0.00);
                $table->decimal('profit_amount', 20, 2)->default(0.00);
                $table->decimal('total_staked', 20, 2)->default(0.00);
                $table->integer('lock_years')->default(1);
                $table->dateTime('release_date');
                $table->string('status')->default('pending');
                $table->dateTime('processed_at')->nullable();
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
        Schema::dropIfExists('fom_token_stakings');
    }
}
