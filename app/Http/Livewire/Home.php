<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use App\Models\Team;

class Home extends Component
{
    public Collection $teams;

    public function mount()
    {
        $this->teams = Team::all();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.home');
    }

    public function generateFixtures()
    {
        Artisan::call('create:fixture');
        return redirect()->route('fixtures');
    }
}
