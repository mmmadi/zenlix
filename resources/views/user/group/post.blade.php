@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  
{!! Html::style('plugins/iCheck/minimal/purple.css'); !!}

<style type="text/css">
  

.users-list>li {
    width: 20%;
  }

</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('group.pageOfGroup')}}
    <small>{{trans('group.group')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('group.groups')}}</li>
        <li>{{$group->name}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-9">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->

  <div class="box box-widget widget-user" >
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header" style="    height: 70px;     padding: 10px;
                background-color: #F59B4D;
                ">
                  <h3 class="widget-user-username" style="
    color: ;
">
                  <i class="fa {!! $group->icon; !!}"></i> 
                  {!! $group->name !!}</h3>
                  <h5 class="widget-user-desc" style="
    color: ;
">{!! $group->description !!}</h5>
                </div>

              </div>



@if (!empty($group->description_full))
<div class="box box-solid">
                <div class="box-body">
<h4>{{trans('group.groupDesc')}}</h4>
<p>
{!! clean(nl2br($group->description_full)) !!}
</p>
                </div>
                </div>
@endif










<div class="box box-widget feed">
<div class="box-header with-border">
                  <div class="user-block">
                    <img class="img-circle" src="{{Zen::showUserImgSmall($feed->author->profile->user_img)}}" alt="user image">
                    <span class="username"><a href="{{URL::to('/user/'.$feed->author->profile->user_urlhash)}}">{{$feed->author->name}}</a></span>
                    <span class="description">
                     
                    {{trans('group.wroteMsg')}}
                    <span data-toggle="tooltip" data-placement="right" title="
                    {{LocalizedCarbon::instance($feed->created_at)->formatLocalized('%H:%M, %d %f %Y')}}
                    ">{{ LocalizedCarbon::instance($feed->created_at)->diffForHumans() }}</span>
                    </span>
                  </div><!-- /.user-block -->
                  <div class="box-tools">
                    <a href="{{URL::to('/group/'.$group->group_urlhash.'/post/'.$feed->feed_urlhash)}}" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="{{trans('group.linkToPost')}}"><i class="fa fa-chain"></i></a>
                    {{-- <a href="{{URL::to('/group/'.$group->group_urlhash.'/post/'.$feed->feed_urlhash.'/edit')}}" class="btn btn-box-tool"><i class="fa fa-pencil-square-o"></i></a> --}}
                    @if (($CurUser->id == $feed->author->id) || ($CurUser->roles->role == 'admin') || ($CurUser->GroupAdminSet($group->id)->count() != 0))
                    <a href="#"  data-id="{{$feed->feed_urlhash}}" class="btn btn-box-tool del_el"><i class="fa fa-times"></i></a>
                    @endif
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                                <div class="box-body">
                  <!-- post text -->
                  <p>{{$feed->text}}</p>
                  <!-- Social sharing buttons -->

                  





                  <small class="pull-right text-muted">
{{$feed->comments()->count()}} {{trans('group.comments')}}
                      


                      </small>
                     


                </div><!-- /.box-body -->


@if ($feed->comments()->count() > 0)
                <div class="box-footer box-comments">



@foreach ($feed->comments as $comment)
                  <div class="box-comment" style="    font-size: 12px; padding: 5px 0;">
                    <!-- User image -->
                    <a href="{{ URL::to('/user/'.$comment->author->profile->user_urlhash) }}"><img class="img-circle img-sm" src="{{$userImgSmall}}" alt="user image"></a>
                    <div class="comment-text">
                      <span class="username">
                        <a href="{{ URL::to('/user/'.$comment->author->profile->user_urlhash) }}">{!! $comment->author->name !!}</a>
                        <span class="text-muted pull-right">
                        <span data-toggle="tooltip"  data-placement="right" title="
                    {{ LocalizedCarbon::parse($comment->created_at)->format('d M Y H:i:s') }}
                    ">{{ LocalizedCarbon::instance($comment->created_at)->diffForHumans() }}</span>
                    </span>
                      </span><!-- /.username -->
                      {!! $comment->text !!}
                    </div><!-- /.comment-text -->
                  </div><!-- /.box-comment -->

@endforeach
                </div><!-- /.box-footer -->
                @endif

@if ($feed->comments_flag == 'true')
<div class="box-footer">
{!! Form::open(array('action' => array('GroupsController@storeComment', $feed->feed_urlhash), 'method'=> 'POST', 'class'=>'form-horizontal')) !!}
                 <div class="@if ($errors->has('text'.$feed->feed_urlhash)) has-error @endif">

                                     {!! Form::text('text'.$feed->feed_urlhash, null, array('class'=>'form-control input-sm', 'placeholder'=>'Press enter to post comment')) !!}
                                     @if ($errors->has('text'.$feed->feed_urlhash)) <p class="help-block">{{ $errors->first('text'.$feed->feed_urlhash) }}</p> @endif

                                     </div>
                                     {!! Form::close(); !!}
                                     </div>
@endif




</div>












                </div>


<div class="col-md-3">

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{trans('group.aboutGroup')}}</h3>
              <div class="box-tools">
                    <a href="{!! URL::to('/group/edit/'.$group->group_urlhash); !!}" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-cog"></i></a>
                  </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
@if (!empty($group->slogan))
              <strong><i class="fa fa-file-text-o margin-r-5"></i> {{trans('group.slogan')}}</strong>
              <p>{{$group->slogan}}</p>
              <hr>
@endif


            @if (!empty($group->address))
                          <strong><i class="fa fa-map-marker margin-r-5"></i> {{trans('group.address')}}</strong>
              <p class="text-muted">{{$group->address}}</p>
<hr>
@endif

@if (count($group->tags))
              <strong><i class="fa fa-pencil margin-r-5"></i> {{trans('group.tags')}}</strong>

              <p>
              @foreach (explode(',',$group->tags) as $t)
                <span class="label label-primary">{{$t}}</span>
              @endforeach
              </p>

              <hr>
@endif

 @if (!empty($group->twitter)) <strong><i class="fa fa-twitter"></i></strong> {{$group->twitter}}<br>@endif
 @if (!empty($group->facebook)) <strong><i class="fa fa-facebook-square"></i></strong> {{$group->facebook}}<br>@endif




            </div>
            <!-- /.box-body -->
          </div>


<div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title">{{trans('group.adminGroups')}}</h3>

                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                      <ul class="users-list clearfix">

@foreach($group->users->where('pivot.priviliges', 'admin') as $user)


                        <li style="width: 33%;">
                          <img src="{{ Zen::showUserImg($user->profile->user_img) }}" alt="User Image">
                          <a class="users-list-name" href="{!! URL::to('/user/'.$user->profile->user_urlhash); !!}">{{ $user->name }}</a>
                          <span class="users-list-date" style="overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;">{{ LocalizedCarbon::instance($user->created_at)->diffForHumans() }}</span>
                        </li>

@endforeach

                      </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->

                  </div>

<div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">{{trans('group.user')}}</h3>

                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                      <ul class="users-list clearfix">

@foreach($group->users()->take(6)->get() as $user)


                        <li style="width: 33%;">
                          <img src="{{ Zen::showUserImg($user->profile->user_img) }}" alt="User Image">
                          <a class="users-list-name" href="{!! URL::to('/user/'.$user->profile->user_urlhash); !!}">{{ $user->name }}</a>
                          <span class="users-list-date" style="overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;">{{ LocalizedCarbon::instance($user->created_at)->diffForHumans() }}</span>
                        </li>

@endforeach

                      </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->
@if($group->users()->count() > 6 )
<div class="box-footer">
{{trans('group.more')}} {{$group->users()->count() - 6 }} {{trans('group.users')}}
</div>
@endif
</div>

                  </div>


</div>


            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
{!! Html::script('plugins/bootbox/bootbox.min.js'); !!}
<!-- page script -->
<script>
  $(function () {


@if (($CurUser->id == $feed->author->id) || ($CurUser->roles->role == 'admin') || ($CurUser->GroupAdminSet($group->id)->count() != 0))
$('body').on('click', '.del_el', function(event) {
            event.preventDefault();

            var elID=$(this).attr('data-id');

bootbox.confirm('{{trans('group.confirmDeletePost')}}', function(result) {
                if (result == true) {
            
            $.ajax({
                    type: "POST",
                    url: "{{URL::to('group/'.$group->group_urlhash.'/post/') }}/"+elID,
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'DELETE'
                      
                    },
                    success: function(html) {
                      window.location="{{URL::to('/group/'.$group->group_urlhash)}}";
                    }
                  });
          }
        });


          });
@endif



            $('input').iCheck({
      checkboxClass: 'icheckbox_minimal-purple',
      //radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>