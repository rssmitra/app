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

$('select[name="bagian"]').change(function () {  
    
    if ( $(this).val() != '070101' ) {     
      // show hide section
      $('#section_medis').show();
      $('#section_non_medis').hide();  
      /*flag string*/
      $('#flag_string').val('medis');

    } else {    
      $('#section_non_medis').show();
      $('#section_medis').hide();
      /*flag string*/
      $('#flag_string').val('non_medis');
    }    
});

$('select[name="kode_golongan"]').change(function () {  
    /*flag string*/
    flag_string = $('#flag_string').val();
    if ( $(this).val() ) {     
      
        $.getJSON("<?php echo site_url('Templates/References/getSubGolongan') ?>/" + $(this).val() + '?flag=' +flag_string, '', function (data) {   
            $('#kode_sub_gol option').remove();         
            $('<option value="">-Pilih Sub Golongan-</option>').appendTo($('#kode_sub_gol'));  
            $.each(data, function (i, o) {   
                $('<option value="' + o.kode_sub_gol + '">' + o.nama_sub_golongan.toUpperCase() + '</option>').appendTo($('#kode_sub_gol'));  
            });   
        });   
    } else {    
        $('#kode_sub_gol option').remove();
    }    
});

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

     <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data" target="blank">
      <br>

      <input type="hidden" name="flag" value="so_mod_1">
      <input type="hidden" name="title" value="Daftar Barang Yang Akan di Stok Opname">
      <input type="hidden" name="flag_string" id="flag_string" value="non_medis">

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Terakhir Stok</label>
        <div class="col-md-2">
          <div class="input-group">
            <input class="form-control date-picker" name="tgl_stok" id="tgl_stok" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Bagian Unit</label>
          <div class="col-md-4">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1)), '070101' , 'bagian', 'bagian', 'form-control', '', '') ?>
          </div>
      </div>

      <!-- hidden form gudang non medis-->
      <div id="section_non_medis">
        <div class="form-group">
          <label class="control-label col-md-2">Golongan</label>
          <div class="col-md-3">
              <?php 
                echo $this->master->custom_selection($params = array('table' => 'mt_golongan_nm', 'id' => 'kode_golongan', 'name' => 'nama_golongan', 'where' => array()), '' , 'kode_golongan', 'kode_golongan', 'form-control', '', '') ?>
          </div>
          <label class="control-label col-md-2">Sub Golongan</label>
          <div class="col-md-3">
              <?php 'mt_sub_golongan_nm' ;
                echo $this->master->get_change($params = array('table' => 'mt_sub_golongan_nm', 'id' => 'kode_sub_gol', 'name' => 'nama_sub_golongan', 'where' => array()),  '' , 'kode_sub_gol', 'kode_sub_gol', 'form-control', '', '') ?>
          </div>
        </div>
      </div>
      
      <div id="section_medis" style="display: none">

        <div class="form-group">
          <label class="control-label col-md-2">Kategori</label>
          <div class="col-md-2">
              <?php 
                echo $this->master->custom_selection($params = array('table' => 'mt_kategori', 'id' => 'kode_kategori', 'name' => 'nama_kategori', 'where' => array()), '' , 'kode_kategori', 'kode_kategori', 'form-control', '', '') ?>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Layanan</label>
          <div class="col-md-3">
          <?php 
              echo $this->master->custom_selection($params = array('table' => 'mt_layanan_obat', 'id' => 'kode_layanan', 'name' => 'nama_layanan', 'where' => array()), '' , 'kode_layanan', 'kode_layanan', 'form-control', '', '') ?>
          </div>
          <label class="control-label col-md-2">Jenis Obat</label>
          <div class="col-md-3">
          <?php 
              echo $this->master->custom_selection($params = array('table' => 'mt_jenis_obat', 'id' => 'kode_jenis', 'name' => 'nama_jenis', 'where' => array()), '' , 'jenis_obat', 'jenis_obat', 'form-control', '', '') ?>
          </div>
        </div>
        
      </div>
      
      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10" style="margin-left: 5px">
          <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
            Proses Pencarian
          </button>
          <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
            Export Excel
          </button>
        </div>
      </div>
      <br>
      <p><b>DOWNLOAD FORMAT FORM STOK OPNAME</b></p>
      <hr>
      <button type="submit" name="submit" value="format_so" class="btn btn-xs btn-primary">
        Download Format 1
      </button>

      <button type="submit" name="submit" value="format_so_2" class="btn btn-xs btn-danger">
        Download Format 2
      </button>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



