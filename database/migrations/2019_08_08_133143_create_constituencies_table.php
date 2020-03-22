<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstituenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constituencies', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->string('name');
            $table->string('code');
            $table->integer('incumbent_party')->unsigned();
            $table->foreign('incumbent_party')->references('id')->on('parties');
            $table->string('region');
            $table->integer('voters');
            $table->string('background')->nullable();
            $table->integer('total_votes')->default(0);
            $table->boolean('declared')->default(false);
            $table->boolean('published')->default(false);
            $table->dateTime('declared_at')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('constituencies');
    }
}
