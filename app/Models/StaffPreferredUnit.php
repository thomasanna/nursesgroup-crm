<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPreferredUnit extends Model
{
    protected $primaryKey = 'staffPreferredUnitId';

    protected $fillable = [
      'unitId',
      'staffId',
      'status'
    ];
}
