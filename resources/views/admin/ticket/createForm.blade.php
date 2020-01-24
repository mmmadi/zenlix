@include("layout.header")

  {!! Html::style('plugins/iCheck/square/blue.css'); !!}

@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('at.ticketForms')}}
    <small>{{trans('at.creationTicketform')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li><a href="{!! URL::to('/admin/ticket/forms'); !!}">{{trans('at.ticketForms')}}</a></li>
        <li class="active">{{trans('at.creationForm')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">
            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"> {{trans('at.creationTicketform')}}</h3>
                </div>





                <div class="box-body">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->



{!! Form::open(array('action' => 'ConfigTicketController@storeForms', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}


                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', trans('at.nameOfForm'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('name', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    </div>


<hr>
              
<div class="form-group">
                      <label for="client_field" class="col-sm-3 control-label">{{trans('at.clientField')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('client_field', ['self'=>trans('at.authorTicket'), 'group'=>trans('at.userFromGroup')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%' )) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.groups')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('clientGroups[]', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
                      </div>
</div>

<hr>


<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.fieldTarget')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('target_field', ['users'=>trans('at.users'), 'group'=>trans('at.groups'), 'user_groups'=>trans('at.usersAndGroups')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.toGroups')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('target_groups[]', $groups, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.toUsers')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('target_users[]', $users, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
                      </div>
</div>
<hr>
<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.prio')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('prio', ['true'=>trans('at.active'), 'false'=>trans('at.noActive')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.fieldSubjTicket')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('subj_field', ['list'=>trans('at.list'), 'text'=>trans('at.inputField')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>
<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.listOfSubjTicket')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('subj_lists[]', $subjs, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
                      </div>
</div>


<hr>
<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.uploadFiles')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('upload_files', ['true'=>trans('at.active'), 'false'=>trans('at.noActive')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

                    <div class="form-group @if ($errors->has('upload_files_types')) has-error @endif">
                    {!! Form::label('upload_files_types', trans('at.fileTypes'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">


{!! Form::select('upload_files_types[]', ['jpeg'=>'jpeg','bmp'=>'bmp','png'=>'png','pdf'=>'pdf','doc'=>'doc','docx'=>'docx'], Null, array('class'=>'form-control input-sm select2-tags', 'style'=>'width: 100%', 'data-placeholder'=>trans('at.fileTypes'), 'multiple'=>'multiple')) !!}

                    @if ($errors->has('upload_files_types')) <p class="help-block">{{ $errors->first('upload_files_types') }}</p> @endif
                    </div>
                    </div>


                  <div class="form-group @if ($errors->has('upload_files_count')) has-error @endif">
                    {!! Form::label('upload_files_count', trans('at.fileCounts'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('upload_files_count', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('upload_files_count')) <p class="help-block">{{ $errors->first('upload_files_count') }}</p> @endif
                    </div>
                    </div>

                  <div class="form-group @if ($errors->has('upload_files_size')) has-error @endif">
                    {!! Form::label('upload_files_size', trans('at.fileLimits'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('upload_files_size', null, array('class'=>'form-control')) !!}
                    @if ($errors->has('upload_files_size')) <p class="help-block">{{ $errors->first('upload_files_size') }}</p> @endif
                    </div>
                    </div>

<hr>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.slaPlans')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('slas[]', $slas, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.deadline2')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('deadline_field', ['true'=>trans('at.active'), 'false'=>trans('at.noActive')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.watching')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('watching_field', ['true'=>trans('at.active'), 'false'=>trans('at.noActive')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.individualOk')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('individual_ok_field', ['true'=>trans('at.active'), 'false'=>trans('at.noActive')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.approveOk')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('check_after_ok', ['true'=>trans('at.active'), 'false'=>trans('at.noActive')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.userCreation')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('create_user', ['true'=>trans('at.active'), 'false'=>trans('at.noActive')], Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%')) !!}
                      </div>
</div>

<div class="form-group">
                      <label for="inputPassword4" class="col-sm-3 control-label">{{trans('at.AdvFields2')}}</label>
                      <div class="col-md-9">

                      {!! Form::select('fields[]', $fields, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple')) !!}
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

$(".select2-tags").select2({
tags: true,
tokenSeparators: [',', ' ']

});


        $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>