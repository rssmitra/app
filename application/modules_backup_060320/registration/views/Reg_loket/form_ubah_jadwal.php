<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-datetimepicker.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<script type="text/javascript">

  $('#form_update_jadwal_dokter').ajaxForm({
    beforeSend: function() {
      achtungShowLoader();  
      //alert('tes');
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);

      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
        /*modal hide*/
        $("#modalUbahJadwal").modal('hide');
        $("#ModalSuccess").modal();
        /*reload table*/
        reload_table();
      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }
  });

  $('input[name="tipe_reschedule"]').click(function (e) {
    var value = $(this).val();
    if (value!='jam_praktek') {
      $('#jam_praktek').hide('fast');

      $.getJSON("<?php echo site_url('Templates/References/getDokterSpesialis') ?>/"  + value, '', function (data) {              

        $('#dokter option').remove();                

        $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter'));                

        $.each(data, function (i, o) {                  

            $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter'));                    

        });                

      });      

      $('#ganti_dokter').show('fast');


    }

    if (value=='jam_praktek') {
      $('#jam_praktek').show('fast');
      $('#ganti_dokter').hide('fast');
    }

  }); 

  
        

</script>

<form class="form-horizontal" method="post" id="form_update_jadwal_dokter" action="<?php echo base_url().'registration/reg_loket/process_update_jadwal'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">

    <!-- hidden form -->
    <input type="hidden" name="jd_id" id="ID" value="<?php echo $jadwal->jd_id?>">

    <h4> <?php echo ucwords($jadwal->nama_bagian) ?> <small style="font-size:11px !important"><i class="ace-icon fa fa-angle-double-right"></i> <?php echo ucwords($jadwal->nama_pegawai) ?></small> </h4>
    <hr>
      <div class="form-group">

        <label class="control-label col-md-3">Reschedule</label>

        <div class="col-md-5">

          <div class="radio">

              <label>

                <input name="tipe_reschedule" type="radio" class="ace" value="jam_praktek" checked="checked"/>

                <span class="lbl"> Jam Praktek </span>

              </label>

              <label>

                <input name="tipe_reschedule" type="radio" class="ace" value="<?php echo isset($jadwal)?$jadwal->jd_kode_spesialis:''?>"/>

                <span class="lbl"> Ganti Dokter </span>

              </label>

          </div>

        </div>

      </div>

      <div id="jam_praktek">

        <div class="form-group">

          <label class="control-label col-sm-3">Pindah ke Jam</label>
          <div class="col-md-2">
            <input name="start" id="start" placeholder="" class="form-control" type="text" >
          </div>

          <label class="control-label col-md-2">s/d</label>
          <div class="col-md-2">
            <input name="end" id="end" placeholder="" class="form-control" type="text" >
          </div>

        </div>

      </div>

      <div id="ganti_dokter" style="display:none">

        <div class="form-group">
          <label class="control-label col-sm-3" for="City">Dokter Pengganti</label>
          <div class="col-sm-6">
              <?php echo $this->master->get_change($params = array('table' => 'mt_karyawan', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), isset($value->jd_kode_dokter)?$value->jd_kode_dokter:'' , 'dokter', 'dokter', 'form-control', '', '') ?>
          </div>
        </div>

        <div class="form-group">

          <label class="control-label col-sm-3">Jam</label>
          <div class="col-md-2">
            <input name="start_pengganti" id="start" placeholder="" class="form-control" type="text" >
          </div>

          <label class="control-label col-md-2">s/d</label>
          <div class="col-md-2">
            <input name="end_pengganti" id="end" placeholder="" class="form-control" type="text" >
          </div>

        </div>

        <div class="form-group">

          <label class="control-label col-sm-3">Kuota</label>
          <div class="col-md-2">
            <input name="kuota" id="kuota" placeholder="" class="form-control" type="number" >
          </div>

        </div>

      </div>

      <div class="form-group">

        <label class="control-label col-sm-3">Keterangan</label>
        <div class="col-md-6">
          <textarea name="keterangan" class="form-control" style="height:50px !important" ></textarea>
        </div>

      </div>

      <div class="form-actions center">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Close</button>
        <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
          <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
          Submit
        </button>
      </div>

</form>
