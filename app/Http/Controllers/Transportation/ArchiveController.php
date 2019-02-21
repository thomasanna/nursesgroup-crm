<?php

namespace App\Http\Controllers\Transportation;

use Illuminate\Http\Request;
use App\Models\TransportArchive;
use Yajra\Datatables\Facades\Datatables;

class ArchiveController
{
    public function viewArchives(){
      return view('transportation.archives.index');
    }

    public function dataArchives(Request $request){
        $query = TransportArchive::with(['trip','trip.driver','trip.staff','trip.booking'])
                  ->Join('transportations','transportations.tripId','transport_archives.tripId')
                  ->whereHas('trip.booking',function ($booking){
                      return $booking->where('unitStatus','4')
                                      ->where('staffStatus','3')
                                      ->where('modeOfTransport','2');
                    })
                    ->selectRaw('ng_transportations.*,ng_transport_archives.archiveId,SUM(CASE WHEN ng_transportations.direction = 1 THEN 1 END) AS outCount,SUM(CASE WHEN ng_transportations.direction = 2 THEN 1 END) AS inCount')
                    ->orderBy('transportations.date', 'DESC')
                    ->orderBy('transportations.driverId', 'ASC')
                    ->orderBy('transportations.pickupTime', 'ASC')
                    ->orderBy('transportations.unitId', 'ASC')
                    ->orderBy('transportations.direction', 'ASC')
                    ->groupBy('transportations.driverId');
        $data = Datatables::of($query);
        $data->addIndexColumn();
        $data->editColumn('DT_Row_Index',function($trip){
            return 1;
        });
        $data->editColumn('date',function($archive){
          return date('d-M-Y, D',strtotime($archive->trip->date));
        });
        $data->editColumn('direction',function($archive){
            if($archive->trip->direction ==1){
                return "Outbond";
            }else{
                return "Inbond";
            }
        });
        $data->editColumn('trip.staff.forname',function($archive){
            if($archive->trip->staff->forname){ return $archive->trip->staff->forname." ".$archive->trip->staff->surname; }
        });

        $data->editColumn('trip.driver.forname',function($archive){
            if($archive->trip->driver->forname){ return $archive->trip->driver->forname." ".$archive->trip->driver->surname; }
        });
        $data->editColumn('trip.pickuptime',function($archive){
            if($archive->trip->direction ==1){
                return $archive->trip->booking->outBoundPickupTime;
            }else{
                return $archive->trip->booking->inBoundPickupTime;
            }
        });
        $data->editColumn('trip.pickuplocation',function($archive){
            if($archive->trip->direction ==1){
                return $archive->trip->staff->pickupLocation;
            }else{
                return $archive->trip->booking->unit->address;
            }
        });
        $data->editColumn('outCount',function($archive){
            if($archive->trip->outCount){
                return $archive->trip->outCount;
            }else{
                return 0;
            }
        });
        $data->editColumn('inCount',function($archive){
            if($archive->trip->inCount){
                return $archive->trip->inCount;
            }else{
                return 0;
            }
        });
        $data->editColumn('droppoint',function($archive){
            if($archive->trip->direction ==1){
                return $archive->trip->booking->unit->address;
            }else{
                return $archive->trip->staff->pickupLocation;
            }
        });
        $data->editColumn('actions',function($archive){
          $html = "";
          $html .= "<a href='".route('transportation.ra.view',[$archive->trip->driverId,$archive->trip->payeeWeek])."'
          class='btn btn-success btn-xs mrs' style='margin: 0 5px;'><i class='fa fa-pencil'></i> Invoice</a>";
          return $html;
        });
        $tableData =$data->make(true);
        return $tableData;
    }
}
