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

    get_total_billing();
   
    oTable = $('#dt_pasien_kasir').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": true,
      "pageLength": 25,
      "bInfo": false,
      "paging": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dt_pasien_kasir').attr('base-url')+'?flag='+$('#flag').val()+'&pelayanan='+$('#pelayanan').val(),
          "data": {flag:$('#flag').val(), date:$('#date').val(), month:$('#month').val(), year:$('#year').val()},
          "type": "POST"
      },

    });


    $("#merge_registrasi").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dt_pasien_kasir input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          merge_registrasi(''+searchIDs+'')
          console.log(searchIDs);
    });

    function merge_registrasi( arr ){
      $.ajax({
          url: 'adm_pasien/loket_kasir/Adm_kasir/merge_data_registrasi',
          type: "post",
          data: { value : arr },
          dataType: "json",
          beforeSend: function() {
          },
          success: function(data) {
            
          }
      });
    }

  })

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


  $('#add_search_by_date').click(function() {
    if (!$(this).is(':checked')) {
      $('#form_tanggal').hide();
    }else{
      $('#form_tanggal').show();
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

  function find_data_reload(result){
      get_total_billing();
      oTable.ajax.url($('#dt_pasien_kasir').attr('base-url')+'?'+result.data).load();
      $("html, body").animate({ scrollTop: "400px" });

  }

  function reload_table(){
    get_total_billing();
    oTable.ajax.reload();
  }
  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dt_pasien_kasir').attr('base-url')+'?flag='+$('#flag').val()+'&pelayanan='+$('#pelayanan').val()).load();
      $("html, body").animate({ scrollDown: "400px" });
      $('#form_search')[0].reset();
  });

  function get_total_billing(){
      var url_search = $('#form_search').attr('action');
      $.ajax({
        url: url_search,
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        success: function(response) {
          console.log(response.data);
          $.getJSON("adm_pasien/loket_kasir/Adm_kasir/get_total_billing?"+response.data, '', function (data) {
             // code here
              $('#total_submit').text( formatMoney(data.total_submit) );
              var total_blm_disubmit = sumClass('total_billing_class');
              $('#total_non_submit').text( formatMoney(total_blm_disubmit) );
          });
        }
      });

    
  }


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

    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/loket_kasir/Adm_kasir/find_data">
     
      <div class="form-group" id="form_tanggal" >
        <label class="control-label col-md-1">Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <label class="control-label col-md-1">Shift</label>
          <div class="col-md-1">
            <select name="shift" id="shift" class="form-control">
              <option value="1">Pagi</option>
              <option value="2">Siang</option>
              <option value="3">Malam</option>
            </select>
          </div>
          <label class="control-label col-md-1">Loket</label>
          <div class="col-md-1">
            <select name="loket" id="loket" class="form-control">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
            </select>
          </div>
        </div>
      </div>
  

      <div class="form-group">
        <label class="control-label col-md-1">&nbsp;</label>
        <div class="col-md-6" style="margin-left:16px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Tampilkan
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reload
          </a>
        </div>
      </div>   

      <div id="showDataTables" style="display: none">
        <table id="dt_pasien_kasir" base-url="adm_pasien/loket_kasir/adm_kasir/get_data" url-detail="billing/Billing/getDetailBillingKasir" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color:#428bca">
              <th></th>
              <th>No. Reg</th>
              <?php echo ($flag=='bpjs') ? '<th>No SEP</th>' : '' ; ?>
              <th>No. MR</th>
              <th>Nama Pasien</th>
              <th>Poli/Klinik Asal</th>
              <th>Penjamin</th>
              <th width="150px">Tanggal Masuk</th>
              <th width="100px">Total Billing</th>
            </tr>
          </thead>
        </table>
      </div>   

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




