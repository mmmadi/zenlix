@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('profile.title')}}
    <small>{{$user->name}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active"><a href="{{URL::to('/user/'.$user->profile->user_urlhash)}}">{{$user->name}}</a></li>
        <li class="active">{{trans('profile.title')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-3">
          
          <div class="box box-primary">
            <div class="box-body box-profile">
            <center>
              <img class="img-responsive" src="{{ $userImg }}" alt="User profile picture">
</center>
              <h3 class="profile-username text-center">{{$user->name}}</h3>

              <p class="text-muted text-center">{{$user->profile->position}}</p>

            </div>

<div class="box-footer">



{!! Form::open(['url'=> action('ProfileController@updateUserImg'), 'method'=>'PATCH', 'files'=> true, 'id'=>'form_user_img']) !!}
 <span class="file-input btn btn-block btn-default btn-sm btn-file btn-flat">
                {{trans('profile.selectImg')}}
{!! Form::file('user_img', ['id'=>'user_img']) !!}
                </span>
{!! Form::close(); !!}



{!! Form::open(['url'=> action('ProfileController@destroyUserImg'), 'method'=>'DELETE']) !!}
{!! HTML::decode(Form::button(trans('profile.delete'), array('type' => 'submit', 'class'=>'btn bg-danger btn-sm btn-block btn-flat'))) !!}
{!! Form::close(); !!}




{{-- <button type="button" class="btn btn-block btn-default btn-sm">Выбрать фото</button> --}}
            </div>



            <!-- /.box-body -->
          </div>

        </div>

        <div class="col-md-9">
          
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">{{trans('profile.about')}}</a></li>
              <li><a href="#notify" data-toggle="tab">{{trans('profile.notify')}}</a></li>
              <li><a href="#interface" data-toggle="tab">{{trans('profile.interface')}}</a></li>
              <li><a href="#security" data-toggle="tab">{{trans('profile.security')}}</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">


{!! Form::model($user, ['method'=>'PATCH', 'url' => 'profile/edit', 'class'=>'form-horizontal']) !!}

                    <div class="form-group">
                    {!! Form::label('name', trans('profile.login'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9 control-label">
                    <strong class="pull-left"> {{$user->email}} </strong>
                    
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('profile.email')) has-error @endif">
                    {!! Form::label('profile.email', trans('profile.email'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('profile[email]', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('profile.email')) <p class="help-block">{{ $errors->first('profile.email') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', trans('profile.name'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('profile.position')) has-error @endif">
                    {!! Form::label('profile[position]', trans('profile.posada'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('profile[position]', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('profile.position')) <p class="help-block">{{ $errors->first('profile.position') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('profile.address')) has-error @endif">
                    {!! Form::label('profile[address]', trans('profile.address'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('profile[address]', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('profile.address')) <p class="help-block">{{ $errors->first('profile.address') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('profile.telephone')) has-error @endif">
                    {!! Form::label('profile[telephone]', trans('profile.tel'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('profile[telephone]', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('profile.telephone')) <p class="help-block">{{ $errors->first('profile.telephone') }}</p> @endif
                    </div>
                    </div>
<hr>
                    <div class="form-group ">
                    {!! Form::label('profile[birthdayDay]', trans('profile.birthday'), array('class'=>'col-sm-3 control-label')) !!}




<div class="col-sm-2">
{!! Form::selectRange('profile[birthdayDay]', 1, 31, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-4">
{!! Form::selectMonth('profile[birthdayMonth]', null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-3">
{!! Form::selectYear('profile[birthdayYear]', 1950, 2015, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>

                    
                   
                    
                   
                    </div>



                    <div class="form-group @if ($errors->has('profile.skype')) has-error @endif">
                    {!! Form::label('profile[skype]', trans('profile.skype'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('profile[skype]', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('profile.skype')) <p class="help-block">{{ $errors->first('profile.skype') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('profile.facebook')) has-error @endif">
                    {!! Form::label('profile[facebook]', trans('profile.facebook'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('profile[facebook]', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('profile.facebook')) <p class="help-block">{{ $errors->first('profile.facebook') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('profile.twitter')) has-error @endif">
                    {!! Form::label('profile[twitter]', trans('profile.twitter'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('profile[twitter]', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('profile.twitter')) <p class="help-block">{{ $errors->first('profile.twitter') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('profile.website')) has-error @endif">
                    {!! Form::label('profile[website]', trans('profile.website'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('profile[website]', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('profile.website')) <p class="help-block">{{ $errors->first('profile.website') }}</p> @endif
                    </div>
                    </div>
<hr>
                    <div class="form-group @if ($errors->has('profile.about')) has-error @endif">
                    {!! Form::label('profile[about]', trans('profile.about'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::textarea('profile[about]', null, array('class'=>'form-control', 'rows'=>'2')) !!}
                    @if ($errors->has('profile.about')) <p class="help-block">{{ $errors->first('profile.about') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('profile.skills')) has-error @endif">
                    {!! Form::label('profile[skills]', trans('profile.skills'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    
{!! Form::select('profile[skills][]', $skills, $skillsSel, array('class'=>'form-control input-sm select2-tags', 'style'=>'width: 100%', 'data-placeholder'=>trans('profile.tags'), 'multiple'=>'multiple')) !!}

                    @if ($errors->has('profile.skills')) <p class="help-block">{{ $errors->first('profile.skills') }}</p> @endif
                    </div>
                    </div>



@if ($fields->count() != 0)

<hr>
<em>{{trans('profile.add')}}</em>


@foreach ($fields as $field)
@if ($user->roles->role != "client")



<?php
$uf=$user->fields()->where('user_field_id', $field->id)->first();
if (empty($uf->field_data)) {
$vf=$field->value;
}
else {
$vf=$uf->field_data;
}
?>



  @if($field->field_type == "text")

                    <div class="form-group">
                    {!! Form::label('userfield_'.$field->id, $field->name, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('userfield_'.$field->id, $vf, array('class'=>'form-control', 'placeholder'=>$field->placeholder)) !!}
                    </div>
                    </div>
  @elseif($field->field_type == "textarea")
                    <div class="form-group">
                    {!! Form::label('userfield_'.$field->id, $field->name, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::textarea('userfield_'.$field->id, $vf, array('class'=>'form-control', 'placeholder'=>$field->placeholder,'rows'=>'2')) !!}
                    </div>
                    </div>

    @elseif($field->field_type == "select")


<?php
$vfArr=explode(',', $field->value);
$vfa=[];
foreach ($vfArr as $vfvalue) {
  //$vfa['0']='';
  $vfa[$vfvalue]=$vfvalue;
}

$uf=$user->fields()->where('user_field_id', $field->id)->first();
if (empty($uf->field_data)) {
$vf=Null;
}
else {
$vf=explode(',', $vf);
}

?>

                    <div class="form-group">
                    {!! Form::label('userfield_'.$field->id, $field->name, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('userfield_'.$field->id, [Null=>trans('profile.selValue')]+$vfa, $vf, array('class'=>'form-control select2', 'data-placeholder'=>$field->placeholder)) !!}
                    </div>
                    </div>



    @elseif($field->field_type == "multiselect")

<?php
$vfArr=explode(',', $field->value);
$vfa=[];
foreach ($vfArr as $vfvalue) {
  $vfa[$vfvalue]=$vfvalue;
}



$uf=$user->fields()->where('user_field_id', $field->id)->first();
if (empty($uf->field_data)) {
$vf=[];
}
else {
$vf=explode(',', $vf);
}

?>


                    <div class="form-group">
                    {!! Form::label('userfield_'.$field->id, $field->name, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('userfield_'.$field->id.'[]', $vfa, $vf, array('class'=>'form-control select2', 'data-placeholder'=>$field->placeholder, 'multiple')) !!}
                    </div>
                    </div>

  @endif





{{-- end non-client block --}}
@endif

@endforeach


@endif




<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('profile.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>

{!! Form::close(); !!}

              </div>

<div class="tab-pane" id="notify">
<div class="box-body">
{!! Form::model($user, ['method'=>'PATCH', 'url' => 'profile/edit/notify', 'class'=>'form-horizontal']) !!}

              

                    <div class="form-group">
                    {!! Form::label('mailNotify',trans('profile.mailNotify'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('mailNotify[]', $mailNotify, $mailNotifySelected, array('class'=>'form-control select2', 'multiple', 'style'=>'width:100%')) !!}
                    </div>
                    </div>

@if (Setting::get('mailStatus') == 'false')
<div class="alert alert-warning alert-dismissible">
                {{trans('profile.notifyDeactive')}}
</div>
@endif


<hr>
              
                    <div class="form-group">
                    {!! Form::label('smsNotify',trans('profile.smsNotify'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('smsNotify[]', $smsNotify, $smsNotifySelected, array('class'=>'form-control select2', 'multiple', 'style'=>'width:100%')) !!}
                    </div>
                    </div>


                    <div class="form-group">
                    {!! Form::label('sms', trans('profile.mob'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('sms', $user->profile->sms, array('class'=>'form-control', 'placeholder'=>'+380...')) !!}
                    </div>
                    </div>
@if (Setting::get('smsStatus') == 'false')
<div class="alert alert-warning alert-dismissible">
                {{trans('profile.notifyDeactive')}}
</div>
@endif

<hr>

                    <div class="form-group">
                    {!! Form::label('pb', trans('profile.pbLogin'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('pb', $user->profile->pb, array('class'=>'form-control', 'placeholder'=>'login@name.com')) !!}
                    </div>
                    </div>

@if (Setting::get('pbStatus') == 'false')
<div class="alert alert-warning alert-dismissible">
                {{trans('profile.notifyDeactive')}}
</div>
@endif



<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('profile.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>

             {!! Form::close(); !!}

</div>
</div>
<div class="tab-pane" id="interface">
<div class="box-body">

{!! Form::model($user->profile, ['method'=>'PATCH', 'url' => 'profile/edit/interface', 'class'=>'form-horizontal']) !!}
 
<div class="form-group @if ($errors->has('lang')) has-error @endif">
{!! Form::label('lang', trans('profile.lang'), array('class'=>'col-sm-3 control-label')) !!}
<div class="col-sm-9">

{!! Form::select('lang', array('en'=>'English','ru'=>'Русский', 'uk'=>'Українська'), Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%'))
!!}
@if ($errors->has('lang')) <p class="help-block">{{ $errors->first('lang') }}</p> @endif
                    </div>
                    </div>


                    <div class="form-group @if ($errors->has('user_urlhash')) has-error @endif">
                    {!! Form::label('user_urlhash', trans('profile.shortURL'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    <div class="input-group @if ($errors->has('user_urlhash')) has-error @endif">
                  <div class="input-group-addon">
                    {!! URL::to('/') !!}/user/
                  </div>

                    {!! Form::text('user_urlhash', null, array('class'=>'form-control')) !!}
                    
                    </div>
                    @if ($errors->has('user_urlhash')) <p class="help-block">{{ $errors->first('user_urlhash') }}</p> @endif
                    </div>
                    </div>

<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('profile.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>
{!! Form::close(); !!}









</div>
</div>
<div class="tab-pane" id="security">
<div class="box-body">


@if ((($user->ldap->status == "true") && ($user->ldap->authType == "system")) || ($user->ldap->status == "false") )

{!! Form::model($user, ['method'=>'PATCH', 'url' => 'profile/edit/password', 'class'=>'form-horizontal']) !!}

                    <div class="form-group @if ($errors->has('old_password')) has-error @endif">
                    {!! Form::label('old_password', trans('profile.oldPass'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::password('old_password', array('class'=>'form-control', 'placeholder'=>'Password')); !!}
                    @if ($errors->has('old_password')) <p class="help-block">{{ $errors->first('old_password') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('password')) has-error @endif">
                    {!! Form::label('password', trans('profile.newPass'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password')); !!}
                    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                    </div>
                    </div>

                    <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                    {!! Form::label('password_confirmation', trans('profile.renewPass'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::password('password_confirmation', array('class'=>'form-control', 'placeholder'=>'Password confirmation')); !!}
                    @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
                    </div>
                    </div>






<div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
{!! HTML::decode(Form::button(trans('profile.save'), array('type' => 'submit', 'class'=>'btn btn-success'))) !!}
</div>
</div>
{!! Form::close(); !!}

@endif

@if ($user->devices->count() > 0)

<hr>
<h4>{{ trans('profile.mobileDevices') }} </h4>

<div class="box-body no-padding">
              <table class="table table-condensed">
                <tbody><tr>
                  
                  <th>{{ trans('profile.mobileName') }}</th>
                  <th>{{ trans('profile.mobileDate') }}</th>
                  
                </tr>
@foreach($user->devices as $device)

                <tr>
                  <td>{{$device->device_name}}</td>
                  <td> {{LocalizedCarbon::instance($device->created_at)->formatLocalized('%d %f %Y, %H:%M')}} </td>
                </tr>
@endforeach

              </tbody></table>
            </div>
@endif






</div>
</div>

              </div>
              </div>



        </div>






                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
<!-- page script -->
<script>
  $(function () {
    var hash = window.location.hash;

    // do some validation on the hash here

    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $('input#user_img').change(function() {
        $('#form_user_img').submit();
    });


$(".select2-tags").select2({
tags: true,
tokenSeparators: [',', ' ']

});

  });
</script>
</body>
</html>