<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $primaryKey = 'vehicleId';

    protected $fillable = [
      'driverId',
      'make',
      'model',
      'regNumber',
      'color',
    ];

    public function driver(){
      return $this->hasOne(Driver::class,'driverId','driverId');
    }
}
