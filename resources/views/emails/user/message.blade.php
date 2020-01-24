{{trans('mail.newPrivMsg', [], Null, $locale)}}

{{trans('mail.linkToMsg', [], Null, $locale)}} {{$appURL.'/message/'.$message_urlhash}}
=============================================================

{{trans('mail.From', [], Null, $locale)}} {{$author}}
{{trans('mail.subj', [], Null, $locale)}} {{strip_tags($subject)}}
{{trans('mail.msg', [], Null, $locale)}} {{strip_tags($text)}}

=============================================================
{{trans('mail.noReply', [], Null, $locale)}}