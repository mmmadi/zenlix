@if ($ticket->individual_ok == "true")
<div id="personal_ok" class="col-md-12 box box-danger">
<div class="box-header with-border">
                      <h3 class="box-title">{{trans('ticket.ticketIndividual')}}</h3>
                    </div>


<div class="box-body">
<div class="col-md-6">

@foreach ($ticket->targetUsers as $targetUser)
@if ($targetUser->pivot->individual_ok_status == "true")
  <p><i class="fa fa-check-circle-o text-green"></i>

  <a href="{{URL::to('/user/'.$targetUser->profile->user_urlhash)}}"> {{$targetUser->name}}</a> в {{LocalizedCarbon::instance($targetUser->pivot->updated_at)->formatLocalized('%H:%M, %d %f %Y')}}</p>
@elseif(($targetUser->pivot->individual_lock_status == "true"))
<p><i class="fa fa-legal text-orange"></i> 

<a href="{{URL::to('/user/'.$targetUser->profile->user_urlhash)}}">{{$targetUser->name}}</a> в {{LocalizedCarbon::instance($targetUser->pivot->updated_at)->formatLocalized('%H:%M, %d %f %Y')}}</p>
@else
  <p><i class="fa fa-circle-o text-red"></i> 

 <a href="{{URL::to('/user/'.$targetUser->profile->user_urlhash)}}"> {{$targetUser->name}}</a></p>
@endif

@endforeach


</div>
<div class="col-md-6">
  <div class="callout callout-default">
                <h4>{{trans('ticket.info')}}</h4>

                <p>{{trans('ticket.ticketIndividualInfo')}}</p>
              </div>
</div>
</div>
</div>
@endif