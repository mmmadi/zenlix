    @if($ticket->prio == "low")
<span class="label label-primary"><i class="fa fa-arrow-down"></i> {{trans('ticket.prioLow')}}</span>
    @elseif($ticket->prio == "normal")
<span class="label label-info"><i class="fa fa-minus"></i> {{trans('ticket.prioNormal')}}</span>
    @elseif($ticket->prio == "high")
<span class="label label-danger"><i class="fa fa-arrow-up"></i> {{trans('ticket.prioHigh')}}</span>
    @endif
