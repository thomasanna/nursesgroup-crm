<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantRightToWork extends Model
{
    protected $table = 'applicant_right_to_works';

    protected $primaryKey = 'applicantRightToWorkId';

    protected $fillable = [
      'applicantId',
      'nationality',
      'passportNumber',
      'passportPlaceOfIssue',
      'passportDateOfIssue',
      'passportExpiryDate',
      'passportDocumentFile',
      'nokForName',
      'nokSurName',
      'nokRelationship',
      'nokAddress',
      'nokPostCode',
      'nokPhone',
      'nokMobile',
      'nokEmail',
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
