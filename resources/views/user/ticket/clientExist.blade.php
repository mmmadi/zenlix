    <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                  <div class="widget-user-image">
                    <img class="img-circle" src="{{ Zen::showUserImg($client->profile->user_img) }}" alt="User Avatar">
                  </div><!-- /.widget-user-image -->
                  <h3 class="widget-user-username">
                  <a href="{{URL::to('/user/'.$client->profile->user_urlhash)}}">
                  	{{$client->name}}
                  	</a>
                  	</h3>
                  <h5 class="widget-user-desc">
                  @if ($client->profile->position)
                  {{$client->profile->position}}
                  @else
                  -
                  @endif
                  </h5>
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                  @if ($client->profile->email)
                    <li><a>{{trans('clientsPart.email')}} <span class="pull-right">{{$client->profile->email}}</span></a></li> @endif
                  @if ($client->profile->skype)
                    <li><a>{{trans('clientsPart.skype')}} <span class="pull-right">{{$client->profile->skype}}</span></a></li>@endif
                  @if ($client->profile->telephone)
                    <li><a>{{trans('clientsPart.tel')}} <span class="pull-right">{{$client->profile->telephone}}</span></a></li>@endif
                  @if ($client->profile->address)
                    <li><a>{{trans('clientsPart.address')}} <span class="pull-right">{{$client->profile->address}}</span></a></li>@endif
                  </ul>
                </div>
    </div>