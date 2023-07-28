<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
    ];

    protected $appends = ['age'];

    public function getAgeAttribute()
    {
        return Carbon::now()->diffInYears($this->date_of_birth);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
