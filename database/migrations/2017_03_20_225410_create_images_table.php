<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->string('class_name')->nullable();
            $table->string('title')->nullable();
            $table->string('slug');
            $table->string('alt')->nullable();
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('access')->default(1);
            $table->string('bucket')->nullable();
            $table->string('path')->nullable();
            $table->string('filename');
            $table->string('extension');
            $table->integer('size')->default(0);
            $table->integer('bandwidth')->unsigned()->default(0);
            $table->integer('user_id')->unsigned();
            $table->integer('views')->unsigned()->default(0);
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['slug', 'class_name']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
