<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-managed contract content:
 *   body_content    → full agreement rendered on /user/contract
 *   summary_content → short summary rendered on /user/contracts/bifonex
 */
class CreateContractTemplatesTable extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        Schema::dropIfExists('contract_templates');
    }
}
