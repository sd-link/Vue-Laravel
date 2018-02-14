<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned()->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('unique_id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->text('content')->nullable();
            $table->integer('order')->default(0)->unsigned();
            $table->timestamps();

            $table->unique(['unique_id', 'template_id'])->unsigned();

            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            // $table->foreign('parent_id')->references('unique_id')->on('template_blocks')->onDelete('cascade');
        });
    }
}
