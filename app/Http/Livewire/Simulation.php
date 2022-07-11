<?php

namespace App\Http\Livewire;

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
    private GameService $gameService;


    public function mount()
    {
        $this->gameService = new GameService();
        $this->teams = Team::all();
        $this->nextWeekFixtures = $this->gameService->getNextWeekFixtures();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.simulation');
    }

    public function playAllWeeks()
    {
        dd("playAllWeeks");
    }

    public function playNextWeek()
    {
        dd("playNextWeek");
    }
     public function resetData()
    {
        dd("resetData");
    }


}
