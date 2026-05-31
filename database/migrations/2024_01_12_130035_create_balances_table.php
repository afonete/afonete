<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->decimal('cash',5,4)->default(0.0000);
            $table->decimal('deposit',5,4)->default(0.0000);
            $table->decimal('earning',5,4)->default(0.0000);
            $table->decimal('ads_earning')->default(0.0000);
            $table->integer('reserved_token')->default(0);
            $table->decimal('used')->default(0.00);
        
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
        Schema::dropIfExists('balances');
    }
}
