<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('parent')->nullable()->unsigned();
            $table->integer('order')->unsigned()->default(0);

            $table->integer('taxonomy_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('taxonomy_id')->references('id')->on('taxonomies')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
