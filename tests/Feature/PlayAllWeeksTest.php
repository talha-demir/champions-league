<?php

namespace Tests\Feature;

use App\Http\Livewire\Simulation;
use App\Models\Fixture;
use App\Models\GameHistory;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class PlayAllWeeksTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Play all weeks test.
     *
     * @return void
     */
    public function test_play_all_weeks()
    {
        //create teams
        Team::factory()->times(4)->create();

        //create fixtures
        Artisan::call('create:fixture');

        Livewire::test(Simulation::class)->call('playAllWeeks');

        $completedFixtures = Fixture::where('is_completed', true)->get();
        $this->assertCount(12, $completedFixtures);

        $gameHistories = GameHistory::all();
        $this->assertCount(24, $gameHistories);
    }
}
