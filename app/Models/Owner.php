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

    protected $appends = ['age', 'revisions', 'average_interval_revision', 'next_revision'];

    public function getAgeAttribute()
    {
        return Carbon::now()->diffInYears($this->date_of_birth);
    }

    public function getRevisionsAttribute()
    {
        return Revision::whereIn('car_id', $this->cars->pluck('id'))
            ->orderBy('review_day')
            ->get();
    }

    public function getAverageIntervalRevisionAttribute()
    {
        $previous = null;

        $avg = $this->revisions
            ->map(function ($revision, $index){
            if($index > 0)
            {
                return Carbon::create($revision->review_day)
                    ->diffInDays($this->revisions[$index-1]->review_day);
            }

            return 0;
        })->avg();


        return intval($avg);
    }

    public function getNextRevisionAttribute()
    {
        $last_revision = $this->revisions->last();

        if(!$last_revision)
            return null;

        return Carbon::create($last_revision->review_day)
            ->addDays($this->average_interval_revision);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
