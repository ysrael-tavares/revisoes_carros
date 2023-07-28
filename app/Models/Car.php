<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'owner_id',
        'model',
        'plate',
        'color',
        'year_of_manufacture',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
