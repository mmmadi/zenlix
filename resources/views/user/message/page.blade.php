@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  
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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-envelope"></i> {{trans('message.title')}}
    <small>{{trans('message.creation')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('message.title')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">

<div class="col-md-3">
  

<a href="{{URL::to('/message')}}" class="btn btn-primary btn-block margin-bottom">{{trans('message.backToMsg')}}</a>

@include('user.message.nav')

{{-- <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Labels</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div> --}}

</div>
<div class="col-md-9">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->

        

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{trans('message.msg')}}</h3>

              <div class="box-tools pull-right">
                               <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm del_el" data-toggle="tooltip" data-container="body" title="{{trans('message.delete')}}">
                    <i class="fa fa-trash-o"></i></button>
                  <a href="{{URL::to('/message/'.$message->message_urlhash.'/reply')}}" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="{{trans('message.answer')}}">
                    <i class="fa fa-reply"></i></a>
      
                </div>

              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3>{{$message->subject}}</h3>
                <h5>От: <a href="{{URL::to('/user/'.$message->fromUser->profile->user_urlhash)}}"> {{ $message->fromUser->name }}</a>
                  <span class="mailbox-read-time pull-right">{{LocalizedCarbon::instance($message->created_at)->formatLocalized('%d %f %Y, %H:%M')}}</span></h5>
              </div>
              <!-- /.mailbox-read-info -->

              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
      {!! $message->text !!}
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->

@if ($message->files->count() != 0)

            <div class="box-footer">
              <ul class="mailbox-attachments clearfix">


              @foreach ($message->files as $file)
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa {!! Zen::fileIcon($file->mime); !!}"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{$file->name }}</a>
                        <span class="mailbox-attachment-size">
                          {!! round(File::size(storage_path('users/'.$file->user_id.'/'.$file->hash.'.'.$file->extension))/1024, 3) !!} KB
                          <a href="{{ URL::to('/files/download/'.$file->hash) }}" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>

                @endforeach
                
              </ul>
            </div>
@endif

            <!-- /.box-footer -->
            <div class="box-footer">
              <div class="pull-right">
                <a href="{{URL::to('/message/'.$message->message_urlhash.'/reply')}}" class="btn btn-default"><i class="fa fa-reply"></i> {{trans('message.answer')}}</a>

              </div>
              <button type="button" class="btn btn-default del_el"><i class="fa fa-trash-o"></i> {{trans('message.delete')}}</button>
          
            </div>
            <!-- /.box-footer -->
          </div>





</div>

                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/bootbox/bootbox.min.js'); !!}
<!-- page script -->
<script>
  $(function () {
    
$('body').on('click', '.del_el', function(event) {
            event.preventDefault();
bootbox.confirm('{{trans('message.confirmDelete')}}', function(result) {
                if (result == true) {

$.ajax({
                    type: "POST",
                    url: "{{URL::to('/message/'.$message->message_urlhash.'/delete') }}",
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'DELETE'
                    },
                    success: function(html) {

                      window.location = "{{URL::to('/message')}}";

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