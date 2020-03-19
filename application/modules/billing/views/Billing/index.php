<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

 
$(document).ready(function(){

  oTableBilling = $('#table-billing').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": false,
    "bInfo": false,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": "billing/Billing/get_data_ri?search_by="+$("#search_by").val()+"&keyword="+$("#keyword_form").val()+"&from="+$("#from_tgl").val()+"&to="+$("#to_tgl").val()+"",
        "type": "POST"
    },
    "columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        {"aTargets" : [1], "mData" : 3, "sClass":  "details-control"}, 
        { "visible": false, "targets": [2,3] },
      ],

  });

  $('#table-billing tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTableBilling.row( tr );
            var data = oTableBilling.row( $(this).parents('tr') ).data();
            var no_registrasi = data[ 1 ];
            var tipe = data[ 2 ];
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("billing/Billing/getDetail/" + no_registrasi + "/" + tipe, '', function (data) {
                    response_data = data;
                     // Open this row
                    row.child( format( response_data ) ).show();
                    tr.addClass('shown');
                });
               
            }
    } );

    $('#table-billing tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            //achtungShowLoader();
            $(this).removeClass('selected');
            //achtungHideLoader();
        }
        else {
            //achtungShowLoader();
            oTableBilling.$('tr.selected').removeClass('selected');
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
      var tableTools_obj = new $.fn.dataTable.TableTools( oTableBilling, {
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
                <p>Copied '+(oTableBilling.fnSettings().fnRecordsTotal())+' row(s) to the clipboard.</p>',
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
      var colvis = new $.fn.dataTable.ColVis( oTableBilling, {
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


    if( $("#keyword_form").val() == ""){

      alert('Masukan keyword minimal 3 Karakter !');

      return $("#keyword_form").focus();

    }else{

      achtungShowLoader();

      find_pasien_by_keyword();

    }    

  });   

})

function format ( data ) {
    return data.html;
}

function getBillingDetail(noreg, type, field){
  preventDefault();
  $.getJSON("billing/Billing/getRincianBilling/" + noreg + "/" + type + "/" +field, '', function (data) {
      response_data = data;
      html = '';
      html += '<div class="center"><p><b>RINCIAN BIAYA '+field+'</b></p></div>';
      //alert(response_data.html); return false;
      $('#detail_item_billing_'+noreg+'').html(data.html);
  });
 
}

function find_pasien_by_keyword(){

  reset_table();

  $('#table_data').show('fast');

  achtungHideLoader();

}

function reset_table(){
    oTableBilling.ajax.url('billing/Billing/get_data_ri?search_by='+$("#search_by").val()+'&keyword='+$("#keyword_form").val()+'&from='+$("#from_tgl").val()+'&to='+$("#to_tgl").val()+'').load();
}

</script>
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

    <form class="form-horizontal" method="post" id="form_search" action="#">

    <div class="col-md-12">

      <center><h4>FORM PENCARIAN DATA PASIEN RAWAT INAP<br><small style="font-size:12px"> Silahkan isi parameter dibawah </small></h4></center>
      <br>

      <!-- hidden form -->
      <input type="hidden" name="sess_kode_bagian" value="<?php echo ($this->session->userdata('kode_bagian'))?$this->session->userdata('kode_bagian'):''?>" id="sess_kode_bagian">
      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" id="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="no_mr" selected>No MR</option>
              <option value="nama_pasien">Nama Pasien</option>
            </select>
          </div>

          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword" id="keyword_form">
          </div>

      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Registrasi</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10" style="margin-left:6px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <a href="#" id="btn_batalkan_kunjungan" class="btn btn-xs btn-danger">
            <i class="ace-icon fa fa-times-circle icon-on-right bigger-110"></i>
            Rollback
          </a>
        </div>
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div id="table_data" style="margin-top:-27px;">
      <table id="table-billing" class="table table-bordered table-hover">
       <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="50px">&nbsp;</th>
            <th width="50px">&nbsp;</th>
            <th></th>
            <th></th>
            <th>Kode</th>
            <th>No MR</th>
            <th>Nama Pasien</th>
            <th>Penjamin</th>
            <th>Ruangan</th>
            <th>Kelas</th>
            <th>Tanggal Masuk</th>
            <th>Dokter</th>
            <th>Status</th>         
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<!-- <script src="<?php //echo base_url().'assets/js/custom/als_datatable.js'?>"></script> -->

<!-- <script src="<?php //echo base_url().'assets/js/custom/billing/billing.js'?>"></script> -->



