var oTable;
var base_url = $('#dynamic-table').attr('base-url'); 
var params = $('#dynamic-table').attr('data-id'); 

$(document).ready(function() {
    /*static datatables*/
    $('#static-table').DataTable({
      "scrollY":        "500px",
      "scrollCollapse": true,
      "paging":         false
    });

    //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "pageLength": 25,
      "scrollY": "600px",
      "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url+'/get_data?'+params,
          "type": "POST"
      },
      "columnDefs": [
          { 
            "targets": [ 0 ], //last column
            "orderable": false, //set not orderable
          },
          { "aTargets" : [ 1 ], "mData" : 1, "sClass":  "details-control"}, 
          { "visible": true, "targets": [ 1 ] },
          { "targets": [ 2 ], "visible": false },
      ],

    });

    $('#dynamic-table tbody').on('click', 'td.details-control', function () {
        var url_detail = $('#dynamic-table').attr('url-detail');
        preventDefault();
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 2 ];                  
        console.log(data);
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/            
            $.getJSON( url_detail + "/" + kode_primary + "?" +params, '' , function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
                tr.addClass('shown');
            });
        }
        
    } );

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

    $("#button_print_multiple").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          print_data(''+searchIDs+'');
          console.log(searchIDs);
    });
    

    $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        reset_table();
        $('#form_search')[0].reset();
    });


});

$('#btn_search_data').click(function (e) {
    var url_search = $('#form_search').attr('action');
    e.preventDefault();
    $.ajax({
      url: url_search,
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      success: function(data) {
        console.log(data.data);
        find_data_reload(data);
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

function export_excel(result){
  window.open(base_url+'/export_excel?'+result.data+'','_blank'); 
}

function format_html ( data ) {
  return data.html;
}

function find_data_reload(result){
    oTable.ajax.url(base_url+'/get_data?'+result.data).load();
}

function reset_table(){
    oTable.ajax.url(base_url+'/get_data?'+params).load();
}

function reload_table(){
   oTable.ajax.reload(); //reload datatable ajax 
}  

function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: base_url+'/delete?'+params,
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

function print_data(myid){
  $.ajax({
    url: base_url+'/print_multiple?'+params,
    type: "post",
    data: {ID:myid, flag: params},
    dataType: "json",
    beforeSend: function() {
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      // response
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);
      PopupCenter(''+base_url+'/print_multiple_preview?'+jsonResponse.queryString+'', 'PRINT PREVIEW', 1000, 550);
    }
  });
}








