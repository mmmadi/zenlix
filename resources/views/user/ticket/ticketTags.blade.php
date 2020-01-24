@if (count($ticketTags) > 0)
<br>
<p>
<small>{{trans('ticket.tags')}}: </small>
@foreach ($ticketTags as $ticketTag)
<small class="label bg-gray" style="font-weight: 500;">{{$ticketTag}}</small> 
@endforeach
</p>
@endif