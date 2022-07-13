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
    public Collection|null $fixtures;

    public function mount(GameService $gameService)
    {
        $this->teams = Team::all();
        $this->fixtures = $gameService->getTeamNamesOfUncompletedFixtures();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.home');
    }

    public function generateFixtures(GameService $gameService)
    {
        if (!$gameService->isLeagueFinished())
        {
            Artisan::call('create:fixture');
            return redirect()->route('fixtures');
        }else
        {
            return redirect()->route('simulation');
        }
    }
}
