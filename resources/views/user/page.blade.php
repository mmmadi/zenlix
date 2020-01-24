@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     {{trans('page.title')}}
    <small>{{$user->name}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active"><a href="{{URL::to('/user/'.$user->profile->user_urlhash)}}">{{$user->name}}</a></li>
        
    </ol>
</section>

    <!-- Main content -->
<section class="content">




   


    


    <div class="row">


        <div class="col-md-3">
          
          <div class="box box-primary">
            <div class="box-body box-profile">
            <center>
              <img class="img-responsive" src="{{ Zen::showUserImg($user->profile->user_img) }}" alt="User profile picture">
</center>
              <h3 class="profile-username text-center">{{$user->name}}</h3>

              <p class="text-muted text-center">{{$user->profile->position}}</p>

            </div>





            <!-- /.box-body -->
          </div>

        </div>

        <div class="col-md-9">
          
<div class="box">
            <div class="box-header">
              <h3 class="box-title">{{trans('page.userinfo')}}</h3>
            </div>
            <div class="box-body">
              

<div class="form-horizontal">


 <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.name')}}</label>
   <div class="col-sm-9 "> {{$user->name}} </div>
 </div>

 <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.posada')}}</label>
   <div class="col-sm-9 "> {{$user->profile->position}} </div>
 </div>

@if (!empty($user->profile->address))
  <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.address')}}</label>
   <div class="col-sm-9 "> {{$user->profile->address}} </div>
 </div>
@endif

@if (!empty($user->profile->telephone))
 <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.tel')}}</label>
   <div class="col-sm-9 "> {{$user->profile->telephone}} </div>
 </div>
 @endif
<hr>


 <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.birthday')}}</label>
   <div class="col-sm-9 "> {{$user->profile->birthdayDay}} {{$user->profile->birthdayMonth}} {{$user->profile->birthdayYear}}</div>
 </div>


 <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.email')}}</label>
   <div class="col-sm-9 "> {{$user->profile->email}} </div>
 </div>

@if (!empty($user->profile->skype))
 <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.skype')}}</label>
   <div class="col-sm-9 "> {{$user->profile->skype}} </div>
 </div>
 @endif

 @if (!empty($user->profile->facebook))
  <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.facebook')}}</label>
   <div class="col-sm-9 "> {{$user->profile->facebook}} </div>
 </div>
@endif

@if (!empty($user->profile->twitter))
  <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.twitter')}}</label>
   <div class="col-sm-9 "> {{$user->profile->twitter}} </div>
 </div>
 @endif

 @if (!empty($user->profile->website))
  <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.website')}}</label>
   <div class="col-sm-9 "> {{$user->profile->website}} </div>
 </div>
 @endif
 <hr>
 @if (!empty($user->profile->about))
  <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.about')}}</label>
   <div class="col-sm-9 "> {{$user->profile->about}} </div>
 </div>
 @endif

@if (!empty($user->profile->skills))
  <div class="form-group">
   <label class="col-sm-3 text-right">{{trans('page.skills')}}</label>
   <div class="col-sm-9 "> {{$user->profile->skills}} </div>
 </div>
 @endif



@if ($user->fields()->count() != 0)

<hr>
<em>{{trans('page.add')}}</em>
<br><br>

@foreach ($user->fields as $field)


@if(!empty($field->field_data))

    <div class="form-group">
   <label class="col-sm-3 text-right">{{$fields->where('id', $field->user_field_id)->first()->name}}</label>
   <div class="col-sm-9 "> {{$field->field_data}} </div>
 </div>

@endif


@endforeach


@endif





</div>


            </div>
          </div>



        </div>






                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
<!-- page script -->
<script>
  $(function () {


  });
</script>
</body>
</html>