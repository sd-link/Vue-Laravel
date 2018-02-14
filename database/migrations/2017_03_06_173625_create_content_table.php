<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->integer('content_type_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('rendered')->nullable();
            $table->biginteger('featured_image_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('access')->default(1);
            $table->biginteger('views')->unsigned()->default(1);
            $table->biginteger('parent')->nullable()->unsigned();
            $table->integer('order')->nullable()->unsigned();
            $table->dateTime('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('content_type_id')->references('id')->on('content_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

}
