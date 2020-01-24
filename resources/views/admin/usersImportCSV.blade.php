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


        <div class="col-md-10">


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

@if ($upload == 'false')
<div class="col-md-2">


{!! Form::open(['url'=> action('ConfigUsersController@updateUsersImportCsv'), 'method'=>'PATCH', 'files'=> true, 'id'=>'form_users_import']) !!}
 <span class="file-input btn-file btn btn-app">
                <i class="fa fa-file-excel-o"></i> {{trans('au.selectFile')}}
{!! Form::file('users_csv', ['id'=>'users_csv']) !!}
                </span>
{!! Form::close(); !!}

</div>
<div class="col-md-10 callout callout-info">
                <h4>{{trans('au.uploadForStart')}}</h4>

                <p>{{trans('au.fileOnlyCSV')}}</p>
              </div>
@endif


@if ($upload == 'true')

{!! Form::open(array('action' => 'ConfigUsersController@updateUsersImportCsvStep2', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}




<div class="form-group">
                      <label for="login" class="col-sm-3 control-label">{{trans('au.loginReq')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('login', $attrs, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="email" class="col-sm-3 control-label">{{trans('au.emailReq')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('email', $attrs, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="name" class="col-sm-3 control-label">{{trans('au.nameReq')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('name', $attrs, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="pass" class="col-sm-3 control-label">{{trans('au.pass')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('pass', $attrs, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="position" class="col-sm-3 control-label">{{trans('au.position')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('position', $attrs, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="telephone" class="col-sm-3 control-label">{{trans('au.tel')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('telephone', $attrs, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="address" class="col-sm-3 control-label">{{trans('au.adr')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('address', $attrs, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>






<hr>

<div class="form-group">
                      <label for="groups" class="col-sm-3 control-label">{{trans('au.groups')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('groups[]', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple')) !!}
                      </div>
</div>

                <div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('au.ticketform')}}</label>
                      <div class="col-md-9">
                <label class="col-md-6">
                  
                  {!! Form::radio('conf_params', 'group', true, array('class'=>'minimal')); !!}
                  {{trans('au.fromGroup')}}
                  <p class="help-block"><small>{{trans('au.ticketByGroup')}}</small></p>
                  
                </label >
                <div class="col-md-6">{!! Form::select('group_conf_id', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}</div>
<div class="col-md-12"></div>
                <label class="col-md-6">
                  {!! Form::radio('conf_params', 'user', false, array('class'=>'minimal')); !!}
                  {{trans('au.private')}}
                  <p class="help-block"><small>{{trans('au.ticketFormPrivate')}}</small></p>
                
                </label>
<div class="col-md-6">{!! Form::select('ticket_form_id', $TicketForms, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}</div>
              </div>
                    </div>




<div class="form-group">
                      <label for="role" class="col-sm-3 control-label">{{trans('au.role')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('role', ['client'=>trans('au.client'),'user'=>trans('au.user')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>
<hr>


<div class="form-group">
                      <label for="ldapStatus" class="col-sm-3 control-label">{{trans('au.ldapAuth')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('ldapStatus', ['false'=>trans('au.no'),'true'=>trans('au.active')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>
<div class="form-group">
                      <label for="ldapType" class="col-sm-3 control-label">{{trans('au.authType')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('ldapType', ['ldap'=>trans('au.onlyLdap'),'system'=>trans('au.ldapAndSystem')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="ldapLogin" class="col-sm-3 control-label">{{trans('au.login')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('ldapLogin', $attrs, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<hr>

<div class="form-group">
                      <label for="notify" class="col-sm-3 control-label">{{trans('au.notifyCreateUser')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('notify', ['false'=>trans('au.no'), 'true'=>trans('au.yes')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>





<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('au.doImport'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>


{!! Form::close(); !!}



@endif






            </div>




                    </div><!-- /.box-body -->
                    </div><!-- /.box -->



<div class="col-md-2">



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

        $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });

    $('input#users_csv').change(function() {
        $('#form_users_import').submit();
    });




  });
</script>
</body>
</html>