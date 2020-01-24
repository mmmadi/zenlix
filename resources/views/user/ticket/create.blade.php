@include("layout.header")

  <!-- bootstrap-tagsinput -->
  {!! Html::style('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css'); !!}

  <!-- Bootstrap time Picker -->
  {!! Html::style('plugins/timepicker/bootstrap-timepicker.min.css'); !!}

  <!-- iCheck -->
  {!! Html::style('plugins/iCheck/minimal/purple.css'); !!}

  {!! Html::style('plugins/dropzone/css/dropzone.css'); !!}

<style type="text/css">
  
  .select2-result-repository { padding-top: 4px; padding-bottom: 3px; }
.select2-result-repository__avatar { float: left; width: 60px; margin-right: 10px; }
.select2-result-repository__avatar img { width: 100%; height: auto; border-radius: 2px; }
.select2-result-repository__meta { margin-left: 70px; }
.select2-result-repository__title { color: black; font-weight: bold; word-wrap: break-word; line-height: 1.1; margin-bottom: 4px; }
.select2-result-repository__forks, .select2-result-repository__stargazers { margin-right: 1em; }
.select2-result-repository__forks, .select2-result-repository__stargazers, .select2-result-repository__watchers { display: inline-block; color: #aaa; font-size: 11px; }
.select2-result-repository__description { font-size: 13px; color: #777; margin-top: 4px; }
.select2-results__option--highlighted .select2-result-repository__title { color: white; }
.select2-results__option--highlighted .select2-result-repository__forks, .select2-results__option--highlighted .select2-result-repository__stargazers, .select2-results__option--highlighted .select2-result-repository__description, .select2-results__option--highlighted .select2-result-repository__watchers { color: #c6dcef; }



</style>
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
                    <h1>
                        <i class="fa fa-tag"></i> {{trans('ticketCreate.newTicket')}}
                        
                    </h1>
                    <ol class="breadcrumb">
                        @include("layout.breadcrumb")
                        <li class="active">{{trans('ticketCreate.newTicket')}}</li>
                    </ol>
                </section>

    <!-- Main content -->
    <section class="content">

{!! Form::open(array('action' => 'TicketController@store', 'method'=> 'PATCH', 'class'=>'form-horizontal')) !!}
<div class="row" style="padding-bottom:20px;">


                            
                            
                            
                            
                            
                            
<div class="col-md-8" >




                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <div class="alert alert-{{ $msg }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{!! Session::get('alert-' . $msg) !!} 
                        </div>
                        @endif
                        @endforeach
                    </div> <!-- end .flash-message -->




<div class="box box-solid" >
    <div class="box-body" >








@if ($TicketForm->client_field == 'self')
@elseif ($TicketForm->client_field == 'group')
<div class="form-group @if ($errors->has('client')) has-error @endif">
<label for='from' class="col-md-2 control-label">
{{trans('ticketCreate.client')}}
</label>
<div class="col-md-10">


@if (empty(old('client')))
<?php $client = []; ?>
@else
<?php 
$client=[];
foreach (old('client') as $key => $value) {
  if (strpos($value,'[new]') !== false) { 
      $client[$value]=$value;
   }
   else {
      $u=zenlix\User::findOrFail($value);
      $client[$value]=$u->name;
   }

} ?>
@endif

{!! Form::select('client[]', $client, Null, array('class'=>'form-control input-sm js-data-client', 'style'=>'width: 100%', 'multiple'=>'multiple', 'data-placeholder'=>trans('ticketCreate.selClients'))) !!}
                 
@if ($errors->has('client')) <p class="help-block">{{ $errors->first('client') }}</p> @endif


                  </div></div>
                  <div class="form-group">
<div class="col-md-2"></div>
<div class="col-md-10">
                <label>

                {!! Form::checkbox('notifyClient', 'true', false, array('class' => 'minimal')); !!}
                  {{trans('ticketCreate.notifyClient')}}
                </label>
</div>
</div>
                  
<hr>
@endif


@if ($TicketForm->target_field == 'user_groups')
              <div class="form-group @if ($errors->has('targetGroup')) has-error @endif" >

<label for='unit' class="col-md-2 control-label" >
{{trans('ticketCreate.to')}}
</label>
<div class="col-md-5">
{!! Form::select('targetGroup', $targetGroup, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketCreate.selGroup'))) !!}
                @if ($errors->has('targetGroup')) <p class="help-block">{{ $errors->first('targetGroup') }}</p> @endif
</div>
<div class="col-md-1 control-label">{{trans('ticketCreate.or')}}</div>
<div class="col-md-4">

{!! Form::select('targetUsers[]', $targetUser, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketCreate.selUsers'), 'multiple'=>'multiple')) !!}
                @if ($errors->has('targetUsers')) <p class="help-block">{{ $errors->first('targetUsers') }}</p> @endif
                
</div>
              </div>
@elseif ($TicketForm->target_field == 'users')
              <div class="form-group @if ($errors->has('targetUsers')) has-error @endif" >

<label for='unit' class="col-md-2 control-label" >
{{trans('ticketCreate.to')}}
</label>
<div class="col-md-10">
{!! Form::select('targetUsers[]', $targetUser, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketCreate.selUsers'), 'multiple'=>'multiple')) !!}
                 @if ($errors->has('targetUsers')) <p class="help-block">{{ $errors->first('targetUsers') }}</p> @endif
</div>

              </div>
@elseif ($TicketForm->target_field == 'group')
              <div class="form-group @if ($errors->has('targetGroup')) has-error @endif" >

<label for='unit' class="col-md-2 control-label" >
{{trans('ticketCreate.to')}}
</label>
<div class="col-md-10">
{!! Form::select('targetGroup', $targetGroup, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketCreate.selGroup'))) !!}
                @if ($errors->has('targetGroup')) <p class="help-block">{{ $errors->first('targetGroup') }}</p> @endif
</div>

              </div>
@endif

@if ($TicketForm->prio == 'true')

{!! Form::hidden('prio', 'normal', ['id'=>'prioVal']) !!}

<div class="control-group" id="for_prio">
    <div class="controls">
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">{{trans('ticketCreate.prio')}}</label>
            <div class="col-sm-10" style=" padding-top: 5px; ">

                <div class="btn-group btn-group-justified">
                    <div class="btn-group">
                        <button type="button" class="btn prio bg-purple btn-xs" data-value="low"><i id="lprioclass" class=""></i> {{trans('ticketCreate.low')}}</button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn prio bg-orange btn-xs active"data-value="normal"><i id="lprioclass" class="fa fa-check"></i> {{trans('ticketCreate.normal')}}</button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn prio bg-maroon btn-xs" data-value="high"><i id="lprioclass" class=""></i> {{trans('ticketCreate.high')}}</button>
                    </div>
                </div>
            </div></div></div></div>
@endif

@if (empty(old('tags')))
<?php $tags = []; ?>
@else
<?php
$tags=[];
foreach (old('tags') as $key => $value) {
  $tags[$value]=$value;
} ?>
@endif


@if ($TicketForm->subj_field == 'list')
              <div class="form-group @if ($errors->has('subj')) has-error @endif">

<label for='unit' class="col-md-2 control-label">
{{trans('ticketCreate.subj')}}
</label>
<div class="col-md-5">
{!! Form::select('subj', $subj, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketCreate.selSubj'))) !!}
                   @if ($errors->has('subj')) <p class="help-block">{{ $errors->first('subj') }}</p> @endif
</div>
<div class="col-md-5">




{!! Form::select('tags[]', $tags, Null, array('class'=>'form-control input-sm select2-tags', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketCreate.tags'), 'multiple'=>'multiple')) !!}

                
</div>
              </div>

@elseif ($TicketForm->subj_field == 'text')
              <div class="form-group @if ($errors->has('subj')) has-error @endif">

<label for='unit' class="col-md-2 control-label">
{{trans('ticketCreate.subj')}}
</label>
<div class="col-md-5">
{!! Form::text('subj', Null, array('class'=>'form-control', 'style'=>'width: 100%', 'placeholder'=>trans('ticketCreate.selSubj'))) !!}
                  @if ($errors->has('subj')) <p class="help-block">{{ $errors->first('subj') }}</p> @endif
</div>
<div class="col-md-5">
{!! Form::select('tags[]', [], Null, array('class'=>'form-control input-sm select2-tags', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketCreate.tags'), 'multiple'=>'multiple')) !!}

                
</div>
              </div>
@endif


              <div class="form-group @if ($errors->has('msg')) has-error @endif">

<label for='unit' class="col-md-2 control-label">
{{trans('ticketCreate.text')}}
</label>
<div class="col-md-10">

{!! Form::textarea('msg', Null, array('class'=>'form-control trumbowyg', 'id'=>'MsgBox')) !!}
        @if ($errors->has('msg')) <p class="help-block">{{ $errors->first('msg') }}</p> @endif         
</div>
              </div>

@if ($TicketForm->upload_files == 'true')
<label for='unit' class="col-md-2 control-label">
{{trans('ticketCreate.files')}}
</label>



<div class="col-md-10" style="padding: 0px;">
<div class="" style="background: #eee;
    display: block;
    margin: 5px 5px 10px 5px;
    min-height: 70px;
    overflow: hidden;
    position: relative;
    width: auto;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;">
                            
                            <div  style="cursor:pointer;">


<div class="dz-clickable" id="myid">

                                                                            <div class="dz-message" data-dz-message="">
                                                                               <div class="text-center m-t-md">
                                <h4 class="text-muted">{{trans('ticketCreate.dropFiles')}}</h4>
                                <p class="text-muted">
                                    {{trans('ticketCreate.orClickFiles')}}
                                </p>
                                </div>
                                                                            </div>
                                                                        
<div class="table table-striped" style="margin-bottom: 0px;" class="files" id="previews">

                                                                            <div id="template" class="file-row">



                                                                                <!-- This is used as the file preview template -->
<table class="table" style="margin-bottom: 0px; background-color: #E6E6E6;">
<tbody><tr>
<td style="width:50%"><p class="name" data-dz-name></p> </td>
<td><small class="text-muted"><p class="size" data-dz-size></p></small></td>
<td style="width:30%">

<div class="progress progress-xs" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                    <div style="width: 0%;" class="progress-bar" data-dz-uploadprogress></div>
                                </div>



</td>
<td class="pull-right"><button data-dz-remove class="btn btn-xs btn-danger delete">
<i class="glyphicon glyphicon-trash"></i>
<span>{{trans('ticketCreate.delete')}}</span>
</button></td>
</tr>
</tbody></table>
                                                                            </div>
                                                                        </div>

                                                                    </div>




                                
                            </div>

                     
                        </div>
</div>

@endif


<div class="col-md-2"></div>
<div class="col-md-10" style=" padding: 5px; ">
  <div class="box-group" id="accordion">

@if ($TicketForm->fields->count() > 0 )

<div class="panel box box-default">
                      <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="" aria-expanded="true">
                           <small>  {{trans('ticketCreate.addFields')}} </small>
                          </a>
                        </h4>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><i class="fa fa-plus"></i></button>
                  </div>
                      </div>
                      <div id="collapseTwo" class="panel-collapse collapse in
" aria-expanded="true" >
                        <div class="box-body">

@foreach ($TicketForm->fields as $field)

<?php ($field->required == 'true') ? $rText=' *' : $rText=''; ?>

  @if ($field->f_type == 'text')

                    <div class="form-group @if ($errors->has('field'.$field->id)) has-error @endif">
                    {!! Form::label('field'.$field->id, $field->field_name.$rText, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('field'.$field->id, Null, array('class'=>'form-control', 'placeholder'=>$field->field_placeholder)) !!}
                    @if ($errors->has('field'.$field->id)) <p class="help-block">{{ $errors->first('field'.$field->id) }}</p> @endif
                    </div>
                    </div>


  @elseif ($field->f_type == 'textarea')

                    <div class="form-group @if ($errors->has('field'.$field->id)) has-error @endif">
                    {!! Form::label('field'.$field->id, $field->field_name.$rText, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::textarea('field'.$field->id, Null, array('class'=>'form-control', 'placeholder'=>$field->field_placeholder, 'rows'=>'2')) !!}
                    @if ($errors->has('field'.$field->id)) <p class="help-block">{{ $errors->first('field'.$field->id) }}</p> @endif
                    </div>
                    </div>



  @elseif ($field->f_type == 'select')

<?php 
$fieldVal=[];
foreach (explode(',', $field->field_value) as $key => $value) {
  $fieldVal[$value]=$value;
} 

?>



                    <div class="form-group @if ($errors->has('field'.$field->id)) has-error @endif">
                    {!! Form::label('field'.$field->id, $field->field_name.$rText, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('field'.$field->id, $fieldVal, [], array('class'=>'form-control select2', 'data-placeholder'=>$field->field_placeholder, 'style'=>'width:100%')) !!}
                    @if ($errors->has('field'.$field->id)) <p class="help-block">{{ $errors->first('field'.$field->id) }}</p> @endif
                    </div>
                    </div>




  @elseif ($field->f_type == 'multiselect')
<?php 
$fieldVal=[];
foreach (explode(',', $field->field_value) as $key => $value) {
  $fieldVal[$value]=$value;
} 

?>

                    <div class="form-group @if ($errors->has('field'.$field->id)) has-error @endif">
                    {!! Form::label('field'.$field->id, $field->field_name.$rText, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('field'.$field->id.'[]', $fieldVal, [], array('class'=>'form-control select2', 'data-placeholder'=>$field->field_placeholder, 'style'=>'width:100%', 'multiple'=>'multiple')) !!}
                    @if ($errors->has('field'.$field->id)) <p class="help-block">{{ $errors->first('field'.$field->id) }}</p> @endif
                    </div>
                    </div>


  @endif


@endforeach


              

              





                        </div>
                      </div>
                    </div>
@endif
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel box box-default">
                      <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false">
                           <small> {{trans('ticketCreate.options')}} </small>
                          </a>
                        </h4>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><i class="fa fa-plus"></i></button>
                  </div>
                      </div>
                      <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="box-body">
          

@if ($TicketForm->slas->count() > 0 )

                    <div class="form-group @if ($errors->has('sla')) has-error @endif">
                    {!! Form::label('sla', trans('ticketCreate.slaPlan'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('sla', $slas, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketCreate.selSlaPlan'))) !!}
                    @if ($errors->has('sla')) <p class="help-block">{{ $errors->first('sla') }}</p> @endif
                    </div>
                    </div>


@endif



@if ($TicketForm->deadline_field == "true" )
<div class="form-group">

<label for="unit" class="col-md-3 control-label">
{{trans('ticketCreate.deadline')}}
</label>
<div class="col-sm-4">
                <label>

                {!! Form::checkbox('deadlineStatus', 'active', false, array('class' => 'minimal')); !!}
                  {{trans('ticketCreate.activate')}}
                </label>
</div>

<div class="col-md-5 bootstrap-timepicker">

                  <div class="input-group">
                                      <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    {!! Form::text('deadlineTime', Null, array('class'=>'form-control timepicker')) !!}
                  </div>
                
                
</div>
<div class="col-sm-12"></div>
<div class="col-sm-3"></div>
<div class="col-sm-2">

    <?php
    //setlocale(LC_ALL, 'ru_RU.UTF-8');

    ?>
{!! Form::selectRange('deadlineDay', 1, 31, date('d'), array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-4">
{!! Form::selectMonth('deadlineMonth', date('m'), array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-3">
{!! Form::selectYear('deadlineYear', date("Y"), date("Y")+1, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>




              </div>
@endif









@if ($TicketForm->watching_field == "true" )
<div class="form-group">

<label for='unit' class="col-md-3 control-label">
{{trans('ticketCreate.watching')}}
</label>
<div class="col-md-9">

{!! Form::select('watchingUsers[]', $watchingUsers, Null, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketCreate.selUsers'), 'multiple'=>'multiple')) !!}
                
</div>
              </div>
@endif



@if ($TicketForm->individual_ok_field == "true" )
 <div class="form-group">
<div class="col-md-3"></div>
<div class="col-md-9">


         
            <label>
              {!! Form::checkbox('individual_ok', 'true', false, ['class'=>'minimal']); !!} 
              {{trans('ticketCreate.onceSuccess')}}
            </label>
          


{{--                 <label>
                  <input type="checkbox" class="minimal" checked>
                  Принять работу после завершения задачи
                </label> --}}
</div>
              </div>
@endif


@if ($TicketForm->check_after_ok == "true" )
<div class="form-group">
<div class="col-md-3"></div>
<div class="col-md-9">

            <label>
              {!! Form::checkbox('check_after', 'true', false, ['class'=>'minimal']); !!} 
               
               {{trans('ticketCreate.checkAfter')}}
            </label>

</div>
              </div>
@endif





                        </div>
                      </div>
                    </div>
@if ($CurUser->roles->role != 'client')




@include('user.ticket.plannerMenu')






@endif                    
                    
                  </div>
                  <br>
</div>


<div class="col-md-2">
</div>
<div class="col-md-10" id="processing">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">

            {!! Form::button(trans('ticketCreate.createTicket'), array('type' => 'submit', 'class'=>'btn btn-success btn-flat')); !!}

        </div>
        <div class="btn-group">
            <a href="{{URL::to('/ticket/create')}}" class="btn btn-default" ><i class="fa fa-eraser"></i> {{trans('ticketCreate.clearForm')}}</a>
        </div>
    </div>






</div>
{{-- 

{!! Form::close(); !!}
 --}}

    </div>








</div>



</div>
<div class="col-md-4" id="CLIENTS">



@if (empty(old('client')))
<?php $client = []; ?>
@else
<?php 
$client=[];

$existClient=[];
$newClient=[];

foreach (old('client') as $key => $value) {



  if (strpos($value,'[new]') !== false) { 
      $client[$value]=$value;

$name=explode('[new]', $value);
      array_push($newClient, $name[0]);

   }
   else {


    //$client=zenlix\User::findOrFail($value);
    array_push($existClient, $value);


   }

} 



?>
@include("user.ticket.clientsList")
@endif











</div>



</div>

{!! Form::close(); !!}
    </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
  <!-- bootstrap-tagsinput -->
  {!! Html::script('plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js'); !!}

<!-- InputMask -->
  {!! Html::script('plugins/input-mask/jquery.inputmask.js'); !!}
  {!! Html::script('plugins/input-mask/jquery.inputmask.date.extensions.js'); !!}
  {!! Html::script('plugins/input-mask/jquery.inputmask.extensions.js'); !!}
<!-- bootstrap time picker -->
  {!! Html::script('plugins/timepicker/bootstrap-timepicker.min.js'); !!}
<!-- bootstrap time picker -->
  {!! Html::script('plugins/dropzone/js/dropzone.js'); !!}
<!-- iCheck -->
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
  <script>
  $(function () {
    $('.tags').tagsinput();
        //Datemask dd/mm/yyyy
    $(".datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Timepicker
    $(".timepicker").timepicker({
      showInputs: false,
      showMeridian: false
    });

    $('input').iCheck({
      checkboxClass: 'icheckbox_minimal-purple',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });

    function formatRepo (repo) {
      if (repo.loading) return repo.text;

      var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'><img src='" + repo.img + "' /></div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'>" + repo.name + "</div>";

      if (repo.id) {
        markup += "<div class='select2-result-repository__description'>" + repo.position + "</div>";
      }

      markup +=
      "</div>" +
      "</div></div>";

      return markup;
    }

    function formatRepoSelection (repo) {
      return repo.name || repo.text;
    }


$(".js-data-client").select2({
  allowClear: true,
  ajax: {
    url: "{{ URL::to('/ticket/clients') }}",
    dataType: 'json',
    allowClear: true,
    placeholder: "Select an attribute",
    delay: 250,
    data: function (params) {
      return {
        q: params.term
      };
    },
    processResults: function (data, params) {
      // parse the results into the format expected by Select2
      // since we are using custom formatting functions we do not need to
      // alter the remote JSON data, except to indicate that infinite
      // scrolling can be used
      params.page = params.page || 1;
 
      return {
        results: data.items
      };
    },
    cache: true
  },
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 1,
  templateResult: formatRepo, // omitted for brevity, see the source of this page
  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
})

;


$(".js-data-client").on("change", function (e) {


console.log($(this).val());

var ClientList=$(this).val();

$.ajax({
                    type: "POST",
                    url: "{{URL::to('/ticket/clients/view') }}",
                    dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'POST',
                      clients: ClientList
                    },
                    success: function(html) {
$.each(html, function(i, item) {

//console.log(item.total);

  //$('#CLIENTS').html(item.html);
  $("#CLIENTS").hide().html(item.html).fadeIn(500);
  //$("#totalComments").val(item.total);

});

}
});


});


$(".select2-tags").select2({
tags: true,
tokenSeparators: [',', ' ']

});

$('body').on('click', '.prio', function(event) {
            event.preventDefault();

$(".prio").removeClass('active');
$(".prio").children().removeClass('fa fa-check');
$(this).addClass('active');
$(this).children().addClass('fa fa-check');


$("input#prioVal").val($(this).attr('data-value'));
});


                       var previewNode = document.querySelector("#template");
                       previewNode.id = "";
                       var previewTemplate = previewNode.parentNode.innerHTML;
                       previewNode.parentNode.removeChild(previewNode);



                       $('#myid').dropzone({
                           url: SYS_URL+'/ticket/upload/files',
                           paramName: "ticketfile",
                           params: {
                               //mode: 'upload_post_file',
                               _token : CSRF_TOKEN
                           },
                           removedfile: function(file) {
                              
                               var _ref;
                               return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                           },
                           maxThumbnailFilesize: 5,
                           previewTemplate: previewTemplate,
                           previewsContainer: "#previews",
                           autoQueue: true,
                           maxFiles: 50,
                           init: function() {
                               this.on('success', function(file, response) {

                                console.log(response.status);

                                       if (response.status == "success") {
                                           $(file.previewTemplate).append('<input type="hidden" name="server_file[]" class="server_file" value="' + response.uniq_code + '">');

                                       } else if (response.status == "error") {
                                           //$(file.previewTemplate).append('<div class="alert alert-danger">'+item.msg+'</div>');
                                           $(file.previewTemplate).html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + response.message + '</div>').fadeOut(3000);
                                       }
                               });
                               this.on("removedfile", function(file) {
                                   var server_file = $(file.previewTemplate).children('.server_file').val();
                                   //console.log(server_file);
                                   $.ajax({
                                       type: 'POST',
                                       url: SYS_URL+'/ticket/files/delete/'+server_file,
                                       data: { _token : CSRF_TOKEN },
                                       dataType: 'html',
                                   });
                               });
                               this.on("addedfile", function(file) {
                                   //console.log(file);
                               });
                               this.on('drop', function(file) {
                                   //alert('file');
                               });
                           }
                       });







  });
</script>



</body>
</html>