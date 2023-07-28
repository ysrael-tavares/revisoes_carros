<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected $appends = ['total_revisions', 'number_cars'];

    public function getTotalRevisionsAttribute()
    {
        return Revision::whereIn('car_id', $this->cars->pluck('id'))->count();
    }

    public function getNumberCarsAttribute()
    {
        return $this->cars->count();
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
