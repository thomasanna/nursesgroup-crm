<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class StaffCategory extends Model
{
    use SoftDeletes;
}
