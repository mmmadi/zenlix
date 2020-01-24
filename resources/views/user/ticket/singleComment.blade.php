                 @foreach ($comments as $comment)
                  <div class="box-comment">
                    <!-- User image -->
                    <img class="img-circle img-sm" src="{{Zen::showUserImgSmall($comment->author->profile->user_img)}}" alt="user image">
                    <div class="comment-text">
                      <span class="username">
                        <a href="{{URL::to('/user/'.$comment->author->profile->user_urlhash)}}">
                        {{$comment->author->name}}
                        </a>
                        <span class="text-muted pull-right">
                      @if ($comment->visible_client == "true") 
                        <i class="fa fa-eye"></i> - 
                        @else
                        <i class="fa fa-eye-slash"></i> - 
                        @endif
                        {{LocalizedCarbon::instance($comment->created_at)->formatLocalized('%H:%M, %d %f %Y')}}</span>
                      </span><!-- /.username -->
                      {!! clean($comment->text) !!}




 @if ($comment->files()->Img('true')->count() > 0)

                  <div class="attachment-block clearfix">

@foreach ($comment->files()->Img('true')->get() as $file)
<a href="{!! asset('/files/view/'.$file->hash) !!}" class="fancybox fancybox.iframe">
                    <img class="attachment-img" style="    margin: 0px 0px 0px 10px; " 
                    src="{!! asset('/files/view/small/'.$file->hash.'.'.$file->extension); !!}" alt="attachment image">
</a>
@endforeach
                    
</div><!-- /.attachment-block -->
  @endif



                  <!-- Attachment -->
  @if ($comment->files()->Img('false')->count() > 0)

                  <div class="attachment-block clearfix">

@foreach ($comment->files()->Img('false')->get() as $file)
<div class="col-md-12">
                    <i class="attachment-img fa {!! Zen::fileIcon($file->mime); !!}" style="    padding: 3px;"></i>
                    <div class="attachment-pushed" style="margin-left: 22px;">
                      <div class="attachment-text">
                        {{$file->name }} <small> 
                        ({!! round(File::size(storage_path('users/'.$file->user_id.'/'.$file->hash.'.'.$file->extension))/1024/1024, 3) !!} Mb)</small>
                        <a href="{{URL::to('/files/download/'.$file->hash) }}" class="pull-right btn btn-default btn-xs">скачать</a>
                      </div><!-- /.attachment-text -->
                    </div><!-- /.attachment-pushed -->
                    </div>
@endforeach

                  </div><!-- /.attachment-block -->
                  @endif

                  <!-- Social sharing buttons -->






                    </div><!-- /.comment-text -->
                  </div><!-- /.box-comment -->
                  @endforeach