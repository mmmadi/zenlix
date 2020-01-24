<div class="btn-group btn-group-justified">
  <div class="btn-group">


    <button id="action_refer_to" style="margin: 0px;"
    @if ($errors->has('targetGroup') || $errors->has('targetUsers'))
    data-status="true"
    @else
    data-status="false"
    @endif
    type="button" class="btn btn-flat margin bg-purple @if ( $errors->has('targetGroup') || $errors->has('targetUsers')) active @endif"

@if ($ticket->status != "free")
    disabled="disabled"
@endif
    ><i class="fa fa-share"></i> {{trans('ticketActionBtns.refer')}}</button>



  </div>



  <div class="btn-group">
    {!! Form::open(array('action' => ['TicketController@updateWorkStatus', $ticket->code], 'method'=> 'POST', 'class'=>'form-horizontal')) !!}
@if ($ticket->status == "free")
    <button type="submit" style="margin: 0px;" class="btn btn-flat margin bg-orange"><i class="fa fa-legal"></i> {{trans('ticketActionBtns.lock')}}</button>
@else
    <button type="submit" style="margin: 0px;" class="btn btn-flat margin bg-orange"
@if (($ticket->status == "success") || ($ticket->status == "waitsuccess") || ($ticket->status == "arch")) disabled="disabled" @endif
    ><i class="fa fa-unlock"></i> {{trans('ticketActionBtns.unlock')}} </button>
@endif
    {!! Form::close(); !!}
  </div>




  <div class="btn-group">
  {!! Form::open(array('action' => ['TicketController@updateSuccessStatus', $ticket->code], 'method'=> 'POST', 'class'=>'form-horizontal')) !!}
@if (($ticket->status == "lock") || ($ticket->status == "free") )
  <button type="submit" style="margin: 0px;" class="btn btn-flat margin bg-olive"
@if (($ticket->status != "lock")) disabled="disabled" @endif
  ><i class="fa fa-check"></i>   {{trans('ticketActionBtns.success')}}</button>
@else
    <button type="submit" style="margin: 0px;" class="btn btn-flat margin bg-olive"
@if (($ticket->status == "arch")) disabled="disabled" @endif
    ><i class="fa fa-close"></i> {{trans('ticketActionBtns.unsuccess')}}</button>
@endif
  {!! Form::close(); !!}
</div>
</div>