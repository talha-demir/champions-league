<?php

namespace App\Http\Livewire;

use App\Services\GameService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Fixtures extends Component
{
    public Collection|null $fixtures;

    public function mount(GameService $gameService)
    {
        $this->fixtures = $gameService->getTeamNamesOfUncompletedFixtures();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.fixtures');
    }

    public function startSimulation()
    {
        return redirect()->route('simulation');
    }
}
