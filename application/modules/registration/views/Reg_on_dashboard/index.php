<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
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

$('select[name="klinik"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian') ?>/" + $(this).val() , function (data) {              

            $('#dokter option').remove();                

            $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter'));                

            $.each(data, function (i, o) {                  

                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter'));                    

            });                

        });            

    } else {          

        $('#dokter option').remove()            

    }        

}); 

function showModalDaftarPerjanjian(booking_id, no_mr)

{  

  $('#result_text_daftar_perjanjian').text('DAFTAR PERJANJIAN PASIEN NO MR ('+no_mr+')');

  $('#form_daftar_perjanjian_pasien_modal').load('registration/reg_pasien/form_reschedule_modal/'+no_mr+'?ID='+booking_id); 

  $("#modalDaftarPerjanjian").modal();
    
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

    <form class="form-horizontal" method="post" id="form_search" action="registration/Reg_on_dashboard/find_data">

    <div class="col-md-12">

      <center><h4>FORM PENCARIAN DATA REGISTRASI ONLINE<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>

      <div class="form-group">

          <label class="control-label col-md-2">*Poli/Klinik</label>

          <div class="col-md-4">

              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100,) ), '' , 'klinik', 'klinik', 'form-control', '', '') ?>

          </div>

      </div>

      <div class="form-group">
          <label class="control-label col-md-2">*Nama Dokter</label>

          <div class="col-md-4">

              <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'dokter', 'dokter', 'form-control', '', '') ?>

          </div>

      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Booking</label>
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
        <div class="col-md-10">
          &nbsp;
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Excel
          </a>
        </div>
      </div>

    </div>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="registration/Reg_on_dashboard" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th></th>
          <th>Kode</th>
          <th>Data Pasien</th>
          <th>Tanggal Perjanjian</th>
          <th>Tujuan Poli</th>
          <th>Nama Dokter</th>
          <th>Jam Praktek</th>
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


<div id="modalDaftarPerjanjian" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">PERJANJIAN PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_daftar_perjanjian_pasien_modal"></div>

      </div>

      </div> -->

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



