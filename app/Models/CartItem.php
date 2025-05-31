<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price'];
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }

        public function item()
{
    return $this->belongsTo(Item::class);
}


    public function getProductImageAttribute()
    {
        return $this->product && $this->product->images()->exists()
            ? 'storage/' . $this->product->images()->first()->image_path
            : null;
    }


    protected $appends = ['product_image', 'total_price'];
}
