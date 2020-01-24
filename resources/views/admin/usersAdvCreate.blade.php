@include("layout.header")

  {!! Html::style('plugins/iCheck/square/blue.css'); !!}

@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('au.users')}}
    <small>{{trans('au.advFields')}}</small>
    </h1>
    <ol class="breadcrumb">
       @include("layout.breadcrumb")
        <li> <a href="{!! URL::to('/admin/users'); !!}">{{trans('au.users')}}</a></li>
        <li class="active">{{trans('au.advFields')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">
            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"> {{trans('au.createField')}}</h3>
                </div>





                <div class="box-body">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->



{!! Form::open(array('action' => 'ConfigUsersController@storeAdv', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}


                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', trans('au.fieldName'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group ">
                    {!! Form::label('field_type', trans('au.fieldType'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('field_type', $fieldTypes, ['text'], array('class'=>'form-control select2')) !!}
                    
                    </div>
                    </div>

                                        <div class="form-group ">
                    {!! Form::label('value', trans('au.value'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('value', null, array('class'=>'form-control')) !!}
                    
                    </div>
                    </div>

               
                    <div class="form-group ">
                    {!! Form::label('placeholder', trans('au.placeholder'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('placeholder', null, array('class'=>'form-control')) !!}
                    
                    </div>
                    </div>

                    <div class="form-group ">
                    {!! Form::label('visible_client', trans('au.clientView'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('visible_client', ['false'=>trans('au.no'),'true'=>trans('au.yes')], ['false'], array('class'=>'form-control select2')) !!}
                    
                    </div>
                    </div>


                    <div class="form-group ">
                    {!! Form::label('status', trans('au.activation'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('status', ['false'=>trans('au.no'),'true'=>trans('au.yes')], ['true'], array('class'=>'form-control select2')) !!}
                    
                    </div>
                    </div>




<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('au.create'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
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