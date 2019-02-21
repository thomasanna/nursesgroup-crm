<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'paymentId';

    protected $fillable = [
      'bookingId',
      'timesheetId',
      'hourlyRate',
      'ta',
      'extraTa',
      'bonus',
      'otherPay',
      'otherPayAmount',
      'remarks',
      'verifiedBy',
      'verifiedOn',
      'approvedBy',
      'approvedOn',
      'paymentWeek',
      'paymentYear',
      'status'
    ];

    public function booking(){
      return $this->hasOne(Booking::class,'bookingId','bookingId');
    }
    public function taxyear(){
      return $this->hasOne(TaxYear::class,'taxYearId','paymentYear');
    }
    public function archive(){
      return $this->hasOne(PaymentArchive::class,'paymentId','paymentId')->withDefault([
        'isPaymentRecorded' =>0,
        'isEmailSent' =>0,
        'raStatus' =>0
      ]);
    }

    public function timesheet(){
      return $this->hasOne(Timesheet::class,'timesheetId','timesheetId')->withDefault([
        'number' =>'',
        'status' =>''
      ]);
    }

    public function scopeGroup($query){
        return $query->groupBy('paymentYear')->groupBy('paymentWeek');
    }

    public function getAllAttributes()
    {
        $columns = $this->getFillable();
        // Another option is to get all columns for the table like so:
        // $columns = \Schema::getColumnListing($this->table);
        // but it's safer to just get the fillable fields

        $attributes = $this->getAttributes();

        foreach ($columns as $column)
        {
            if (!array_key_exists($column, $attributes))
            {
                $attributes[$column] = null;
            }
        }
        return $attributes;
    }


}
