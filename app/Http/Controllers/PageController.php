<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Menu;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(string $slug)
    {
        // Redirect specific pages to the unified booking flow
        if ($slug === 'inicio') {
            return redirect('/');
        }
        if ($slug === 'vehiculos') {
            return redirect()->route('search.results');
        }
        if ($slug === 'reservas') {
            return redirect()->route('booking.manage');
        }

        $page = Page::where('slug', $slug)
            ->where('published', true)
            ->first();

        if (!$page) {
            abort(404, 'Página no encontrada');
        }

        return view('pages.show', compact('page'));
    }

    public function menu(string $slug)
    {
        $menu = Menu::with(['items.page'])->where('slug', $slug)->first();

        if (!$menu) {
            return response()->json([], 404);
        }

        $items = $menu->items->whereNull('parent_id')->sortBy('menu_order')->values();

        return response()->json($items->map(fn($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'type' => $item->type,
            'url' => $item->url ?? ($item->page ? '/pages/' . $item->page->slug : '#'),
            'page' => $item->page ? ['title' => $item->page->title, 'slug' => $item->page->slug] : null,
            'children' => $menu->items->where('parent_id', $item->id)->sortBy('menu_order')->values()->map(fn($child) => [
                'id' => $child->id,
                'title' => $child->title,
                'type' => $child->type,
                'url' => $child->url ?? ($child->page ? '/pages/' . $child->page->slug : '#'),
                'page' => $child->page ? ['title' => $child->page->title, 'slug' => $child->page->slug] : null,
            ]),
        ]));
    }
}
