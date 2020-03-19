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
  
    $('#form_permintaan').ajaxForm({
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
          $('#page-area-content').load('purchasing/permintaan/Req_pembelian?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 
    
    $('select[name="jenis_pendaftaran"]').change(function () {      

        showChangeModul( $(this).val() );        

    });

    $('select[name=metode_pembayaran]').change(function () {
      if( $(this).val()==1 ){
        $('#div_tunai').show();
        $('#div_debet').hide();
        $('#div_kredit').hide();
      }

      if( $(this).val()==2 ){
        $('#div_tunai').hide();
        $('#div_debet').show();
        $('#div_kredit').hide();
      }

      if( $(this).val()==3 ){
        $('#div_tunai').hide();
        $('#div_debet').hide();
        $('#div_kredit').show();
      }

    });

})

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
              <form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/permintaan/Req_pembelian/process')?>" enctype="multipart/form-data" >
                
                <div class="form-group">
                  <label class="control-label col-md-2">No. Rekam Medis</label>
                  <div class="col-md-3" style="padding-top:4px; margin-left:7px;">
                    <?php echo $result->reg_data->no_mr?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Nama Pasien</label>
                  <div class="col-md-3" style="padding-top:4px; margin-left:7px;">
                    <?php echo $result->reg_data->nama_pasien?>
                  </div>

                  <label class="control-label col-md-1">JK</label>
                  <div class="col-md-2" style="padding-top:4px; margin-left:7px;">
                    <?php echo $result->reg_data->jk?>
                  </div>

                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Poli/Klinik</label>
                  <div class="col-md-3" style="padding-top:4px; margin-left:7px;">
                    <?php echo $result->reg_data->bagian_masuk_field?>
                  </div>
                  <label class="control-label col-md-1">Dokter</label>
                  <div class="col-md-2" style="padding-top:4px; margin-left:7px;">
                    <?php echo $result->reg_data->nama_pegawai?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Umur Saat Pelayanan</label>
                  <div class="col-md-4" style="padding-top:4px; margin-left:7px;">
                    <?php echo $result->reg_data->umur?> (Thn)
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Penjamin</label>
                  <div class="col-md-4" style="padding-top:4px; margin-left:7px;">
                    <?php echo $result->reg_data->nama_perusahaan?>
                  </div>
                </div>

                <hr class="separator">
                <p><b>DATA PEMBAYARAN</b></p>
                <div class="form-group">
                  <label class="control-label col-md-2">Pembayar (a.n)</label>
                  <div class="col-md-3">
                    <input name="pembayar" id="pembayar" value="<?php echo $result->reg_data->nama_pasien?>" class="form-control" type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Metode Pembayaran</label>
                  <div class="col-md-2">
                     <select class="form-control" name="metode_pembayaran">
                        <option>-Silahkan Pilih-</option>
                        <option value="1" selected>Tunai/ Cash</option>
                        <option value="2">Kartu Debit</option>
                        <option value="3">Kartu Kredit</option>
                      </select>
                  </div>
                </div>

                <hr class="separator">
                <div id="div_tunai">
                    <p><b>PEMBAYARAN TUNAI</b></p>
                    <div class="form-group">
                      <label class="control-label col-md-2">Jumlah Pembayaran</label>
                      <div class="col-md-2">
                        <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-2">Uang Yang Dibayarkan</label>
                      <div class="col-md-2">
                        <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-2">Pengembalian Uang</label>
                      <div class="col-md-2">
                        <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                      </div>
                    </div>
                </div>
                
                <div id="div_debet" style="display:none">
                  <p><b>PEMBAYARAN KARTU DEBET</b></p>
                  <div class="form-group">
                    <label class="control-label col-md-2">Jumlah Pembayaran</label>
                    <div class="col-md-2">
                      <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Kartu Debit</label>
                    <div class="col-md-4">
                      <?php echo $this->master->custom_selection_with_label($params = array('table' => 'mt_bank', 'id' => 'acc_no', 'name' => 'nama_bank', 'where' => array() ), '' , 'bank', 'bank', 'form-control', '', '') ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Nomor Kartu</label>
                    <div class="col-md-2">
                      <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Nomor Batch</label>
                    <div class="col-md-2">
                      <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                    </div>
                  </div>
                </div>
                

                <div id="div_kredit" style="display:none">
                  <p><b>PEMBAYARAN KARTU KREDIT</b></p>
                  <div class="form-group">
                    <label class="control-label col-md-2">Jumlah Pembayaran</label>
                    <div class="col-md-2">
                      <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Kartu Kredit</label>
                    <div class="col-md-4">
                      <?php echo $this->master->custom_selection_with_label($params = array('table' => 'mt_bank', 'id' => 'acc_no', 'name' => 'nama_bank', 'where' => array() ), '' , 'bank', 'bank', 'form-control', '', '') ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Nomor Kartu</label>
                    <div class="col-md-2">
                      <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Nomor Batch</label>
                    <div class="col-md-2">
                      <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                    </div>
                  </div>
                </div>

                <hr class="separator">
                <p><b>NOTA KREDIT PERUSAHAAN</b></p>
                <div class="form-group">
                  <label class="control-label col-md-2">Jumlah</label>
                  <div class="col-md-2">
                    <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2">Diskon</label>
                  <div class="col-md-2">
                    <input name="pembayar" id="pembayar" value="" class="form-control" type="text">
                  </div>
                </div>

                <div class="form-actions center">
                <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                  <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                  Batal
                </button>
                <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                  <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                  Submit
                </button>
              </div>

            </form>
          </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


