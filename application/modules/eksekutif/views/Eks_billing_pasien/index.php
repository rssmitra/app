<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/custom/chart.js"></script>

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

  $(document).ready(function(){

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
            "url": $('#dt_pasien_kasir').attr('base-url')+'?flag='+$('input[name="flag"]:checked').val()+'&pelayanan='+$('input[name="pelayanan"]:checked').val()+'&poliklinik='+$('#poliklinik').val()+'&select_dokter='+$('#select_dokter').val()+'&start_date='+$('#from_tgl').val()+'&to_date='+$('#to_tgl').val()+'&status_pembayaran='+$('input[name="status_pembayaran"]:checked').val(),
            "type": "POST"
        },
        "drawCallback": function (response) { 
        // Here the response
          var objData = response.json;
          $('#txt_paid').text(formatMoney(objData.total_paid));
          $('#txt_unpaid').text(formatMoney(objData.total_unpaid));
          $('#txt_cancel').text(formatMoney(objData.total_cancel));
          $('#txt_bill').text(formatMoney(objData.total_billing));
      },
    });

  })


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
      $('#parameter_text').html(result.data);
      oTable.ajax.url($('#dt_pasien_kasir').attr('base-url')+'?'+result.data).load();
  }

  $('select[name="poliklinik"]').change(function () {      
    $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {              
        $('#select_dokter option').remove();       
        $('<option value="">-Pilih Dokter-</option>').appendTo($('#select_dokter'));    
        $.each(data, function (i, o) {       
            $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#select_dokter'));   
        });      
    });    
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
  
    <form class="form-horizontal" method="post" id="form_search" action="eksekutif/Eks_billing_pasien/find_data">
      <!-- hidden form -->
      <div class="row">
          <div class="col-xs-10">
            <span style="font-size: 14px; font-weight: bold">Form Pencarian Data</span><br>
            
            <div class="clearfix"></div>

            <div class="form-group">
              <label class="control-label col-md-2">Penjamin</label>
                <div class="col-md-10">
                  <div class="radio">
                    <label>
                      <input name="flag" type="radio" class="ace" value="all" checked="">
                      <span class="lbl"> Semua</span>
                    </label>

                    <label>
                      <input name="flag" type="radio" class="ace" value="bpjs">
                      <span class="lbl"> BPJS</span>
                    </label>

                    <label>
                      <input name="flag" type="radio" class="ace" value="asuransi">
                      <span class="lbl"> Asuransi</span>
                    </label>

                    <label>
                      <input name="flag" type="radio" class="ace" value="umum">
                      <span class="lbl"> Umum</span>
                    </label>
                  </div>

                </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tipe Pelayanan</label>
                <div class="col-md-10">
                  <div class="radio">
                    <label>
                      <input name="pelayanan" type="radio" class="ace" value="all" checked="">
                      <span class="lbl"> Semua</span>
                    </label>
                    <label>
                      <input name="pelayanan" type="radio" class="ace" value="RJ">
                      <span class="lbl"> Rawat Jalan</span>
                    </label>
                    <label>
                      <input name="pelayanan" type="radio" class="ace" value="RI">
                      <span class="lbl"> Rawat Inap</span>
                    </label>
                  </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-md-2">Poliklinik/Unit Asal</label>
                <div class="col-md-4">
                <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'poliklinik', 'poliklinik', 'form-control', '', '') ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Dokter</label>
                <div class="col-md-4">
                <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'select_dokter', 'select_dokter', 'form-control', '', '') ?>
                </div>
            </div>
            
            <div class="form-group" id="form_tanggal" >
              <label class="control-label col-md-2">Periode tanggal kunjungan</label>
              <div class="col-md-2">
                <div class="input-group">
                  <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
              <label class="control-label col-md-1">s/d Tgl</label>
              <div class="col-md-2">
                <div class="input-group">
                  <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Status Pembayaran</label>
                <div class="col-md-10">
                  <div class="radio">
                    <label>
                      <input name="status_pembayaran" type="radio" class="ace" value="all" checked="">
                      <span class="lbl"> Semua</span>
                    </label>
                    <label>
                      <input name="status_pembayaran" type="radio" class="ace" value="paid">
                      <span class="lbl"> Lunas</span>
                    </label>
                    <label>
                      <input name="status_pembayaran" type="radio" class="ace" value="unpaid">
                      <span class="lbl"> Belum Lunas</span>
                    </label>
                    <label>
                      <input name="status_pembayaran" type="radio" class="ace" value="cancel">
                      <span class="lbl"> Batal Kunjungan</span>
                    </label>
                  </div>
                </div>
            </div>

            <div class="form-group" id="form_tanggal">
              <div class="col-md-2 no-padding">
                <a href="#" id="btn_search_data"  class="btn btn-xs btn-primary">
                  <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                  Tampilkan Data
                </a>
              </div>
            </div>

          </div>

      </div>
      <hr>
      <div style="background: black; padding: 3px; color: white">
        <span style="font-style: italic">parameter data : <span id="parameter_text"></span></span>
      </div>
      <br>
      <table class="table">
        <tr>
          <td>
            <span style="font-size:11px; font-style: italic">A. Total Sudah dibayar :</span><br>
            <span style="font-weight: bold; font-size: 20px">Rp.<span id="txt_paid">0</span>,-</span>
          </td>
          <td>
            <span style="font-size:11px; font-style: italic">B. Total Belum dibayar :</span><br>
            <span style="font-weight: bold; font-size: 20px">Rp.<span id="txt_unpaid">0</span>,-</span>
          </td>
          <td>
            <span style="font-size:11px; font-style: italic">C. Total Kunjungan batal :</span><br>
            <span style="font-weight: bold; font-size: 20px">Rp.<span id="txt_cancel">0</span>,-</span>
          </td>
          <td>
            <span style="font-size:11px; font-style: italic">D. Total Billing :</span><br>
            <span style="font-weight: bold; font-size: 20px">Rp.<span id="txt_bill">0</span>,-</span>
          </td>
        </tr>
      </table>
      <table id="dt_pasien_kasir" base-url="eksekutif/Eks_billing_pasien/get_data" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="50px" class="center">No</th>
            <th>No. MR</th>
            <th>Nama Pasien</th>
            <th>Penjamin</th>
            <th>Billing Unit</th>
            <th>Poli/Klinik Asal</th>
            <th>Dokter</th>
            <th width="150px">Tanggal</th>
            <th width="150px">Status/Sisa Bayar</th>
            <th width="100px">Total Billing</th>
          </tr>
        </thead>
      </table>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




