<?php

namespace App\Http\Controllers\Bands;

use Illuminate\Http\Request;
use App\Models\StaffBand;

class BandController
{
    public function getPayRate(Request $req){
      return StaffBand::find($req->bandId)->price;
    }
}
