<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $dates = [
        'start_date',
        'end_date',
    ];

    protected $fillable = ["unit","year","notes"];

    public function subunits()
    {
        return $this->belongsToMany(Truck::class, 'truck_subunits', 'main_truck_id', 'subunit_truck_id')
            ->withPivot('start_date', 'end_date')
            ->withTimestamps();
    }

    public function mainTruck()
    {
        return $this->belongsToMany(Truck::class, 'truck_subunits', 'subunit_truck_id', 'main_truck_id')
            ->withPivot('start_date', 'end_date')
            ->withTimestamps();
    }
}
