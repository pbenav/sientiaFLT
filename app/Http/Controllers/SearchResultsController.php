<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchResultsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = Vehicle::active()->available();

        if ($request->filled('types')) {
            $query->whereIn('type', explode(',', $request->types));
        }
        if ($request->filled('transmissions')) {
            $query->whereIn('transmission', explode(',', $request->transmissions));
        }
        if ($request->filled('fuels')) {
            $query->whereIn('fuel_type', explode(',', $request->fuels));
        }
        if ($request->filled('min_price')) {
            $query->where('daily_rate', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('daily_rate', '<=', $request->max_price);
        }
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        if ($request->filled('search_term')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search_term}%")
                    ->orWhere('brand', 'like', "%{$request->search_term}%")
                    ->orWhere('model', 'like', "%{$request->search_term}%");
            });
        }

        $sort = $request->input('sort', 'price_asc');
        match ($sort) {
            'price_desc' => $query->orderBy('daily_rate', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->orderBy('daily_rate', 'asc'),
        };

        $results = $query->with(['location', 'primaryImage'])->get();

        return view('search-results', [
            'results' => $results,
            'totalResults' => $results->count(),
        ]);
    }
}
