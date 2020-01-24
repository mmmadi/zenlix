

                    <div class="panel box box-default">
                      <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapsePlanner" aria-expanded="false">
                           <small> {{trans('plannerMenu.planner')}} </small>
                          </a>
                        </h4>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="collapse" data-parent="#accordion" href="#collapsePlanner"><i class="fa fa-plus"></i></button>
                  </div>
                      </div>
                      <div id="collapsePlanner" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="box-body">

<div class="form-group">

<label for="unit" class="col-md-3 control-label">
{{trans('plannerMenu.activity')}}
</label>
<div class="col-sm-4">
                <label>

                {!! Form::checkbox('plannerStatus', 'true', false, array('class' => 'minimal')); !!}
                  {{trans('plannerMenu.active')}}
                </label>
</div>
</div>


<div class="form-group">
<label for='planner' class="col-md-3 control-label">
{{trans('plannerMenu.name')}}
</label>
<div class="col-md-9">
{!! Form::text('plannerName', Null, array('class'=>'form-control', 'placeholder'=>trans('plannerMenu.name'))) !!}
</div>
</div>

<div class="form-group">

<label class="col-md-3 control-label">{{trans('plannerMenu.period')}}</label>
<div class="col-md-9">
{!! Form::select('plannerPeriod', ['day'=>trans('plannerMenu.Day'), 'week'=>trans('plannerMenu.Week'), 'month'=>trans('plannerMenu.Month')], 'day', array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('plannerMenu.selPeriod'))) !!}
</div>
</div>

<div class="form-group">

<label class="col-md-3 control-label">{{trans('plannerMenu.every')}} </label>
<div class="col-sm-2">
{!! Form::selectRange('plannerEveryDay', 1, 31, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-7 ">
{{trans('plannerMenu.dayOfPeriod')}}
</div>

</div>
</div>

<div class="form-group">

<label class="col-md-3 control-label">{{trans('plannerMenu.time')}}</label>
<div class="col-md-9 bootstrap-timepicker">
                  <div class="input-group">
                                      <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    {!! Form::text('plannerTime', Null, array('class'=>'form-control timepicker')) !!}
                  </div>
</div>
</div>


<div class="form-group">
<label for='plannerStart' class="col-md-3 control-label">{{trans('plannerMenu.dateStart')}}</label>
<div class="col-sm-2">
{!! Form::selectRange('plannerStartDay', 1, 31, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-4">
{!! Form::selectMonth('plannerStartMonth', null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-3">
{!! Form::selectYear('plannerStartYear', date("Y"), date("Y")+1, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
</div>

<div class="form-group">
<label for='plannerEnd' class="col-md-3 control-label">{{trans('plannerMenu.dateEnd')}}</label>
<div class="col-sm-2">
{!! Form::selectRange('plannerEndDay', 1, 31, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-4">
{!! Form::selectMonth('plannerEndMonth', null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-3">
{!! Form::selectYear('plannerEndYear', date("Y"), date("Y")+1, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
</div>


<div class="alert alert-info alert-dismissible">
{{trans('plannerMenu.msg')}}
              </div>


</div>
</div>
