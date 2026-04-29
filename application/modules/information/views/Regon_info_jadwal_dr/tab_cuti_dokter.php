<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script type="text/javascript">

jQuery(function($) {  

  $('.date-picker').datepicker({  
    autoclose: true,    
    todayHighlight: true    
  })  
  .next().on(ace.click_event, function(){   
    $(this).prev().focus();    
  });  

});

var cutiFormVerified = false;

$(document).ready(function(){

  $('#form_cuti_dr').ajaxForm({
    beforeSubmit: function() {
      if (!cutiFormVerified) {
        // Show verification modal before submit
        $('#modal-verifikasi-cuti .modal-title').text('Verifikasi Proses Cuti Dokter');
        $('#verif_password_cuti').val('');
        $('#verif_kode_cuti').val('');
        $('#modal-verifikasi-cuti').data('action-mode', 'submit');
        $('#modal-verifikasi-cuti').modal('show');
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
        oTableCutiDr.ajax.reload();
        // reset form
        $('#form_cuti_dr')[0].reset();
      }else{
        $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
      }
      achtungHideLoader();
      cutiFormVerified = false; // reset after submission
    }
  });

  oTableCutiDr = $('#datatable-cuti-dr').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": false,
      // "pageLength": 25,
      "bInfo": false,
      "paging": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#datatable-cuti-dr').attr('base-url'),
          "type": "POST"
      },
  });
  

})

$('select[name="klinik_rajal"]').change(function () {      
    if ($(this).val()) {   
      $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {              
          $('#dokter_rajal option').remove();                
          $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter_rajal'));                
          $.each(data, function (i, o) {                  
              $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter_rajal'));                    
          });                
      });   
    } else {    
        $('#dokter_rajal option').remove()      
    }        
});

function show_data(id){
  preventDefault();
  $.getJSON("<?php echo site_url('information/Regon_info_jadwal_dr/show_data_cuti') ?>/"+id, '' , function (response) {
      $('#cuti_id').val(response.cuti_id);
      $('#from_tgl').val(response.from_tgl);
      $('#to_tgl').val(response.to_tgl);
      $('#klinik_rajal').val(response.kode_bag);
      $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + response.kode_bag, '', function (data) {              
          $('#dokter_rajal option').remove();                
          $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter_rajal'));                
          $.each(data, function (i, o) {      
              var selected = (o.kode_dokter == response.kode_dr) ? 'selected' : '';
              $('<option value="' + o.kode_dokter + '" '+selected+'>' + o.nama_pegawai + '</option>').appendTo($('#dokter_rajal'));                    
          });                
      }); 
      $('#dokter_rajal').val(response.kode_dr);
      $('#keterangan_cuti').val(response.keterangan_cuti);
  })
}

function delete_data_cuti(id){
  preventDefault();
  // Show verification modal for delete
  $('#modal-verifikasi-cuti .modal-title').text('Verifikasi Hapus Cuti Dokter');
  $('#verif_password_cuti').val('');
  $('#verif_kode_cuti').val('');
  $('#modal-verifikasi-cuti').data('action-mode', 'delete');
  $('#modal-verifikasi-cuti').data('delete-id', id);
  $('#modal-verifikasi-cuti').modal('show');
}

function submitVerifikasiCuti() {
    var password = $('#verif_password_cuti').val();
    var kodeVerifikasi = $('#verif_kode_cuti').val();
    if (!password) {
        alert('Password harus diisi!');
        $('#verif_password_cuti').focus();
        return;
    }
    if (!kodeVerifikasi) {
        alert('Kode verifikasi harus diisi!');
        $('#verif_kode_cuti').focus();
        return;
    }

    var actionMode = $('#modal-verifikasi-cuti').data('action-mode');
    var deleteId = $('#modal-verifikasi-cuti').data('delete-id');

    $.ajax({
        url: 'information/Regon_info_jadwal_dr/verify_code',
        type: 'POST',
        data: {
            password: password,
            kode_verifikasi: kodeVerifikasi
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 200) {
                $('#modal-verifikasi-cuti').modal('hide');
                if (actionMode === 'delete' && deleteId) {
                    // Proceed with delete after verification
                    $.ajax({
                        url: 'information/Regon_info_jadwal_dr/delete_data_cuti',
                        type: "post",
                        data: { ID : deleteId },
                        dataType: "json",
                        complete: function(xhr) {
                            var data = xhr.responseText;
                            var jsonResponse = JSON.parse(data);
                            if(jsonResponse.status === 200){
                                $.achtung({message: jsonResponse.message, timeout:5});
                                oTableCutiDr.ajax.reload();
                            } else {
                                $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                            }
                        }
                    });
                } else if (actionMode === 'submit') {
                    // Proceed with form submit after verification
                    cutiFormVerified = true;
                    $('#form_cuti_dr').submit();
                }
            } else {
                alert(response.message || 'Password atau kode verifikasi salah!');
            }
            $('#verif_password_cuti').val('');
            $('#verif_kode_cuti').val('');
        },
        error: function() {
            alert('Terjadi kesalahan pada server.');
        }
    });
}

// Toggle show/hide password
$(document).on('mousedown', '#toggle-verif-password-cuti', function() {
    $('#verif_password_cuti').attr('type', 'text');
    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
});
$(document).on('mouseup mouseleave', '#toggle-verif-password-cuti', function() {
    $('#verif_password_cuti').attr('type', 'password');
    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
});
$(document).on('mousedown', '#toggle-verif-kode-cuti', function() {
    $('#verif_kode_cuti').attr('type', 'text');
    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
});
$(document).on('mouseup mouseleave', '#toggle-verif-kode-cuti', function() {
    $('#verif_kode_cuti').attr('type', 'password');
    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
});

$('#modal-verifikasi-cuti').on('show.bs.modal', function () {
    setTimeout(function() {
        $('.modal-backdrop').addClass('verifikasi-cuti-backdrop');
    }, 10);
});
$('#modal-verifikasi-cuti').on('hidden.bs.modal', function () {
    $('.modal-backdrop').removeClass('verifikasi-cuti-backdrop');
    $(this).data('action-mode', '').data('delete-id', '');
});

</script>

<form class="form-horizontal" method="post" id="form_cuti_dr" action="<?php echo site_url('information/Regon_info_jadwal_dr/process_cuti_dr')?>" enctype="multipart/form-data">   

<p style="margin-top:5px"><b>Form Cuti Dokter </b></p>

<!-- hidden -->
<input type="hidden" name="cuti_id" id="cuti_id" value="">

<div class="form-group">
  <label class="control-label col-sm-1" for="">*Klinik</label>
  <div class="col-sm-3">
      <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1, 'is_public' => 1)), '' , 'klinik_rajal', 'klinik_rajal', 'form-control', '', '') ?>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-sm-1" for="City">*Dokter</label>
  <div class="col-sm-3">
      <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'dokter_rajal', 'dokter_rajal', 'form-control', '', '') ?>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-sm-1">Tanggal</label>
  <div class="col-md-2">
    <div class="input-group">
        <input name="from_tgl" id="from_tgl" value="" placeholder="<?php echo date('Y-m-d')?>" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">
        <span class="input-group-addon">
          <i class="ace-icon fa fa-calendar"></i>
        </span>
      </div>
  </div>
  <label class="control-label col-sm-1">s.d Tanggal</label>
  <div class="col-md-2">
    <div class="input-group">
        <input name="to_tgl" id="to_tgl" value="" placeholder="<?php echo date('Y-m-d')?>" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">
        <span class="input-group-addon">
          <i class="ace-icon fa fa-calendar"></i>
        </span>
      </div>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-sm-1" for="City">Keterangan</label>
  <div class="col-sm-6">
      <textarea name="keterangan_cuti" id="keterangan_cuti" class="form-control" style="height: 50px !important"></textarea>
  </div>
</div>

<button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
  <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
  Submit
</button>
<hr>
<div>
  <table id="datatable-cuti-dr" base-url="information/Regon_info_jadwal_dr/get_data_cuti_dr" class="table table-bordered table-hover">
    <thead>
    <tr>  
      <th width="30px" class="center">No</th>
      <th class="center" width="100px">#</th>
      <th width="250px">Nama Dokter</th>
      <th width="250px">Poliklinik</th>
      <th width="120px">Tanggal Cuti</th>
      <th width="120px">s.d Tanggal</th>
      <th>Keterangan Cuti</th> 
      <th width="100px">Status</th> 
    </tr>
  </thead>
  <tbody>
  </tbody>
  </table>
</div>



</form>

<!-- Modal Verifikasi Manajemen Cuti -->
<div class="modal fade" id="modal-verifikasi-cuti" tabindex="-1" role="dialog" aria-labelledby="modalVerifikasiCutiLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalVerifikasiCutiLabel">Verifikasi Manajemen</h5>
      </div>
      <div class="modal-body">
        <div class="form-group" style="position: relative; margin-bottom: 10px;">
          <label for="verif_password_cuti">Password User</label>
          <div class="input-group">
            <input type="password" class="form-control" id="verif_password_cuti" placeholder="Masukkan Password User" autocomplete="off">
            <span class="input-group-addon" id="toggle-verif-password-cuti" style="cursor: pointer; background: transparent; border-left: none;">
              <i class="fa fa-eye"></i>
            </span>
          </div>
        </div>
        <div class="form-group" style="position: relative; margin-bottom: 10px;">
          <label for="verif_kode_cuti">Kode Verifikasi</label>
          <div class="input-group">
            <input type="password" class="form-control" id="verif_kode_cuti" placeholder="Masukkan Kode Verifikasi" autocomplete="off">
            <span class="input-group-addon" id="toggle-verif-kode-cuti" style="cursor: pointer; background: transparent; border-left: none;">
              <i class="fa fa-eye"></i>
            </span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" style="height: 42px !important" class="btn btn-danger" data-dismiss="modal">Batal</button>
        <button type="button" style="height: 42px !important" class="btn btn-primary" onclick="submitVerifikasiCuti()">Submit</button>
      </div>
    </div>
  </div>
</div>

<style>
  .modal-backdrop.verifikasi-cuti-backdrop {
    background-color: #222 !important;
    opacity: 0.85 !important;
  }
  #modal-verifikasi-cuti .modal-dialog {
    margin-top: 10vh;
    max-width: 400px;
  }
  #modal-verifikasi-cuti .modal-content {
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    border: none;
  }
  #modal-verifikasi-cuti .modal-header {
    border-bottom: 1px solid #eee;
    background: #f7f7f7;
    border-radius: 10px 10px 0 0;
    padding: 16px 24px 12px 24px;
  }
  #modal-verifikasi-cuti .modal-title {
    font-weight: bold;
    font-size: 18px;
  }
  #modal-verifikasi-cuti .modal-body {
    padding: 20px 24px 10px 24px;
  }
  #modal-verifikasi-cuti .form-group label {
    font-weight: 500;
    margin-bottom: 8px;
  }
  #modal-verifikasi-cuti .form-control {
    border-radius: 6px;
    font-size: 12px;
  }
  #modal-verifikasi-cuti .modal-footer {
    border-top: 1px solid #eee;
    padding: 12px 24px 16px 24px;
    border-radius: 0 0 10px 10px;
    background: #f7f7f7;
  }
</style>