<?php

namespace Tests\Feature;

use App\Http\Livewire\Home;
use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Tests\TestCase;

class CreateFixturesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_fixtures(): void
    {
        //create teams
        $teams = Team::factory()->times(4)->create();
        $this->assertCount(4, $teams);

        //create fixtures
        Livewire::test(Home::class)->call('generateFixtures');
        $fixtures = Fixture::get();
        $this->assertCount(12, $fixtures);
    }
}
