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

var formVerified = false;

$(document).ready(function(){

    $('#form_info_jadwal_dr').ajaxForm({
      beforeSubmit: function() {
        if (!formVerified) {
          // Show verification modal before submit
          $('#modal-verifikasi-form .modal-title').text('Verifikasi Proses Jadwal Dokter');
          $('#verif_password_form').val('');
          $('#verif_kode_form').val('');
          $('#modal-verifikasi-form').modal('show');
          return false; // prevent form submission
        }
        achtungShowLoader();
        return true;
      },
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
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
        formVerified = false; // reset after submission
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

function submitVerifikasiForm() {
    var password = $('#verif_password_form').val();
    var kodeVerifikasi = $('#verif_kode_form').val();
    if (!password) {
        alert('Password harus diisi!');
        $('#verif_password_form').focus();
        return;
    }
    if (!kodeVerifikasi) {
        alert('Kode verifikasi harus diisi!');
        $('#verif_kode_form').focus();
        return;
    }

    $.ajax({
        url: '<?php echo site_url("information/Regon_info_jadwal_dr/verify_code")?>',
        type: 'POST',
        data: {
            password: password,
            kode_verifikasi: kodeVerifikasi
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 200) {
                $('#modal-verifikasi-form').modal('hide');
                formVerified = true;
                // Re-trigger form submit after verification
                $('#form_info_jadwal_dr').submit();
            } else {
                alert(response.message || 'Password atau kode verifikasi salah!');
            }
            $('#verif_password_form').val('');
            $('#verif_kode_form').val('');
        },
        error: function() {
            alert('Terjadi kesalahan pada server.');
        }
    });
}

// Toggle show/hide password
$(document).on('mousedown', '#toggle-verif-password-form', function() {
    $('#verif_password_form').attr('type', 'text');
    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
});
$(document).on('mouseup mouseleave', '#toggle-verif-password-form', function() {
    $('#verif_password_form').attr('type', 'password');
    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
});
$(document).on('mousedown', '#toggle-verif-kode-form', function() {
    $('#verif_kode_form').attr('type', 'text');
    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
});
$(document).on('mouseup mouseleave', '#toggle-verif-kode-form', function() {
    $('#verif_kode_form').attr('type', 'password');
    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
});

$('#modal-verifikasi-form').on('show.bs.modal', function () {
    setTimeout(function() {
        $('.modal-backdrop').addClass('verifikasi-form-backdrop');
    }, 10);
});
$('#modal-verifikasi-form').on('hidden.bs.modal', function () {
    $('.modal-backdrop').removeClass('verifikasi-form-backdrop');
});

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

<style>
  /* Page header */
  .page-header-idx {
    border-bottom: 3px solid #2c6fad;
    padding-bottom: 8px;
    margin-bottom: 18px;
  }
  .page-header-idx h1 {
    font-size: 20px;
    color: #1a4f8a;
    font-weight: 700;
    margin: 0;
  }
  .page-header-idx h1 small {
    font-size: 13px;
    color: #888;
    font-weight: 400;
  }

  /* Form card */
  .frm-card {
    border: 1px solid #c0d4e8;
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 12px;
  }
  .frm-card-hdr {
    background: #1a4f8a;
    color: #fff;
    padding: 9px 16px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .frm-card-hdr small {
    font-weight: 400;
    opacity: .85;
  }
  .frm-card-body {
    padding: 16px 18px;
    background: #fff;
  }
  .frm-actions {
    padding: 10px 18px;
    background: #f8fafd;
    border-top: 1px solid #d0dce8;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
  }

  /* Schedule table */
  .jadwal-tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
  }
  .jadwal-tbl thead tr {
    background: #2c6fad;
    color: #fff;
  }
  .jadwal-tbl thead th {
    padding: 8px 10px;
    text-align: center;
    font-weight: 600;
    border: 1px solid #1e5590;
    font-size: 12px;
  }
  .jadwal-tbl tbody td {
    padding: 6px 8px;
    border: 1px solid #d0dce8;
    vertical-align: middle;
  }
  .jadwal-tbl tbody tr:nth-child(even) {
    background: #f5f9fd;
  }
  .jadwal-tbl tbody tr:hover {
    background: #eef4fb;
  }
  .jadwal-tbl .form-control {
    font-size: 12px;
    height: 30px;
    padding: 4px 8px;
  }
  .jadwal-tbl .day-label {
    font-weight: 600;
    color: #1a4f8a;
    white-space: nowrap;
  }
  .jadwal-tbl .btn-xs {
    padding: 3px 7px;
    font-size: 11px;
  }

  /* Delete highlight */
  input[type=checkbox].ace:checked + .lbl::before {
    display: inline-block;
    background-color: red !important;
    border-color: #adb8c0;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05), inset 15px 10px -12px rgba(255, 255, 255, 0.1);
  }

  /* Verification modal */
  .modal-backdrop.verifikasi-form-backdrop {
    background-color: #222 !important;
    opacity: 0.85 !important;
  }
  #modal-verifikasi-form .modal-dialog {
    margin-top: 10vh;
    max-width: 400px;
  }
  #modal-verifikasi-form .modal-content {
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    border: none;
  }
  #modal-verifikasi-form .modal-header {
    border-bottom: 1px solid #eee;
    background: #f7f7f7;
    border-radius: 10px 10px 0 0;
    padding: 16px 24px 12px 24px;
  }
  #modal-verifikasi-form .modal-title {
    font-weight: bold;
    font-size: 18px;
  }
  #modal-verifikasi-form .modal-body {
    padding: 20px 24px 10px 24px;
  }
  #modal-verifikasi-form .form-group label {
    font-weight: 500;
    margin-bottom: 8px;
  }
  #modal-verifikasi-form .form-control {
    border-radius: 6px;
    font-size: 12px;
  }
  #modal-verifikasi-form .modal-footer {
    border-top: 1px solid #eee;
    padding: 12px 24px 16px 24px;
    border-radius: 0 0 10px 10px;
    background: #f7f7f7;
  }
</style>

<!-- Page Header -->
<div class="page-header-idx">
  <h1>
    <i class="fa fa-calendar-check-o" style="color:#2c6fad"></i>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_info_jadwal_dr" action="<?php echo site_url('information/regon_info_jadwal_dr/process')?>" enctype="multipart/form-data">

      <!-- Card: Informasi Dokter -->
      <div class="frm-card">
        <div class="frm-card-hdr">
          <i class="fa fa-user-md"></i> Informasi Dokter
        </div>
        <div class="frm-card-body">

          <div class="form-group" style="margin-bottom:10px">
            <label class="control-label col-sm-2">Spesialis <span style="color:#d9534f">*</span></label>
            <div class="col-sm-5">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), isset($value->jd_kode_spesialis)?$value->jd_kode_spesialis:'' , 'spesialis', 'spesialis', 'form-control', '', '') ?>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:0">
            <label class="control-label col-sm-2">Dokter <span style="color:#d9534f">*</span></label>
            <div class="col-sm-4">
              <?php echo $this->master->get_change($params = array('table' => 'mt_karyawan', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), isset($value->jd_kode_dokter)?$value->jd_kode_dokter:'' , 'dokter', 'dokter', 'form-control', '', '') ?>
            </div>
          </div>

        </div>
      </div>

      <!-- Card: Jadwal Praktek -->
      <div class="frm-card">
        <div class="frm-card-hdr">
          <i class="fa fa-calendar"></i> Jadwal Praktek
          <small style="margin-left:auto"><i class="fa fa-info-circle"></i> Atur jam praktek untuk setiap hari</small>
        </div>
        <div style="overflow-x:auto">
          <table class="jadwal-tbl">
            <thead>
              <tr>
                <th width="40">ID</th>
                <th width="90">Hari</th>
                <th width="90">Jam Mulai</th>
                <th width="30"></th>
                <th width="90">Jam Selesai</th>
                <th width="180">Keterangan</th>
                <th width="70">Kuota</th>
                <th width="120">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $disabled = '';
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

              <input name="jd_hari[]" id="jd_hari_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo $day_lib?>" class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="hidden">
              <input name="curr_edit[]" id="curr_edit_<?php echo $id?>_<?php echo $day_lib?>" value="" class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="hidden">
              <input name="delete[<?php echo $day_lib?>]" id="curr_delete_<?php echo $id?>_<?php echo $day_lib?>" type="hidden" class="ace custom-checkbox" value="">

              <tr>
                <td class="center">
                  <input name="jd_id[]" id="jd_id_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo isset($id)?$id:0?>" class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" readonly style="width:50px; text-align:center">
                </td>
                <td>
                  <span class="day-label"><?php echo $day_lib?></span>
                </td>
                <td>
                  <input name="start[]" id="start_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo isset($start)?$start:''?>" <?php echo $disabled?> class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" placeholder="HH:mm">
                </td>
                <td class="center" style="font-weight:600; color:#888">s/d</td>
                <td>
                  <input name="end[]" id="end_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo isset($end)?$end:''?>" <?php echo $disabled?> class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" placeholder="HH:mm">
                </td>
                <td>
                  <input name="keterangan[]" id="keterangan_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo isset($keterangan)?$keterangan:''?>" <?php echo $disabled?> class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" placeholder="Keterangan">
                </td>
                <td>
                  <input name="kuota_dr[]" id="kuota_dr_<?php echo $id?>_<?php echo $day_lib?>" value="<?php echo isset($kuota)?$kuota:''?>" <?php echo $disabled?> class="class_form_<?php echo $id?>_<?php echo $day_lib?> form-control" type="text" placeholder="0" style="width:55px; text-align:center">
                </td>
                <td class="center" style="white-space:nowrap">
                  <a href="#" id="btn_delete_<?php echo $id;?>_<?php echo $day_lib;?>" <?php echo ($id!=0)?'':'style="display:none"'?> onclick="btn_delete(<?php echo $id?>,'<?php echo $day_lib?>')" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-times-circle"></i></a>
                  <a href="#" id="btn_update_<?php echo $id;?>_<?php echo $day_lib;?>" <?php echo ($id!=0)?'':'style="display:none"'?> onclick="update(<?php echo $id?>,'<?php echo $day_lib?>')" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-edit"></i></a>
                  <a href="#" id="btn_submit_<?php echo $id;?>_<?php echo $day_lib;?>" <?php echo ($id==0)?'':'style="display:none"'?> onclick="submit(<?php echo $id?>,'<?php echo $day_lib?>')" class="btn btn-xs btn-primary" title="Konfirmasi"><i class="fa fa-check"></i></a>
                  <a href="#" id="btn_batal_<?php echo $id;?>_<?php echo $day_lib;?>" style="display:none" onclick="cancel(<?php echo $id?>,'<?php echo $day_lib?>')" class="btn btn-xs btn-warning" title="Batal"><i class="fa fa-refresh"></i></a>
                </td>
              </tr>

              <?php endfor;?>
            </tbody>
          </table>
        </div>

        <!-- Action buttons -->
        <div class="frm-actions">
          <!--hidden field-->
          <input type="hidden" name="flag" value="<?php echo isset($flag)?$flag:''?>">

          <a onclick="getMenu('information/regon_info_jadwal_dr')" href="#" class="btn btn-sm btn-default" style="border:1px solid #c0d4e8">
            <i class="fa fa-arrow-left"></i> Kembali
          </a>
          <?php if($flag != 'read'):?>
          <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
            <i class="fa fa-close"></i> Reset
          </button>
          <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
            <i class="fa fa-check-square-o"></i> Submit
          </button>
          <?php endif; ?>
        </div>
      </div>

    </form>

  </div>
</div>

<!-- Modal Verifikasi Manajemen -->
<div class="modal fade" id="modal-verifikasi-form" tabindex="-1" role="dialog" aria-labelledby="modalVerifikasiFormLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalVerifikasiFormLabel">Verifikasi Manajemen</h5>
      </div>
      <div class="modal-body">
        <div class="form-group" style="position: relative; margin-bottom: 10px;">
          <label for="verif_password_form">Password User</label>
          <div class="input-group">
            <input type="password" class="form-control" id="verif_password_form" placeholder="Masukkan Password User" autocomplete="off">
            <span class="input-group-addon" id="toggle-verif-password-form" style="cursor: pointer; background: transparent; border-left: none;">
              <i class="fa fa-eye"></i>
            </span>
          </div>
        </div>
        <div class="form-group" style="position: relative; margin-bottom: 10px;">
          <label for="verif_kode_form">Kode Verifikasi</label>
          <div class="input-group">
            <input type="password" class="form-control" id="verif_kode_form" placeholder="Masukkan Kode Verifikasi" autocomplete="off">
            <span class="input-group-addon" id="toggle-verif-kode-form" style="cursor: pointer; background: transparent; border-left: none;">
              <i class="fa fa-eye"></i>
            </span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" style="height: 42px !important" class="btn btn-danger" data-dismiss="modal">Batal</button>
        <button type="button" style="height: 42px !important" class="btn btn-primary" onclick="submitVerifikasiForm()">Submit</button>
      </div>
    </div>
  </div>
</div>
