<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_id')->unsigned();
            $table->string('group')->nullable();
            $table->string('field_id')->nullable();
            $table->string('type')->nullable();
            $table->string('label')->nullable();
            $table->string('placeholder')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('required')->default(false);
            $table->string('class')->nullable();
            $table->integer('label_position')->default(1);
            $table->text('meta')->nullable();

            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
