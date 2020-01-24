         

<div id="chatUIofflineMSG" style="display: none;" class="alert alert-warning alert-dismissible">
               
               <i class="icon fa fa-warning"></i> 
                NodeJS server offline!
              </div>


@foreach ($onlineUsers as $onlineUser)

          <li>
            <a href="javascript::;" class="chatUserSelect" data-toUser="{{ $onlineUser->id }}">
             <img class="img-circle img-sm" src="{{Zen::showUserImgSmall($onlineUser->profile->user_img)}}" alt="user image">

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">{{$onlineUser->name}}
                
                <span id="chatUIuser_{{ $onlineUser->id }}" class="pull-right chatUINewMsg" style="color:#ff851b; @if (Zen::checkUnreadChat($onlineUser->id) == false) display:none; @endif"><i class="fa fa-commenting "></i></span>
                
                </h4>

                <p>{{$onlineUser->profile->position}}</p>
              </div>
            </a>
          </li>
@endforeach
