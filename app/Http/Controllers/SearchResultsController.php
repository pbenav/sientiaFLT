<?php

namespace App\Http\Controllers;

use App\DTOs\VehicleSearchDto;
use App\Services\VehicleSearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchResultsController extends Controller
{
    public function __invoke(Request $request, VehicleSearchService $searchService): View
    {
        $pickup = $request->filled('pickup_date') ? \Carbon\Carbon::parse($request->pickup_date) : now();
        $dropoff = $request->filled('dropoff_date') ? \Carbon\Carbon::parse($request->dropoff_date) : now()->addDay();

        $categories = $request->filled('categories') ? explode(',', $request->categories) : null;
        $transmissions = $request->filled('transmissions') ? explode(',', $request->transmissions) : null;
        $fuels = $request->filled('fuels') ? explode(',', $request->fuels) : null;

        $dto = VehicleSearchDto::fromRequest([
            'category_id' => $categories ? (int) $categories[0] : null,
            'transmission' => $transmissions ? $transmissions : null,
            'fuel_type' => $fuels ? $fuels : null,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'location_id' => $request->location_id,
            'search' => $request->search_term,
            'start_date' => $request->pickup_date,
            'end_date' => $request->dropoff_date,
        ]);

        $sort = $request->input('sort', 'price_asc');
        $results = $searchService->searchWithSort($dto, $sort);

        $days = (int) max(1, $pickup->diffInDays($dropoff));

        return view('search-results', [
            'results' => $results,
            'totalResults' => $results->count(),
            'pickup' => $pickup,
            'dropoff' => $dropoff,
            'days' => $days,
        ]);
    }
}
