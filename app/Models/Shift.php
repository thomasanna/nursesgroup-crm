<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Shift extends Model
{
	// use Cachable;
    protected $primaryKey = 'shiftId';

    protected $fillable = [
      'name',
      'colorCode',
      'status'
    ];
}
