<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchContact extends Model
{
    protected $primaryKey = 'branchContactId';

    protected $fillable = [
      'branchId',
      'name',
      'status'
    ];
}
