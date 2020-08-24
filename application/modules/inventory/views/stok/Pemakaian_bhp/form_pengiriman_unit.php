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

  get_header_penerimaan_brg();
  get_detail_barang();

    $('#form_pengiriman_unit').ajaxForm({
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
          
          $('#form_div').hide();
          $('#div_table').show();
          $('#page-area-content').load('purchasing/pendistribusian/Distribusi_permintaan?flag='+jsonResponse.flag+'');
          PopupCenter('purchasing/pendistribusian/Distribusi_permintaan/print_preview/'+jsonResponse.id+'?flag='+jsonResponse.flag+'', 'Cetak Bukti Pengiriman Barang ke Unit', 900, 600);

        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

})

function get_header_penerimaan_brg(){
  preventDefault();
  $('#div_header_penerimaan').load('purchasing/pendistribusian/Pengiriman_unit/show_penerimaan_brg?ID='+$('#id_penerimaan').val()+'&flag='+$('#flag').val()+'');
}

function get_detail_barang(){
  preventDefault();
  $('#daftar_barang_diterima').load('purchasing/pendistribusian/Pengiriman_unit/show_detail_brg?ID='+$('#id_penerimaan').val()+'&flag='+$('#flag').val()+'');
}

</script>
<style type="text/css">
  .dropdown-item{
    height : 100px;
    width: 300px;
  }
</style>
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
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">

          <!-- <a onclick="getMenu('purchasing/pendistribusian/Distribusi_permintaan?flag=<?php echo $string?>')" href="#" class="btn btn-sm btn-success" style="margin-left: -0px">
              <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
              Kembali ke daftar
          </a> -->

          <form class="form-horizontal" method="post" id="form_pengiriman_unit" action="<?php echo site_url('purchasing/pendistribusian/Pengiriman_unit/process')?>" enctype="multipart/form-data" style="margin-top: -10px">
            <br>
            <!-- input form hidden -->
            <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">
            <input type="hidden" name="id_penerimaan" id="id_penerimaan" value="<?php echo $id ; ?>">
            

            <div id="div_header_penerimaan"></div>

            <div class="col-xs-8 no-padding">
              <div id="form_div">

                <div class="form-group">
                  <label class="control-label col-md-3">Nomor Permintaan</label>
                  <div class="col-md-4">
                    <input name="nomor_permintaan" id="nomor_permintaan" value="<?php echo isset($value)?$value->nomor_permintaan:$nomor_permintaan; ?>" placeholder="Auto" class="form-control" type="text" readonly>
                  </div>
                  <label class="control-label col-md-1">Tanggal</label>
                  <div class="col-md-4" style="padding-top: 4px; padding-left: 20px">
                    <?php echo $this->tanggal->formatDate(date('Y-m-d')); ?>
                  </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3">Bagian/Unit</label>
                    <div class="col-md-7">
                    <?php 
                        echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), isset($value)?$value->kode_bagian_minta:'' , 'kode_bagian_minta', 'kode_bagian_minta', 'form-control', '', '') ?>
                    </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Keterangan</label>
                  <div class="col-md-6">
                    <textarea name="catatan" style="height: 50px !important; width: 300px" <?php echo ($flag=='read')?'readonly':''?>></textarea>
                  </div>
                </div>
                
              </div>
            </div>

            <div class="col-xs-4 no-padding"> 
              <blockquote style="font-size: 12px">
                <strong>Keterangan</strong>
                <ol>
                  <li>Pengiriman barang ke unit/depo akan tercatat sebagai permintaan unit</li>
                  <li>Pengiriman barang sudah disetujui dan diketahui oleh kasubag dan verifikator</li>
                </ol>
              </blockquote>
            </div>
            

            <div id="daftar_barang_diterima"></div>

            <hr class="separator">

            <center>
                  <button class="btn btn-sm btn-success" onclick="getMenu('purchasing/penerimaan/Riwayat_penerimaan_brg/view_data?flag=<?php echo $flag?>')">Kembali ke Riwayat</button>
                  <button class="btn btn-sm btn-primary" type="submit" id="btnSave">Submit Pengiriman Barang</button>
            </center>
            
          </form>
        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


