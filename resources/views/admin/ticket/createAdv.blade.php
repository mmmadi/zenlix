@include("layout.header")

  {!! Html::style('plugins/iCheck/square/blue.css'); !!}

@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('at.AdvField')}}
    <small>{{trans('at.FieldTicketCreation')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li><a href="{!! URL::to('/admin/ticket/adv'); !!}">{{trans('at.AdvFields2')}}</a></li>
        <li class="active">{{trans('at.CreationField')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">
            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"> {{trans('at.CreationAdvField')}}</h3>
                </div>





                <div class="box-body">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->


{!! Form::open(array('action' => 'ConfigTicketController@storeAdv', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}
                    
                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', trans('at.Name'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('field_name')) has-error @endif">
                    {!! Form::label('field_name', trans('at.NameInForm'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('field_name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('field_name')) <p class="help-block">{{ $errors->first('field_name') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('field_placeholder')) has-error @endif">
                    {!! Form::label('field_placeholder', trans('at.placeholder'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('field_placeholder', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('field_placeholder')) <p class="help-block">{{ $errors->first('field_placeholder') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('field_value')) has-error @endif">
                    {!! Form::label('field_value', trans('at.value'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('field_value', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('field_value')) <p class="help-block">{{ $errors->first('field_value') }}</p> @endif
                    </div>
                    </div>


<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.fieldType')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('f_type', ['text'=>trans('at.text'), 'texarea'=> trans('at.BigText'),'select'=>trans('at.list'), 'multiselect'=>trans('at.multilist')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%' )) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.ReqField')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('required', ['true'=>trans('at.yes'), 'false'=>trans('at.no')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%' )) !!}
                      </div>
</div>



<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('at.create'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
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