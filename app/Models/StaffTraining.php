<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTraining extends Model
{
    protected $primaryKey = 'staffTrainingId';

    protected $fillable = [
      'staffId',
      'courseId',
      'provider',
      'issueDate',
      'expiryDate',
      'status',
      'comment',
      'documentFile',
    ];

    public function course(){
      return $this->hasOne(TrainingCourse::class,'trainingCourseId','courseId')->withDefault([
        'courseName' =>''
      ]);
    }
}
