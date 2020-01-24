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
                        <i class="fa fa-tag"></i> {{trans('ticketEdit.editTicket')}} #{{$ticket->code}}
                        
                    </h1>
                    <ol class="breadcrumb">
                        @include("layout.breadcrumb")
                        <li class="active">{{trans('ticketEdit.editTicket')}} #{{$ticket->code}}</li>
                    </ol>
                </section>

    <!-- Main content -->
    <section class="content">

{!! Form::model($ticket, array('action' => ['TicketController@update', $ticket->code], 'method'=> 'POST', 'class'=>'form-horizontal')) !!}
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



@if (!empty(old('client'))) 

<?php

foreach (old('client') as $key => $value) {
  if (strpos($value,'[new]') !== false) { 
      $clients[$value]=$value;
   }
   else {
      $u=zenlix\User::findOrFail($value);
      $clients[$value]=$u->name;
   }

}

?>


@endif







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



@if ($TicketForm->client_field == 'self')
@elseif ($TicketForm->client_field == 'group')
<div class="form-group @if ($errors->has('client')) has-error @endif">
<label for='from' class="col-md-2 control-label">
{{trans('ticketEdit.editClient')}}
</label>
<div class="col-md-10">



{!! Form::select('client[]', $clients, $clientSel, array('class'=>'form-control input-sm js-data-client', 'style'=>'width: 100%', 'multiple'=>'multiple', 'data-placeholder'=>trans('ticketEdit.editSelClient'))) !!}
                 
@if ($errors->has('client')) <p class="help-block">{{ $errors->first('client') }}</p> @endif


                  </div></div>
                 
                  
<hr>
@endif


@if ($TicketForm->target_field == 'user_groups')
              <div class="form-group @if ($errors->has('targetGroup')) has-error @endif" >

<label for='unit' class="col-md-2 control-label" >
{{trans('ticketEdit.editTo')}}
</label>
<div class="col-md-5">
{!! Form::select('targetGroup', $targetGroup, $ticket->target_group_id, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketEdit.editSelGroup'))) !!}
                @if ($errors->has('targetGroup')) <p class="help-block">{{ $errors->first('targetGroup') }}</p> @endif
</div>
<div class="col-md-1 control-label"> {{trans('ticketEdit.or')}} </div>
<div class="col-md-4">

{!! Form::select('targetUsers[]', $targetUser, $targetUserSel, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketEdit.editSelUsers'), 'multiple'=>'multiple')) !!}
                 @if ($errors->has('targetUsers')) <p class="help-block">{{ $errors->first('targetUsers') }}</p> @endif
                
</div>
              </div>
@elseif ($TicketForm->target_field == 'users')
              <div class="form-group @if ($errors->has('targetUsers')) has-error @endif" >

<label for='unit' class="col-md-2 control-label" >
{{trans('ticketEdit.editTo')}}
</label>
<div class="col-md-10">
{!! Form::select('targetUsers[]', $targetUser, $targetUserSel, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketEdit.editSelUsers'), 'multiple'=>'multiple')) !!}
                 @if ($errors->has('targetUsers')) <p class="help-block">{{ $errors->first('targetUsers') }}</p> @endif
</div>

              </div>
@elseif ($TicketForm->target_field == 'group')
              <div class="form-group @if ($errors->has('targetGroup')) has-error @endif" >

<label for='unit' class="col-md-2 control-label" >
{{trans('ticketEdit.editTo')}}
</label>
<div class="col-md-10">
{!! Form::select('targetGroup', $targetGroup, $ticket->target_group_id, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketEdit.editSelGroup'))) !!}
                @if ($errors->has('targetGroup')) <p class="help-block">{{ $errors->first('targetGroup') }}</p> @endif
</div>

              </div>
@endif

@if ($TicketForm->prio == 'true')

{!! Form::hidden('prio', $ticket->prio, ['id'=>'prioVal']) !!}

<div class="control-group" id="for_prio">
    <div class="controls">
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">{{trans('ticketEdit.prio')}}</label>
            <div class="col-sm-10" style=" padding-top: 5px; ">

                <div class="btn-group btn-group-justified">
                    <div class="btn-group">
                        
                        @if ($ticket->prio == "low") 
                        <button type="button" class="btn prio bg-purple btn-xs active" data-value="low">
                        <i id="lprioclass" class="fa fa-check"></i> {{trans('ticketEdit.low')}}</button>
                        @else
                        <button type="button" class="btn prio bg-purple btn-xs active" data-value="low">
                        <i id="lprioclass" class=""></i> {{trans('ticketEdit.low')}}</button>
                        @endif
                        
                    </div>
                    <div class="btn-group">
                     @if ($ticket->prio == "normal") 
                        <button type="button" class="btn prio bg-orange btn-xs active" data-value="normal"><i id="lprioclass" class="fa fa-check"></i> {{trans('ticketEdit.norm')}}</button>
                     @else
                        <button type="button" class="btn prio bg-orange btn-xs" data-value="normal"><i id="lprioclass" class=""></i> {{trans('ticketEdit.norm')}}</button>
                     @endif
                    </div>
                    <div class="btn-group">
                    @if ($ticket->prio == "high") 
                    <button type="button" class="btn prio bg-maroon btn-xs active" data-value="high"><i id="lprioclass" class="fa fa-check"></i> {{trans('ticketEdit.high')}}</button>
                    @else
                        <button type="button" class="btn prio bg-maroon btn-xs" data-value="high"><i id="lprioclass" class=""></i> {{trans('ticketEdit.high')}}</button>
                    @endif
                    </div>
                </div>
            </div></div></div></div>
@endif




@if ($TicketForm->subj_field == 'list')
              <div class="form-group @if ($errors->has('subj')) has-error @endif">

<label for='unit' class="col-md-2 control-label">
{{trans('ticketEdit.subj')}}
</label>
<div class="col-md-5">
{!! Form::select('subj', $subj, $ticket->subject, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketEdit.selSubj'))) !!}
                   @if ($errors->has('subj')) <p class="help-block">{{ $errors->first('subj') }}</p> @endif
</div>
<div class="col-md-5">




{!! Form::select('tags[]', $tags, $tagsSel, array('class'=>'form-control input-sm select2-tags', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketEdit.tags'), 'multiple'=>'multiple')) !!}

                
</div>
              </div>

@elseif ($TicketForm->subj_field == 'text')
              <div class="form-group @if ($errors->has('subj')) has-error @endif">

<label for='unit' class="col-md-2 control-label">
{{trans('ticketEdit.subj')}}
</label>
<div class="col-md-5">
{!! Form::text('subj', $ticket->subject, array('class'=>'form-control input-sm', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketEdit.selSubj'))) !!}
                  @if ($errors->has('subj')) <p class="help-block">{{ $errors->first('subj') }}</p> @endif
</div>
<div class="col-md-5">
{!! Form::select('tags[]', $tags, $tagsSel, array('class'=>'form-control input-sm select2-tags', 'style'=>'width: 100%', 'data-placeholder'=>'Тэги', 'multiple'=>'multiple')) !!}

                
</div>
              </div>
@endif


              <div class="form-group @if ($errors->has('msg')) has-error @endif">

<label for='unit' class="col-md-2 control-label">
{{trans('ticketEdit.text')}}
</label>
<div class="col-md-10">

{!! Form::textarea('msg', $ticket->text, array('class'=>'form-control trumbowyg', 'id'=>'MsgBox')) !!}
        @if ($errors->has('msg')) <p class="help-block">{{ $errors->first('msg') }}</p> @endif         
</div>
              </div>

@if ($TicketForm->upload_files == 'true')
<label for='unit' class="col-md-2 control-label">
{{trans('ticketEdit.files')}}
</label>

@if ($ticket->files->count() > 0)

<div class="col-md-10" style="padding: 0px;">
<div class="attachment-block clearfix">
@foreach ($ticket->files as $file)
<div class="col-md-12 file-element">
                    <i class="attachment-img fa {!! Zen::fileIcon($file->mime); !!}" style="    padding: 3px;"></i>
                    <div class="attachment-pushed" style="margin-left: 22px;">
                      <div class="attachment-text">
                        {{$file->name }} <small> 
                        ({!! round(File::size(storage_path('users/'.$file->user_id.'/'.$file->hash.'.'.$file->extension))/1024/1024, 3) !!} {{trans('ticketEdit.Mb')}})</small>

                        <button data-hash="{{$file->hash}}" class="pull-right btn btn-default btn-xs delete_file">удалить</button>

                      </div><!-- /.attachment-text -->
                    </div><!-- /.attachment-pushed -->
</div>
@endforeach
</div>
</div>
<div class="col-md-2"></div>

@endif

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
                                <h4 class="text-muted">{{trans('ticketEdit.dropFile')}}</h4>
                                <p class="text-muted">
                                    {{trans('ticketEdit.dropOrClick')}}
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
<span>{{trans('ticketEdit.delete')}}</span>
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
                           <small>  {{trans('ticketEdit.addFields')}} </small>
                          </a>
                        </h4>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><i class="fa fa-plus"></i></button>
                  </div>
                      </div>
                      <div id="collapseTwo" class="panel-collapse collapse in
" aria-expanded="true" >
                        <div class="box-body">

@foreach ($ticket->fields as $field)

<?php ($field->required == 'true') ? $rText=' *' : $rText=' '; ?>

  @if ($field->f_type == 'text')
{{-- 
@if ($ticket->fields->count() > 0 )
@foreach ($ticket->fields as $datafield)

{{$datafield->pivot->field_data}}

@endforeach
@endif
 --}}



                    <div class="form-group @if ($errors->has('field'.$field->id)) has-error @endif">
                    {!! Form::label('field'.$field->id, $field->field_name.$rText, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::text('field'.$field->id, $field->value, array('class'=>'form-control', 'placeholder'=>$field->field_placeholder)) !!}
                    @if ($errors->has('field'.$field->id)) <p class="help-block">{{ $errors->first('field'.$field->id) }}</p> @endif
                    </div>
                    </div>


  @elseif ($field->f_type == 'textarea')

                    <div class="form-group @if ($errors->has('field'.$field->id)) has-error @endif">
                    {!! Form::label('field'.$field->id, $field->field_name.$rText, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::textarea('field'.$field->id, $field->value, array('class'=>'form-control', 'placeholder'=>$field->field_placeholder, 'rows'=>'2')) !!}
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

{{-- 
$ticket->FieldsData($field->id)->pivot->field_data
 --}}
                    <div class="form-group @if ($errors->has('field'.$field->id)) has-error @endif">
                    {!! Form::label('field'.$field->id, $field->field_name.$rText, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('field'.$field->id, $fieldVal, $field->value, array('class'=>'form-control select2', 'data-placeholder'=>$field->field_placeholder, 'style'=>'width:100%')) !!}
                    @if ($errors->has('field'.$field->id)) <p class="help-block">{{ $errors->first('field'.$field->id) }}</p> @endif
                    </div>
                    </div>




  @elseif ($field->f_type == 'multiselect')
<?php 
$fieldVal=[];
foreach (explode(',', $field->field_value) as $key => $value) {
  $fieldVal[$value]=$value;
} 
$fieldValSel=[];
foreach (explode(',', $field->value) as $key => $value) {
  $fieldValSel[$value]=$value;
} 


?>

                    <div class="form-group @if ($errors->has('field'.$field->id)) has-error @endif">
                    {!! Form::label('field'.$field->id, $field->field_name.$rText, array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('field'.$field->id.'[]', $fieldVal, $fieldValSel, array('class'=>'form-control select2', 'data-placeholder'=>$field->field_placeholder, 'style'=>'width:100%', 'multiple'=>'multiple')) !!}
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
                           <small> {{trans('ticketEdit.options')}} </small>
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
                    {!! Form::label('sla', trans('ticketEdit.slaPlan'), array('class'=>'col-sm-3 control-label')) !!}
                    <div class="col-sm-9">
                    {!! Form::select('sla', $slas, $ticket->sla_id, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketEdit.selSla'))) !!}
                    @if ($errors->has('sla')) <p class="help-block">{{ $errors->first('sla') }}</p> @endif
                    </div>
                    </div>


@endif



@if ($TicketForm->deadline_field == "true" )
<div class="form-group">

<label for="unit" class="col-md-3 control-label">
{{trans('ticketEdit.deadline')}}
</label>
<div class="col-sm-4">
                <label>

                {!! Form::checkbox('deadlineStatus', 'active', $deadlineStatus, array('class' => 'minimal')); !!}
                  {{trans('ticketEdit.Activate')}}
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
{!! Form::selectRange('deadlineDay', 1, 31, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-4">
{!! Form::selectMonth('deadlineMonth', null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>
<div class="col-sm-3">
{!! Form::selectYear('deadlineYear', date("Y"), date("Y")+1, null, array('class'=>'form-control select2 input-sm', 'style'=>'width:100%')); !!}
</div>




              </div>
@endif









@if ($TicketForm->watching_field == "true" )
<div class="form-group">

<label for='unit' class="col-md-3 control-label">
{{trans('ticketEdit.Watching')}}
</label>
<div class="col-md-9">

{!! Form::select('watchingUsers[]', $watchingUsers, $watchingUsersSel, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'data-placeholder'=>trans('ticketEdit.editSelUsers'), 'multiple'=>'multiple')) !!}
                
</div>
              </div>
@endif



@if ($TicketForm->individual_ok_field == "true" )
 <div class="form-group">
<div class="col-md-3"></div>
<div class="col-md-9">


         
            <label>
              {!! Form::checkbox('individual_ok', 'true', $individual_okStatus, ['class'=>'minimal']); !!} 
              
              {{trans('ticketEdit.OnceSuccess')}}
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
              {!! Form::checkbox('check_after', 'true', $check_afterStatus, ['class'=>'minimal']); !!} 
               {{trans('ticketEdit.ApproveAfterOk')}}
            </label>

</div>
              </div>
@endif



                        </div>
                      </div>
                    </div>
                    
                    
                  </div>
                  <br>
</div>


<div class="col-md-2">
</div>
<div class="col-md-10" id="processing">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">

            {!! Form::button(trans('ticketEdit.Save'), array('type' => 'submit', 'class'=>'btn btn-success btn-flat')); !!}

        </div>
        <div class="btn-group">
            <button href="{{URL::to('/ticket/create')}}" id="del_ticket" class="btn btn-danger" ><i class="fa fa-eraser"></i> {{trans('ticketEdit.DeleteTicket')}}</button>
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

  {!! Html::script('plugins/bootbox/bootbox.min.js'); !!}
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
        "<div class='select2-result-repository__avatar'><img src='" + repo.name + "' /></div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'>" + repo.name + "</div>";

      if (repo.id) {
        markup += "<div class='select2-result-repository__description'>" + repo.name + "</div>";
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



//delete_file
$('body').on('click', '.delete_file', function(event) {
            event.preventDefault();
            var curEl=$(this);
            var fileHash=$(this).attr('data-hash');

bootbox.confirm('Действительно желаете удалить файл?', function(result) {
          if (result == true) {

            $.ajax({
                                       type: 'POST',
                                       url: SYS_URL+'/ticket/files/delete/'+fileHash,
                                       data: { _token : CSRF_TOKEN },
                                       dataType: 'html',
                                       success: function(html) {
                                        curEl.closest('div.file-element').hide();
                                       }
                                   });


                



          }
          else {

          }
        });


          });

$('body').on('click', 'button#del_ticket', function(event) {
            event.preventDefault();
bootbox.confirm('Действительно желаете удалить заявку?', function(result) {
                if (result == true) {

$.ajax({
                    type: "POST",
                    url: "{{URL::to('/ticket/delete/'.$ticket->code) }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'DELETE'
                    },
                    success: function(html) {

                      window.location = "{{URL::to('/ticket/list')}}";

                    }
                  });


                }
                else if (result == false) {}
              });
          });


  });
</script>



</body>
</html>