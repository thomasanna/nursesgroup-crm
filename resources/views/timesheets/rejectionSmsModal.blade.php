<!-- MODAL FOR REJECTION SMSM -->
<div class="modal fade in" id="modal-sms" role="dialog"  action="{{route('timesheet.verify.rejectsms')}}" token="{{ csrf_token() }}">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Rejection</h4>
        </div>
        <div class="modal-body">
        <textarea class="form-control msgContent" style="height:100px;" name="msgContent"></textarea>
        <input type="hidden" name="timesheetIdSMS"/>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary resendSMS" action="{{route(('timesheet.verify.sms.resend'))}}" token="{{ csrf_token() }}">Send SMS</button>
        </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>