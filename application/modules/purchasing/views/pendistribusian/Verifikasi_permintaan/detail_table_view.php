<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">

  function checkAll(elm) {

    if($(elm).prop("checked") == true){
      // Centang semua checkbox dan isi input jml_acc dengan nilai jml_diminta
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').each(function(){
        var kode_brg = $(this).val();
        $(this).prop("checked", true);
        var jml_diminta = $('#jml_diminta_<?php echo $flag?>_<?php echo $id?>_' + kode_brg).text().trim();
        $('#jml_acc_<?php echo $flag?>_<?php echo $id?>_' + kode_brg).val(jml_diminta).trigger('change');
      });
    }else{
      // Uncheck semua checkbox dan kosongkan input jml_acc
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').each(function(){
        var kode_brg = $(this).val();
        $(this).prop("checked", false);
        $('#jml_acc_<?php echo $flag?>_<?php echo $id?>_' + kode_brg).val('').trigger('change');
      });
    }

  }

  function checkOne(elm) {

    if($(elm).prop("checked") == true){
        var kode_brg = $(elm).val();
        $('#jml_acc_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( $('#jml_diminta_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').text() );
          $(elm).prop("checked", true);
    }else{
      $(elm).prop("checked", false);
        var kode_brg = $(elm).val();
        $('#jml_acc_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( '' );
    }

  }

  function approve(field, id, app, flag){
    var selected_data = $("#table-rincian-barang input:checkbox:checked").map(function(){
      return $(this).val();
    }).toArray();

    var length = selected_data.length;
    // tambahkan app untuk mengetahui status approve / kembalikan
    var formData = $('#form_input_<?php echo $flag?>_<?php echo $id?>').serializeArray();
    formData.push({name: 'flag_approval', value: app});

    if(app == 1 && length == 0){
      Swal.fire({
        title: 'Perhatian!',
        text: 'Silahkan ceklis rincian barang yang disetujui',
        icon: 'warning',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      });
    }else{
      var confirmTitle  = (app == 1) ? 'Konfirmasi Persetujuan' : 'Konfirmasi Pengembalian';
      var confirmText   = (app == 1)
        ? 'Apakah Anda yakin akan menyetujui permintaan ini?'
        : 'Apakah Anda yakin akan mengembalikan permintaan ini?';
      var confirmBtn    = (app == 1) ? 'Ya, Setuju' : 'Ya, Kembalikan';
      var confirmColor  = (app == 1) ? '#28a745' : '#d33';

      Swal.fire({
        title: confirmTitle,
        text: confirmText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: confirmBtn,
        cancelButtonText: 'Batal',
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6c757d',
      }).then(function(result){
        if(result.isConfirmed){
          $.ajax({
              url: 'purchasing/pendistribusian/Verifikasi_permintaan/prosess_approval',
              type: "post",
              data: formData,
              dataType: "json",
              beforeSend: function() {
                achtungShowLoader();
              },
              complete: function(xhr) {
                var data=xhr.responseText;
                var jsonResponse = JSON.parse(data);
                achtungHideLoader();
                if(jsonResponse.status === 200){
                  Swal.fire({
                    title: 'Berhasil!',
                    text: jsonResponse.message,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                  }).then(function(){
                    $('#page-area-content').load('purchasing/pendistribusian/Verifikasi_permintaan?flag='+flag+'&status='+app+'');
                    reload_table();
                  });
                }else{
                  Swal.fire({
                    title: 'Gagal!',
                    text: jsonResponse.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33',
                  });
                }
              }
          });
        }
      });
    }

  }

</script>

<style>
  .det-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin: 6px 0 10px; }
  .det-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .det-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .det-tbl thead tr { background: #2c6fad; color: #fff; }
  .det-tbl thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; vertical-align: middle; }
  .det-tbl tbody tr:nth-child(even) { background: #f5f9fd; }
  .det-tbl tbody tr:hover { background: #e8f0f9; }
  .det-tbl tbody td { padding: 7px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
  .det-tbl tbody tr.row-disabled { opacity: 0.45; background-color: #f9dede !important; }
  .verif-info { padding: 10px 14px; font-size: 12px; background: #f8fafd; border-bottom: 1px solid #d0dce8; }
  .verif-info table td:first-child { width: 150px; color: #555; font-weight: 600; padding: 4px 0; }
  .verif-actions { padding: 10px 14px; border-top: 1px solid #d0dce8; background: #f8fafd; }
</style>

<form class="form-horizontal" method="post" id="form_input_<?php echo $flag?>_<?php echo $id?>" action="<?php echo site_url('purchasing/pendistribusian/Verifikasi_permintaan/process_approval')?>" enctype="multipart/form-data">

  <?php
    $current_user_id = $this->session->userdata('user')->user_id;
    $ttd_user_id = $this->master->get_ttd_data(($flag == 'medis') ? 'verifikator_m_1' : 'verifikator_nm_1', 'reff_id');
    if($current_user_id == 1 || $current_user_id == $ttd_user_id){
      // Tampilkan form verifikasi (tidak perlu return)
    } else {
      echo '<div class="alert alert-danger">Anda tidak memiliki akses untuk memverifikasi permintaan ini.</div>';
      return;
    }
  ?>

  <input type="hidden" name="id_tc_permintaan_inst" id="id_tc_permintaan_inst" value="<?php echo $id?>">
  <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">

  <div class="det-wrap">
    <div class="det-hdr"><i class="fa fa-check-circle-o"></i> Form Verifikasi Permintaan</div>
    <div class="verif-info">
      <table>
        <tr>
          <td>Tanggal Verifikasi</td>
          <td>: <?php echo date('d/M/Y H:i:s')?></td>
        </tr>
        <tr>
          <td>Verifikator</td>
          <td>:
            <?php echo $this->master->get_ttd_data(($flag == 'medis')?'verifikator_m_1' : 'verifikator_nm_1','label'); ?>
            (<?php echo $this->master->get_ttd_data(($flag == 'medis')?'verifikator_m_1' : 'verifikator_nm_1','value'); ?>)
            <span style="color: red; font-style: italic; font-size: 11px"> &mdash; Menunggu persetujuanxx..</span>
            <input type="hidden" name="pemeriksa" id="verifikator_m_1" value="<?php echo $this->master->get_ttd_data(($flag == 'medis')?'verifikator_m_1' : 'verifikator_nm_1','label'); ?>">
          </td>
        </tr>
        <tr>
          <td>Catatan</td>
          <td>: <textarea class="form-control" name="catatan_verifikator_m_1" id="catatan_verifikator_m_1" style="height:60px; width:100%; max-width:400px; margin-top:4px" placeholder="Masukan catatan..."><?php echo ($dt_detail_brg[0]->acc_note) ? $dt_detail_brg[0]->acc_note : '';?></textarea></td>
        </tr>
      </table>
    </div>

    <table id="table-rincian-barang" class="det-tbl">
      <thead>
        <tr>
          <th width="30px"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer; width:16px"></th>
          <th width="35px">No</th>
          <th width="100px">Kode Barang</th>
          <th>Nama Barang</th>
          <th width="45px">BHP?</th>
          <th width="90px">Stok Akhir</th>
          <th width="110px">Jml Diminta</th>
          <th width="100px">Jml Disetujui</th>
          <th width="190px">Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no=0;
          $kode_brg_list = array();
          foreach($dt_detail_brg as $r){ $kode_brg_list[] = $r->kode_brg; }
          $kode_brg_count = array_count_values($kode_brg_list);
          $kode_brg_seen  = array();
          foreach($dt_detail_brg as $row_dt) : $no++;
          $is_bhp = $row_dt->is_bhp == 1 ? '<i class="fa fa-check green"></i>' : '';
          $is_duplikat  = $kode_brg_count[$row_dt->kode_brg] > 1;
          $is_disabled  = $is_duplikat && in_array($row_dt->kode_brg, $kode_brg_seen);
          if(!$is_disabled){ $kode_brg_seen[] = $row_dt->kode_brg; }
          $duplikat_label = $is_duplikat
            ? ($is_disabled
                ? ' <span class="label label-danger" title="Baris ini diabaikan karena kode barang duplikat"><i class="fa fa-ban"></i> Duplikat (Diabaikan)</span>'
                : ' <span class="label label-warning" title="Kode barang ini muncul lebih dari satu kali"><i class="fa fa-exclamation-triangle"></i> Duplikat</span>')
            : '';
          $tr_class       = $is_disabled ? ' class="row-disabled"' : '';
          $input_disabled = $is_disabled ? 'disabled' : '';
        ?>
        <input type="hidden" name="list_brg[<?php echo $row_dt->id_tc_permintaan_inst_det?>]" value="<?php echo $row_dt->id_tc_permintaan_inst_det?>">
        <tr<?php echo $tr_class?>>
          <td class="center">
            <input type="checkbox" class="checkbox_brg_<?php echo $flag?>_<?php echo $id?>"
              id="checkbox_brg_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id_tc_permintaan_inst_det?>"
              name="selected[<?php echo $row_dt->id_tc_permintaan_inst_det?>]"
              value="<?php echo $row_dt->id_tc_permintaan_inst_det?>"
              onClick="checkOne(this);" style="cursor:pointer; width:16px"
              <?php echo ($row_dt->status_verif == 1) ? 'checked': ''?>
              <?php echo $input_disabled?>>
          </td>
          <td class="center"><?php echo $no?></td>
          <td>
            <a href="javascript:void(0)" onclick="showMutasiBarang('<?php echo $row_dt->kode_brg?>', '<?php echo $row_dt->kode_bagian_minta?>', '<?php echo $flag?>', '<?php echo $row_dt->nama_brg?>')" style="color:#1a4f8a; text-decoration:underline; cursor:pointer; font-weight:600">
              <?php echo $row_dt->kode_brg?>
            </a>
            <?php echo $duplikat_label?>
          </td>
          <td><?php echo $row_dt->nama_brg?></td>
          <td class="center"><?php echo $is_bhp?></td>
          <td class="center"><?php echo $row_dt->jumlah_stok_sebelumnya?></td>
          <td class="center">
            <span id="jml_diminta_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id_tc_permintaan_inst_det?>"><?php echo $row_dt->jumlah_permintaan;?></span>
            <?php echo $row_dt->satuan_kecil?>
          </td>
          <td class="center">
            <input type="text" name="jml_acc[<?php echo $row_dt->id_tc_permintaan_inst_det?>]"
              id="jml_acc_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id_tc_permintaan_inst_det?>"
              value="<?php echo $row_dt->jml_acc_atasan;?>"
              style="width:50px; text-align:center; border:none !important; border-bottom:1px solid grey !important; font-size:14px; font-weight:bold;"
              class="form-control" <?php echo $input_disabled?>>
          </td>
          <td class="center">
            <input type="text" name="keterangan_verif[<?php echo $row_dt->id_tc_permintaan_inst_det?>]"
              id="keterangan_verif_jml_acc_<?php echo $row_dt->id_tc_permintaan_inst_det?>"
              value="<?php echo $row_dt->keterangan_verif;?>"
              style="text-align:left; border:none !important; border-bottom:1px solid grey !important; width:100% !important"
              class="form-control" <?php echo $input_disabled?>>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>

    <div class="verif-actions">
      <p style="font-style:italic; font-size:11px; margin-bottom:8px">
        Tanggal terakhir diverifikasi: <?php echo $this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_acc);?>
      </p>
      <?php if($dt_detail_brg[0]->status_acc != 1) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="approve('verifikator_m_1', <?php echo $id; ?>,1,'<?php echo $_GET['flag']?>')">
        <i class="fa fa-check-circle"></i> Setuju
      </button>
      <button type="button" class="btn btn-sm btn-danger" onclick="approve('verifikator_m_1', <?php echo $id; ?>,0,'<?php echo $_GET['flag']?>')">
        <i class="fa fa-times-circle"></i> Kembalikan
      </button>
      <?php
        else:
          $txt = ($dt_detail_brg[0]->status_acc == 1) ? 'Disetujui' : 'Ditolak';
          $clr = ($dt_detail_brg[0]->status_acc == 1) ? 'success' : 'danger';
      ?>
      <div class="alert alert-<?php echo $clr;?>" style="margin-bottom:0">
        <strong>Permintaan <?php echo $txt; ?></strong> &mdash; Permintaan ini telah disetujui.
        <button type="button" class="btn btn-xs btn-warning" onclick="rollbackApproval(<?php echo $id; ?>,'<?php echo $_GET['flag']?>')">
          <i class="fa fa-undo"></i> Rollback
        </button>
      </div>
      <?php endif; ?>
    </div>
  </div>

</form>

<!-- Modal Mutasi Barang -->
<div class="modal fade" id="modalMutasiBarang" tabindex="-1" role="dialog" aria-labelledby="modalMutasiBarangLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:900px">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a4f8a; color:#fff; padding:10px 15px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalMutasiBarangLabel"><i class="fa fa-exchange"></i> Mutasi Barang</h4>
      </div>
      <div class="modal-body" style="padding:15px">
        <div style="margin-bottom:10px">
          <table style="font-size:12px">
            <tr><td style="width:120px; font-weight:600">Kode Barang</td><td>: <span id="mutasi_kode_brg"></span></td></tr>
            <tr><td style="font-weight:600">Nama Barang</td><td>: <span id="mutasi_nama_brg"></span></td></tr>
            <tr><td style="font-weight:600">Unit Bagian</td><td>: <span id="mutasi_kode_bagian"></span></td></tr>
          </table>
        </div>
        <p style="font-size:11px; color:#888; font-style:italic">* Menampilkan data mutasi 120 hari terakhir</p>
        <table id="tbl-mutasi-brg" class="table table-striped table-bordered table-hover" style="width:100%; font-size:12px">
          <thead>
            <tr style="background-color:#2c6fad; color:#fff">
              <th width="30px">No</th>
              <th>Tanggal</th>
              <th>Stok Awal</th>
              <th>Masuk</th>
              <th>Keluar</th>
              <th>Stok Akhir</th>
              <th>Keterangan</th>
              <th>Petugas</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer" style="padding:8px 15px">
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function showMutasiBarang(kode_brg, kode_bagian, flag, nama_brg){
  $('#mutasi_kode_brg').text(kode_brg);
  $('#mutasi_nama_brg').text(nama_brg);
  $('#mutasi_kode_bagian').text(kode_bagian);

  var ajaxUrl = 'purchasing/pendistribusian/Verifikasi_permintaan/get_data_mutasi?kode_brg=' + kode_brg + '&kode_bagian=' + kode_bagian + '&flag=' + flag;

  // Destroy previous instance if exists
  if($.fn.DataTable.isDataTable('#tbl-mutasi-brg')){
    $('#tbl-mutasi-brg').DataTable().destroy();
    $('#tbl-mutasi-brg tbody').empty();
  }

  $('#tbl-mutasi-brg').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "searching": true,
    "pageLength": 100,
    "scrollY": "400px",
    "ajax": {
      "url": ajaxUrl,
      "type": "POST"
    },
    "language": {
      "emptyTable": "Tidak ada data mutasi",
      "zeroRecords": "Data tidak ditemukan"
    }
  });

  $('#modalMutasiBarang').modal('show');
}
</script>
