<div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">{{trans('message.navCat')}}</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li {!! Request::is('message/inbox') ? ' class="active"' : null !!}><a href="{{URL::to('/message/inbox')}}"><i class="fa fa-inbox"></i> {{trans('message.navIn')}}
                @if ($messagesCount != 0)<span class="label label-primary pull-right messagesCount">{{$messagesCount}}</span>@endif
                  </a></li>
                <li {!! Request::is('message/sent') ? ' class="active"' : null !!}><a href="{{URL::to('/message/sent')}}"><i class="fa fa-envelope-o"></i> {{trans('message.navOut')}} </a></li>
                <li {!! Request::is('message/draft') ? ' class="active"' : null !!}><a href="{{URL::to('/message/draft')}}"><i class="fa fa-file-text-o"></i> {{trans('message.navNotes')}}</a></li>
                <li {!! Request::is('message/trash') ? ' class="active"' : null !!}><a href="{{URL::to('/message/trash')}}"><i class="fa fa-trash-o"></i> {{trans('message.navTrash')}}</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>