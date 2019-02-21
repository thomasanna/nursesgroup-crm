<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverRightToWork extends Model
{
    protected $table = 'driver_right_to_works';

    protected $primaryKey = 'driverRightToWorkId';

    protected $fillable = [
      'driverId',
      'nationality',
      'passportNumber',
      'passportPlaceOfIssue',
      'passportDateOfIssue',
      'passportExpiryDate',
      'passportDocumentFile',
      'visaType',
      'visaNumber',
      'visaPlaceOfIssue',
      'visaDateOfIssue',
      'visaExpiryDate',
      'visaDocumentFile',
      'visaComments',
      'visaExternalVerificationRequired',
      'visaFollowUpDate',
      'checkedBy',
      'checkedDate',
      'disciplinaryProcedure',
      'pendingInvestigation',
      'medicalCondition',
      'disciplinaryProcedureComment',
      'pendingInvestigationComment',
      'medicalConditionComment',
      'status',
      'maximumPermittedWeeklyHours',
    ];

    public function country(){
      return $this->hasOne(Country::class,'countryId','nationality')->withDefault([
        'type' =>0
      ]);
    }

}
