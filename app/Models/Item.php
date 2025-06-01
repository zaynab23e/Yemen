<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory,SoftDeletes; 
    protected $fillable = ['name', 'description', 'price', 'menucategory_id', 'image','type','is_available'];
    protected $table = 'items';
    public function getImageUrlAttribute()
    {
        return asset('storage/items/' . $this->image);
    }
    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class,'menucategory_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
