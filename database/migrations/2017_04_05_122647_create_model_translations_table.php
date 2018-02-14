<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('translatable_type');
            $table->biginteger('translatable_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('locale');
            $table->string('key');
            $table->text('value');
            $table->timestamps();

            // $table->unique(['translatable_id', 'translatable_type', 'locale', 'key']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
