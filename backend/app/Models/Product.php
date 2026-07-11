<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'short_description',
        'price', 'sale_price', 'stock', 'sku', 'brand', 'weight', 'status', 'featured'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
    
    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

    public function wishlistedBy(){
        return $this->hasMany(Wishlist::class);
    }
}
