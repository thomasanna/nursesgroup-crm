<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverPreferredUnit extends Model
{
    protected $table = 'driver_prefered_units';

    protected $primaryKey = 'driverPreferedUnitId';

    protected $fillable = [
      'driverId',
      'unitId',
      'status'
    ];
}
