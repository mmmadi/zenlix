@if ($ticket->status == "free")
<span class="label label-primary"><i class="fa fa-clock-o"></i> {{trans('ticket.waitAction')}}</span>
@elseif($ticket->status == "lock")
<span class="label label-warning"><i class="fa fa-gavel"></i> {{trans('ticket.inWork')}}</span>
@elseif($ticket->status == "waitsuccess")
<span class="label label-danger"><i class="fa fa-archive"></i> {{trans('ticket.waitApprove')}}</span>
@elseif($ticket->status == "success")
<span class="label label-success"><i class="fa fa-check-circle"></i> {{trans('ticket.ticketSuccess')}}</span>
@elseif($ticket->status == "arch")
<span class="label label-default"><i class="fa fa-archive"></i> {{trans('ticket.ticketArchive')}}</span>
@endif