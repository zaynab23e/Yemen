<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'menucategories'; // Specify the table name if it differs from the model name
    protected $fillable = ['name', 'description','image'];
    public function items()
    {
        return $this->hasMany(Item::class, 'menucategory_id');
    }
}
