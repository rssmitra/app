<script type="text/javascript">

    $('select[name="pasca_pulang"]').change(function () {      

        if ($(this).val() == 'Meninggal') {        
            $('#kode_kematian').show('fast');
        }else{
            $('#kode_kematian').hide('fast');
        }
    }); 

    $('#btn_pasien_pulang').click(function (e) {  
      e.preventDefault();
      /*process pasien pulang*/
      $.ajax({
          url: "adm_pasien/loket_kasir/Adm_kasir_ri/processPelayananSelesai",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              $.achtung({message: response.message, timeout:5});
              $("#page-area-content").load("adm_pasien/loket_kasir/Adm_kasir_ri/form/"+$('#kode_ri').val()+"/"+$('#no_kunjungan').val()+"");
            }else{
              $.achtung({message: response.message, timeout:5});
            }
            
          }
      });

    });
</script>
<div class="row" style="padding:8px">
    <div class="col-sm-12">
        <p><b><i class="fa fa-edit"></i> SELESAIKAN KUNJUNGAN PASIEN </b></p>

        <input type="hidden" value="Atas Persetujuan Dokter" name="cara_keluar" id="cara_keluar">

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Pasca Pulang</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'pasca_pulang')), '' , 'pasca_pulang', 'pasca_pulang', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group" id="kode_kematian" style="display:none">
            <label class="control-label col-sm-2" for="">Kode Kematian</label>
            <div class="col-sm-5">
                <input type="text" name="pl_kode_kematian" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
               <button type="button" class="btn btn-xs btn-danger" id="btn_hide_" onclick="backToDefaultForm()"> <i class="fa fa-angle-double-left"></i> Sembunyikan </button>
               <!-- <button type="submit" class="btn btn-xs btn-primary" id="btn_submit_selesai"> <i class="fa fa-save"></i> Submit </button> -->
               <a href="#" class="btn btn-xs btn-primary" id="btn_pasien_pulang"><i class="fa fa-save"></i> Submit </a>
            </div>
        </div>

    </div>

</div>





