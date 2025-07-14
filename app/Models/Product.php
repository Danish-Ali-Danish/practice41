<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'brand_id',
        'price',
        'compare_price',
        'stock',
        'short_description',
        'description',
        'image',
    ];

    use Searchable;

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
