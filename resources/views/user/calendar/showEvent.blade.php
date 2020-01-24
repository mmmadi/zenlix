          
<div class="modal fade" id="event_modal_show">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{trans('calendar.editEvent')}}</h4>
      </div>
      <div class="modal-body">
        





{!! Form::open(array('action' => 'CalendarController@updateEvent', 'method'=> 'POST', 'class'=>'form-horizontal')) !!}


                    <div class="form-group">
                    {!! Form::label('event_name', trans('calendar.name'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9" id="eventShowName">
                    
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('event_info', trans('calendar.desc'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9" id="eventShowDescription">
                    
                    </div>
                    </div>


                    <div class="form-group">
                    {!! Form::label('event_user', trans('calendar.author'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9" id="eventShowUser">
                    
                    </div>
                    </div>

                    <div class="form-group">
                    {!! Form::label('event_period', trans('calendar.period'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9" id="eventShowPeriod">
                    
                    </div>
                    </div>





{!! Form::close() !!}

      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
