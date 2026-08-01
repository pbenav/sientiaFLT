<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'template',
        'status',
        'layout',
        'meta_title',
        'meta_description',
        'og_image',
        'published',
        'in_menu',
        'menu_order',
    ];

    protected $casts = [
        'published' => 'boolean',
        'in_menu' => 'boolean',
        'menu_order' => 'integer',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'page_id');
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeByMenuOrder($query)
    {
        return $query->orderBy('menu_order', 'asc');
    }
}
