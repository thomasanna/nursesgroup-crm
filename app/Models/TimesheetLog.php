<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimesheetLog extends Model
{
    protected $primaryKey = 'timesheetLogId';

    protected $table = 'timesheet_logs';

    protected $fillable = [
      'timesheetId',
      'content',
      'author',
      'type'
    ];

    public function admin(){
      return $this->hasOne(Admin::class,'adminId','author');
    }
}
