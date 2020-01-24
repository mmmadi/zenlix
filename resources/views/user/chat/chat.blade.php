                  
@foreach ($chatMessages as $chatMessage)

@if ($CurUser->id == $chatMessage->fromUser->id)
                    <div class="direct-chat-msg">




                      <img data-toggle='tooltip' data-placement='top' title='Вы' class="img-circle img-sm" src="{{$userImgSmall}}" alt="user image"><!-- /.direct-chat-img -->
                      <div class="direct-chat-text" style="    padding: 5px 5px;    margin: 5px 0 0 38px;    line-height: 14px;
    font-size: 13px;">
                        {{$chatMessage->text}}
                      </div><!-- /.direct-chat-text -->
                    </div><!-- /.direct-chat-msg -->
@else




                    <!-- Message to the right -->
                    <div class="direct-chat-msg right">

                      <img data-toggle='tooltip' data-placement='top' title='{{$chatMessage->fromUser->name}}' class="img-circle img-sm pull-right" src="{{Zen::showUserImgSmall($chatMessage->fromUser->profile->user_img)}}" alt="user image"><!-- /.direct-chat-img -->
                      <div class="direct-chat-text" style="    padding: 5px 5px; margin-right: 38px;    line-height: 14px;
    font-size: 13px;">
                        {{$chatMessage->text}}
                      </div><!-- /.direct-chat-text -->
                    </div><!-- /.direct-chat-msg -->
@endif
@endforeach

