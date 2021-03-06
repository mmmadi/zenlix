          
<div class="modal fade" id="event_modal_edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{trans('calendar.editEvent')}}</h4>
      </div>
      <div class="modal-body">
        





{!! Form::open(array('action' => 'CalendarController@updateEvent', 'method'=> 'POST', 'class'=>'form-horizontal')) !!}


                    <div class="form-group">
                    {!! Form::label('eventTitle', trans('calendar.name'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('eventTitle', null, array('class'=>'form-control')) !!}
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('eventDescription', trans('calendar.desc'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::textarea('eventDescription', null, array('class'=>'form-control', 'rows'=>'3')) !!}
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('event_allday', trans('calendar.weeky'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="eventAllday" id="eventAllday" value="true"> {{trans('calendar.allDay')}}
                        </label>
                      </div>
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('period')) has-error @endif">
                    {!! Form::label('period', trans('calendar.period'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="reservationtimeEdit">
                  <input type="hidden" name="startDateEdit" value="" id="startDateEdit">
                  <input type="hidden" name="endDateEdit" value="" id="endDateEdit">
                </div>

                    @if ($errors->has('period')) <p class="help-block">{{ $errors->first('period') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('event_personal', trans('calendar.typeEvent'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="eventPersonal" id="eventPersonal" value="true"> {{trans('calendar.privateEvent')}}
                        </label>
                      </div>
                    </div>
                    </div>


                    <div class="form-group">
                    {!! Form::label('groupsEdit', trans('calendar.inGroups'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('groupsEdit[]', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple','id'=>'groupsEdit')) !!}
                    
                    </div>
                    </div>





<div class="form-group">
<label for="visibility" class="col-sm-3 control-label">{{trans('calendar.colorEvents')}}</label>
<div class="col-sm-9">
<div class="cur_color_event"> <small>{{trans('calendar.selectedColor')}} </small></div>

<input type="hidden" name="current_backgroundColor" class="current_backgroundColor" value="">
<input type="hidden" name="current_borderColor" class="current_borderColor" value="">

<div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                    <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                    <ul class="fc-color-picker" id="color-chooser_event">
                      <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>                                           
                      <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                    </ul>
                  </div><!-- /btn-group -->
                  
</div>
</div>




<div class="form-group">
                        <div class="col-sm-12">
<button id="cal_delete_current" data-code="" class="btn btn-danger pull-left">{{trans('calendar.delete')}}</button>


<input type="hidden" name="event_code" id="event_code" value="">

        {{-- <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Закрыть</button> --}}
        {!! Form::button(trans('calendar.save'), array('type' => 'submit', 'class'=>'btn btn-success pull-right')); !!}
</div>
</div>

{!! Form::close() !!}

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
