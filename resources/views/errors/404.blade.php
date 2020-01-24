@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  



<div class="content-wrapper">
<section class="content-header">
          <h1>
            {{trans('errorPage.title')}}
          </h1>
          <ol class="breadcrumb">
            @include("layout.breadcrumb")
            <li class="active">{{trans('errorPage.subtitle')}}</li>
          </ol>
        </section>
        <section class="content">
          <div class="error-page">
            <h2 class="headline text-yellow"> 404</h2>
            <div class="error-content">
              <h3><i class="fa fa-warning text-yellow"></i> {{trans('errorPage.404Info')}}</h3>
              <p>
                {{trans('errorPage.404msg')}}
                {!! trans('errorPage.404desc')!!}
              </p>
              <form class="search-form" method="GET" action="{!! URL::to('/search') !!}">
                <div class="input-group">
                  <input type="text" name="q" class="form-control" placeholder="Search">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
                  </div>
                </div><!-- /.input-group -->
              </form>
            </div><!-- /.error-content -->
          </div><!-- /.error-page -->
        </section>
</div>
@include("layout.footer")
<!-- page script -->

</body>
</html>