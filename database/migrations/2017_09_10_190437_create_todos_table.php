<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->string('type');
            $table->unsignedBigInteger('unique_id')->index();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();

            $table->unique(['unique_id', 'project_id'], 'abcd')->unsigned();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('parent_id')->references('unique_id')->on('todos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todo_blocks');
    }
}
