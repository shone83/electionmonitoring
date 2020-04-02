<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectoralUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electoral_units', function (Blueprint $table) {
            $table->id();
            $table->integer('town_id')->index()->unsigned()->nullable();
            $table->integer('settlement_id')->index()->unsigned()->nullable();
            $table->string('name');
            $table->integer('expected_result');
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
        Schema::dropIfExists('electoral_units');
    }
}
