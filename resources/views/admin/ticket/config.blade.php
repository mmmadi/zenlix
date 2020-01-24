@include("layout.header")
{!! Html::style('plugins/iCheck/square/blue.css'); !!}


@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('at.ticketPerfs')}}
    <small>{{trans('at.conf')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">
        {{trans('at.ticketPerfs')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->


                        


            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('at.conf')}}</h3>
                </div>





                <div class="box-body">





{!! Form::open(array('action' => 'ConfigTicketController@update', 'method'=> 'POST', 'class'=>'form-horizontal')) !!}

                <div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.ticketCode')}}</label>
                      <div class="col-md-9">
                <label class="col-md-12">
                  
                  {!! Form::radio('ticketCode', 'autoinc', $ticketCodeInc, array('class'=>'minimal')); !!}
                  {{trans('at.byOrder')}}
  
                </label >

<div class="col-md-12"></div>
                <label class="col-md-6">
                  {!! Form::radio('ticketCode', 'code', $ticketCodeCode, array('class'=>'minimal')); !!}
                  {{trans('at.symbolCode')}}
                  
                
                </label>

  <div class="col-md-4 control-label">{{trans('at.symbolCounts')}}</div>
<div class="col-md-2">{!! Form::text('ticketCodeCount', Setting::get('ticket.codeCount'), array('class'=>'form-control input-sm', 'style'=>'width: 100%')) !!} </div>






              </div>
                    </div>







 
                    <div class="form-group @if ($errors->has('ticketDays2arch')) has-error @endif">
                    {!! Form::label('ticketDays2arch', trans('at.moveToArchive'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    <div class="input-group"> 
                    {!! Form::text('ticketDays2arch', Setting::get('ticket.days2arch'), array('class'=>'form-control')) !!}
                    <span class="input-group-addon">{{trans('at.days')}}</span>
                    </div>
                    @if ($errors->has('ticketDays2arch')) <p class="help-block">{{ $errors->first('ticketDays2arch') }}</p> @endif
                    </div>
                    </div> 

                    <div class="form-group @if ($errors->has('ticketDays2Del')) has-error @endif">
                    {!! Form::label('ticketDays2Del', trans('at.removeTickets'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    <div class="input-group"> 
                    {!! Form::text('ticketDays2Del', Setting::get('ticket.days2del'), array('class'=>'form-control')) !!}
                    <span class="input-group-addon">{{trans('at.days')}}</span>
                    </div>
                    @if ($errors->has('ticketDays2Del')) <p class="help-block">{{ $errors->first('ticketDays2Del') }}</p> @endif
                    </div>
                    </div> 
<hr>
                    <div class="form-group">
                    {!! Form::label('ticketDeadlineNotifyStatus', trans('at.notifyOfDeadline'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('ticketDeadlineNotifyStatus', ['true'=>trans('at.active'), 'false'=>trans('at.no')], [Setting::get('ticket.deadlineNotifyStatus')], array('class'=>'form-control select2')) !!}
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('ticketDeadlineNotify')) has-error @endif">
                    {!! Form::label('ticketDeadlineNotify', trans('at.notifyBefore'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    <div class="input-group"> 
                    {!! Form::text('ticketDeadlineNotify', Setting::get('ticket.deadlineNotify'), array('class'=>'form-control')) !!}
                    <span class="input-group-addon">{{trans('at.days')}}</span>
                    </div>
                    @if ($errors->has('ticketDeadlineNotify')) <p class="help-block">{{ $errors->first('ticketDeadlineNotify') }}</p> @endif
                    </div>
                    </div> 
<hr>

                    <div class="form-group">
                    {!! Form::label('ticketOvertimeNotifyStatus', trans('at.notifyAfterDeadline'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('ticketOvertimeNotifyStatus', ['true'=>trans('at.active'), 'false'=>trans('at.no')], [Setting::get('ticket.overtimeNotifyStatus')], array('class'=>'form-control select2')) !!}
                    </div>
                    </div>





                
<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('at.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>


{!! Form::close(); !!}


            </div>




                    </div><!-- /.box-body -->
                    </div><!-- /.box -->



<div class="col-md-3">


</div>


                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
<!-- page script -->
<script>
  $(function () {

        $(".select2").select2({
        allowClear: false
    });


        $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>