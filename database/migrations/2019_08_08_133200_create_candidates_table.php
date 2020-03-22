<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->string('name');
            $table->boolean('mp')->default(false);
            $table->integer('party_id')->unsigned();
            $table->foreign('party_id')->references('id')->on('parties');
            $table->string('picture')->default('/img/defaultimg.jpg');
            $table->text('description')->nullable();
            $table->integer('constituency_id')->unsigned();
            $table->foreign('constituency_id')->references('id')->on('constituencies');
            $table->integer('constituency_votes')->default(0);
            $table->integer('previous_constituency_votes')->default(0);
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
        Schema::dropIfExists('candidates');
    }
}
