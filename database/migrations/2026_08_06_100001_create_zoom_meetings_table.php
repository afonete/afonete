<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomMeetingsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('zoom_meetings')) {
            Schema::create('zoom_meetings', function (Blueprint $table) {
                $table->id();
                $table->string('topic')->default('Official Bifonex Live Zoom Presentation');
                $table->text('zoom_link');
                $table->string('meeting_id')->nullable();
                $table->string('passcode')->nullable();
                $table->text('description')->nullable();
                $table->enum('status', ['active', 'ended'])->default('ended');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('zoom_meetings');
    }
}
