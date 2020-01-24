@include("layout.header")
@include("layout.topmenu")
@include("layout.navbar")  
{!! Html::style('plugins/iCheck/minimal/purple.css'); !!}
{!! Html::style('plugins/fullcalendar/fullcalendar.min.css'); !!}

{!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css'); !!}
{!! Html::style('plugins/daterangepicker/daterangepicker.css'); !!}

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
     <i class="fa fa-calendar"></i> {{trans('calendar.calendar')}}
    <small>{{trans('calendar.eventPlanner')}}</small>
    </h1>
    <ol class="breadcrumb">
        @include("layout.breadcrumb")
        <li class="active">{{trans('calendar.calendar')}}</li>
        
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

<div class="box box-primary">

                    <div class="box-body" id="calendar">
                      


                    </div><!-- /.box-body -->

</div>


</div>


<div class="col-md-3">


              


<div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">{{trans('calendar.filterEvent')}}</h3>
                </div>
                <div class="box-body">



<form class="form-horizontal" role="form">
<div class="form-group">
  
  <div class="col-sm-12">
    <div class="checkbox">
    <label>
      <input type="checkbox" id="eventFilterPersonal" class="make_event_filter" value="true" checked> <small>{{trans('calendar.private')}}</small>
      
    </label>
  </div>
  </div>
    <div class="col-sm-12">
    <div class="checkbox">
    <label>
      <input type="checkbox" id="eventFilterGroup" class="make_event_filter" value="true" checked> <small>{{trans('calendar.groups')}}</small>
                          
                    
    </label>
  </div>
  </div>



</div>
<div class="form-group">
                      <div class="col-sm-12">
                    {!! Form::select('groupsCal', $groups, $groupsSel, array('class'=>'form-control input-sm select2', 'style'=>'width: 100%', 'multiple'=>'multiple', 'id'=>'groupsCal')) !!}
                    
                    </div>
                    </div>


<button id="showCalBtn" data-code="" class="btn btn-block btn-flat btn-success">{{trans('calendar.show')}}</button>



<input type="hidden" id="filter_events" value="0,1">
</form>


                </div>
              </div>

                  </div>


</div>


  @include('user.calendar.createEvent')
@include('user.calendar.editEvent')
@include('user.calendar.showEvent')

        </section>
    <!-- /.content -->
  </div>

@include("layout.footer")
{!! Html::script('plugins/iCheck/icheck.min.js'); !!}
{!! Html::script('plugins/bootbox/bootbox.min.js'); !!}

{!! Html::script('plugins/fullcalendar/lib/moment.min.js'); !!}
{!! Html::script('plugins/fullcalendar/fullcalendar.min.js'); !!}
{!! Html::script('plugins/fullcalendar/lang-all.js'); !!}
{!! Html::script('plugins/daterangepicker/daterangepicker.js'); !!}
<!-- page script -->
<script>
  $(function () {

$('#reservationtime').daterangepicker({
  timePicker: true, 
  timePicker24Hour: true,
  timePickerIncrement: 15, 
  locale: {
            format: 'YYYY-MM-DD H:mm:ss'
        }

  

}, function(start, end, label) {

$("#startDate").val(start.format('YYYY-MM-DD H:mm:ss'));
$("#endDate").val(end.format('YYYY-MM-DD H:mm:ss'));

});

$('#reservationtimeEdit').daterangepicker({
  timePicker: true, 
  timePicker24Hour: true,
  timePickerIncrement: 15, 
  locale: {
            format: 'YYYY-MM-DD H:mm:ss'
        }

  

}, function(start, end, label) {

$("#startDateEdit").val(start.format('YYYY-MM-DD H:mm:ss'));
$("#endDateEdit").val(end.format('YYYY-MM-DD H:mm:ss'));

});


 /* initialize the external events
         -----------------------------------------------------------------*/
        function ini_events(ele) {
            ele.each(function() {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                };
                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject);
                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 1070,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });
            });
        }
        ini_events($('#external-events div.external-event'));
        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();

        function loadCal(personal,group,groupArr) {
            $('#calendar').fullCalendar('destroy');
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                firstDay: 1,
                lang: 'ru',
                timeFormat: 'H(:mm)',
                buttonText: {
                    today: '{{trans('calendar.today')}}',
                    month: '{{trans('calendar.month')}}',
                    week: '{{trans('calendar.week')}}',
                    day: '{{trans('calendar.day')}}'
                },
                //Random default events

                eventSources: [
                    // your event source
                    {
                        url: '{{URL::to('/calendar/events')}}',
                        type: 'GET',
                        data: {
                            _token : CSRF_TOKEN,
                            personal: personal,
                            group: group,
                            groupArr: groupArr
                        },
                        error: function() {
                            alert('there was an error while fetching events!');
                        }
                    }
                    // any other sources...
                ],
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                eventClick: function(calEvent, jsEvent, view) {
                    //get_cal_event
                    if (calEvent.editable == false) {
                        $.ajax({
                            url: '{{URL::to('/calendar/event')}}',
                            data: {
                                _token : CSRF_TOKEN,
                                uniq_code: calEvent.id
                            },
                            //data: 'title='+ event.title+'&start='+ start +'&end='+ end,
                            type: "GET",
                            dataType: "json",
                            success: function(json) {
                                //alert("insert Successfully");
                                $.each(json, function(i, item) {

                                  $("#eventShowName").html(item.title);
                                  $("#eventShowDescription").html(item.description);
                                  $("#eventShowUser").html(item.userName);
                                  $("#eventShowPeriod").html(item.reservationtimeEdit);

                                    //$("#ei_name").text(item.title);
                                    //$("#ei_desc").text(item.description);
                                    //$("#ei_period").text(item.period);
                                    //$("#ei_author").html(item.author);
                                })
                                $('#event_modal_show').modal('show');
                            }
                        });
                    } else if (calEvent.editable == true) {
                        $.ajax({
                            url: '{{URL::to('/calendar/event')}}',
                            data: {
                                _token : CSRF_TOKEN,
                                uniq_code: calEvent.id
                            },
                            //data: 'title='+ event.title+'&start='+ start +'&end='+ end,
                            type: "GET",
                            dataType: "json",
                            success: function(json) {
                                //alert("insert Successfully");
                                $.each(json, function(i, item) {
                                  //console.log(item.title);
                                    $("#eventTitle").val(item.title);
                                    $("#eventDescription").val(item.description);
                                    $("#groupsEdit").select2().val(item.groupsEdit).trigger("change");
                                    $("#event_code").val(item.code);
                                    $("#cal_delete_current").attr('data-code', item.code);
                                    //console.log('pl');
                                    ////$("#visibility").val();
                                    //$("#visibility").val(item.visibility);
                                    $(".current_backgroundColor").val(item.backgroundColor);
                                    $(".current_borderColor").val(item.borderColor);
                                    $(".cur_color_event").css({
                                        "background-color": item.backgroundColor,
                                        "border-color": item.borderColor
                                    });
                                    ////console.log(item.allDay);
                                    //$("#reservationtimeEdit").val(item.reservationtimeEdit);
                                    $("#reservationtimeEdit").data('daterangepicker').setStartDate(item.startDateEdit);
                                    $("#reservationtimeEdit").data('daterangepicker').setEndDate(item.endDateEdit);
                                    
                                    $("#startDateEdit").val(item.startDateEdit);
                                    $("#endDateEdit").val(item.endDateEdit);
                                    if (item.eventPersonal == true) {
                                      $("input#eventPersonal").iCheck('check');
                                    }
                                    else {
                                      $("input#eventPersonal").iCheck('uncheck');
                                    }

                                    if (item.eventAllday == true) {
                                        $("input#eventAllday").iCheck('check');
                                        //$("#reservation").prop('disabled', true);
                                    } else if (item.eventAllday == false) {
                                        $("input#eventAllday").iCheck('uncheck');
                                        //$("#reservation").prop('disabled', false);
                                    }
                                });
                            
                              $('#event_modal_edit').modal('show');
                            }
                        });
                        //$("#current_event_hash").val(calEvent.id);
                        
                    }
                    //alert('Event id: ' + calEvent.id);
                },
                drop: function(date, allDay) {
                },
                eventDrop: function(event, delta) {
                    //var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
                    //var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
                    var start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
                    //var end = moment(event.end).format("YYYY-MM-DD HH:mm:ss");
                    var end = (event.end == null) ? start : event.end.format();
                    //console.log(event.end.format());
                    $.ajax({
                        url: '{{URL::to('/calendar/event/drop')}}',
                        data: {
                            _token : CSRF_TOKEN,
                            title: event.title,
                            start: start,
                            end: end,
                            id: event.id,
                            allday: event.allDay
                        },
                        //data: 'title='+ event.title+'&start='+ start +'&end='+ end,
                        type: "POST",
                        success: function(json) {
                            // alert("Updated Successfully");
                        }
                    });
                    //console.log("a: "+event.id);
                },
                eventResize: function(event) {
                    var start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
                    var end = moment(event.end).format("YYYY-MM-DD HH:mm:ss");
                    //console.log(cr);
                    $.ajax({
                        url: '{{URL::to('/calendar/event/resize')}}',
                        data: {
                            _token : CSRF_TOKEN,
                            title: event.title,
                            start: start,
                            end: end,
                            id: event.id,
                            allday: event.allDay
                        },
                        //data: 'title='+ event.title+'&start='+ start +'&end='+ end,
                        type: "POST",
                        success: function(json) {
                            // alert("Updated Successfully");
                        }
                    });
                },




                eventRender: function(event, element) {
      
/*                    element.popover({
                        title: event.name,
                        html: true,
                        trigger: 'manual',
                        placement: 'top',
                        title: event.title,
                        content: "<small>" + event.description + "</small>",
                    }).on("mouseenter", function() {
                        var _this = this;
                        $(this).popover("show");
                        $(this).siblings(".popover").on("mouseleave", function() {
                            $(_this).popover('hide');
                        });
                    })
                    .on("mouseleave", function() {
                        var _this = this;
                        setTimeout(function() {
                            if (!$(".popover:hover").length) {
                                $(_this).popover("hide");
                            }
                        }, 100)
                    });*/
                },
                dayClick: function(date, jsEvent, view) {

                  $('#event_modal_create').modal('show');
                    $("#startDate").val(date.format('YYYY-MM-DD H:mm:ss'));
                    $("#endDate").val(date.format('YYYY-MM-DD H:mm:ss'));
                    //$("#reservationtime").val(date.format('YYYY-MM-DD hh:mm:ss')+' - '+date.format('YYYY-MM-DD hh:mm:ss'))
                    $("#reservationtime").data('daterangepicker').setStartDate(date.format('YYYY-MM-DD H:mm:ss'));
                    $("#reservationtime").data('daterangepicker').setEndDate(date.format('YYYY-MM-DD H:mm:ss'));
                                    
                  //console.log('Clicked on: ' + date.format());

                }
            });
        }
        /* ADDING EVENTS */
        var currColor = "#3c8dbc"; //Red by default
        //Color chooser button
        var colorChooser = $("#color-chooser-btn");
        $("#color-chooser > li > a").click(function(e) {
            e.preventDefault();
            //Save color
            currColor = $(this).css("color");
            //Add color effect to button
            $('#add-new-event').css({
                "background-color": currColor,
                "border-color": currColor
            });
        });
        $("#add-new-event").click(function(e) {
            e.preventDefault();
            //Get value and make sure it is not null
            var val = $("#new-event").val();
            if (val.length == 0) {
                return;
            }
            //Create events
            var event = $("<div />");
            event.css({
                "background-color": currColor,
                "border-color": currColor,
                "color": "#fff"
            }).addClass("external-event");
            event.html(val);
            $('#external-events').prepend(event);
            //Add draggable funtionality
            ini_events(event);
            //Remove event from text input
            $("#new-event").val("");
        });

        $("#color-chooser_event > li > a").click(function(e) {
            e.preventDefault();
            //Save color
            currColor = $(this).css("color");
            //Add color effect to button
            $('.cur_color_event').css({
                "background-color": currColor,
                "border-color": currColor
            });
            $(".current_backgroundColor").val(currColor);
            $(".current_borderColor").val(currColor);
        });



loadCal(true,true,[{{implode(',',$groupsSel)}}]);



//showCalBtn
$('body').on('click', '#showCalBtn', function(event) {
            event.preventDefault();
var eventFilterPersonal = $("#eventFilterPersonal").is(":checked");
var eventFilterGroup = $("#eventFilterGroup").is(":checked");
var groupsCal = $("#groupsCal").val();

loadCal(eventFilterPersonal,eventFilterGroup,groupsCal);


          });






$('body').on('click', '#cal_delete_current', function(event) {
            event.preventDefault();

            var elID=$(this).attr('data-code');

bootbox.confirm('{{trans('calendar.confirmDeleteEvent')}}', function(result) {
                if (result == true) {
            
            $.ajax({
                    type: "POST",
                    url: "{{URL::to('calendar/event/') }}/"+elID,
                    //dataType: "json",
                    data: {
                      _token : CSRF_TOKEN,
                      _method: 'DELETE'
                      
                    },
                    success: function(html) {
                      window.location="{{URL::to('/calendar')}}";
                    }
                  });
          }
        });


          });




            $('input').iCheck({
      checkboxClass: 'icheckbox_minimal-purple',
      //radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>