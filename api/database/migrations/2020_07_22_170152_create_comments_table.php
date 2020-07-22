<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
          $table->text('body');
          $table->string('commentable_type', 255);
          $table->foreignId('commentable_id');
          $table->foreignId('creator_id');
          $table->foreignId('parent_id')->nullable();
          $table->timestamp('updated_at', 0)->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
          $table->timestamp('created_at', 0)->useCurrent();
          $table->id();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
