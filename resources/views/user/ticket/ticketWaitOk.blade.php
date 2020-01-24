<div id="inspect_ok" class="col-md-12 box box-success">
<div class="box-header with-border">
                      <h3 class="box-title">{{trans('ticket.ticketWaitSuccess')}}</h3>
                    </div>


<div class="box-body">
@if ($AccessModify == true)
<div class="col-md-6">

{!! Form::open(array('action' => ['TicketController@updateSuccessStatusApprove', $ticket->code], 'method'=> 'POST', 'class'=>'form-horizontal')) !!}

 <div class="form-group" >

<div class="col-md-12">
                        
{!! Form::textarea('msg', Null, array('class'=>'form-control animated', 'placeholder'=>trans('ticket.ticketAddMsg'), 'style'=>'overflow: hidden; word-wrap: break-word; resize: vertical;', 
'rows'=>'2')) !!}

</div>
 </div>

<button type="submit" class="btn btn btn-success btn-block"><i class="fa fa-check"></i> {{trans('ticket.Success')}}</button>

{!! Form::close(); !!}

</div>
<div class="col-md-6">

{!! Form::open(array('action' => ['TicketController@updateSuccessStatusNoApprove', $ticket->code], 'method'=> 'POST', 'class'=>'form-horizontal')) !!}

 <div class="form-group" >

<div class="col-md-12">
                        
{!! Form::textarea('msg', Null, array('class'=>'form-control animated', 'placeholder'=>trans('ticket.ticketAddMsg'), 'style'=>'overflow: hidden; word-wrap: break-word; resize: vertical;', 
'rows'=>'2')) !!}

</div>
 </div>

<button type="submit" class="btn btn btn-danger btn-block"><i class="fa fa-close"></i> {{trans('ticket.notSuccess')}}</button>

{!! Form::close(); !!}


</div>
<div class="col-md-12">
	
	  <div class="callout callout-default">
                <h4>{{trans('ticket.info')}}</h4>

                <p>{{trans('ticket.approveInfo')}} </p>
              </div>

</div>

@else
  <div class="callout callout-default">
                <h4>{{trans('ticket.info')}}</h4>

                <p>{{trans('ticket.approveInfoFull')}}</p>
              </div>
@endif
</div>
</div>
