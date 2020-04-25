<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnouts', function (Blueprint $table) {
            $table->id();
            $table->integer('electoral_unit_id')->index()->unsigned()->nullable();
            $table->string('turnout');
            $table->string('safe_voter');
            $table->timestamp("turnout_by_hour");
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
        Schema::dropIfExists('turnouts');
    }
}
