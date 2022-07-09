<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_history', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('fixture_id')->constrained('fixtures');
            $table->foreignId('team_id')->constrained('teams');
            $table->integer('goals_scored');
            $table->integer('goals_conceded');
            $table->integer('week');
            $table->boolean('won');
            $table->float('game_point')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_history');
    }
};
