<?php

namespace App\Http\Livewire;

use App\Models\Team;
use App\Services\GameService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Simulation extends Component
{
    public Collection $teams;
    public Collection $nextWeekFixtures;
    private GameService $gameService;
    public Team|null $championTeam;

    public function mount(GameService $gameService)
    {
        $this->gameService = $gameService;
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->teams = Team::all();
        $this->nextWeekFixtures = $this->gameService->getNextWeekFixtures();
        $this->championTeam = $this->gameService->getChampionTeam();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.simulation');
    }

    public function playAllWeeks()
    {
        $this->gameService = App::make(GameService::class);
        $this->gameService->playAllWeeks();
        $this->refreshData();
    }

    public function playNextWeek()
    {
        $this->gameService = App::make(GameService::class);
        $this->gameService->playNextWeekMatches();
        $this->refreshData();
    }

    public function resetData()
    {
        $this->gameService = App::make(GameService::class);
        $this->gameService->resetData();
        return redirect()->route('home');
    }


}
