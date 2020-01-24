{{trans('mail.congRegister', [], Null, $locale)}}

{{trans('mail.registerLink', [], Null, $locale)}} {{$appURL}}
=============================================================

{{trans('mail.infoLogin', [], Null, $locale)}}

{{trans('mail.yourLogin', [], Null, $locale)}} {{$user['email']}}
@if ($pass) {{trans('mail.yourPass', [], Null, $locale)}} {{$pass}} @else {{trans('mail.yourPassOld', [], Null, $locale)}} @endif


=============================================================
{{trans('mail.noReply', [], Null, $locale)}}