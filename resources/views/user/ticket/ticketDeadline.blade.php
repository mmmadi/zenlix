@if ((!$ticket->sla_id) && ($ticket->deadline_time) )

<span class="label label-primary bg-maroon"><i class="fa fa-clock-o"></i> 

{{trans('ticket.deadlineTo')}} {{LocalizedCarbon::instance($ticket->deadline_time)->formatLocalized('%d %f %Y, %H:%M')}}

</span>
    
@endif