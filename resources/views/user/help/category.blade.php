@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  

<style type="text/css">


.cat-list {
      margin: 0;
    padding: 0;
    list-style: none;
    overflow: auto;
}





          .placeholder {
            outline: 1px dashed #4183C4;
            /*-webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            margin: -1px;*/
            height: 30px;
        }

.cat-list > li .text {
  display: inline-block;
  margin-left: 5px;
  font-weight: 600;
}

.mjs-nestedSortable-error {
            background: #fbe3e4;
            border-color: transparent;
        }

        ul {
            margin: 0;
            padding: 0;
            padding-left: 30px;

        }

        ul.sortable, ul.sortable ul {
            margin: 0 0 0 25px;
            padding: 0;
            list-style-type: none;

        }

        ul.sortable {
            margin: 0 0;
        }

        .sortable li {
            margin: 5px 0 0 0;
            padding: 0;
        }

        .sortable li div  {


/*              border: 1px solid black;             
    padding: 3px;             
    margin: 0; */            
    

        border-radius: 2px;
    padding: 5px;
    background: #f4f4f4;
    margin-bottom: 2px;
    border-left: 2px solid #e6e7e8;
    color: #444;
    cursor: move;  
            /*
            border: 1px solid #d4d4d4;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            border-color: #D4D4D4 #D4D4D4 #BCBCBC;
            padding: 6px;
            margin: 0;
            cursor: move;
            background: #f6f6f6;
            background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
            background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
            background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
            background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
            background: linear-gradient(to bottom,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );
            */
        }

        .sortable li.mjs-nestedSortable-branch div {
           /* background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #f0ece9 100%);
            background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#f0ece9 100%);
            */
            list-style-type: none;

        }

        .sortable li.mjs-nestedSortable-leaf div {


        }

        li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
            border-color: #999;
            background: #fafafa;
        }

        .disclose {
            cursor: pointer;
            width: 10px;
            display: none;
        }

        .sortable li.mjs-nestedSortable-collapsed > ul {
            display: none;
        }

        .sortable li.mjs-nestedSortable-branch > div > .disclose {
            display: inline-block;
        }

        .sortable li.mjs-nestedSortable-collapsed > div > .disclose > span:before {
            content: '+ ';
        }

        .sortable li.mjs-nestedSortable-expanded > div > .disclose > span:before {
            content: '- ';
        }


</style>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-graduation-cap"></i> {{trans('help.title')}}
    <small>{{trans('help.list')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('help.title')}}</li>
    </ol>
</section>

    <!-- Main content -->
<section class="content">




    



    


    <div class="row">


        <div class="col-md-10">


                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></p>
                        @endif
                        @endforeach
                        </div> <!-- end .flash-message -->



            <div class="box">



                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> Категории</h3>

<div class="box-tools">
                <a href="{{URL::to('/help/add/category')}}" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="{{trans('help.addCat')}}">
                  <i class="fa fa-plus-square"></i></a></div>

                </div>





                <div class="box-body">



                                                  <ul class="cat-list sortable">
                                    @foreach ($categories as $cat)
                                      @if ($cat->parent_id == 0)
                                    <li id="list-{{$cat->id}}"><div>

                                        <span class="handle ui-sortable-handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                        </span>

                                    <span class="text">{{$cat->name}}</span>
                                    <span class="pull-right">
                                            <a href='{{URL::to('/help/edit/category/'.$cat->id)}}' style="text-decoration:none; color:#000000 !important;" class="fa fa-edit"></a>

                                            <a href='#' style="text-decoration:none; color:#000000 !important;" class="fa fa-trash-o del_el" data-id="{{$cat->id }}"></a>

                                            </span>

                                    </div>

                                    @include('user.help.categoryTree', array('cat', $cat))

                                    </li>
                                    @endif

                                    @endforeach
                                </ul>



                </div>
                </div>














            
                    </div><!-- /.box -->



<div class="col-md-2">




</div>


                </div>





            



        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")

<!-- page script -->
{!! Html::script('plugins/bootbox/bootbox.min.js'); !!}
{!! Html::script('plugins/nestedSortable/jquery.mjs.nestedSortable.js'); !!}


<script>
  $(function () {
        $('.cat-list').nestedSortable({
            ForcePlaceholderSize: true,
            listType: "ul",
            handle: 'div',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
            maxLevels: 4,
            update: function() {
                list = $(this).nestedSortable('serialize');
                //console.log(list);
                $.post(SYS_URL+'/help/edit/category', {
                    _token : CSRF_TOKEN,
                    list: list
                }, function(data) {
                    console.log(data);
                });
            }
        });
$('body').on('click', '.del_el', function(event) {
            event.preventDefault();
            var elID=$(this).attr('data-id');
bootbox.confirm('{{trans('help.confirmDeleteCat')}}', function(result) {
                if (result == true) {
            
            $.ajax({
                    type: "POST",
                    url: "{{URL::to('/help/delete/category/') }}/"+elID,
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'DELETE'
                      
                    },
                    success: function(html) {
                      window.location="{{URL::to('/help/edit/category')}}";
                    }
                  });
          }
        });


          });


  });
</script>
</body>
</html>