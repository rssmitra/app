<link rel="stylesheet" href="<?php echo  base_url()?>assets/css/jquery-ui.custom.css" />
<link rel="stylesheet" href="<?php echo  base_url()?>assets/css/fullcalendar.css" />
    
<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div class="row">
      <div class="col-sm-9">
        <div class="space"></div>

        <!-- #section:plugins/data-time.calendar -->
        <div id="calendar"></div>

        <!-- /section:plugins/data-time.calendar -->
      </div>

      <div class="col-sm-3">
        <div class="widget-box transparent">
          <div class="widget-header">
            <h4>Deskripsi Warna</h4>
          </div>

          <div class="widget-body">
            <div class="widget-main no-padding">
              <div id="external-events">
                <div class="external-event label-warning" data-id="1" data-class="label label-warning">
                  <i class="ace-icon fa fa-arrows"></i>
                  Rawat Jalan
                </div>

                <div class="external-event label-danger" data-id="0" data-class="label label-danger">
                  <i class="ace-icon fa fa-arrows"></i>
                  Rawat Inap
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div><!-- /.col -->
</div><!-- /.row -->

<!-- fullcalendar -->
<script src="<?php echo base_url()?>assets/js/jquery-ui.custom.js"></script>
<script src="<?php echo base_url()?>assets/js/jquery.ui.touch-punch.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/moment.js"></script>
<script src="<?php echo base_url()?>assets/js/fullcalendar.js"></script>
<script src="<?php echo base_url()?>assets/js/bootbox.js"></script>

<script type="text/javascript">
			jQuery(function($) {

      /* initialize the external events
        -----------------------------------------------------------------*/

        $('#external-events div.external-event').each(function() {

          // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
          // it doesn't need to have a start or end
          var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
          };

          // store the Event Object in the DOM element so we can get to it later
          $(this).data('eventObject', eventObject);

          // make the event draggable using jQuery UI
          $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
          });
          
        });

        /* initialize the calendar
        -----------------------------------------------------------------*/

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();


        var calendar = $('#calendar').fullCalendar({
          //isRTL: true,
          buttonHtml: {
            prev: '<i class="ace-icon fa fa-chevron-left"></i>',
            next: '<i class="ace-icon fa fa-chevron-right"></i>'
          },
        
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month'
          },
          
          // events: getEventData(),
          eventSources: [
          // your event source
            {
              url: 'adm_pasien/publish_report/Adm_publish_report/get_highseason_date',
              method: 'POST',
              extraParams: {
                custom_param1: 'something',
                custom_param2: 'somethingelse'
              },
              failure: function() {
                alert('there was an error while fetching events!');
              },
            }
          // any other sources...
          ],
          editable: true,
          droppable: true, // this allows things to be dropped onto the calendar !!!
          drop: function(date, allDay) { 
            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');
            var categoryId = $(this).attr('data-id');
            var $extraEventClass = $(this).attr('data-class');
            console.log(originalEventObject);
            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);
            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;
            copiedEventObject.categoryId = categoryId;
            if($extraEventClass) copiedEventObject['className'] = [$extraEventClass];
            
            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                        
          },
          selectable: true,
          selectHelper: true,
          select: function(start, end, allDay) {
            bootbox.prompt("New Event Title:", function(title) {
              if (title !== null) {
                calendar.fullCalendar('renderEvent',
                  {
                    title: title,
                    start: start,
                    end: end,
                    allDay: allDay,
                    className: 'label label-info'
                  },
                  true // make the event "stick"
                );
              }
            });
            

            calendar.fullCalendar('unselect');
          }
          ,
          eventClick: function(calEvent, jsEvent, view) {
            console.log(calEvent);
            // default
            show_modal('adm_pasien/publish_report/Adm_publish_report/view_only?flag='+calEvent.categoryId+'&from_tgl='+calEvent.date_exist+'', 'PUBLISH REPORT');
            
          }
          
        });

      })

      function delete_data(myid){
        if(confirm('Are you sure?')){
          $.ajax({
              url: 'adm_pasien/publish_report/Adm_publish_report/delete',
              type: "post",
              data: {ID:myid},
              dataType: "json",
              beforeSend: function() {
                achtungShowLoader();  
              },
              uploadProgress: function(event, position, total, percentComplete) {
              },
              complete: function(xhr) {     
                var data=xhr.responseText;
                var jsonResponse = JSON.parse(data);
                if(jsonResponse.status === 200){
                  $.achtung({message: jsonResponse.message, timeout:5});
                  return false;
                }else{
                  $.achtung({message: jsonResponse.message, timeout:5});
                }
                achtungHideLoader();
              }

            });

        }else{
          return false;
        }
        
      }

</script>




