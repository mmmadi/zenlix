<div class="col-md-12 box box-danger">

<div class="box-body">


{!! Form::open(array('action' => ['TicketController@updateRefer', $ticket->code], 'method'=> 'POST', 'class'=>'form-horizontal')) !!}


@if ($TicketForm->target_field == 'user_groups')
              <div class="form-group @if ($errors->has('targetGroup')) has-error @endif" >

<label for='unit' class="col-md-2 control-label" >
{{trans('ticketReferForm.to')}}
</label>
<div class="col-md-5">
{!! Form::select('targetGroup', $tG, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketReferForm.group'))) !!}
                @if ($errors->has('targetGroup')) <p class="help-block">{{ $errors->first('targetGroup') }}</p> @endif
</div>
<div class="col-md-5">

{!! Form::select('targetUsers[]', $tU, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketReferForm.users'), 'multiple'=>'multiple')) !!}
                
                
</div>
              </div>
@elseif ($TicketForm->target_field == 'users')
              <div class="form-group @if ($errors->has('targetUsers')) has-error @endif" >

<label for='unit' class="col-md-2 control-label" >
{{trans('ticketReferForm.to')}}
</label>
<div class="col-md-10">
{!! Form::select('targetUsers[]', $tU, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketReferForm.users'), 'multiple'=>'multiple')) !!}
                 @if ($errors->has('targetUsers')) <p class="help-block">{{ $errors->first('targetUsers') }}</p> @endif
</div>

              </div>
@elseif ($TicketForm->target_field == 'group')
              <div class="form-group @if ($errors->has('targetGroup')) has-error @endif" >

<label for='unit' class="col-md-2 control-label" >
{{trans('ticketReferForm.to')}}
</label>
<div class="col-md-10">
{!! Form::select('targetGroup', $tG, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketReferForm.group'))) !!}
                @if ($errors->has('targetGroup')) <p class="help-block">{{ $errors->first('targetGroup') }}</p> @endif
</div>

              </div>
@endif




 <div class="form-group" >
 <label for='unit' class="col-md-2 control-label" >
{{trans('ticketReferForm.desc')}}
</label>
<div class="col-md-10">
                        


{!! Form::textarea('msg', Null, array('class'=>'form-control animated', 'placeholder'=>trans('ticketReferForm.addInfo'), 'style'=>'overflow: hidden; word-wrap: break-word; resize: vertical;', 
'rows'=>'2')) !!}

</div>
 </div>
 <div class="form-group" >
 <div class="col-md-2">
</div>
<div class="col-md-10">
            
            {!! Form::button(trans('ticketReferForm.refer'), array('type' => 'submit', 'class'=>'btn btn-flat margin bg-purple pull-right')); !!}
</div>
 </div>
{!! Form::close(); !!}

</div>
</div>