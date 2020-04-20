<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

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

    getMenuTabs('adm_pasien/loket_kasir/Adm_resume_lhk/get_data?method=tunai&from_tgl=<?php echo $date?>&flag='+$('#flag').val()+'', 'tab_content_data');
    
  })

  $( ".date-picker" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          $('#btn_search_data').click();    
        }         
        return false;                
      }       
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
      e.preventDefault();
      $.ajax({
      url: $('#form_search').attr('action'),
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        export_excel(data);
      }
    })
  });

  function export_excel(result){

    window.open('adm_pasien/loket_kasir/Adm_resume_lhk/export_excel?'+result.data+'','_blank'); 
  }

  function find_data_reload(result){
      getMenuTabs('adm_pasien/loket_kasir/Adm_resume_lhk/get_data?method=tunai&'+result.data, 'tab_content_data');
  }

  function reload_table(){
    oTable.ajax.reload();
  }

  function click_tab(method){

    getMenuTabs('adm_pasien/loket_kasir/Adm_resume_lhk/get_data?method='+method+'&from_tgl='+$('#from_tgl').val()+'&flag='+$('#flag').val()+'', 'tab_content_data')

  }

  function publish_report(){

    getMenuTabs('adm_pasien/loket_kasir/Adm_resume_lhk/publish_report?flag='+$('#flag').val()+'&from_tgl='+$('#from_tgl').val()+'&method=bill', 'tab_content_data');

  }

  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dt_harian_kasir').attr('base-url')+'?flag='+$('#flag').val()).load();
      $("html, body").animate({ scrollDown: "400px" });
      $('#form_search')[0].reset();
  });

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

    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/loket_kasir/Adm_resume_lhk/find_data">
        <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">

        <div class="form-group">
            <label class="control-label col-md-2">Tanggal Transaksi</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
            <div class="col-md-6" style="margin-left: -1.5%">
              <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Tampilkan
              </a>
              <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reload
              </a>

              <!-- <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
                <i class="ace-icon fa fa-file-excel-o icon-on-right bigger-110"></i>
                Export Excel
              </a> -->
            </div>
        </div>

        <hr>
        <div class="tabbable">
          <ul class="nav nav-tabs" id="myTab">
            <li class="active">
              <a data-toggle="tab" href="#tunai" onclick="click_tab('tunai')">
                Rekap Tunai
              </a>
            </li>

            <li>
              <a data-toggle="tab" href="#debet" onclick="click_tab('debet')">
                Rekap Debet
              </a>
            </li>

            <li>
              <a data-toggle="tab" href="#kredit" onclick="click_tab('kredit')">
                Rekap Kredit
              </a>
            </li>

            <li>
              <a data-toggle="tab" href="#kredit" onclick="click_tab('bill')">
                Rekapitulasi Pendapatan Keseluruhan
              </a>
            </li>

            <li class="pull-right green">
              <a data-toggle="tab" href="#publish" onclick="publish_report()" style="background-color: #79fc79;">
                 PUBLISH LAPORAN <i class="fa fa-external-link bigger-120"></i>
              </a>
            </li>

          </ul>

          <div class="tab-content">
            <div id="tunai" class="tab-pane fade in active">
              <div id="tab_content_data"></div>
            </div>
          </div>
        </div>

        

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




