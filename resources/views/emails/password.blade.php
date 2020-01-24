<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>{{trans('mail.passwordReset')}}</h2>

    <div>
      {{trans('mail.passwordResetInfo')}}
      {{ URL::to('password/reset', array($token)) }}.
    </div>
  </body>
</html>