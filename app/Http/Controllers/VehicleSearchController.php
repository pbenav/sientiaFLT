<?php

namespace App\Http\Controllers;

use Livewire\Livewire;

class VehicleSearchController extends Controller
{
    public function __invoke()
    {
        return Livewire::mount(\App\Http\Livewire\VehicleSearch::class, []);
    }
}
