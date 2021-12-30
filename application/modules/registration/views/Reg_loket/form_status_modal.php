<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-datetimepicker.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<script type="text/javascript">
  
  $(document).ready(function(){
  
    $('#form_update_status_loket').ajaxForm({
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
          /*modal hide*/
          $("#modalUbahStatusLoket").modal('hide');
          $("#ModalSuccess").modal();
          /*reload table*/
          reload_table();
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    });

    $('select[name="status"]').change(function () {    
      if( $(this).val()=='Reschedule' ){
        $('#reschedule_field').show('fast');
      }else{
        $('#reschedule_field').hide('fast');
      }
    }); 


})

  
        

</script>

<form class="form-horizontal" method="post" id="form_update_status_loket" action="<?php echo base_url().'registration/reg_loket/process_update_loket'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">

    <!-- hidden form -->
    <input type="hidden" name="jd_id" id="ID" value="<?php echo $jadwal->jd_id?>">

    <h4> <?php echo ucwords($jadwal->nama_bagian) ?> <small style="font-size:11px !important"><i class="ace-icon fa fa-angle-double-right"></i> <?php echo ucwords($jadwal->nama_pegawai) ?></small> </h4>
    <hr>
    <div class="form-group">
      <label class="control-label col-md-3">Loket Pendaftaran</label>
      <div class="col-md-4">
        <div class="col-xs-3" style="margin-left:-5%">
          <label>
            <input name="status_loket" class="ace ace-switch ace-switch-7" type="checkbox" <?php $status_loket = ($jadwal->status_loket=='on')?'checked':''; echo $status_loket; ?> >
            <span class="lbl"></span>
          </label>
        </div>
      </div>
      <div class="col-md-5"><small><b>"ON"</b> Loket dibuka, <b>"OFF"</b> Loket ditutup</small></div>
      
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Status</label>
      <div class="col-md-4">
        <select name="status" class="form-control" id="status">
          <option value="">-Silahkan Pilih-</option>
          <option value="Loket dibuka" <?php echo ($jadwal->status_jadwal=='Loket dibuka')?'selected':'' ?> >Loket Dibuka</option>
          <option value="Reschedule" <?php echo ($jadwal->status_jadwal=='Reschedule')?'selected':'' ?> >Reschedule</option>
          <option value="Sedang Praktek" <?php echo ($jadwal->status_jadwal=='Sedang Praktek')?'selected':'' ?> >Sedang Praktek</option>
          <option value="Dengan Perjanjian" <?php echo ($jadwal->status_jadwal=='Dengan Perjanjian')?'selected':'' ?> >Dengan Perjanjian
          <option value="Tidak Praktek" <?php echo ($jadwal->status_jadwal=='Tidak Praktek')?'selected':'' ?> >Tidak Praktek
        </select>
      </div>
    </div>

    <div class="form-group" id="reschedule_field" style="display:none">
      <label class="control-label col-md-3">Reschedule to</label>
      <div class="col-md-4">
        <div class="input-group bootstrap-timepicker">
            <input id="timepicker1" name="reschedule_to" type="text" class="form-control">
            <span class="input-group-addon">
              <i class="fa fa-clock-o bigger-110"></i>
            </span>
          </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Keterangan</label>
      <div class="col-md-4">
        <textarea name="keterangan" class="form-control" style="height:50px !important"></textarea>
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
