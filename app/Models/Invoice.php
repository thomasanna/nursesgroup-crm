<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    protected $primaryKey = 'invoiceId';

    protected $fillable = [
      'bookingId',
      'timesheetId',
      'status',
      'totalHr',
      'totTa',
	    'totEnic',
	    'lineTotal',
	    'remarks',
	    'invceFrqncy',
	    'weekYear',
	    'monthNumbr',
	    'verifiedBy',
	    'verifiedOn',
	    'approvedBy',
	    'approvedOn',
	    'weekNumbr'
    ];

    public function booking(){
      return $this->hasOne(Booking::class,'bookingId','bookingId');
    }

    public function timesheet(){
      return $this->hasOne(Timesheet::class,'timesheetId','timesheetId')->withDefault([
        'number' =>'',
        'status' =>''
      ]);
    }

    public function scopeVerified($query){
        return $query->where('status', 1);
    }

    public function archive(){
      return $this->hasOne(InvoiceArchive::class,'invoiceId','invoiceId')->withDefault([
        'isPaymentRecorded' =>0,
        'isEmailSent' =>0,
        'invoiceStatus' =>0
      ]);
    }
}
