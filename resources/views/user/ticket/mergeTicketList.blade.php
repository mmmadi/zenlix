@foreach ($ticket->merged as $merge)

                    <li class="item">
                      <div class="product-img">
                        <i class="fa fa-ticket"></i>
                      </div>
                      <div class="product-info" style="margin-left: 20px;">
                        <a href="{{URL::to('/ticket/'.$merge->code)}}" class="product-title">#{{ $merge->code }} </a><span class="product-description pull-right">
{{-- 
@if ($ticket->author_id != $watchingUser->id)
 --}}


                        <button data-id="{{$merge->code}}" type="button" class="btn btn-sm bg-purple margin removeMerge">{{trans('ticket.remove')}}</button>


</span>
                        <span class="product-description">
                          #{{ $merge->subject }}
                        </span>
                      </div>
                    </li>
@endforeach