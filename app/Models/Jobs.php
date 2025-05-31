<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'title',
        'description',
    ];

  public function user()
{
    return $this->belongsTo(User::class);
}

}
