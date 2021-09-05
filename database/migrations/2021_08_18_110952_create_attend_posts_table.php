<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attend_posts', function (Blueprint $table) {
            $table->id();
            $table->mediumText('content');
            $table->foreignId('user_id')
                    ->constrained()
                    ->onDelete('cascade');
            $table->string('image')->nullable();
            $table->boolean('flag')->default(false);
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
        Schema::dropIfExists('attend_posts');
    }
}