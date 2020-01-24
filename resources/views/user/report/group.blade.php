@include("layout.header")
{!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css'); !!}
{!! Html::style('plugins/daterangepicker/daterangepicker.css'); !!}


@include("layout.topmenu")
@include("layout.navbar")  



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-pie-chart"></i> {{trans('report.reports')}}
    <small>{{trans('report.userReports')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li>Отчёты</li>
        <li class="active">{{trans('report.userReports')}}</li>
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






                <div class="box-body">
     
{!! Form::open(array('action' => 'ReportController@showGroupReport', 'method'=> 'POST', 'class'=>'form-horizontal')) !!}

                    <div class="form-group @if ($errors->has('group')) has-error @endif">
                    {!! Form::label('group', trans('report.group'), array('class'=>'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                    {!! Form::select('group', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                    @if ($errors->has('group')) <p class="help-block">{{ $errors->first('group') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('period')) has-error @endif">
                    {!! Form::label('period', trans('report.period'), array('class'=>'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">

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
                        <div class="col-sm-offset-2 col-sm-8">
{!! HTML::decode(Form::button(trans('report.generate'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>



{!! Form::close(); !!}



                </div>


                </div>














            
                    </div><!-- /.box -->



<div class="col-md-3">







</div>


                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/daterangepicker/moment.min.js'); !!}
{!! Html::script('plugins/daterangepicker/daterangepicker.js'); !!}

<!-- page script -->
<script>
  $(function () {

$('#reservationtime').daterangepicker({
  timePicker: true, 
  timePicker24Hour: true,
  timePickerIncrement: 30, 
  locale: {
            format: 'YYYY-MM-DD hh:mm:ss'
        }

  

}, function(start, end, label) {

$("#startDate").val(start.format('YYYY-MM-DD hh:mm:ss'));
$("#endDate").val(end.format('YYYY-MM-DD hh:mm:ss'));

});

  });
</script>
</body>
</html>