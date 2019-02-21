<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantDbs extends Model
{
    protected $table = 'applicant_dbs';

    protected $primaryKey = 'applicantDbsId';

    protected $fillable = [
      'applicantId',
      'dbsType',
      'apctnNumber',
      'apctnAppliedDate',
      'apctnSubmittedBy',
      'apctnAmountPaid',
      'apctnPaidBy',
      'apctnFollowUpDate',
      'apctnStatus',
      'validDbsNumber',
      'validIssueDate',
      'validRegisteredBody',
      'validType',
      'validCertificate',
      'validPoliceRecordsOption',
      'validSection142Option',
      'validChildActListOption',
      'validVulnerableAdultOption',
      'validCpoRelevantOption',
      'updateServiceNumber',
      'updateServiceCheckedDate',
      'updateServiceCheckedBy',
      'updateServiceStatus',
      'updateServicePoliceRecordsOption',
      'updateServiceSection142Option',
      'updateServiceChildActListOption',
      'updateServiceVulnerableAdultOption',
      'updateServiceCpoRelevantOption'
    ];
}
