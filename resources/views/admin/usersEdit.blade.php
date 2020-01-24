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
    <small>{{trans('au.profileEdit')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li> <a href="{!! URL::to('/admin/users'); !!}">{{trans('au.users')}}</a></li>
        <li class="active">{{trans('au.profileEdit')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">
            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"> {{trans('au.profileEdit')}}</h3>
                </div>





                <div class="box-body">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->



{!! Form::model($user, array('action' => array('ConfigUsersController@update', $user->id), 'method'=> 'POST', 'class'=>'form-horizontal')) !!}


                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', trans('au.fio'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('email')) has-error @endif">
                    {!! Form::label('email', trans('au.loginOrMail'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('email', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                    </div>
                    </div>

               



<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('au.groupAnUser')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('groups[]', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('au.groupAnSuperuser')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('groupsSuper[]', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
                      </div>
</div>


                <div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('au.ticketform')}}</label>
                      <div class="col-md-9">
                <label class="col-md-6">
                  
                  {!! Form::radio('conf_params', 'group', true, array('class'=>'minimal')); !!}
                  {{trans('au.fromGroup')}}
                  <p class="help-block"><small>{{trans('au.formByGroup')}}</small></p>
                  
                </label >
                <div class="col-md-6">{!! Form::select('group_conf_id', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}</div>
<div class="col-md-12"></div>
                <label class="col-md-6">
                  {!! Form::radio('conf_params', 'user', false, array('class'=>'minimal')); !!}
                  {{trans('au.private')}}
                  <p class="help-block"><small>{{trans('au.ticketFormPrivate')}}</small></p>
                
                </label>
<div class="col-md-6">{!! Form::select('ticket_form_id', $forms, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}</div>
              </div>
                    </div>




<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('au.role')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('userRole', $roles, $user->roles->role, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>
<hr>

<div class="form-group">
                      <label for="LDAPStatus" class="col-sm-3 control-label">{{trans('au.ldapAuth')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('LDAPStatus', ['false'=>trans('au.no'), 'true'=>trans('au.active')], $user->ldap->status, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>
<div class="form-group">
                      <label for="LDAPAuth" class="col-sm-3 control-label">{{trans('au.allowAuthMethods')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('LDAPAuth', ['ldap'=>trans('au.onlyLdap'), 'system'=>trans('au.ldapAndSystem')], $user->ldap->authType, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

                    <div class="form-group @if ($errors->has('ldapLogin')) has-error @endif">
                    {!! Form::label('ldapLogin', trans('au.login'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('ldapLogin', $user->ldap->login, array('class'=>'form-control')) !!}
                    @if ($errors->has('ldapLogin')) <p class="help-block">{{ $errors->first('ldapLogin') }}</p> @endif
                    </div>
                    </div>



<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('au.edit'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
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