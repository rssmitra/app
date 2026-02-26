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
<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_input_<?php echo $flag?>_<?php echo $id?>" action="<?php echo site_url('purchasing/pendistribusian/Verifikasi_permintaan/process_approval')?>" enctype="multipart/form-data" >

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
    
      <!-- hidden form -->
      <input type="hidden" name="id_tc_permintaan_inst" id="id_tc_permintaan_inst" value="<?php echo $id?>">
      <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">

      <table border="0" style="width: 100%">

        <tr>
          <td style="width: 10%; padding-bottom:5px;padding-top:8px; padding-left: 4px;"> Tanggal Verifikasi</td>
          <td style="padding-left:5px;"><?php echo date('d/M/Y H:i:s')?></td>
        </tr>
        <tr>
          <td style="padding-bottom:5px;padding-top:8px; vertical-align: top; padding-left: 4px;">Verifikator</td>
          <td style="padding-left:5px;padding-top: 5px; width: 350px" ><?php echo $this->master->get_ttd_data(($flag == 'medis')?'verifikator_m_1' : 'verifikator_nm_1','label'); ?> (<?php echo $this->master->get_ttd_data('verifikator_m_1','value'); ?>)<br><span style="color: red"><i>Menunggu persetujuan..</i></span> 
            <input type="hidden" name="pemeriksa" id="verifikator_m_1" value="<?php echo $this->master->get_ttd_data(($flag == 'medis')?'verifikator_m_1' : 'verifikator_nm_1','label'); ?>">
          </td>
        </tr>
        <tr>
          <td style="padding-bottom:5px;padding-top:8px;vertical-align: top;padding-left: 4px;">&nbsp;</td>
          <td style="padding-left:5px;">
          <textarea class="form-control" name="catatan_verifikator_m_1" id="catatan_verifikator_m_1" style="height:70px !important; width: 100%" placeholder="Masukan catatan..."><?php echo ($dt_detail_brg[0]->acc_note) ? $dt_detail_brg[0]->acc_note : '';?></textarea>
          </td>
        </tr>
      </table>
      <br>
      <!-- PAGE rasio BEGINS -->
      <table id="table-rincian-barang" class="table table-bordered" style="width:100%">
        <tr style="background-color: #0d528021">
          <th class="center" width="30px"><input type="checkbox" class="form-control" onClick="checkAll(this);" style="cursor:pointer; width: 17px"></th>
          <th class="center" width="30px" style="vertical-align: middle">No</th>
          <th style="width: 100px; vertical-align: middle">Kode Barang</th>
          <th style="vertical-align: middle">Nama Barang</th>
          <th style="vertical-align: middle; text-align: center; width: 50px">BHP?</th>
          <th class="center" style="width: 100px; vertical-align: middle">Stok AKhir</th>
          <th class="center" style="width: 120px; vertical-align: middle">Jml Diminta</th>
          <th class="center" style="width: 100px; vertical-align: middle">Jml Disetujui</th>
          <th class="center" style="width: 200px; vertical-align: middle">Keterangan</th>
        </tr>
        <?php
          $no=0;
          $arr_total_biaya = array();
          // hitung kemunculan setiap kode_brg untuk deteksi duplikat
          $kode_brg_list = array();
          foreach($dt_detail_brg as $r){ $kode_brg_list[] = $r->kode_brg; }
          $kode_brg_count = array_count_values($kode_brg_list);
          $kode_brg_seen  = array(); // tracking kode yg sudah dirender
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
          $tr_style      = $is_disabled ? ' style="opacity:0.45; background-color:#f9dede;"' : '';
          $input_disabled = $is_disabled ? 'disabled' : '';
        ?>

          <!-- input hidden kode brg -->
           <input type="hidden" name="list_brg[<?php echo $row_dt->id_tc_permintaan_inst_det?>]" class="form-control" value="<?php echo $row_dt->id_tc_permintaan_inst_det?>" >

          <tr<?php echo $tr_style?>>
            <td class="center"><input type="checkbox" class="checkbox_brg_<?php echo $flag?>_<?php echo $id?>" id="checkbox_brg_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id_tc_permintaan_inst_det?>" name="selected[<?php echo $row_dt->id_tc_permintaan_inst_det?>]" class="form-control" value="<?php echo $row_dt->id_tc_permintaan_inst_det?>" onClick="checkOne(this);" style="cursor:pointer; width: 17px" <?php echo ($row_dt->status_verif == 1) ? 'checked': ''?> <?php echo $input_disabled?>></td>
            <td class="center" style="vertical-align: middle"><?php echo $no?></td>
            <td style="vertical-align: middle"><?php echo $row_dt->kode_brg . $duplikat_label?></td>
            <td style="vertical-align: middle"><?php echo $row_dt->nama_brg?></td>
            <td style="vertical-align: middle; text-align: center"><?php echo $is_bhp?></td>
            <td style="vertical-align: middle; text-align: center"><?php echo $row_dt->jumlah_stok_sebelumnya?></td>
            <td class="center" style="vertical-align: middle"><span id="jml_diminta_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id_tc_permintaan_inst_det?>"><?php echo $row_dt->jumlah_permintaan;?></span> <?php echo $row_dt->satuan_kecil?></td>
            <td class="center" style="vertical-align: middle">
              <input type="text" name="jml_acc[<?php echo $row_dt->id_tc_permintaan_inst_det?>]" id="jml_acc_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id_tc_permintaan_inst_det?>" value="<?php echo $row_dt->jml_acc_atasan;?>" style="width: 50px; text-align: center; border: none !important; border-bottom: 1px solid grey !important; font-size: 14px; font-weight: bold;" class="form-control" <?php echo $input_disabled?>>
            </td>
            <td class="center" style="vertical-align: middle">
              <input type="text" name="keterangan_verif[<?php echo $row_dt->id_tc_permintaan_inst_det?>]" id="keterangan_verif_jml_acc_<?php echo $row_dt->id_tc_permintaan_inst_det?>" value="<?php echo $row_dt->keterangan_verif;?>" style="text-align: left; border: none !important; border-bottom: 1px solid grey !important; width: 100% !important" class="form-control" <?php echo $input_disabled?>>
            </td>
          </tr>
        <?php endforeach;?>
      </table>

      <p style="padding: 5px; font-style: italic">Tanggal terakhir di verifikasi, <?php echo $this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_acc);?></p>

      <hr>
      <?php if($dt_detail_brg[0]->status_acc != 1) : ?>
      <button type="button" class="btn btn-xs btn-success" onclick="approve('verifikator_m_1', <?php echo $id; ?>,1,'<?php echo $_GET['flag']?>')"><i class="fa fa-check-circle"></i> Setuju</button>
      <button type="button" class="btn btn-xs btn-danger" onclick="approve('verifikator_m_1', <?php echo $id; ?>,0,'<?php echo $_GET['flag']?>')"><i class="fa fa-times-circle"></i> Kembalikan</button>
      <?php 
        else:
          $txt = ($dt_detail_brg[0]->status_acc == 1) ? 'Disetujui' : 'Ditolak';
          $clr = ($dt_detail_brg[0]->status_acc == 1) ? 'success' : 'danger';
      ?>
        <div class="alert alert-<?php echo $clr;?>"><strong style="font-weight: bold; font-size: 14px">Permintaan <?php echo $txt; ?></strong><br>Permintaan ini telah disetujui. 
          <button type="button" class="btn btn-xs btn-warning" onclick="rollbackApproval(<?php echo $id; ?>,'<?php echo $_GET['flag']?>')"><i class="fa fa-undo"></i> Rollback</button>
        </div>
      <?php endif; ?>

    <!-- PAGE rasio ENDS -->

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


