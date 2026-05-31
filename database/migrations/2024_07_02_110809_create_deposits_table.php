<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_deposited', 10, 2);
            $table->decimal('amount_removed', 10, 2);

            // $table->string('package_type', 100)->comment("Venture,Package");
            // $table->integer("package_id");
            $table->string('currency_type',200)->nullable();
            $table->string('deposit_method',200)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected','under-review','cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}
