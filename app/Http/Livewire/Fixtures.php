<?php

namespace App\Http\Livewire;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Fixtures extends Component
{
    public $fixtures;

    public function mount()
    {
        $this->fixtures = Fixture::where('is_completed', false)->get()
          ->groupBy('week')->map(function ($group) {
              return $group->map(function ($value) {
                  return [ "home_team_name" => Team::find($value->home_team_id)->name, "away_team_name" => Team::find($value->away_team_id)->name];
              });
          });
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
