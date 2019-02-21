<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffBand extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'bandId';
}
