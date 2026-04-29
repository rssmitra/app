<script type="text/javascript">
  $(document).ready(function() {

  //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({

      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "paging": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "information/Regon_info_jadwal_dr/get_data",
          "type": "POST"
      },

    });

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    // Override delete button to use verification modal
    $(document).off('click', '#button_delete').on('click', '#button_delete', function(event){
        event.preventDefault();
        event.stopImmediatePropagation();
        var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
        }).toArray();
        if(searchIDs.length === 0){
            $.achtung({message: 'Tidak ada item yang dipilih', timeout:5, className: 'achtungFail'});
            return;
        }
        // Show verification modal
        $('#modal-verifikasi-jadwal .modal-title').text('Verifikasi Hapus Jadwal Dokter');
        $('#verif_password_jadwal').val('');
        $('#verif_kode_jadwal').val('');
        $('#modal-verifikasi-jadwal').data('action-mode', 'delete');
        $('#modal-verifikasi-jadwal').data('delete-ids', searchIDs.join(','));
        $('#modal-verifikasi-jadwal').modal('show');
    });

});

function submitVerifikasiJadwal() {
    var password = $('#verif_password_jadwal').val();
    var kodeVerifikasi = $('#verif_kode_jadwal').val();
    if (!password) {
        alert('Password harus diisi!');
        $('#verif_password_jadwal').focus();
        return;
    }
    if (!kodeVerifikasi) {
        alert('Kode verifikasi harus diisi!');
        $('#verif_kode_jadwal').focus();
        return;
    }

    var actionMode = $('#modal-verifikasi-jadwal').data('action-mode');
    var deleteIds = $('#modal-verifikasi-jadwal').data('delete-ids');

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
                $('#modal-verifikasi-jadwal').modal('hide');
                if (actionMode === 'delete' && deleteIds) {
                    // Proceed with delete after verification
                    achtungShowLoader();
                    $.ajax({
                        url: 'information/Regon_info_jadwal_dr/delete',
                        type: "post",
                        data: {ID: deleteIds},
                        dataType: "json",
                        complete: function(xhr) {
                            var data = xhr.responseText;
                            var jsonResponse = JSON.parse(data);
                            if(jsonResponse.status === 200){
                                $.achtung({message: jsonResponse.message, timeout:5});
                                oTable.ajax.reload();
                            } else {
                                $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                            }
                            achtungHideLoader();
                        }
                    });
                }
            } else {
                alert(response.message || 'Password atau kode verifikasi salah!');
            }
            $('#verif_password_jadwal').val('');
            $('#verif_kode_jadwal').val('');
        },
        error: function() {
            alert('Terjadi kesalahan pada server.');
        }
    });
}

// Toggle show/hide password
$(document).on('mousedown', '#toggle-verif-password', function() {
    $('#verif_password_jadwal').attr('type', 'text');
    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
});
$(document).on('mouseup mouseleave', '#toggle-verif-password', function() {
    $('#verif_password_jadwal').attr('type', 'password');
    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
});
$(document).on('mousedown', '#toggle-verif-kode', function() {
    $('#verif_kode_jadwal').attr('type', 'text');
    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
});
$(document).on('mouseup mouseleave', '#toggle-verif-kode', function() {
    $('#verif_kode_jadwal').attr('type', 'password');
    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
});

$('#modal-verifikasi-jadwal').on('show.bs.modal', function () {
    setTimeout(function() {
        $('.modal-backdrop').addClass('verifikasi-jadwal-backdrop');
    }, 10);
});
$('#modal-verifikasi-jadwal').on('hidden.bs.modal', function () {
    $('.modal-backdrop').removeClass('verifikasi-jadwal-backdrop');
    $(this).data('action-mode', '').data('delete-ids', '');
});

</script>
<style type="text/css">
    table{
      width: 100% !important;
      font-size: 12px;
    }
    .table-custom thead {
      background-color: #14506b;
      color: white;
    }

    .table-custom th, td {
      padding: 10px;
      border: 1px solid #c5d0dc;
    }
    .table-custom tbody tr:hover {background-color: #e6e6e6e0;}

    /* Verification modal styles */
    .modal-backdrop.verifikasi-jadwal-backdrop {
      background-color: #222 !important;
      opacity: 0.85 !important;
    }
    #modal-verifikasi-jadwal .modal-dialog {
      margin-top: 10vh;
      max-width: 400px;
    }
    #modal-verifikasi-jadwal .modal-content {
      border-radius: 10px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.25);
      border: none;
    }
    #modal-verifikasi-jadwal .modal-header {
      border-bottom: 1px solid #eee;
      background: #f7f7f7;
      border-radius: 10px 10px 0 0;
      padding: 16px 24px 12px 24px;
    }
    #modal-verifikasi-jadwal .modal-title {
      font-weight: bold;
      font-size: 18px;
    }
    #modal-verifikasi-jadwal .modal-body {
      padding: 20px 24px 10px 24px;
    }
    #modal-verifikasi-jadwal .form-group label {
      font-weight: 500;
      margin-bottom: 8px;
    }
    #modal-verifikasi-jadwal .form-control {
      border-radius: 6px;
      font-size: 12px;
    }
    #modal-verifikasi-jadwal .modal-footer {
      border-top: 1px solid #eee;
      padding: 12px 24px 16px 24px;
      border-radius: 0 0 10px 10px;
      background: #f7f7f7;
    }
</style>

<div class="clearfix" style="margin-bottom:-5px">
  <?php echo $this->authuser->show_button('information/regon_info_jadwal_dr','C','',1)?>
  <?php echo $this->authuser->show_button('information/regon_info_jadwal_dr','D','',8)?>
</div>
<hr class="separator">


<div style="margin-top:-27px">
    <table id="dynamic-table" base-url="information/regon_info_jadwal_dr" class="table-custom">
      <thead>
      <tr>
        <th style="color: white !important" width="30px" class="center" rowspan="2"></th>
        <th style="color: white !important" rowspan="2" width="120px">Action</th>
        <th style="color: white !important" rowspan="2">Nama Dokter</th>
        <th style="color: white !important" rowspan="2">Spesialis</th>
        <th style="color: white !important" colspan="7" class="center">Hari/Jam Praktek</th>
      </tr>
      <tr style="color: white !important">
        <th style="color: white !important" class="center" width="125px">Senin</th>
        <th style="color: white !important" class="center" width="125px">Selasa</th>
        <th style="color: white !important" class="center" width="125px">Rabu</th>
        <th style="color: white !important" class="center" width="125px">Kamis</th>
        <th style="color: white !important" class="center" width="125px">Jumat</th>
        <th style="color: white !important" class="center" width="125px">Sabtu</th>
        <th style="color: white !important" class="center" width="125px">Minggu</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<!-- Modal Verifikasi Manajemen -->
<div class="modal fade" id="modal-verifikasi-jadwal" tabindex="-1" role="dialog" aria-labelledby="modalVerifikasiJadwalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalVerifikasiJadwalLabel">Verifikasi Manajemen</h5>
      </div>
      <div class="modal-body">
        <div class="form-group" style="position: relative; margin-bottom: 10px;">
          <label for="verif_password_jadwal">Password User</label>
          <div class="input-group">
            <input type="password" class="form-control" id="verif_password_jadwal" placeholder="Masukkan Password User" autocomplete="off">
            <span class="input-group-addon" id="toggle-verif-password" style="cursor: pointer; background: transparent; border-left: none;">
              <i class="fa fa-eye"></i>
            </span>
          </div>
        </div>
        <div class="form-group" style="position: relative; margin-bottom: 10px;">
          <label for="verif_kode_jadwal">Kode Verifikasi</label>
          <div class="input-group">
            <input type="password" class="form-control" id="verif_kode_jadwal" placeholder="Masukkan Kode Verifikasi" autocomplete="off">
            <span class="input-group-addon" id="toggle-verif-kode" style="cursor: pointer; background: transparent; border-left: none;">
              <i class="fa fa-eye"></i>
            </span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" style="height: 42px !important" class="btn btn-danger" data-dismiss="modal">Batal</button>
        <button type="button" style="height: 42px !important" class="btn btn-primary" onclick="submitVerifikasiJadwal()">Submit</button>
      </div>
    </div>
  </div>
</div>
