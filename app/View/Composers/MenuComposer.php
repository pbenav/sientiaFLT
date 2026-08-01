<?php

namespace App\View\Composers;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        // Load main navigation menu
        $mainMenu = Menu::with(['items.page'])->where('slug', 'main')->first();
        $menuItems = $mainMenu ? $mainMenu->items->whereNull('parent_id')->sortBy('menu_order')->values() : collect();

        // Load published pages that are in menu
        $menuPages = Page::where('published', true)
            ->where('in_menu', true)
            ->orderBy('menu_order', 'asc')
            ->get();

        // Load footer menu
        $footerMenu = Menu::with(['items.page'])->where('slug', 'footer')->first();
        $footerItems = $footerMenu ? $footerMenu->items->whereNull('parent_id')->sortBy('menu_order')->values() : collect();

        // Load legal pages
        $legalPages = Page::where('published', true)
            ->whereIn('slug', ['aviso-legal', 'privacidad', 'cookies'])
            ->get()->keyBy('slug');

        $view->with([
            'menuItems' => $menuItems,
            'menuPages' => $menuPages,
            'footerItems' => $footerItems,
            'legalPages' => $legalPages,
        ]);
    }
}
