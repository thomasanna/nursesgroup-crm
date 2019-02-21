<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripClub extends Model
{
    protected $table = 'trip_clubs';

    protected $primaryKey = 'clubId';

    protected $fillable = [
      'dayNumber',
    ];

    public function driver(){
      return $this->hasOne(Driver::class,'driverId','driverId');
    }
}
