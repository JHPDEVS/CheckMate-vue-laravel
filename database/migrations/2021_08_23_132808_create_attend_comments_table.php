<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attend_comments', function (Blueprint $table) {
            $table->id();
            $table->mediumText('content');
            $table->foreignId('user_id')
                    ->constrained()
                    ->onDelete('cascade');
            $table->foreignId('attend_post_id')
                    ->constrained()
                    ->onDelete('cascade');
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
        Schema::dropIfExists('attend_comments');
    }



}