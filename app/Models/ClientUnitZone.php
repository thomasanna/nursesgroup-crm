<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientUnitZone extends Model
{
    protected $table = 'client_unit_zones';
    protected $fillable = [
      'clientUnitId',
      'zoneId',
      'status'
    ];
}
