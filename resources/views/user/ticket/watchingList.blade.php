@foreach ($ticket->watchingUsers as $watchingUser)

                    <li class="item">
                      <div class="product-img">
                        <img src="{{ Zen::showUserImg($watchingUser->profile->user_img) }}" alt="Product Image">
                      </div>
                      <div class="product-info">
                        <a href="{{URL::to('/user/'.$watchingUser->profile->user_urlhash)}}" class="product-title">{{$watchingUser->name}} </a><span class="product-description pull-right">
{{-- 
@if ($ticket->author_id != $watchingUser->id)
 --}}

@if (($ticket->targetUsers->contains($watchingUser->id) == false) && ($ticket->author_id != $watchingUser->id))

                        <button data-id="{{$watchingUser->id}}" type="button" class="btn btn-sm bg-purple margin removeWatching">Убрать</button>

@endif
</span>
                        <span class="product-description">
                          {{$watchingUser->profile->position}}
                        </span>
                      </div>
                    </li>
@endforeach