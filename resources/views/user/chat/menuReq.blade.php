@if ($CurUser->roles->role != 'client')


@if ($chatResponces->count() != 0)

            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-headphones "></i>
              <span class="label label-success">{{$chatResponces->count()}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">{{trans('chat.have')}} <strong>{{$chatResponces->count()}}</strong> {{trans('chat.req')}}</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">


@foreach ($chatResponces as $chatResponce)

                  <li><!-- start message -->
                    <a href="#" class="chatUserSelectRequest" data-touser="{{$chatResponce->id}}">
                      <div class="pull-left">
                        <img src="{{Zen::showUserImgSmall($chatResponce->profile->user_img)}}" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        {{$chatResponce->name}}
                        <small><i class="fa fa-clock-o"></i> {{LocalizedCarbon::instance($chatResponce->chatRequest->created_at)->diffForHumans()}}</small>
                      </h4>
                      <p></p>
                    </a>
                  </li>
                  

@endforeach


                </ul>
              </li>
              
            </ul>
         
@endif
@endif