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

<p>{{trans('au.total')}} {{$countRes}}</p>

<p>{{trans('au.top5')}}</p>


<table id="example1" class="table table-bordered">
                <thead>
                <tr>
                    <th><center>{{trans('au.name')}} </center></th>
                    <th><center>{{trans('au.email')}} </center></th>
                    <th><center>{{trans('au.tel')}} </center></th>
                    <th><center>{{trans('au.unit')}} </center></th>
                    <th><center>{{trans('au.name2')}} </center></th>
                    <th><center>{{trans('au.loginad')}} </center></th>
                    <th><center>{{trans('au.loginad2')}}</center></th>
                </tr>
                </thead>
                <tbody>


@foreach ($users as $user)
<tr>
<td style=" vertical-align: middle; ">{{$user['name']}}</td>
<td style=" vertical-align: middle; ">{{$user['mail']}}</td>
<td style=" vertical-align: middle; ">{{$user['telephone']}}</td>
<td style=" vertical-align: middle; ">{{$user['department']}}</td>
<td style=" vertical-align: middle; ">{{$user['title']}}</td>
<td style=" vertical-align: middle; ">{{$user['samaccountname']}}</td>
<td style=" vertical-align: middle; ">{{$user['userprincipalname']}}</td>
</tr>


@endforeach

</tbody>
</table>
<hr>






{!! Form::open(array('action' => 'ConfigUsersController@showImportLDAPStep3', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}




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
                  <p class="help-block"><small>{{trans('au.fromByGroup')}}</small></p>
                  
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

<hr>
<div class="form-group">
                      <label for="targetImport" class="col-sm-3 control-label">{{trans('au.users')}}</label>
                      <div class="col-md-9">
                      {!! Form::select('targetImport', ['all'=>trans('au.all'), 'selected'=>trans('au.selectedBottom')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
                    
                    <div class="col-md-3"></div>
                      <div class="col-md-9">
                      {!! Form::select('selectedUsers[]', $usersAll, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple')) !!}
                      </div>

</div>







<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('au.doImport'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
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



        $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });



  });
</script>
</body>
</html>