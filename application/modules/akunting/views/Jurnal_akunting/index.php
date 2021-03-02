<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script type="text/javascript">
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

  // setInterval("reload_table();",7000);

  $(document).ready(function(){

    $('#keyword').focus();

    $("#merge_registrasi").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dt_pasien_kasir input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          merge_registrasi(''+searchIDs+'')
          console.log(searchIDs);
    });

    $('#btn_search_data').click(function (e) {
        var url_search = $('#form_search').attr('action');
        e.preventDefault();
        $.ajax({
          url: url_search,
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          success: function(response) {
            console.log(response.data);
            $('#showDataTables').load('akunting/Jurnal_akunting/getDetailTransaksi?'+response.data+'');
          }
        });
    });

    
    $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        $('#showDataTables').load();
    });

    $( "#keyword" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            $('#btn_search_data').click();    
          }         
          return false;                
        }       
    });

    $('#inputAccount').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "Templates/References/getAccountCoa",
              data: 'keyword=' + query,             
              dataType: "json",
              type: "POST",
              success: function (response) {
                result($.map(response, function (item) {
                    return item;
                }));
              }
          });
      },
      afterSelect: function (item) {
        // do what is needed with item
        var val_item=item.split(':')[0];
        $('#inputAccount').val(val_item);
        $('#acc_no').val(val_item);

            
      }
    });

     $('#btn_export_excel').click(function (e) {
          e.preventDefault();
          $.ajax({
          url:  $('#form_search').attr('action'),
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(result) {
            achtungHideLoader();
            window.open('akunting/Jurnal_akunting/export_excel?'+result.data+'','_blank'); 
          }
      });
           
    });
    

  })

</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->


<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="akunting/Jurnal_akunting/find_data">
      <!-- hidden form -->
      <div class="form-group" style="margin-bottom: 3px">
        <!-- no account -->
        <div class="control-label col-md-2">
          <div class="checkbox" style="margin-top: -5px">
            <label>
              <input name="checked_kode_akun" id="checked_kode_akun" type="checkbox" class="ace" value="1">
              <span class="lbl"> Kode Akun</span>
            </label>
          </div>
        </div>
        <div class="col-md-2" style="margin-left: -15px">
          <input type="text" class="form-control" name="acc_no" id="inputAccount">
        </div>
        <!-- tanggal -->
        <div class="control-label col-md-1">
          <div class="checkbox" style="margin-top: -5px">
            <label>
              <input name="checked_tgl" id="checked_tgl" type="checkbox" class="ace" value="1" checked>
              <span class="lbl"> Tanggal</span>
            </label>
          </div>
        </div>
        <div class="col-md-1" style="margin-left: -15px">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d'))));?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>  
        </div>
        <div class="col-md-1" style="margin-left:3%">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>  
        </div>
        <div class="col-md-3" style="margin-left: 3.5%">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Cari
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reload
          </a>
          <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="ace-icon fa fa-file-excel icon-on-right bigger-110"></i>
            Export Excel
          </a>
        </div>

      </div>
      <hr>
      <div id="showDataTables"></div>   

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




