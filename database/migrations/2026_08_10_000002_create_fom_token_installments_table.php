<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFomTokenInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fom_token_installments')) {
            Schema::create('fom_token_installments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('activation_id')->nullable();
                $table->string('package_name')->nullable();
                $table->decimal('total_return', 20, 2)->default(0.00);
                $table->integer('installment_number')->default(1);
                $table->decimal('amount', 20, 2)->default(0.00);
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
        Schema::dropIfExists('fom_token_installments');
    }
}
