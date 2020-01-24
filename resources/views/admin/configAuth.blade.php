@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('ac.systemConfig')}}
    <small>{{trans('ac.auth')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li>{{trans('ac.systemConfig')}}</li>
        <li class="active">{{trans('ac.auth')}}</li>
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
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('ac.authConfig')}}</h3>
                </div>





                <div class="box-body">





{!! Form::open(array('action' => 'ConfigSystemController@updateAuth', 'method'=> 'POST', 'class'=>'form-horizontal')) !!}



                    <div class="form-group">
                    {!! Form::label('AuthUsers', trans('ac.userRegisters'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('AuthUsers', ['true'=>trans('ac.yes'), 'false'=>trans('ac.no')], Setting::get('AuthUsers'), array('class'=>'form-control select2')) !!}
                    </div>
                    </div>


                    <div class="form-group">
                    {!! Form::label('RecoveryPasswords', trans('ac.restorePass'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('RecoveryPasswords', ['true'=>trans('ac.yes'), 'false'=>trans('ac.no')], Setting::get('RecoveryPasswords'), array('class'=>'form-control select2')) !!}
                    </div>
                    </div>


                    <div class="form-group">
                    {!! Form::label('LdapAuth', trans('ac.ldapAuth'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('LdapAuth', ['true'=>trans('ac.yes'), 'false'=>trans('ac.no')], Setting::get('LdapAuth'), array('class'=>'form-control select2')) !!}
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('ldapServer')) has-error @endif">
                    {!! Form::label('ldapServer', trans('ac.ldapServer'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-4">
                    {!! Form::text('ldapServer', $ldapServer, array('class'=>'form-control')) !!}
                    @if ($errors->has('ldapServer')) <p class="help-block">{{ $errors->first('ldapServer') }}</p> @endif
                    </div>
<div class="col-sm-1 control-label"> {{trans('ac.port')}}</div>
                    <div class="col-sm-4">
                    {!! Form::text('ldapPort', $ldapPort, array('class'=>'form-control')) !!}

                    </div>



                    </div>

                    <div class="form-group @if ($errors->has('ldapDomain')) has-error @endif">
                    {!! Form::label('ldapDomain', trans('ac.ldapDomain'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('ldapDomain', $ldapDomain, array('class'=>'form-control')) !!}
                    @if ($errors->has('ldapDomain')) <p class="help-block">{{ $errors->first('ldapDomain') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('ldapDC')) has-error @endif">
                    {!! Form::label('ldapDC', trans('ac.ldapDC'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('ldapDC', $ldapDC, array('class'=>'form-control')) !!}
                    @if ($errors->has('ldapDC')) <p class="help-block">{{ $errors->first('ldapDC') }}</p> @endif
                    </div>
                    </div>


                
<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('ac.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
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
<!-- page script -->
<script>
  $(function () {


    $(".select2").select2({
        allowClear: false
    });


  });
</script>
</body>
</html>