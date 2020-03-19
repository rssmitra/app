var oTable;
$(document).ready(function() {
  //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      /*"scrollX": false,
      "scrollY": false,*/
      "ordering": false,
      "bPaginate": true,
      "info": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "casemix/Csm_dokumen_klaim/get_data",
          "type": "POST"
      },
      /*"columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        {"aTargets" : [1], "mData" : 2, "sClass":  "details-control"}, 
        { "visible": false, "targets": 2 }
      ],*/

      //Set column definition initialisation properties.
      /*"columnDefs": [
        { 
          "targets": [ 1, 2, -1, -2 ], //last column
          "orderable": false, //set not orderable
        }
      ],*/

    });

    $('#dynamic-table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var data = oTable.row( $(this).parents('tr') ).data();
            var inv = data[ 1 ];
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("casemix/Csm_dokumen_klaim/getDetail/" + inv, '', function (data) {
                    response_data_from_invoice = data;
                     // Open this row
                    row.child( format( response_data_from_invoice ) ).show();
                    tr.addClass('shown');
                });
               
            }
    } );

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            //achtungShowLoader();
            $(this).removeClass('selected');
            //achtungHideLoader();
        }
        else {
            //achtungShowLoader();
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //achtungHideLoader();
        }
    } );

    
    
      //TableTools settings
      TableTools.classes.container = "btn-group btn-overlap";
      TableTools.classes.print = {
        "body": "DTTT_Print",
        "info": "tableTools-alert gritter-item-wrapper gritter-info gritter-center white",
        "message": "tableTools-print-navbar"
      }
    
      //initiate TableTools extension
      var tableTools_obj = new $.fn.dataTable.TableTools( oTable, {
        "sSwfPath": "assets/js/dataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf", //in Ace demo ../assets will be replaced by correct assets path
        
        "sRowSelector": "td:not(:last-child)",
        "sRowSelect": "multi",
        "fnRowSelected": function(row) {
          //check checkbox when row is selected
          try { $(row).find('input[type=checkbox]').get(0).checked = true }
          catch(e) {}
        },
        "fnRowDeselected": function(row) {
          //uncheck checkbox
          try { $(row).find('input[type=checkbox]').get(0).checked = false }
          catch(e) {}
        },
    
        "sSelectedClass": "success",
            "aButtons": [
          {
            "sExtends": "copy",
            "sToolTip": "Copy to clipboard",
            "sButtonClass": "btn btn-white btn-primary btn-bold",
            "sButtonText": "<i class='fa fa-copy bigger-110 pink'></i>",
            "fnComplete": function() {
              this.fnInfo( '<h3 class="no-margin-top smaller">Table copied</h3>\
                <p>Copied '+(oTable.fnSettings().fnRecordsTotal())+' row(s) to the clipboard.</p>',
                1500
              );
            }
          },
          
          {
            "sExtends": "csv",
            "sToolTip": "Export to CSV",
            "sButtonClass": "btn btn-white btn-primary  btn-bold",
            "sButtonText": "<i class='fa fa-file-excel-o bigger-110 green'></i>"
          },
          
          {
            "sExtends": "pdf",
            "sToolTip": "Export to PDF",
            "sButtonClass": "btn btn-white btn-primary  btn-bold",
            "sButtonText": "<i class='fa fa-file-pdf-o bigger-110 red'></i>"
          },
          
          {
            "sExtends": "print",
            "sToolTip": "Print view",
            "sButtonClass": "btn btn-white btn-primary  btn-bold",
            "sButtonText": "<i class='fa fa-print bigger-110 grey'></i>",
            
            "sMessage": "<div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Optional Navbar &amp; Text</small></a></div></div>",
            
            "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                  <p>Please use your browser's print function to\
                  print this table.\
                  <br />Press <b>escape</b> when finished.</p>",
          }
            ]
        } );
      //we put a container before our table and append TableTools element to it
        $(tableTools_obj.fnContainer()).appendTo($('.tableTools-container'));
      
      //also add tooltips to table tools buttons
      //addding tooltips directly to "A" buttons results in buttons disappearing (weired! don't know why!)
      //so we add tooltips to the "DIV" child after it becomes inserted
      //flash objects inside table tools buttons are inserted with some delay (100ms) (for some reason)
      setTimeout(function() {
        $(tableTools_obj.fnContainer()).find('a.DTTT_button').each(function() {
          var div = $(this).find('> div');
          if(div.length > 0) div.tooltip({container: 'body'});
          else $(this).tooltip({container: 'body'});
        });
      }, 200);
      
      
      
      //ColVis extension
      var colvis = new $.fn.dataTable.ColVis( oTable, {
        "buttonText": "<i class='fa fa-search'></i>",
        "aiExclude": [0, 6],
        "bShowAll": true,
        //"bRestore": true,
        "sAlign": "right",
        "fnLabel": function(i, title, th) {
          return $(th).text();//remove icons, etc
        }
        
      }); 
      
      //style it
      $(colvis.button()).addClass('btn-group').find('button').addClass('btn btn-white btn-info btn-bold')
      
      //and append it to our table tools btn-group, also add tooltip
      $(colvis.button())
      .prependTo('.tableTools-container .btn-group')
      .attr('title', 'Show/hide columns').tooltip({container: 'body'});
      
      //and make the list, buttons and checkboxed Ace-like
      $(colvis.dom.collection)
      .addClass('dropdown-menu dropdown-light dropdown-caret dropdown-caret-right')
      .find('li').wrapInner('<a href="javascript:void(0)" />') //'A' tag is required for better styling
      .find('input[type=checkbox]').addClass('ace').next().addClass('lbl padding-8');
    
    
      
      /////////////////////////////////
      //table checkboxes
      $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
      
      //select/deselect all rows according to table header checkbox
      $('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
        var th_checked = this.checked;//checkbox inside "TH" table header
        
        $(this).closest('table').find('tbody > tr').each(function(){
          var row = this;
          if(th_checked) tableTools_obj.fnSelect(row);
          else tableTools_obj.fnDeselect(row);
        });
      });
      
      //select/deselect a row when the checkbox is checked/unchecked
      $('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
        var row = $(this).closest('tr').get(0);
        if(!this.checked) tableTools_obj.fnSelect(row);
        else tableTools_obj.fnDeselect($(this).closest('tr').get(0));
      });
      
        $(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
        e.stopImmediatePropagation();
        e.stopPropagation();
        e.preventDefault();
      });
      
      
      //And for the first simple table, which doesn't have TableTools or dataTables
      //select/deselect all rows according to table header checkbox
      var active_class = 'active';
      $('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
        var th_checked = this.checked;//checkbox inside "TH" table header
        
        $(this).closest('table').find('tbody > tr').each(function(){
          var row = this;
          if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
          else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
        });
      });
      
      //select/deselect a row when the checkbox is checked/unchecked
      $('#simple-table').on('click', 'td input[type=checkbox]' , function(){
        var $row = $(this).closest('tr');
        if(this.checked) $row.addClass(active_class);
        else $row.removeClass(active_class);
      });
    
      
    
      /********************************/
      //add tooltip for small view action buttons in dropdown menu
      $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
      
      //tooltip placement on right or left
      function tooltip_placement(context, source) {
        var $source = $(source);
        var $parent = $source.closest('table')
        var off1 = $parent.offset();
        var w1 = $parent.width();
    
        var off2 = $source.offset();
        //var w2 = $source.width();
    
        if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
        return 'left';
      }

      $('#btn_search_data').click(function (e) {
          e.preventDefault();
          $.ajax({
          url: 'casemix/Csm_dokumen_klaim/find_data',
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data);
          }
        });
      });

      $('#btn_export_excel').click(function (e) {
          e.preventDefault();
          var month = $('#month').val();
          var year = $('#year').val();
          var tipe = $('#tipe').val();
          var from_tgl_reg = $('#from_tgl_reg').val();
          var to_tgl_reg = $('#to_tgl_reg').val();
          var field = $('input[name=search_by_field]:checked').val();
          window.open('Templates/export_data/exportContent?month='+month+'&year='+year+'&tipe='+tipe+'&frmdt='+from_tgl_reg+'&todt='+to_tgl_reg+'&field='+field+'&type=excel&mod=Csm_dokumen_klaim','_blank');     
      });

      $('input[name=search_data]').click(function(e){
        var field = $('input[name=search_data]:checked').val();
        if ( field == 'dokumen_klaim' ) {
          /*load datatables menu klaim*/
          $('#page-area-content').load('casemix/Csm_dokumen_klaim');
        }else if (field == 'reg_not_merge') {
          /*load data register not merge*/
          $('#page-area-content').load('casemix/Csm_reg_not_merge');
        }else{
          /*default menu klaim*/
          $('#page-area-content').load('casemix/Csm_dokumen_klaim');

        }
      });

      $('input[name=search_by_field]').click(function(e){
        var field = $('input[name=search_by_field]:checked').val();
        if ( field == 'month_year' ) {
          $('#month_year_field').show('fast');
          $('#tanggal_field').hide('fast');
        }else{
          if (field=='created_date') {
            $('#text_label').html('Tanggal Input/Costing');
          }else {
            $('#text_label').html('Tanggal Transaksi');
          }
          $('#month_year_field').hide('fast');
          $('#tanggal_field').show('fast');
        }
      });

      $('#btn_reset_data').click(function (e) {
            e.preventDefault();
            document.getElementById("form_search").reset();
            reset_table();
      });

      $("#button_delete").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          delete_data(''+searchIDs+'')
          console.log(searchIDs);
      });

      /*$('#button').on( 'click', function () {
          alert($('input[type="checkbox"][name=selected_id]').val());
      });*/

      
});

function reset_table(){
    oTable.ajax.url('casemix/Csm_dokumen_klaim/get_data').load();
    $("html, body").animate({ scrollTop: "100px" });

}

function find_data_reload(result){

    var data = result.data;  
    oTable.ajax.url('casemix/Csm_dokumen_klaim/get_data?month='+data.month+'&year='+data.year+'&tipe='+data.tipe+'&frmdt='+data.from_tgl_reg+'&todt='+data.to_tgl_reg+'&field='+data.search_by_field+'&search=excecute').load();
    $("html, body").animate({ scrollTop: "100px" });
    
}

function format ( data ) {
    return data.html;
}

function reload_table(){
   oTable.ajax.reload(); //reload datatable ajax 
}
  

function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'casemix/Csm_dokumen_klaim/delete',
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








