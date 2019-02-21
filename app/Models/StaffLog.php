<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffLog extends Model
{

    protected $table = 'staff_logs';

    protected $primaryKey = 'staffLogId';

    protected $fillable = [
      'staffId',
      'date',
      'priority',
      'content',
      'entryBy'
    ];

    public function admin(){
      return $this->hasOne(Admin::class,'adminId','entryBy');
    }

}
