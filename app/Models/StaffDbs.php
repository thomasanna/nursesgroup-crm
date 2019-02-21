<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDbs extends Model
{
    protected $table = 'staff_dbs';

    protected $primaryKey = 'staffDbsId';

    protected $fillable = [
      'staffId',
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
