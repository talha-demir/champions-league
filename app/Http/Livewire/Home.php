<?php

namespace App\Http\Livewire;

use App\Services\GameService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use App\Models\Team;

class Home extends Component
{
    public Collection $teams;
    private GameService $gameService;
    public Collection|null $fixtures;

    public function mount(GameService $gameService)
    {
        $this->gameService = $gameService;
        $this->teams = Team::all();
        $this->fixtures = $this->gameService->getTeamNamesOfUncompletedFixtures();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.home');
    }

    public function generateFixtures()
    {
        $this->gameService = App::make(GameService::class);
        if (!$this->gameService->isLeagueFinished())
        {
            Artisan::call('create:fixture');
            return redirect()->route('fixtures');
        }else
        {
            return redirect()->route('simulation');
        }
    }
}
