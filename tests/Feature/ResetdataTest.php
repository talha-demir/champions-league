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

class ResetdataTest extends TestCase
{
    /**
     * Reset data test.
     *
     * @return void
     */
    public function test_reset_data(): void
    {
        //create teams
        Team::factory()->times(4)->create();

        //create fixtures
        Artisan::call('create:fixture');

        Livewire::test(Simulation::class)->call('playAllWeeks');
        Livewire::test(Simulation::class)->call('resetData');

        $fixtures = Fixture::where('is_completed', true)->get();
        $this->assertCount(0, $fixtures);

        $gameHistories = GameHistory::all();
        $this->assertCount(0, $gameHistories);
    }
}
