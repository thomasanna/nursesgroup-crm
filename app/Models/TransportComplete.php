<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportComplete extends Model
{
    protected $table = 'transport_completes';

    protected $primaryKey = 'completedTransId';

    protected $fillable = [
      'tripId',
      'driverId',
      'taxYear',
      'payeeWeek',
      'proceedToPay',
      'raGenerated',
      'emailSent',
      'paymentRecorded',
    ];

    public function driver(){
      return $this->hasOne(Driver::class,'driverId','driverId');
    }

    public function trip(){
      return $this->hasOne(Transportation::class,'tripId','tripId');
    }

    public function payment(){
      return $this->hasOne(TransportPayment::class,'completedTransId','completedTransId');
    }

    public function getPaymentStatusAttribute(){
        if(TransportPayment::where('completedTransId',$this->completedTransId)->count()==0){
          return 0;
        }else{
          return 1;
        }
    }

    protected $appends = ['payment_status'];
}
