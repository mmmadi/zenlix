          
<div class="modal fade" id="event_modal_create">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{trans('calendar.CreationEvents')}}</h4>
      </div>
      <div class="modal-body">
        





{!! Form::open(array('action' => 'CalendarController@storeEvent', 'method'=> 'POST', 'class'=>'form-horizontal')) !!}


                    <div class="form-group">
                    {!! Form::label('event_name', trans('calendar.name'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('event_name', null, array('class'=>'form-control')) !!}
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('event_info', trans('calendar.desc'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::textarea('event_info', null, array('class'=>'form-control', 'rows'=>'3')) !!}
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('event_allday', trans('calendar.weeky'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="event_allday" value="true"> {{trans('calendar.allDay')}}
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
                  <input type="text" class="form-control pull-right" id="reservationtime">
                  <input type="hidden" name="startDate" value="" id="startDate">
                  <input type="hidden" name="endDate" value="" id="endDate">
                </div>

                    @if ($errors->has('period')) <p class="help-block">{{ $errors->first('period') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('event_personal', 'Тип события', array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="event_personal" value="true"> {{trans('calendar.privateEvent')}}
                        </label>
                      </div>
                    </div>
                    </div>


                    <div class="form-group">
                    {!! Form::label('groups', trans('calendar.inGroups'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('groups[]', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
                    
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
                        <div class="col-sm-offset-3 col-sm-8">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('calendar.close')}}</button>
        {!! Form::button(trans('calendar.create'), array('type' => 'submit', 'class'=>'btn btn-success')); !!}
</div>
</div>

{!! Form::close() !!}

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
