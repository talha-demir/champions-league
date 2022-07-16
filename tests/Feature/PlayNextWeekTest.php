<?php

namespace Tests\Feature;

use App\Http\Livewire\Home;
use App\Http\Livewire\Simulation;
use App\Models\Fixture;
use App\Models\GameHistory;
use App\Models\Team;
use App\Services\GameService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class PlayNextWeekTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Play next week test
     *
     * @return void
     */
    public function test_play_next_week(): void
    {
        //create teams
        Team::factory()->times(4)->create();

        //create fixtures
        Artisan::call('create:fixture');

        Livewire::test(Simulation::class)->call('playNextWeek');

        $completedFixtures = Fixture::where('is_completed', true)->get();
        $this->assertCount(2, $completedFixtures);

        $gameHistories = GameHistory::all();
        $this->assertCount(4, $gameHistories);
    }
}
