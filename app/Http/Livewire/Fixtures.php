<?php

namespace App\Http\Livewire;

use App\Models\Fixture;
use App\Models\Team;
use App\Services\GameService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
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
