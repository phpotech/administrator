<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug', 225)->unique();
            $table->boolean('active')->default(1)->index();
            $table->timestamps();
        });

        Schema::create('page_translations', function(Blueprint $table)
        {
            $table->increments('id');

            $table->unsignedInteger('page_id');
            $table->unsignedInteger('language_id');

            $table->string('title', 255);
            $table->text('body');

            $table->unique(['page_id', 'language_id']);

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
        Schema::drop('page_translations');
    }
}
