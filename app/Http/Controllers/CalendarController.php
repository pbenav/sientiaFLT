<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function show(Vehicle $vehicle): View
    {
        return view('calendar.vehicle', compact('vehicle'));
    }
}
