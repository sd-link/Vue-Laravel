<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->biginteger('content_id')->unsigned();
            $table->string('type');
            $table->string('title');
            $table->mediumText('content')->nullable();
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('unique_id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('tblock_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->timestamps();

            $table->unique(['unique_id', 'content_id'])->unsigned();

            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('template_block_id')->references('id')->on('template_blocks')->onDelete('cascade');
        });
    }
}
