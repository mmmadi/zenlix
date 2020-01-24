@include("layout.header")

  <!-- iCheck -->
  {!! Html::style('plugins/iCheck/minimal/blue.css'); !!}
  {!! Html::style('plugins/fancybox/jquery.fancybox.css'); !!}
   {!! Html::style('plugins/dropzone/css/dropzone.css'); !!}


<style>
.info-box {
  display: block;
  min-height: 90px;
  background: #fff;
  width: 100%;
  box-shadow: 0 1px 1px rgba(0,0,0,0.1);
  border-radius: 2px;
  margin-bottom: 15px;
}
.info-box-icon {
  border-top-left-radius: 2px;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 2px;
  display: block;
  float: left;
  height: 90px;
  width: 90px;
  text-align: center;
  font-size: 45px;
  line-height: 90px;
  background: rgba(0,0,0,0.2);
}
.info-box-content {
  padding: 5px 10px;
  margin-left: 90px;
}
.info-box-text {
  text-transform: uppercase;
}
.progress-description, .info-box-text {
  display: block;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.info-box-number {
  display: block;
  font-weight: bold;
  font-size: 17px;
}
.info-box .progress, .info-box .progress .progress-bar {
  border-radius: 0;
}
.info-box .progress {
  background: rgba(0,0,0,0.2);
  margin: 5px -10px 5px -10px;
  height: 2px;
}
.progress-description {
  margin: 0;
}
.progress-description, .info-box-text {
  display: block;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.info-box .progress .progress-bar {
  background: #fff;
}
.info-box .progress, .info-box .progress .progress-bar {
  border-radius: 0;
}
</style>
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

<div class="wrapper">

@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
                    <h1>
                        <i class="fa fa-ticket"></i> {{trans('clientTL.ticket')}} <strong>#{{$ticket->code}}</strong>
                        <small>
                            {{$ticket->subject}}
                        </small>
                    </h1>
                    <ol class="breadcrumb">
                        @include("layout.breadcrumb")
                        <li class="active">{{trans('clientTL.ticket')}} #{{$ticket->code}}</li>
                    </ol>
                </section>

    <!-- Main content -->
<section class="content">
                    <!-- title row -->
                    
                    
                    
                                <div class="row">

            <div class="col-md-8">
            </div>
            </div>
                    
                    
                    
<div class="row">
<div class="col-md-8">
    <div class="row">





    <div class="col-md-12">


{{-- <div class="callout callout-success">
                                        <p><i class="fa fa-check-circle"></i> Заявка успешно выполнена пользователем <strong> <a href="view_user?7371a131b959f3527cbde59f0e5caf96">System Account</a></strong> 2015-10-05 17:25:11.<br> Через некоторое время заявка перейдет в архив.</p>
                                    </div> --}}


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <div class="alert alert-{{ $msg }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{!! Session::get('alert-' . $msg) !!} 
                        </div>
                        @endif
                        @endforeach
                    </div> 
                    <!-- end .flash-message -->



<div class="box box-widget">
                <div class="box-header with-border">

                  <div class="user-block">
                    <img class="img-circle" src="{{Zen::showUserImgSmall($ticket->authorUser->profile->user_img)}}" alt="user image">
                    <span class="username"><a href="{{URL::to('/user/'.$ticket->authorUser->profile->user_urlhash)}}">{{$ticket->authorUser->name }}</a></span>
                    <span class="description">{{trans('clientTL.referTo')}} 
{{$ticket->targetGroup->name or Null}}
@if ($ticket->targetUsers()->count() > 0)
({!! implode(', ',$targetUsers) !!})
@endif
                    <a href="#" style="color: inherit;"></a>

                    <br>
                    @include("user.ticket.ticketPrio")
                    @include("user.ticket.ticketStatus")


                    </span>

                  </div><!-- /.user-block -->
                  <div class="box-tools">
<small class="box-tools pull-right text-muted">
                                
                                <i class="fa fa-clock-o"></i>
                                {{LocalizedCarbon::instance($ticket->created_at)->formatLocalized('%d %f %Y, %H:%M')}}  </small>
                  </div><!-- /.box-tools -->

                </div><!-- /.box-header -->
                <div class="box-body">

<h3 style="    margin-top: 0px;">{{$ticket->subject}}</h3>
                  <!-- post text -->
                  <p>
                  {!! $ticket->text !!}
                  </p>
                  <!-- Attachment -->






@if ($ticket->files->count() > 0 )

  @if ($ticket->files()->Img('true')->count() > 0)

                  <div class="attachment-block clearfix">

@foreach ($ticket->files()->Img('true')->get() as $file)
<a href="{!! asset('/files/view/'.$file->hash) !!}" class="fancybox fancybox.iframe">
                    <img class="attachment-img" style="    margin: 0px 0px 0px 10px;" 
                    src="{!! asset('/files/view/small/'.$file->hash.'.'.$file->extension); !!}" alt="attachment image">
</a>
@endforeach
                    
</div><!-- /.attachment-block -->
  @endif



                  <!-- Attachment -->
                  @if ($ticket->files()->Img('false')->count() > 0)

                  <div class="attachment-block clearfix">

@foreach ($ticket->files()->Img('false')->get() as $file)
<div class="col-md-12">
                    <i class="attachment-img fa {!! Zen::fileIcon($file->mime); !!}" style="    padding: 3px;"></i>
                    <div class="attachment-pushed" style="margin-left: 22px;">
                      <div class="attachment-text">
                        {{$file->name }} <small> 
                        ({!! round(File::size(storage_path('users/'.$file->user_id.'/'.$file->hash.'.'.$file->extension))/1024/1024, 3) !!} {{trans('clientTL.Mb')}})</small>
                        <a href="{{ URL::to('/files/download/'.$file->hash) }}" class="pull-right btn btn-default btn-xs">{{trans('clientTL.download')}}</a>
                      </div><!-- /.attachment-text -->
                    </div><!-- /.attachment-pushed -->
                    </div>
@endforeach

                  </div><!-- /.attachment-block -->
                  @endif

                  <!-- Social sharing buttons -->
@endif
@if ($ticket->fields->count() > 0 )
<div class="col-md-12">

<h4>{{trans('clientTL.addInfo')}}</h4>
<table class="table table-bordered">
<tbody>

@foreach ($ticket->fields as $field)

  <tr>
<td style="width: 100px;">{{$field->field_name}}:</td>
<td>{{$field->pivot->field_data}}</td>
  </tr>
@endforeach
</tbody>
</table>
</div>
@endif

{{-- 
3. кто может редактировать/удалять заявку/восстанавливать? (MODIFY)
    - автор заявки
    - суперпользователь из отделов, где:
                      - заявка адресована на отдел
                      - заявка адресована конкретному пользователю



$AccessAction
$AccessModify

 --}}








                  <span class="pull-right text-muted"> @if($ticket->comments()->where('visible_client', 'true')->count() > 0) {{$ticket->comments()->where('visible_client', 'true')->count()}} {{trans('clientTL.comments')}} @endif</span>
                </div><!-- /.box-body -->
                <div class="box-footer box-comments">
<div class="box-body">
                                   


{{-- 


    - автор заявки
    - любой из отдела, если на весь отдел, но не конкретному
    - конкретный назначенный пользователь
    - суперпользователь из отделов, где:
                      - заявка адресована на отдел
                      - заявка адресована конкретному пользователю


 --}}

{{--  
@if (($ticket->targetUsers()->where('user_id', $user->id)->count() == 1) || 
( ($user->groups()->where('group_id', $ticket->target_group_id)->count() == 1) && 
($ticket->targetUsers->count() == 0) )
||
($ticket->authorUser->id == $user->id)
)


@if($ticket->individual_ok == "true")
@include("user.ticket.ticketActionButtonsIndividual")
@else
@include("user.ticket.ticketActionButtons")
@endif

@else
 --}}

                </div><!-- /.box-body -->





@include("user.ticket.ticketIndividual")






                </div><!-- /.box-footer -->

              </div>

                    
                    
                    
                    </div>
    </div>
    


    
    <div class="row">
    <div class="col-md-12">
    
<div id="msg"></div>
<div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-comments-o"></i> {{trans('clientTL.commentes')}}</a></li>
                                    
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="">
                                <div class="box-body chat" id="comment_content">
                                    
    


    
    
<div class="box-footer box-comments" style="background: #ffffff;" id="content_chat">




@if ($ticket->comments()->where('visible_client', 'true')->count() == 0)

<div class="alert alert-info alert-dismissible" id="noComments">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> {{trans('clientTL.noComments')}}</h4>
                {{trans('clientTL.youFirst')}}
              </div>
@else

@include("client.ticket.singleComment")


@endif


                </div>






                                    <!-- chat item -->
                                    
                                </div><!-- /.chat -->
                                <div class="box-footer">
                                    <div class="row" id="for_msg">
                                        <div class="col-md-12">
<form action="#" method="post">
                    <img class="img-responsive img-circle img-sm" src="{{$userImgSmall}}" alt="alt text">
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                      <textarea id="msg" name="msg" class="form-control" data-toggle="popover" data-html="true" data-trigger="manual" data-placement="top" data-content="<small>{{trans('clientTL.writeSometh')}}</small>" placeholder="{{trans('clientTL.commToTicket')}}" style="overflow: hidden; word-wrap: break-word; resize: vertical;"></textarea> 


                      <div class="text-muted well well-sm no-shadow dz-clickable" id="myid" style="margin-bottom: 0px;">

                          <div class="dz-message" data-dz-message="">
                            <center class="text-muted"><small>{{trans('clientTL.dropFiles')}}</small></center>
                          </div>

                          <style type="text/css">
                          .note-editor .note-dropzone { opacity: 0 !important; }
                          </style>

                          <form action="#" class="">
                            <input type="hidden" name="mode" value="upload_drop_file">
                          </form>

                        <div class="table table-striped" id="previews" style="margin-bottom: 0px;">
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
<span>Delete</span>
</button></td>
</tr>
</tbody></table>
                                                                            </div>
                        </div>
                        </div>







<div class="btn-group pull-right">
                                            <button value="9a5db2c1e9c442d9cd048a12cf9e426d" id="do_comment" class="btn btn-success btn-sm "><i class="fa fa-comment"></i> {{trans('clientTL.send')}}</button>
<input type="hidden" id="totalComments" value="{{$ticket->comments->count()}}">
                                          
                                            
  <!--input type="file" id="do_comment_file" value="9a5db2c1e9c442d9cd048a12cf9e426d" class="file-inputs" title="+"-->
                                            
                                            
                                            </div>





                    </div>
                  </form>

                        </div>

</div>
        
                      
                                </div>
                            </div>
                                    </div><!-- /.tab-pane -->
 
                                </div><!-- /.tab-content -->
                            </div>





    

    </div></div>
    
    
    
</div>
<div class="col-md-4">

<div class="row">


    @if ($ticket->clients->count() > 0)
    <div class="col-md-12">


@if ($ticket->clients->count() == 1)

@foreach ($ticket->clients as $client)
    <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                  <div class="widget-user-image">
                    <img class="img-circle" src="{{ Zen::showUserImg($client->profile->user_img) }}" alt="User Avatar">
                  </div><!-- /.widget-user-image -->
                  <h3 class="widget-user-username">{{$client->name}}</h3>
                  <h5 class="widget-user-desc">{{$client->profile->position}}</h5>
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                  @if ($client->profile->email)
                    <li><a>{{trans('clientTL.email')}} <span class="pull-right">{{$client->profile->email}}</span></a></li> @endif
                  @if ($client->profile->skype)
                    <li><a>{{trans('clientTL.skype')}} <span class="pull-right">{{$client->profile->skype}}</span></a></li>@endif
                  @if ($client->profile->telephone)
                    <li><a>{{trans('clientTL.tel')}} <span class="pull-right">{{$client->profile->telephone}}</span></a></li>@endif
                  @if ($client->profile->address)
                    <li><a>{{trans('clientTL.adr')}} <span class="pull-right">{{$client->profile->address}}</span></a></li>@endif
                  </ul>
                </div>
    </div>
@endforeach

@else

@foreach ($ticket->clients as $client)
<div class="box box-widget widget-user-2">
                <div class="widget-user-header bg-info">
                  <div class="widget-user-image">
                    <img class="img-circle" src="{{ Zen::showUserImg($client->profile->user_img) }}" alt="User Avatar">
                  </div><!-- /.widget-user-image -->
                  <h3 class="widget-user-username">{{$client->name}}</h3>
                  <h5 class="widget-user-desc">{{$client->profile->position}}</h5>
                </div>
                </div>
@endforeach

@endif
    </div>
    @endif

<div class="col-md-12">
<div class="box box-default">
                    <div class="box-header with-border">
                      <h3 class="box-title">{{trans('clientTL.watching')}}</h3>
                      <div class="box-tools pull-right">
                        
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">





                      <ul class="users-list clearfix" id="watching-panel">



@include("user.ticket.watchingListPanel")




                        
                      </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->


                  </div>
</div>


</div>





@if ($ticket->sla_id)
@include('user.ticket.slabox')
@endif



</div>

</div>







                                        
                    

                </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}

{!! Html::script('plugins/fancybox/jquery.fancybox.pack.js'); !!}

{!! Html::script('plugins/bootbox/bootbox.min.js'); !!}
{!! Html::script('plugins/moment/moment-with-locales.min.js'); !!}
{!! Html::script('plugins/moment/moment-duration-format.js'); !!}


<!-- bootstrap time picker -->
  {!! Html::script('plugins/dropzone/js/dropzone.js'); !!}
<!-- page script -->
<script>
  $(function () {
        $('.fancybox').fancybox({
    "type": "image"
    });

var MOMENTJS_DAY ='{{trans('clientTL.d')}}',
    MOMENTJS_HOUR ='{{trans('clientTL.h')}}',
    MOMENTJS_MINUTE='{{trans('clientTL.m')}}',
    MOMENTJS_SEC='{{trans('clientTL.s')}}';

function makemytime(s) {
        String.prototype.toHHMMSS = function() {
            var sec_num = parseInt(this, 10); // don't forget the second param
            var hours = Math.floor(sec_num / 3600);
            var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
            var seconds = sec_num - (hours * 3600) - (minutes * 60);
            if (hours < 10) {
                hours = "0" + hours;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            var time = hours + ':' + minutes + ':' + seconds;
            return time;
        }




        $('time#f').each(function(i, e) {
            var time = $(e).attr('datetime');
            
            var duration = moment.duration(time * 1000, 'milliseconds');
            $(e).html('<span>' + duration.format("d " + MOMENTJS_DAY + ", h " + MOMENTJS_HOUR + ", m " + MOMENTJS_MINUTE + ", s " + MOMENTJS_SEC + "") + '</span>');
            //time.from(now)
            //var time = moment($(e).attr('datetime'));
            //$(e).html('<span>' + moment.duration(2, "seconds").humanize() + '</span>');
        });

}
makemytime();
var intr = null;

        function gotimer_reaction() {
            //setInterval(function() {
            if ($('#reaction_timer').attr('data-status') == 'true') {
                var t = $('#reaction_timer > #f').attr('datetime');
                t++;
                $('#reaction_timer > #f').attr('datetime', t);
                var el = $('#reaction_timer > time#f').attr('datetime');
                var duration = moment.duration(el * 1000, 'milliseconds');
                $('#reaction_timer > time#f').html('<span>' + duration.format("d " + MOMENTJS_DAY + ", h " + MOMENTJS_HOUR + ", m " + MOMENTJS_MINUTE + ", s " + MOMENTJS_SEC + "") + '</span>');
            }
            //  }, 1000);
            //$('#work_timer').attr('value', 'false');
        }

        function gotimer_worker() {
            //setInterval(function() {
            if ($('#work_timer').attr('data-status') == 'true') {
                var t = $('#work_timer > #f').attr('datetime');
                t++;
                $('#work_timer > #f').attr('datetime', t);
                var el = $('#work_timer > time#f').attr('datetime');
                var duration = moment.duration(el * 1000, 'milliseconds');
                $('#work_timer > time#f').html('<span>' + duration.format("d " + MOMENTJS_DAY + ", h " + MOMENTJS_HOUR + ", m " + MOMENTJS_MINUTE + ", s " + MOMENTJS_SEC + "") + '</span>');
            }
            //  }, 1000);
            //$('#work_timer').attr('value', 'false');
        }

        function gotimer_deadline() {
            //setInterval(function() {
            if ($('#deadline_timer').attr('data-status') == 'true') {
                var t = $('#deadline_timer > #f').attr('datetime');
                t++;
                $('#deadline_timer > #f').attr('datetime', t);
                var el = $('#deadline_timer > time#f').attr('datetime');
                var duration = moment.duration(el * 1000, 'milliseconds');
                $('#deadline_timer > time#f').html('<span>' + duration.format("d " + MOMENTJS_DAY + ", h " + MOMENTJS_HOUR + ", m " + MOMENTJS_MINUTE + ", s " + MOMENTJS_SEC + "") + '</span>');
            }
            //  }, 1000);
            //$('#work_timer').attr('value', 'false');
        }



        if ($('#reaction_timer').attr('data-status') == 'true') {
            //gotimer_worker();
            intr = setInterval(gotimer_reaction, 1000);
            //clearInterval(intr);
        }
        if ($('#deadline_timer').attr('data-status') == 'true') {
            //gotimer_worker();
            intr = setInterval(gotimer_deadline, 1000);
            //clearInterval(intr);
        }
        if ($('#work_timer').attr('data-status') == 'true') {
            //gotimer_worker();
            intr = setInterval(gotimer_worker, 1000);
            //clearInterval(intr);
        }

/*
        var intr = null;
        //setInterval(plus_sec(), 1000);
        function gotimer_worker() {
            //setInterval(function() {
            if ($('#work_timer').attr('value') == 'true') {
                var t = $('#work_timer > #f').attr('datetime');
                t++;
                $('#work_timer > #f').attr('datetime', t);
                var el = $('#work_timer > time#f').attr('datetime');
                var duration = moment.duration(el * 1000, 'milliseconds');
                $('#work_timer > time#f').html('<span>' + duration.format("d " + MOMENTJS_DAY + ", h " + MOMENTJS_HOUR + ", m " + MOMENTJS_MINUTE + ", s " + MOMENTJS_SEC + "") + '</span>');
            }
            //  }, 1000);
            //$('#work_timer').attr('value', 'false');
        }

        function gotimer_deadline() {
            //setInterval(function() {
            if ($('#deadline_timer').attr('value') == 'true') {
                var t = $('#deadline_timer > #f').attr('datetime');
                t--;
                $('#deadline_timer > #f').attr('datetime', t);
                var el = $('#deadline_timer > time#f').attr('datetime');
                var duration = moment.duration(el * 1000, 'milliseconds');
                $('#deadline_timer > time#f').html('<span>' + duration.format("d " + MOMENTJS_DAY + ", h " + MOMENTJS_HOUR + ", m " + MOMENTJS_MINUTE + ", s " + MOMENTJS_SEC + "") + '</span>');
            }
            //  }, 1000);
            //$('#work_timer').attr('value', 'false');
        }
        if ($('#deadline_timer').attr('value') == 'true') {
            //gotimer_worker();
            intr = setInterval(gotimer_deadline, 1000);
            //clearInterval(intr);
        }
        if ($('#work_timer').attr('value') == 'true') {
            //gotimer_worker();
            intr = setInterval(gotimer_worker, 1000);
            //clearInterval(intr);
        }
        */


var ids = [];
//do_comment

$('textarea#msg').bind('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.keyCode == 13) {
                $("button#do_comment").click();
            }
        });

$('body').on('click', 'button#do_comment', function(event) {
            event.preventDefault();
            var msg = $("textarea#msg").val();
            var totalComments=$("input#totalComments").val();
            if (msg.replace(/ /g, '').length > 1) {
                $("textarea#msg").popover('hide');
                $("#for_msg").removeClass('has-error');

                $.ajax({
                    type: "POST",
                    url: "{{URL::to('/ticket/comment'.'/'.$ticket->code)}}",
                    dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'PATCH',
                      msg: msg,
                      visible_client: 'true',
                      totalComments: totalComments,
                      fileIDS: ids
                    },
                    success: function(html) {
                        //$("#content_chat").html(html);

                          if ($("#noComments").length) {
                            $("#noComments").hide();
                          }

                        $("textarea#msg").val('');
                       autosize.update($('textarea'));
$.each(html, function(i, item) {

//console.log(item.total);

  $('#content_chat').append(item.html);
  $("#totalComments").val(item.total);

});

$("#previews").html('');
ids = [];
                       
                    }
                });
              }
              else {
                $("textarea#msg").popover('show');
                $("#for_msg").addClass('has-error');
                setTimeout(function() {
                    $("textarea#msg").popover('hide');
                }, 2000);
              }

          });



        $('input').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue',
      increaseArea: '20%' // optional
    });





                       var previewNode = document.querySelector("#template");
                       previewNode.id = "";
                       var previewTemplate = previewNode.parentNode.innerHTML;
                       previewNode.parentNode.removeChild(previewNode);



                       $('#myid').dropzone({
                           url: SYS_URL+'/ticket/comment/{{$ticket->code}}/file',
                           paramName: "commentfile",
                           params: {
                               //mode: 'upload_post_file',
                               _token : CSRF_TOKEN,
                               _method: "PATCH"
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

                                //console.log(response.hash);

                                       if (response.status == "success") {
                                           $(file.previewTemplate).append('<input type="hidden" name="server_file[]" class="server_file" value="' + response.hash + '">');

                                           ids.push(response.hash);

                                       } else if (response.status == "error") {
                                           //$(file.previewTemplate).append('<div class="alert alert-danger">'+item.msg+'</div>');
                                           $(file.previewTemplate).html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + response.message + '</div>').fadeOut(3000);
                                       }
                               });
                               this.on("removedfile", function(file) {
                                   var server_file = $(file.previewTemplate).children('.server_file').val();
                                   ids = jQuery.grep(ids, function(value) {
                                           return value != server_file;
                                    });

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
function initWatching() {
$(".js-data-watching-client").select2({
  allowClear: true,
  ajax: {
    url: "{{ URL::to('/ticket/watching') }}",
    dataType: 'json',
    allowClear: true,
    placeholder: "Select an attribute",
    delay: 250,
    data: function (params) {
      return {
        q: params.term,
        ticketCode: '{{$ticket->code}}'
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
});
}
initWatching();

$(".js-data-watching-client").on("change", function (e) {


console.log($(this).val());

var ClientList=$(this).val();

$.ajax({
                    type: "POST",
                    url: "{{URL::to('/ticket/'.$ticket->code.'/watching') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'PATCH',
                      USERID: ClientList
                      //TICKETCODE: '{{$ticket->code}}'
                    },
                    success: function(html) {

                      //console.log('ok');

                        $(".js-data-watching-client").val(null).select2("destroy");
                        initWatching();

                        $.ajax({
                              type: "GET",
                              url: "{{URL::to('/ticket/watching/view') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN,
                                //_method: 'PATCH',
                                TICKETCODE: '{{$ticket->code}}'
                              },
                              success: function(html) {
                                    $("#watching-data").hide().html(html).fadeIn(500);
                              }
                        });

}
});


});

$('#myModal').on('hidden.bs.modal', function (e) {
  // do something...

                        $.ajax({
                              type: "GET",
                              url: "{{URL::to('/ticket/watching/view/panel') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN,
                                //_method: 'PATCH',
                                TICKETCODE: '{{$ticket->code}}'
                              },
                              success: function(html) {
                                    $("#watching-panel").hide().html(html).fadeIn(500);
                              }
                        });


});


//action_refer_to
$('body').on('click', 'button#action_refer_to', function(event) {
            event.preventDefault();

            var dataStatus=$(this).attr('data-status');

            if (dataStatus == "false") {
              //ajax refer_to

                        $.ajax({
                              type: "GET",
                              url: "{{URL::to('/ticket/refer') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN,
                                //_method: 'PATCH',
                                TICKETCODE: '{{$ticket->code}}'
                              },
                              success: function(html) {
                                    $("#refer_to").hide().html(html).fadeIn(500);
                                    $(".select2").select2({
                                          allowClear: true,
                                      });
                              }
                        });


              $(this).attr('data-status', 'true').addClass('active');
            }
            else {
              //hide
              $("#refer_to").html(null);
              $(this).attr('data-status', 'false').removeClass('active');
            }

          });

$('body').on('click', '.removeWatching', function(event) {
            event.preventDefault();

            var ClientList=$(this).attr('data-id');
            $.ajax({
                    type: "POST",
                    url: "{{URL::to('/ticket/'.$ticket->code.'/watching') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'DELETE',
                      USERID: ClientList
                      //TICKETCODE: '{{$ticket->code}}'
                    },
                    success: function(html) {
                                                $.ajax({
                              type: "GET",
                              url: "{{URL::to('/ticket/watching/view') }}",
                              //dataType: "json",
                              data: {
                                _token : CSRF_TOKEN,
                                //_method: 'PATCH',
                                TICKETCODE: '{{$ticket->code}}'
                              },
                              success: function(html) {
                                    $("#watching-data").hide().html(html).fadeIn(500);
                              }
                        });
                    }
                  });

          });

/*$('body').on('click', 'button#del_ticket', function(event) {
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
          });*/


  });
</script>
</body>
</html>