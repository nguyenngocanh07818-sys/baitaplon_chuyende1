<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = [
        'category_id','brand_id','name','slug','sku','description',
        'engine_capacity','fuel_type','transmission','power','weight','color',
        'price','sale_price','thumbnail','is_featured','status'
    ];

    protected $appends = ['average_rating'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute(){
        return $this->reviews()->avg('rating') ?: 0;
    }
    public function inventory()
    {
        return $this->hasOne(\App\Models\Inventory::class);
    }
}

