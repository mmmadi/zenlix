                        @foreach ($ticket->watchingUsers as $watchingUser)


                        <li>
                          <img src="{{ Zen::showUserImgSmall($watchingUser->profile->user_img) }}" alt="User Image">
                          <a class="users-list-name" href="{{URL::to('/user/'.$watchingUser->profile->user_urlhash)}}">{{$watchingUser->name}}</a>
                  
                        </li>

                        @endforeach