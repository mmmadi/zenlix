@include("layout.header")

  {!! Html::style('plugins/iCheck/square/blue.css'); !!}

@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('at.creationSLA')}}
    <small>{{trans('at.newSLA')}}</small>
    </h1>
    <ol class="breadcrumb">
       @include("layout.breadcrumb")
        <li><a href="{!! URL::to('/admin/ticket/sla'); !!}">{{trans('at.slaPlans')}}</a></li>
        <li class="active">{{trans('at.creationSLA')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">
            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"> {{trans('at.creationSLA')}}</h3>
                </div>





                <div class="box-body">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->





{!! Form::open(array('action' => 'ConfigTicketController@storeSla', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}

                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', trans('at.nameOfSla'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    </div>





<div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">{{trans('at.reaction')}}</h3>
                                </div>
                                <div class="box-body">
                                    <!-- Color Picker -->




  <div class="form-group">
    <label for="email_gate_mailbox" class="col-sm-4 control-label"><small>{{trans('at.lowprio')}}</small></label>
    <div class="col-sm-8">


<div class="input-group col-sm-12">
                                        

         {!! Form::text('react_low_1', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.d')}}</small></span>
                                                
        {!! Form::text('react_low_2', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.h')}}</small></span>
        
        {!! Form::text('react_low_3', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.m')}}</small></span>
        
        {!! Form::text('react_low_4', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.s')}}</small></span>
                                    
</div>


    </div>
  </div>

    <div class="form-group">
    <label for="email_gate_mailbox" class="col-sm-4 control-label"><small>{{trans('at.normprio')}}</small></label>
    <div class="col-sm-8">
      <div class="input-group col-sm-12">

         {!! Form::text('react_def_1', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.d')}}</small></span>
                                                
        {!! Form::text('react_def_2', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.h')}}</small></span>
        
        {!! Form::text('react_def_3', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.m')}}</small></span>
        
        {!! Form::text('react_def_4', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.s')}}</small></span>


                                    </div>
    </div>
  </div>

    <div class="form-group">
    <label for="email_gate_mailbox" class="col-sm-4 control-label"><small>{{trans('at.highprio')}}</small></label>
    <div class="col-sm-8">
     <div class="input-group col-sm-12">

         {!! Form::text('react_high_1', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.d')}}</small></span>
                                                
        {!! Form::text('react_high_2', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.h')}}</small></span>
        
        {!! Form::text('react_high_3', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.m')}}</small></span>
        
        {!! Form::text('react_high_4', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.s')}}</small></span>

                                    </div>
    </div>
  </div>




                                </div><!-- /.box-body -->
                            </div>




<div class="box box-warning">
                                <div class="box-header">
                                    <h3 class="box-title">{{trans('at.work')}}</h3>
                                </div>
                                 <div class="box-body">
                                    <!-- Color Picker -->




  <div class="form-group">
    <label for="email_gate_mailbox" class="col-sm-4 control-label"><small>{{trans('at.lowprio')}}</small></label>
    <div class="col-sm-8">


<div class="input-group col-sm-12">



         {!! Form::text('work_low_1', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.d')}}</small></span>
                                                
        {!! Form::text('work_low_2', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.h')}}</small></span>
        
        {!! Form::text('work_low_3', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.m')}}</small></span>
        
        {!! Form::text('work_low_4', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.s')}}</small></span>


                                    </div>


    </div>
  </div>

    <div class="form-group">
    <label for="email_gate_mailbox" class="col-sm-4 control-label"><small>{{trans('at.normprio')}}</small></label>
    <div class="col-sm-8">
      <div class="input-group col-sm-12">
                                        


         {!! Form::text('work_def_1', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.d')}}</small></span>
                                                
        {!! Form::text('work_def_2', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.h')}}</small></span>
        
        {!! Form::text('work_def_3', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.m')}}</small></span>
        
        {!! Form::text('work_def_4', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.s')}}</small></span>

                                    </div>
    </div>
  </div>

    <div class="form-group">
    <label for="email_gate_mailbox" class="col-sm-4 control-label"><small>{{trans('at.highprio')}}</small></label>
    <div class="col-sm-8">
     <div class="input-group col-sm-12">
                                        

         {!! Form::text('work_high_1', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.d')}}</small></span>
                                                
        {!! Form::text('work_high_2', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.h')}}</small></span>
        
        {!! Form::text('work_high_3', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.m')}}</small></span>
        
        {!! Form::text('work_high_4', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.s')}}</small></span>                                        


                                    </div>
    </div>
  </div>




                                </div><!-- /.box-body -->
                            </div>








<div class="box box-danger">
                                <div class="box-header">
                                    <h3 class="box-title">{{trans('at.deadline')}}</h3>
                                </div>
                                 <div class="box-body">
                                    <!-- Color Picker -->




  <div class="form-group">
    <label for="email_gate_mailbox" class="col-sm-4 control-label"><small>{{trans('at.lowprio')}}</small></label>
    <div class="col-sm-8">


<div class="input-group col-sm-12">



         {!! Form::text('deadline_low_1', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.d')}}</small></span>
                                                
        {!! Form::text('deadline_low_2', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.h')}}</small></span>
        
        {!! Form::text('deadline_low_3', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.m')}}</small></span>
        
        {!! Form::text('deadline_low_4', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.s')}}</small></span>



                                    </div>


    </div>
  </div>

    <div class="form-group">
    <label for="email_gate_mailbox" class="col-sm-4 control-label"><small>{{trans('at.normprio')}}</small></label>
    <div class="col-sm-8">
      <div class="input-group col-sm-12">


         {!! Form::text('deadline_def_1', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.d')}}</small></span>
                                                
        {!! Form::text('deadline_def_2', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.h')}}</small></span>
        
        {!! Form::text('deadline_def_3', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.m')}}</small></span>
        
        {!! Form::text('deadline_def_4', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.s')}}</small></span>



                                    </div>
    </div>
  </div>

    <div class="form-group">
    <label for="email_gate_mailbox" class="col-sm-4 control-label"><small>{{trans('at.highprio')}}</small></label>
    <div class="col-sm-8">
     <div class="input-group col-sm-12">



         {!! Form::text('deadline_high_1', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.d')}}</small></span>
                                                
        {!! Form::text('deadline_high_2', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.h')}}</small></span>
        
        {!! Form::text('deadline_high_3', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.m')}}</small></span>
        
        {!! Form::text('deadline_high_4', '0', array('class'=>'form-control input-sm')) !!}
        <span class="input-group-addon"><small>{{trans('at.s')}}</small></span>




                                    </div>
    </div>
  </div>




                                </div><!-- /.box-body -->
                            </div>








<div class="form-group">
                        <div class="col-sm-12">
{!! HTML::decode(Form::button(trans('at.create'), array('type' => 'submit', 'class'=>'btn btn-success pull-right'))) !!}
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
<!-- iCheck -->
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
  <!-- bootstrap-tagsinput -->
  {!! Html::script('plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js'); !!}
<!-- page script -->
<script>
  $(function () {

        $('.tags').tagsinput();


        $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>