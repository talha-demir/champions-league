<?php

namespace App\Http\Livewire;

use App\Models\Fixture;
use App\Models\Team;
use App\Services\GameService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Simulation extends Component
{
    public Collection $teams;
    public Collection $nextWeekFixtures;
    public Team|null $championTeam;
    public Fixture $getLastWeekFixture;
    public Collection|null $lastWeekResults;
    public array|null $predictions;

    public function mount(GameService $gameService)
    {
        $this->refreshData($gameService);
    }

    public function refreshData(GameService $gameService)
    {
        $this->teams = Team::all();
        $this->nextWeekFixtures = $gameService->getNextWeekFixtures();
        $this->championTeam = $gameService->getChampionTeam();
        $this->lastWeekResults = $gameService->getLastWeekResults();
        $this->predictions = $gameService->predictions();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.simulation');
    }

    public function playAllWeeks(GameService $gameService)
    {
        $gameService->playAllWeeks();
        $this->refreshData($gameService);
    }

    public function playNextWeek(GameService $gameService)
    {
        $gameService->playNextWeekMatches();
        $this->refreshData($gameService);
    }

    public function resetData(GameService $gameService)
    {
        $gameService->resetData();
        return redirect()->route('home');
    }
}
