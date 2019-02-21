<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\ClientUnitSchedule;
use Auth;

class ChangePaymentController
{
    public function edit(Request $request, $id)
    {

        $payment = Payment::find(decrypt($id));
        $viewData = compact("payment");

        return view('payroll.payments.editPayment',$viewData);
    }

    public function update(Request $request)
    {
        return redirect(route('payment.list'))->with('message','Succesfully updated the payment !!');
    }

}
