<script type="text/javascript">

    $('select[name="cara_keluar"]').change(function () {      

        if ($(this).val() == 'Rujuk ke Poli Lain') {        
            $('#poli_dirujuk').show('fast');
        }else{
            $('#poli_dirujuk').hide('fast');
        }
    }); 
</script>
<div class="row" style="padding:8px">
    <div class="col-sm-12">
        <p><b><i class="fa fa-edit"></i> SELESAIKAN KUNJUNGAN PASIEN </b></p>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Cara Keluar Pasien</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'cara_keluar')), 'Atas Persetujuan Dokter' , 'cara_keluar', 'cara_keluar', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group" id="poli_dirujuk" style="display:none">
            <label class="control-label col-sm-2" for="">Rujuk ke Poli-</label>
            <div class="col-sm-5">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'rujukan_tujuan', 'rujukan_tujuan', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Pasca Pulang</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'pasca_pulang')), 'Dalam Masa Pengobatan' , 'pasca_pulang', 'pasca_pulang', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
               <button type="button" class="btn btn-xs btn-danger" id="btn_hide_" onclick="backToDefaultForm()"> <i class="fa fa-angle-double-left"></i> Sembunyikan </button>
               <button type="submit" class="btn btn-xs btn-primary" id="btn_submit_selesai"> <i class="fa fa-save"></i> Submit </button>
            </div>
        </div>

    </div>

</div>





