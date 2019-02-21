<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitLog extends Model
{
    protected $primaryKey = 'unitLogId';

    protected $table = 'unit_logs';

    protected $fillable = [
      'unitId',
      'content',
      'author'
    ];

    public function admin(){
      return $this->hasOne(Admin::class,'adminId','author');
    }
}
