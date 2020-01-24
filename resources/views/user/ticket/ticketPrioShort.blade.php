    @if($ticket->prio == "low")
<small><span class="label label-primary"><i class="fa fa-arrow-down"></i></span></small>
    @elseif($ticket->prio == "normal")
<small><span class="label label-info"><i class="fa fa-minus"></i> </span></small>
    @elseif($ticket->prio == "high")
<small><span class="label label-danger"><i class="fa fa-arrow-up"></i> </span></small>
    @endif
