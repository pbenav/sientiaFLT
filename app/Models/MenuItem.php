<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'type',
        'url',
        'title',
        'target',
        'class',
        'icon',
        'menu_order',
        'page_id',
    ];

    protected $casts = [
        'menu_order' => 'integer',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('menu_order');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function scopeByMenuOrder($query)
    {
        return $query->orderBy('menu_order', 'asc');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public static function getByMenu(string $menuSlug, array $options = []): array
    {
        $menu = Menu::with(['items.page'])->where('slug', $menuSlug)->first();
        if (!$menu) return [];

        $items = $menu->items->whereNull('parent_id')->sortBy('menu_order')->values();

        if (isset($options['max_depth']) && $options['max_depth'] > 1) {
            $items = static::loadChildren($items, $options['max_depth'] - 1);
        }

        return $items->toArray();
    }

    protected static function loadChildren($items, int $depth): \Illuminate\Support\Collection
    {
        return $items->map(function ($item) use ($depth) {
            $children = MenuItem::where('parent_id', $item['id'])
                ->with(['page' => function ($q) {
                    $q->select('id', 'title', 'slug');
                }])
                ->orderBy('menu_order')
                ->get();

            if ($depth > 0 && $children->isNotEmpty()) {
                $item['children'] = static::loadChildren($children, $depth - 1)->toArray();
            } else {
                $item['children'] = $children->map(fn($c) => [
                    'id' => $c->id,
                    'title' => $c->title,
                    'type' => $c->type,
                    'url' => $c->url,
                    'page' => $c->page ? ['title' => $c->page->title, 'slug' => $c->page->slug] : null,
                ])->toArray();
            }

            return $item;
        });
    }
}
