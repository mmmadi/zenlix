{{trans('mail.descComment', [], Null, $locale)}}
------------------------------------------------------------


{{trans('mail.authorComment', [], Null, $locale)}} {{$initUser}}
{{trans('mail.msgComment', [], Null, $locale)}} {!! strip_tags($initComment) !!}


{{trans('mail.link', [], Null, $locale)}} {{$appURL.'/ticket/'.$ticket['code']}}
=============================================================

{{trans('mail.ticketInfo', [], Null, $locale)}}

{{trans('mail.code', [], Null, $locale)}} {{$ticket['code']}}

{{trans('mail.authorTicket', [], Null, $locale)}} {{$author}}
{{trans('mail.Clients', [], Null, $locale)}} {{$clients}}
{{trans('mail.Target', [], Null, $locale)}} {{$targets}}

{{trans('mail.Subj', [], Null, $locale)}} {{$ticket['subject']}}
{{trans('mail.MsgTicket', [], Null, $locale)}} {!! strip_tags($ticket['text']) !!}

@if ($canReply) 
-------------------------------------------------------------
{{trans('mail.canReply', [], Null, $locale)}}
@endif