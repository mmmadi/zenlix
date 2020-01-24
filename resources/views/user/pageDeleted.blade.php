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
              

<div class="callout callout-danger">
                <h4>{{trans('page.userDeleted')}}</h4>

                <p>{{trans('page.userDeletedFull')}}</p>
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