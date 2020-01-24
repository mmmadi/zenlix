@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('au.users')}}
    <small>{{trans('au.userImport')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('au.users')}}</li>
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
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> {{trans('au.userImport')}}</h3>
                </div>





                <div class="box-body">

{!! Form::open(array('action' => 'ConfigUsersController@showImportLDAPStep2', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}


                    <div class="form-group @if ($errors->has('LDAPAddress')) has-error @endif">
                    {!! Form::label('LDAPAddress', trans('au.adr'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('LDAPAddress', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('LDAPAddress')) <p class="help-block">{{ $errors->first('LDAPAddress') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('LDAPPort')) has-error @endif">
                    {!! Form::label('LDAPPort', trans('au.port'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('LDAPPort', Null, array('class'=>'form-control', 'placeholder'=>trans('au.exPort'))) !!}
                    @if ($errors->has('LDAPPort')) <p class="help-block">{{ $errors->first('LDAPPort') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('LDAPSuffix')) has-error @endif">
                    {!! Form::label('LDAPSuffix', trans('au.sufix'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('LDAPSuffix', Null, array('class'=>'form-control', 'placeholder'=>trans('au.exSuffix'))) !!}
                    @if ($errors->has('LDAPSuffix')) <p class="help-block">{{ $errors->first('LDAPSuffix') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('LDAPDN')) has-error @endif">
                    {!! Form::label('LDAPDN', trans('au.baseDn'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('LDAPDN', Null, array('class'=>'form-control', 'placeholder'=>trans('au.exBaseDn'))) !!}
                    @if ($errors->has('LDAPDN')) <p class="help-block">{{ $errors->first('LDAPDN') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('LDAPLogin')) has-error @endif">
                    {!! Form::label('LDAPLogin', trans('au.login'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('LDAPLogin', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('LDAPLogin')) <p class="help-block">{{ $errors->first('LDAPLogin') }}</p> @endif
                    </div>
                    </div>

                   <div class="form-group @if ($errors->has('LDAPPassword')) has-error @endif">
                    {!! Form::label('LDAPPassword', trans('au.pass'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::password('LDAPPassword', array('class'=>'form-control')) !!}
                    @if ($errors->has('LDAPPassword')) <p class="help-block">{{ $errors->first('LDAPPassword') }}</p> @endif
                    </div>
                    </div>

<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('au.next'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
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







  });
</script>
</body>
</html>