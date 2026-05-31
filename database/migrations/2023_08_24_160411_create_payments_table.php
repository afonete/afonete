<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('package')->comment("venture or package name");
            $table->string("category",100)->comment("type of paid item ex: package,upgrade, venture");
            $table->integer("category_id")->comment("to general details of purchased item based on their id");
            $table->string('amount');
            $table->string('paid');
            $table->date("expiration_date");
            $table->boolean('is_expired')->default(false)->comment("determine if a item is expired");
            $table->string('over_paid');
            $table->string('status')->comment('0->tried, 1->paid well, 2->underpayement, 3->overpayment');
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
        Schema::dropIfExists('payments');
    }
}
