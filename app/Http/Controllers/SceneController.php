<?php

namespace App\Http\Controllers;

class SceneController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function fixtures()
    {
        return view('fixtures');
    }

    public function simulation()
    {
        return view('simulation');
    }
}
