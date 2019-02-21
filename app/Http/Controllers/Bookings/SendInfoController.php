<?php

namespace App\Http\Controllers\Bookings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Booking\FinalSms;
use App\Notifications\SendPlainSMS;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPlainSMS as SendPlainSMSEvent;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Staff;
use App\Models\ClientUnitSchedule;
use App\Models\BookingSms;
use App\Models\BookingLog;
use PDF;
use Auth;

use \Swift_Mailer;
use \Swift_SmtpTransport as SmtpTransport;

class SendInfoController
{
    public function sendFinalSMS(Request $request,$id){
        $booking = Booking::find(decrypt($id));
        $times = ClientUnitSchedule::where('clientUnitId',$booking->unit->clientUnitId)
                                    ->where('staffCategoryId',$booking->category->categoryId)
                                    ->where('shiftId',$booking->shiftId)
                                    ->first();
        $time = $times->startTime;
        $date = $booking->date;

        $bookingTime = Carbon::parse($date." ".$time);
        $bookingTime->subHours(22);
        $currentTime = Carbon::now();

        if($bookingTime->gt($currentTime)){
            $booking->startTime = $times->startTime;
            $booking->endTime = $times->endTime;

            $emailAddress = "0044".substr($booking->staff->mobile, 1).'@mail.mightytext.net';
            $emailAddress = str_replace(" ","",$emailAddress);
            try{
                Notification::send($booking, new FinalSms($emailAddress));
                $booking->confirmSmsStatus = 1;
                $booking->save();
                return redirect(route('booking.current'))->with('message',"Final sms send successfully!!");
            }catch(\Exception $e){
              return redirect(route('booking.current'))->with('message',"Error in Sending Final SMS!!");
            }

        }else{
            return redirect(route('booking.current'))->with('message',"Final sms can send only before 22 hours from schedule time!!");
        }
    }
    public function sendProfile(Request $request,$id)  {
        $staff = Staff::with(['category','training','training.course'])->find(decrypt($id));
        $pdf = PDF::loadView('bookings.pdf.profile',compact('staff'))
                  ->setPaper('A4','potriat');
        session()->forget('checkPayeePayment');
        return $pdf->stream('profile.pdf');
    }
    public function previewSendSms(Request $request,$id,$page,$searchKeyword="")  {
        $booking = Booking::with('staff','shift','unit')->find(decrypt($id));
        $times = ClientUnitSchedule::where('clientUnitId',$booking->unit->clientUnitId)
                                    ->where('staffCategoryId',$booking->category->categoryId)
                                    ->where('shiftId',$booking->shiftId)
                                    ->first();
        if(!empty($times)){
            $booking->startTime = $times->startTime;
            $booking->endTime = $times->endTime;
        }else{
            $booking->startTime = '';
            $booking->endTime = '';
            return redirect(route('booking.current'))
            ->with('message','Client schedule data is missing! Please update the same to proceed.');

        }

        $startTime = Carbon::createFromFormat('H:i:s', $booking->startTime);
        $endTime = Carbon::createFromFormat('H:i:s', $booking->endTime);

        $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $booking->date." ".$times->startTime);
        $currentTime = Carbon::now();

        $isBeforeTwentyTwoHr = false;
        if($currentTime->gt($startDateTime->copy()->subHours(22))){
            $isBeforeTwentyTwoHr = true;
        }

        $diffInHours = ($startTime->diffInMinutes($endTime))/60;

        $staffShiftCost = $this->getStaffShiftRate($booking, $booking->staff);

        $extraTA = empty($booking->extraTA)?0:$booking->extraTA;
        $total = (empty($booking->transportAllowence)?0:$booking->transportAllowence)
                            +((empty($booking->bonus)?0:$booking->bonus)*$diffInHours)
                            +$staffShiftCost*$diffInHours
                            +$extraTA;
        $confirm = ['sms'=>"",'btn'=>0,'time'=>""];

        $confirmSms = 'SHIFT BOOKED, Hi ' .$booking->staff->forname.' '.$booking->staff->surname.', ';
        $confirmSms.= date('d/m/Y,D',strtotime($booking->date));
        $confirmSms.= ', '.$booking->shift->name.' , Thank you NSG';

        $confirm['sms'] = $confirmSms;
        $smsAlert = $this->checkSmsSentAlready($booking->bookingId,$booking->staff->staffId,1);
        if($smsAlert['status'] ==1){
            $confirm['btn'] = 1;
            $confirm['time'] = "@".date('d-M H:i:s',strtotime($smsAlert['when']));
        }

        $final = ['sms'=>"",'btn'=>0,'time'=>""];

        $finalSms = 'SHIFT CONFIRMATION, Hi ' .$booking->staff->forname.' '.$booking->staff->surname.', ';
        $finalSms.= date('d/m/Y,D',strtotime($booking->date)).','.$booking->unit->alias.','.$booking->unit->address;
        $finalSms.= ', '.$booking->shift->name.', '.date('H:i',strtotime($booking->startTime)).' to '.date('H:i',strtotime($booking->endTime));
        if(!empty($booking->modeOfTransport==1)){ $finalSms .= ", Trust you are OK transport.";}
        if(!empty($booking->modeOfTransport==2)){ $finalSms .= ", Pick up Time ".date('H:i',strtotime($booking->outBoundPickupTime));}
        $finalSms.= ' , Please confirm, Thank you NSG';

        $final['sms'] = $finalSms;

        $smsAlert = $this->checkSmsSentAlready($booking->bookingId,$booking->staff->staffId,2);
        if($smsAlert['status'] ==1){
            $final['btn'] = 1;
            $final['time'] = "@".date('d-M H:i:s',strtotime($smsAlert['when']));
        }

        $transport = ['sms'=>"",'btn'=>0,'time'=>""];

        $transportSms = "";
        if(!empty($booking->modeOfTransport==2 && !empty($booking->outBoundPickupTime))){
            $outBoundPickupTime = Carbon::createFromFormat('H:i:s', $booking->outBoundPickupTime)->format('H:i');
            $transportation = $booking->transportation()->where('direction','1')->first();
            $driver = (!empty($transportation))?$transportation->driver:null;
            $transportSms   = "Transport arranged, Outbound, Pick up time : ".$outBoundPickupTime.", ";
            $transportSms  .= "Pickup Location : ".$booking->staff->pickupLocation.", ";
            if(!empty($driver)){
                $transportSms  .= "Driver : ".$driver->forname.' '.$driver->surname.", ".$driver->mobile.", ";
                $vehicle = $driver->vehicle()->first();
                if(!empty($vehicle))
                    $transportSms  .= $vehicle->make.", ".$vehicle->model.", ".$vehicle->color.".";
            }
            $transportSms  .= " Inbound trip also arranged.";
        }else{
          $transportSms = "Trust you are OK transport.";
        }

        $transport['sms'] = $transportSms;
        $smsAlert = $this->checkSmsSentAlready($booking->bookingId,$booking->staff->staffId,3);
        if($smsAlert['status'] ==1){
            $transport['btn'] = 1;
            $transport['time'] = "@".date('d-M H:i:s',strtotime($smsAlert['when']));
        }

        $payment = ['sms'=>"",'btn'=>0,'time'=>""];

        $paymentSms = "Payment Indicator : Hi ";
        $paymentSms .= $booking->staff->forname.' '.$booking->staff->surname.',';
        $paymentSms .= " Shift ".date('d/m/Y,D',strtotime($booking->date)).', '.$booking->unit->alias;
        $paymentSms .= ', '.$booking->shift->name.', '.date('H:i',strtotime($booking->startTime)).' to '.date('H:i',strtotime($booking->endTime));
        $paymentSms .='. You can earn up £'.$total.' from this shift.';
        $paymentSms .= "(Number of Hours ".$diffInHours.", Hourly Rate £ ".$staffShiftCost;
        if($booking->transportAllowence != 0){ $paymentSms .=", TA £".$booking->transportAllowence; }
        if($booking->extraTA !=0){ $paymentSms .=", Extra TA £".$booking->extraTA; }
        if($booking->bonus !=0){ $paymentSms .=", Bonus £".$booking->bonus; }
        $paymentSms .=") Thank you, NSG";

        $payment['sms'] = $paymentSms;

        $smsAlert = $this->checkSmsSentAlready($booking->bookingId,$booking->staff->staffId,4);
        if($smsAlert['status'] ==1){
            $payment['btn'] = 1;
            $payment['time'] = "@".date('d-M H:i:s',strtotime($smsAlert['when']));
        }

        return view('bookings.sendSms',compact("page","booking","diffInHours","staffShiftCost","total",'confirm','final','transport','payment','searchKeyword','isBeforeTwentyTwoHr'));
    }
    public function sendShiftConfirmSMS(Request $request){
        $booking = Booking::with('staff')->find(decrypt($request->bookingId));
        $emailAddress = "0044".substr($booking->staff->mobile, 1).'@mail.mightytext.net';
        $emailAddress = str_replace(" ","",$emailAddress);
        $booking->staff->message = $request->shiftConfirmation;

        $transport = new SmtpTransport('smtp.gmail.com', 465, 'ssl');
        $transport->setUsername($booking->staff->branch->mail_sender);
        $transport->setPassword('Migration18');
        $gmail = new Swift_Mailer($transport);
        Mail::setSwiftMailer($gmail);
        
        Mail::to($emailAddress)->queue(new SendPlainSMSEvent($request->shiftConfirmation));
        $booking->confirmSmsStatus = 1;
        $booking->save();
        BookingSms::create([
            'bookingId'=>decrypt($request->bookingId),
            'smsType'   =>1,
            'staffId'   =>$booking->staff->staffId,
            'sentTime'   =>date('Y-m-d H:i:s'),
        ]);

        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' => "Shift Confirmation <span class='logHgt'>SMS Sent</span> to <strong>".$booking->staff->forname." ".$booking->staff->surname."</strong>",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);

        if($request->has('from')){
            return ['status'=>true];
        }
        return redirect(route('booking.preview.send.sms',[$request->bookingId,$request->page]))
                ->with('message','Succesfully send shift confirmation SMS!!');

    }
    public function sendFinalShiftConfirmSMS(Request $request){
        $booking = Booking::with('staff')->find(decrypt($request->bookingId));
        $emailAddress = "0044".substr($booking->staff->mobile, 1).'@mail.mightytext.net';
        $emailAddress = str_replace(" ","",$emailAddress);
        $booking->staff->message = $request->finalShiftConfirm;

        $transport = new SmtpTransport('smtp.gmail.com', 465, 'ssl');
        $transport->setUsername($booking->staff->branch->mail_sender);
        $transport->setPassword('Migration18');
        $gmail = new Swift_Mailer($transport);
        Mail::setSwiftMailer($gmail);

        Mail::to($emailAddress)->queue(new SendPlainSMSEvent($request->finalShiftConfirm));
        $booking->finalConfirmationSms = 1;
        $booking->save();

        BookingSms::create([
            'bookingId'=>decrypt($request->bookingId),
            'smsType'   =>2,
            'staffId'   =>$booking->staff->staffId,
            'sentTime'   =>date('Y-m-d H:i:s'),
        ]);

        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' => "Final Shift Confirmation <span class='logHgt'>SMS Sent</span> to <strong>".$booking->staff->forname." ".$booking->staff->surname."</strong>",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);

        return redirect(route('booking.preview.send.sms',[$request->bookingId,$request->page]))
                ->with('message','Succesfully send final shift confirmation SMS!!');

    }
    public function sendTransportSMS(Request $request){
        $booking = Booking::with('staff')->find(decrypt($request->bookingId));
        $emailAddress = "0044".substr($booking->staff->mobile, 1).'@mail.mightytext.net';
        $emailAddress = str_replace(" ","",$emailAddress);

        $transport = new SmtpTransport('smtp.gmail.com', 465, 'ssl');
        $transport->setUsername($booking->staff->branch->mail_sender);
        $transport->setPassword('Migration18');
        $gmail = new Swift_Mailer($transport);
        Mail::setSwiftMailer($gmail);

        Mail::to($emailAddress)->queue(new SendPlainSMSEvent($request->transportSms));
        BookingSms::create([
            'bookingId'=>decrypt($request->bookingId),
            'smsType'   =>3,
            'staffId'   =>$booking->staff->staffId,
            'sentTime'   =>date('Y-m-d H:i:s'),
        ]);
        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' => "Transport <span class='logHgt'>SMS Sent</span> to <strong>".$booking->staff->forname." ".$booking->staff->surname."</strong>",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);
        return redirect(route('booking.preview.send.sms',[$request->bookingId,$request->page]))
                ->with('message','Succesfully send transport SMS!!');

    }
    public function sendPaymentSMS(Request $request){
        $booking = Booking::with('staff')->find(decrypt($request->bookingId));
        $emailAddress = "0044".substr($booking->staff->mobile, 1).'@mail.mightytext.net';
        $emailAddress = str_replace(" ","",$emailAddress);

        $transport = new SmtpTransport('smtp.gmail.com', 465, 'ssl');
        $transport->setUsername($booking->staff->branch->mail_sender);
        $transport->setPassword('Migration18');
        $gmail = new Swift_Mailer($transport);
        Mail::setSwiftMailer($gmail);

        Mail::to($emailAddress)->queue(new SendPlainSMSEvent($request->paymentSms));
        BookingSms::create([
            'bookingId'=>decrypt($request->bookingId),
            'smsType'   =>4,
            'staffId'   =>$booking->staff->staffId,
            'sentTime'   =>date('Y-m-d H:i:s'),
        ]);
        BookingLog::create([
          'bookingId' =>$booking->bookingId,
          'content' => "Payment <span class='logHgt'>SMS Sent</span> to <strong>".$booking->staff->forname." ".$booking->staff->surname."</strong>",
          'author' =>Auth::guard('admin')->user()->adminId,
        ]);
        return redirect(route('booking.preview.send.sms',[$request->bookingId,$request->page]))
                ->with('message','Succesfully send payment SMS!!');

    }
    public function sendOtherSMS(Request $request){
        $booking = Booking::with('staff')->find(decrypt($request->bookingId));
        $emailAddress = "0044".substr($booking->staff->mobile, 1).'@mail.mightytext.net';
        $emailAddress = str_replace(" ","",$emailAddress);

        $transport = new SmtpTransport('smtp.gmail.com', 465, 'ssl');
        $transport->setUsername($booking->staff->branch->mail_sender);
        $transport->setPassword('Migration18');
        $gmail = new Swift_Mailer($transport);
        Mail::setSwiftMailer($gmail);
        Mail::to($emailAddress)->queue(new SendPlainSMSEvent($request->otherSms));
        return redirect(route('booking.preview.send.sms',[$request->bookingId,$request->page]))
                ->with('message','Succesfully send other SMS!!');

    }

    private function getStaffShiftRate($booking, $staff){
        $shiftId = $booking->shift->shiftId;
        $shiftType = null;
        if(($shiftId==1) || ($shiftId==3)){
            $shiftType = "DAY";
        }else{
            $shiftType = "NIGHT";
        }

        $bookingDate = Carbon::createFromFormat('Y-m-d', $booking->date);
        $isWeekend = $bookingDate->isWeekend();

        $staffRate = 0;
        if(($shiftType == "DAY")&&($isWeekend)){
            //weekend day
            $staffRate = $staff->payRateWeekendDay;
        }elseif(($shiftType == "NIGHT")&&($isWeekend)){
            //weekend night
            $staffRate = $staff->payRateWeekendNight;
        }elseif(($shiftType == "DAY")&&(!$isWeekend)){
            //week day
            $staffRate = $staff->payRateWeekday;
        }elseif(($shiftType == "NIGHT")&&(!$isWeekend)){
            //week night
            $staffRate = $staff->payRateWeekNight;
        }
        $startTime = Carbon::createFromFormat('H:i:s', $booking->startTime);
        $endTime = Carbon::createFromFormat('H:i:s', $booking->endTime);

        $diffInHours = ($startTime->diffInMinutes($endTime))/60;

        //$staffShiftRate = $diffInHours*$staffRate;

        return $staffRate;
     }

     public function checkSmsSentAlready($bookingId,$staffId,$type){
        $sms = BookingSms::where('bookingId',$bookingId)->where('staffId',$staffId)->where('smsType',$type)->first();
        if(!empty($sms)){
            return ['status'=>1,'when'=>$sms->sentTime];
        }else{
            return ['status'=>0];
        }

     }
}
