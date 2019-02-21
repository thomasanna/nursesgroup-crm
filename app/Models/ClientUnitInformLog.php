<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClientUnitInformLog extends Model
{
    protected $table = 'client_unit_informed_logs';
    protected $primaryKey = 'unitInformedLogId';

    protected $fillable = [
      'bookingId',
      'informedTo',
      'date',
      'time',
      'modeOfInform',
      'notes',
    ];

    public function setDateAttribute($value){
        if(is_null($value)){
            $this->attributes['date'] = null;
        }else{
            $this->attributes['date'] = Carbon::createFromFormat('d-m-Y',$value)->format('Y-m-d') ;
        }
    }
}
