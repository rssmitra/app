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

$(document).ready(function(){
  
    $('#form_info_jadwal_dr').ajaxForm({
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
          $('#page-area-content').load('information/regon_info_jadwal_dr?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    $('select[name="spesialis"]').change(function () {      

        /*hide first*/
        $('#show_detail_praktek').hide('fast');
        $('#tgl_kunjungan_form').hide('fast');
        $('#view_last_message').hide('fast');
        $('#show_jadwal_dokter').hide('fast');
        $('#tgl_kunjungan').val('');

        if ($(this).val()) {          

            $.getJSON("<?php echo site_url('Templates/References/getDokterSpesialis') ?>/" + $(this).val(), '', function (data) {              

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

    $('#btn_ubah_form').click(function (e) { 
       
    });


})

function btn_delete(id, day){
  $(".class_form_"+id+"_"+day+"").prop("readonly", true); 
  $("#btn_update_"+id+"_"+day+"").hide('fast');
  $("#btn_submit_"+id+"_"+day+"").hide('fast');
  $("#btn_batal_"+id+"_"+day+"").show('fast');
  $("#curr_delete_"+id+"_"+day+"").val(id);
}

function update(id, day){
  $(".class_form_"+id+"_"+day+"").prop("readonly", false); 
  $("#btn_submit_"+id+"_"+day+"").show('fast');
  $("#btn_batal_"+id+"_"+day+"").show('fast');
}

function cancel(id, day){
  $(".class_form_"+id+"_"+day+"").prop("readonly", true); 
  $("#btn_submit_"+id+"_"+day+"").hide('fast');
  $("#btn_batal_"+id+"_"+day+"").hide('fast');
  $("#btn_update_"+id+"_"+day+"").show('fast');
  $('#curr_edit_'+id+'_'+day+'').val('');
  $("#curr_delete_"+id+"_"+day+"").val('');
}

function submit(id, day){

  var post_data = {
    id:$('#jd_id_'+id+'_'+day+'').val(),
    day:$('#jd_hari_'+id+'_'+day+'').val(),
    start:$('#start_'+id+'_'+day+'').val(),
    end:$('#end_'+id+'_'+day+'').val(),
    kuota:$('#kuota_dr_'+id+'_'+day+'').val(),
    curr_edit_:$('#curr_edit_'+id+'_'+day+'').val(1),
  };

  if( id == 0 ){

    $("#btn_delete_"+id+"_"+day+"").show('fast');

    $("#btn_update_"+id+"_"+day+"").show('fast');

    $("#btn_submit_"+id+"_"+day+"").hide('fast');

    $("#btn_batal_"+id+"_"+day+"").hide('fast');

  }else{

    $("#btn_batal_"+id+"_"+day+"").hide('fast');

    $("#btn_submit_"+id+"_"+day+"").hide('fast');

  }

  $("#curr_edit_"+id+"_"+day+"").val(1);
  
  $(".class_form_"+id+"_"+day+"").prop("readonly", true); 
  

}

</script>
<style type="text/css">

  input[type=checkbox].ace:checked + .lbl::before {
    display: inline-block;
    /*content: '\f00c';*/
    background-color: red !important;
    border-color: #adb8c0;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05), inset 15px 10px -12px rgba(255, 255, 255, 0.1);
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
              <form class="form-horizontal" method="post" id="form_info_jadwal_dr" action="<?php echo site_url('information/regon_info_jadwal_dr/process')?>" enctype="multipart/form-data">
                <br>

                <div class="form-group">
                   <label class="control-label col-sm-1" for="Province">*Spesialis</label>
                   <div class="col-sm-5">
                        <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), isset($value->jd_kode_spesialis)?$value->jd_kode_spesialis:'' , 'spesialis', 'spesialis', 'form-control', '', '') ?>

                    </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-1" for="City">*Dokter</label>
                  <div class="col-sm-4">
                      <?php echo $this->master->get_change($params = array('table' => 'mt_karyawan', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), isset($value->jd_kode_dokter)?$value->jd_kode_dokter:'' , 'dokter', 'dokter', 'form-control', '', '') ?>
                  </div>
                </div>

                <p style="margin-top:5px"><b><i class="fa fa-calendar"></i> JADWAL PRAKTEK </b></p>

                <?php 
                  for ($i=1; $i < 8; $i++) : 
                    $day_lib = $this->tanggal->getDayByNum($i);
                  if(isset($jadwal)){
                    $key = array_search($day_lib, array_column($jadwal, 'jd_hari'));
                    if (isset($key)) {
                      if ($day_lib==$jadwal[$key]['jd_hari']) {
                          $id = $jadwal[$key]['jd_id'];
                          $start = $this->tanggal->formatTime($jadwal[$key]['jd_jam_mulai']);
                          $end = $this->tanggal->formatTime($jadwal[$key]['jd_jam_selesai']);
                          $keterangan = $jadwal[$key]['jd_keterangan'];
                          $kuota = $jadwal[$key]['jd_kuota'];
                          $checked = 'checked';
                          $disabled = 'readonly';
                      }else{
                          $id = 0;
                          $start = '';
                          $end = '';
                          $keterangan = '';
                          $kuota = '';
                          $checked = '';
                          $disabled = '';
                      }
                    }
                    
                  }

                ?>

                  <input name="jd_hari[]" id="jd_hari_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo $day_lib?>" placeholder="" class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="hidden" >

                  <input name="curr_edit[]" id="curr_edit_<?php echo $id?>_<?php echo $day_lib?>" value="" placeholder="" class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="hidden" >

                  <input name="delete[<?php echo $day_lib?>]" id="curr_delete_<?php echo $id?>_<?php echo $day_lib?>" type="hidden" class="ace custom-checkbox" value=""  >

                  <div class="form-group">
                    <label class="control-label col-sm-1">ID</label>
                    <div class="col-md-1" style="margin-left:-5%">
                      <input name="jd_id[]" id="jd_id_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo isset($id)?$id:0?>" placeholder="" class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" readonly style="width:55px">
                    </div>

                    <label class="control-label col-sm-1" style="margin-left:-1.5%"><?php echo $day_lib?></label>
                    <div class="col-md-1" style="margin-left:-1.5%">
                      <input name="start[]" id="start_"<?php echo $id?>_<?php echo $day_lib?> value="<?php echo isset($start)?$start:''?>" placeholder="" <?php echo $disabled?> class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" >
                    </div>

                    <label class="control-label col-md-1">s/d</label>
                    <div class="col-md-1" style="margin-left:-5%">
                      <input name="end[]" id="end_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo isset($end)?$end:''?>" placeholder="" <?php echo $disabled?> class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" >
                    </div>

                    <label class="control-label col-md-1">Keterangan</label>
                    <div class="col-md-2" style="margin-left:-1%">
                      <input name="keterangan[]" id="keterangan_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo isset($keterangan)?$keterangan:''?>" placeholder="" <?php echo $disabled?> class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" style="width:200px">
                    </div>

                    <label class="control-label col-md-1" style="margin-left: 4.5%">Kuota dr</label>
                    <div class="col-md-1" style="margin-left:-2%">
                      <input name="kuota_dr[]" id="kuota_dr_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo isset($kuota)?$kuota:''?>" placeholder="" <?php echo $disabled?> class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" >
                    </div>

                  <div class="col-md-2" style="margin-left:-2%">
                    <div class="checkbox" >
                      <label>
                        <a href="#" id="btn_delete_<?php echo $id;?>_<?php echo $day_lib;?>" <?php echo ($id!=0)?'':'style="display:none"'?> onclick="btn_delete(<?php echo $id?>,'<?php echo $day_lib?>')" class="btn btn-xs btn-danger"><i class="fa fa-times-circle"></i></a>

                        <a href="#" id="btn_update_<?php echo $id;?>_<?php echo $day_lib;?>" <?php echo ($id!=0)?'':'style="display:none"'?> onclick="update(<?php echo $id?>,'<?php echo $day_lib?>')" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>

                        <a href="#" id="btn_submit_<?php echo $id;?>_<?php echo $day_lib;?>" <?php echo ($id==0)?'':'style="display:none"'?> onclick="submit(<?php echo $id?>,'<?php echo $day_lib?>')" class="btn btn-xs btn-primary"><i class="fa fa-check"></i></a>

                        <a href="#" id="btn_batal_<?php echo $id;?>_<?php echo $day_lib;?>" style="display:none" onclick="cancel(<?php echo $id?>,'<?php echo $day_lib?>')" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i></a>
                      </label>

                    </div>
                  </div>

                  </div>

                <?php endfor;?>


                <div class="form-actions center">

                  <!--hidden field-->
                  <input type="hidden" name="flag" value="<?php echo isset($flag)?$flag:''?>">

                  <a onclick="getMenu('information/regon_info_jadwal_dr')" href="#" class="btn btn-sm btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                  </a>
                  <?php if($flag != 'read'):?>
                  <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                    <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                    Reset
                  </button>
                  <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                  </button>
                <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


