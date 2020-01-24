@include("layout.header")

<!-- Content Wrapper. Contains page content -->
  <div class="">
    <!-- Content Header (Page header) -->
<section class="">
                    

                </section>

    <!-- Main content -->
<section class="content">
                    <!-- title row -->
                    
                    

                    
                    
                    
<div class="row">
<div class="col-md-8 col-md-offset-2">
    <div class="row">


<div class="col-md-12">
<h2>
                        <i class="fa fa-ticket"></i> {{trans('ticketPrint.ticket')}} <strong>#{{$ticket->code}}</strong>
                        <small>
                            {{$ticket->subject}}
                        </small>
                    </h2>
</div>


    <div class="col-md-12">




<div class="box box-widget">
                <div class="box-header with-border">

                  <div class="user-block">
                    <img class="img-circle" src="{{Zen::showUserImgSmall($ticket->authorUser->profile->user_img)}}" alt="user image">
                    <span class="username"><a href="{{URL::to('/user/'.$ticket->authorUser->profile->user_urlhash)}}">{{$ticket->authorUser->name }}</a></span>
                    <span class="description">{{trans('ticketPrint.adrTo')}} 
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

                    <i class="attachment-img fa {!! Zen::fileIcon($file->mime); !!}" style="    padding: 3px;"></i>
                    <div class="attachment-pushed" style="margin-left: 22px;">
                      <div class="attachment-text">
                        {{$file->name }} <small> 
                        ({!! round(File::size(storage_path('users/'.$file->user_id.'/'.$file->hash.'.'.$file->extension))/1024/1024, 3) !!} {{trans('ticketPrint.Mb')}})</small>
                        
                      </div><!-- /.attachment-text -->
                    </div><!-- /.attachment-pushed -->
@endforeach

                  </div><!-- /.attachment-block -->
                  @endif

                  <!-- Social sharing buttons -->
@endif
@if ($ticket->fields->count() > 0 )
<div class="col-md-12">

<h4>{{trans('ticketPrint.add')}}</h4>
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





                  <span class="pull-right text-muted"> @if($ticket->comments->count() > 0) {{$ticket->comments->count()}} {{trans('ticketPrint.comments')}} @endif</span>
                </div><!-- /.box-body -->
              </div>

    


   
<div class="col-md-8 col-md-offset-2">

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
                    <li><a>{{trans('ticketPrint.email')}} <span class="pull-right">{{$client->profile->email}}</span></a></li> @endif
                  @if ($client->profile->skype)
                    <li><a>{{trans('ticketPrint.skype')}} <span class="pull-right">{{$client->profile->skype}}</span></a></li>@endif
                  @if ($client->profile->telephone)
                    <li><a>{{trans('ticketPrint.tel')}} <span class="pull-right">{{$client->profile->telephone}}</span></a></li>@endif
                  @if ($client->profile->address)
                    <li><a>{{trans('ticketPrint.address')}} <span class="pull-right">{{$client->profile->address}}</span></a></li>@endif
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




</div>








</div>

</div>


</div>
</div>
</div>


<!-- Modal -->




                                        
                    

                </section>
    <!-- /.content -->
  </div>
<script type="text/javascript">
  

var CSRF_TOKEN='{!! csrf_token() !!}';
var SYS_URL='{!! URL::to('/'); !!}';

</script>
<!-- jQuery 2.1.4 -->
{!! Html::script('plugins/jQuery/jQuery-2.1.4.min.js'); !!}
<!-- jQuery UI 1.11.4 -->
<!-- jQuery 2.1.4 -->
{!! Html::script('plugins/jQuery/jQuery-2.1.4.min.js'); !!}
{!! Html::script('dist/js/jquery-ui.min.js'); !!}
<!-- Bootstrap 3.3.5 -->
{!! Html::script('bootstrap/js/bootstrap.min.js'); !!}
<!-- Select2 -->
{!! Html::script('plugins/select2/select2.full.min.js'); !!}

<!-- AdminLTE App -->
{!! Html::script('dist/js/app.min.js'); !!}

<!-- trumbowyg -->
{!! Html::script('plugins/trumbowyg/trumbowyg.min.js'); !!}

<!-- DataTables -->
{!! Html::script('plugins/datatables/media/js/jquery.dataTables.min.js'); !!}

{!! Html::script('plugins/datatables/media/js/dataTables.bootstrap.min.js'); !!}

{!! Html::script('plugins/autosize/autosize.min.js'); !!}
