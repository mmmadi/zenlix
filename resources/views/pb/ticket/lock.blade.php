{{trans('pb.lock', [], Null, $locale)}} {{$initUser}}

{{trans('pb.info', [], Null, $locale)}}
{{trans('pb.code', [], Null, $locale)}} {{$ticket['code']}}
{{trans('pb.author', [], Null, $locale)}} {{$author}}
{{trans('pb.clients', [], Null, $locale)}} {{$clients}}
{{trans('pb.target', [], Null, $locale)}} {{$targets}}
{{trans('pb.subj', [], Null, $locale)}} {{$ticket['subject']}}
{{trans('pb.msg', [], Null, $locale)}} {!! strip_tags($ticket['text']) !!}

{{trans('pb.link', [], Null, $locale)}} {{$appURL.'/ticket/'.$ticket['code'] }}
