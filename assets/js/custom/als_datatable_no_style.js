var oTable;
var base_url = $('#dynamic-table').attr('base-url'); 

$(document).ready(function() {
  /*static datatables*/
    $('#static-table').DataTable({
      "paging":         false
    });
  //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "paging": false,
      "scrollY": "600px",
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url+'/get_data',
          "type": "POST"
      },

    });

    /*setInterval( function () {
        oTable.ajax.reload(null, false);
    }, 60000 );*/


    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
      
    $("#button_delete").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          delete_data(''+searchIDs+'')
          console.log(searchIDs);
    });

    $('#btn_search_data').click(function (e) {
          e.preventDefault();
          $.ajax({
          url: base_url+'/find_data',
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data,base_url);
          }
        });
      });

      $('#btn_export_excel').click(function (e) {
        var url_search = $('#form_search').attr('action');
        e.preventDefault();
        $.ajax({
          url: url_search,
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          success: function(data) {
            console.log(data.data);
            export_excel(data);
          }
        });
      });

    $('#btn_reset_data').click(function (e) {
            e.preventDefault();
            reset_table();
      });


    $( ".form-control" )    
     .keypress(function(event) {        
       var keycode =(event.keyCode?event.keyCode:event.which);         
       if(keycode ==13){          
         event.preventDefault();          
         $('#btn_search_data').focus();        
         return false;                 
        }  
    });
     
});

function export_excel(result){

  window.open(base_url+'/export_excel?'+result.data+'','_blank'); 

}

function find_data_reload(result){

    oTable.ajax.url(base_url+'/get_data?'+result.data).load();
    // $("html, body").animate({ scrollTop: "400px" });

}

function reset_table(){
    oTable.ajax.url(base_url+'/get_data').load();
    // $("html, body").animate({ scrollTop: "400px" });

}

function reload_table(){
   oTable.ajax.reload(); //reload datatable ajax 
}
  

function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: base_url+'/delete',
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
            reload_table();
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

/*jQuery(function($) {

      

})*/








