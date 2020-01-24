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

<div class="wrapper">

@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
                    <h1>
                        <i class="fa fa-ticket"></i> {{trans('ticketDeleted.ticket')}} <strong>#{{$ticket->code}}</strong>
                        <small>
                            {{$ticket->subject}}
                        </small>
                    </h1>
                    <ol class="breadcrumb">
                        @include("layout.breadcrumb")
                        <li class="active">{{trans('ticketDeleted.ticket')}} #{{$ticket->code}}</li>
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
                    <span class="description">{{trans('ticketDeleted.adrTo')}}
{{$ticket->targetGroup->name or Null}}
@if ($ticket->targetUsers()->count() > 0)
({!! implode(', ',$targetUsers) !!})
@endif
                    <a href="#" style="color: inherit;"></a>

                    <br>
                    @include("user.ticket.ticketPrio")
                    


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
                        ({!! round(File::size(storage_path('users/'.$file->user_id.'/'.$file->hash.'.'.$file->extension))/1024/1024, 3) !!} {{trans('ticketDeleted.Mb')}})</small>
                        <a href="{{ URL::to('/files/download/'.$file->hash) }}" class="pull-right btn btn-default btn-xs">{{trans('ticketDeleted.download')}}</a>
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

<h4>{{trans('ticketDeleted.add')}}</h4>
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








                  <span class="pull-right text-muted">@if($ticket->comments->count() > 0) {{$ticket->comments->count()}} {{trans('ticketDeleted.comments')}} @endif</span>
                </div><!-- /.box-body -->
                <div class="box-footer box-comments">
<div class="box-body">
                                   
<div class="btn-group btn-group-justified">
  <div class="btn-group">
    {!! Form::open(array('action' => ['TicketController@destroyRestore', $ticket->code], 'method'=> 'POST', 'class'=>'form-horizontal')) !!}
    <button type="submit" style="margin: 0px;" class="btn bg-purple btn-flat margin"><i class="fa fa-recycle"></i> {{trans('ticketDeleted.restore')}}</button>
    {!! Form::close(); !!}
  </div>

  <div class="btn-group">
    {!! Form::open(array('action' => ['TicketController@destroyApprove', $ticket->code], 'method'=> 'DELETE', 'class'=>'form-horizontal')) !!}
    <button type="submit" style="margin: 0px;" class="btn bg-maroon btn-flat margin"><i class="fa fa-close"></i> {{trans('ticketDeleted.deleteSuccess')}}</button>
    {!! Form::close(); !!}
  </div>

  </div>

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
                                    <li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-comments-o"></i> {{trans('ticketDeleted.comments2')}}</a></li>
                                    <li class=""><a href="#tab_2" data-toggle="tab" id="get_new_log">{{trans('ticketDeleted.logs')}}</a></li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="">
                                <div class="box-body chat" id="comment_content">
                                    
    


    
    
<div class="box-footer box-comments" style="background: #ffffff;" id="content_chat">




@if ($ticket->comments->count() == 0)

<div class="alert alert-info alert-dismissible" id="noComments">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> {{trans('ticketDeleted.noComments')}}</h4>
              </div>
@else

@include("user.ticket.singleComment")


@endif


                </div>






                                    <!-- chat item -->
                                    
                                </div><!-- /.chat -->
                                <div class="box-footer">
                                    <div class="row" id="for_msg">
                                        <div class="col-md-12">


                        </div>

</div>
        
                      
                                </div>
                            </div>
                                    </div><!-- /.tab-pane -->
                                    <div class="tab-pane" id="tab_2">
                                        

<div class="box box-solid">
<div class="box-body">






@include("user.ticket.ticketLogs")








                                  
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
                  <h3 class="widget-user-username">
                  <a href="{{URL::to('/users/'.$client->profile->user_urlhash)}}">{{$client->name}}</a></h3>
                  <h5 class="widget-user-desc">{{$client->profile->position}}</h5>
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                  @if ($client->profile->email)
                    <li><a>{{trans('ticketDeleted.email')}} <span class="pull-right">{{$client->profile->email}}</span></a></li> @endif
                  @if ($client->profile->skype)
                    <li><a>{{trans('ticketDeleted.skype')}} <span class="pull-right">{{$client->profile->skype}}</span></a></li>@endif
                  @if ($client->profile->telephone)
                    <li><a>{{trans('ticketDeleted.tel')}} <span class="pull-right">{{$client->profile->telephone}}</span></a></li>@endif
                  @if ($client->profile->address)
                    <li><a>{{trans('ticketDeleted.address')}} <span class="pull-right">{{$client->profile->address}}</span></a></li>@endif
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
                  <h3 class="widget-user-username">
                  <a href="{{URL::to('/users/'.$client->profile->user_urlhash)}}">{{$client->name}}</a></h3>
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
                      <h3 class="box-title">{{trans('ticketDeleted.ticketWatching')}}</h3>
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

<!-- bootstrap time picker -->
  {!! Html::script('plugins/dropzone/js/dropzone.js'); !!}
<!-- page script -->
<script>
  $(function () {
        $('.fancybox').fancybox({
    "type": "image"
    });

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