<script type="text/javascript">

  function checkAll(elm) {

    if($(elm).prop("checked") == true){
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>:not(:disabled)').each(function(){
        var kode_brg = $(this).val();
        var jml_permohonan = $('#jml_permohonan_hidden_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val();
        var jml_pemeriksa = $('#jml_acc_pemeriksa_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val();
        var jml_input = (jml_pemeriksa > 0) ? jml_pemeriksa : jml_permohonan;
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( jml_input );
          $(this).prop("checked", true);
      });
    }else{
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').prop("checked", false);
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>:not(:disabled)').each(function(){
        var kode_brg = $(this).val();
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( '' );
      });
    }

  }

  function checkOne(elm) {

    if($(elm).prop("checked") == true){
        var kode_brg = $(elm).val();
        var jml_permohonan = $('#jml_permohonan_hidden_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val();
        console.log(jml_permohonan);
        var jml_pemeriksa = $('#jml_acc_pemeriksa_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val();
        var jml_input = (jml_pemeriksa > 0) ? jml_pemeriksa : jml_permohonan;
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( jml_input );
          $(elm).prop("checked", true);
    }else{
      $(elm).prop("checked", false);
        var kode_brg = $(elm).val();
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( '0' );
    }

  }

  function approve(field, id, app, flag){
    var selected_data = $("#table-rincian-barang input:checkbox:checked:not(:disabled)").map(function(){
      return $(this).val();
    }).toArray();

    var permohonan_det_ids = $("#table-rincian-barang input:checkbox:checked:not(:disabled)").map(function(){
      return $(this).attr('data-id');
    }).toArray();

    var input_data = $("#table-rincian-barang input[type=number]:not(:disabled)").map(function(){
      if( $(this).val() != 0 ){
        return $(this).val();
      }
    }).toArray();

    var is_free_text = $("#table-rincian-barang .is_free_text:not(:disabled)").map(function(){
      if( $(this).val() != 0 ){
        return $(this).val();
      }
    }).toArray();

    var length = selected_data.length;

    if(length == 0){
      alert('Silahkan ceklis rincian barang yang disetujui');
    }else{
      // proses approve
      if(confirm('Are you sure?')){
        $.ajax({
            url: 'purchasing/persetujuan_pemb/App_persetujuan_pemb/prosess_approval',
            type: "post",
            data: {
                    id_tc_permohonan : $('#id_tc_permohonan').val(),
                    selected : selected_data,
                    permohonan_det_ids : permohonan_det_ids,
                    acc_value : input_data,
                    approval_by : $('#'+field).val(),
                    verifikator : field,
                    flag_approval : app,
                    catatan : $('#catatan_'+field+'').val(),
                    is_free_text : is_free_text,
                    flag : flag,
                  },
            dataType: "json",
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
                $('#page-area-content').load('purchasing/persetujuan_pemb/App_persetujuan_pemb/view_data?flag='+flag+'');
                reload_table();
              }else{
                $.achtung({message: jsonResponse.message, timeout:5});
              }
              achtungHideLoader();
            }

          });

      }else{
        return false;
      }
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
  .det-tbl tbody tr.row-disabled { opacity: 0.5; background-color: #f5f5f5 !important; }
  .apv-panel { padding: 12px 14px; font-size: 12px; background: #f8fafd; border-bottom: 1px solid #d0dce8; }
  .apv-panel table td:first-child { width: 170px; color: #555; font-weight: 600; padding: 5px 0; vertical-align: top; }
  .apv-notice { font-size: 12px; padding: 10px 14px; background: #fff9e6; border-left: 3px solid #f0ad4e; margin: 0; }
  .apv-notice ol { margin: 5px 0 0 18px; padding: 0; }
  .apv-notice li { margin-bottom: 3px; }
</style>

<?php
  // verifikator 1
  if($dt_detail_brg[0]['flag_jenis'] != 1){
    if( $this->session->userdata('user')->user_id != 1){
      if($dt_detail_brg[0]['tgl_pemeriksa'] == NULL) {
        $verifikator = ($flag=='medis')?'verifikator_m_1':'verifikator_nm_1';
        $user_ttd = $this->master->get_ttd_data($verifikator, 'reff_id');
        if ($user_ttd != $this->session->userdata('user')->user_id) {
          echo '<div class="alert alert-danger"><strong>Peringatan!</strong> Anda bukan sebagai verifikator.</div>'; exit;
        }
      }
      // verifikator 2
      if($dt_detail_brg[0]['tgl_pemeriksa']!=NULL AND $dt_detail_brg[0]['tgl_penyetuju']==NULL) {
        $verifikator = ($flag=='medis')?'verifikator_m_2':'verifikator_nm_2';
        $user_ttd = $this->master->get_ttd_data($verifikator, 'reff_id');
        if ($user_ttd != $this->session->userdata('user')->user_id) {
          echo '<div class="alert alert-danger"><strong>Peringatan!</strong> Anda bukan sebagai verifikator.</div>'; exit;
        }
      }
    }
  }
?>

<div class="det-wrap" style="margin-bottom:4px">
  <div class="det-hdr">
    <i class="fa fa-file-text-o"></i> Nomor Permintaan: <?php echo $dt_detail_brg[0]['kode_permohonan']?>
  </div>
</div>

<form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/persetujuan_pemb/App_persetujuan_pemb/process')?>" enctype="multipart/form-data">

  <input type="hidden" name="id_tc_permohonan" id="id_tc_permohonan" value="<?php echo $dt_detail_brg[0]['id_tc_permohonan']?>">

  <div class="row" style="margin:0 0 8px">
    <div class="col-xs-8" style="padding-left:0; padding-right:6px">
      <div class="det-wrap" style="margin:0">
        <div class="det-hdr"><i class="fa fa-pencil-square-o"></i> Form Persetujuan</div>
        <div class="apv-panel">
          <table style="width:100%">
            <tr>
              <td>Tanggal</td>
              <td><input type="text" class="form-control" value="<?php echo date('Y-m-d H:i:s')?>" style="width:160px" readonly></td>
            </tr>
            <tr>
              <td>Nomor Persetujuan</td>
              <td><input type="text" class="form-control" placeholder="<?php echo '____ '.$format_no_acc?>" readonly style="width:200px"></td>
            </tr>

            <?php if( $flag == 'medis') : ?>

              <?php if($dt_detail_brg[0]['tgl_pemeriksa']==NULL) :?>
              <tr>
                <td>Pemeriksa</td>
                <td>
                  <?php echo $this->master->get_ttd_data('verifikator_m_1','label'); ?> (<?php echo $this->master->get_ttd_data('verifikator_m_1','value'); ?>)
                  <span style="color:red; font-style:italic; font-size:11px"> &mdash; Menunggu persetujuan..</span>
                  <input type="hidden" name="pemeriksa" id="verifikator_m_1" value="<?php echo $this->master->get_ttd_data('verifikator_m_1','label'); ?>">
                </td>
              </tr>
              <tr>
                <td>Catatan</td>
                <td><textarea class="form-control" name="catatan_verifikator_m_1" id="catatan_verifikator_m_1" style="height:60px; width:100%" placeholder="Masukan catatan..."></textarea></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-top:6px">
                  <button type="button" class="btn btn-sm btn-success" onclick="approve('verifikator_m_1', <?php echo $dt_detail_brg[0]['id_tc_permohonan']; ?>,'Y','medis')"><i class="fa fa-check-circle"></i> Setuju</button>
                  <button type="button" class="btn btn-sm btn-danger" onclick="approve('verifikator_m_1', <?php echo $dt_detail_brg[0]['id_tc_permohonan']; ?>,'N','medis')"><i class="fa fa-times-circle"></i> Tidak</button>
                </td>
              </tr>
              <?php endif;?>

              <?php if($dt_detail_brg[0]['tgl_pemeriksa']!=NULL AND $dt_detail_brg[0]['tgl_penyetuju']==NULL) :?>
              <tr>
                <td>Menyetujui</td>
                <td>
                  <?php echo $this->master->get_ttd_data('verifikator_m_2','label'); ?> (<?php echo $this->master->get_ttd_data('verifikator_m_2','value'); ?>)
                  <span style="color:red; font-style:italic; font-size:11px"> &mdash; Menunggu persetujuan..</span>
                  <input type="hidden" name="pemeriksa" id="verifikator_m_2" value="<?php echo $this->master->get_ttd_data('verifikator_m_2','label'); ?>">
                </td>
              </tr>
              <tr>
                <td>Catatan</td>
                <td><textarea class="form-control" style="height:60px; width:100%" placeholder="Masukan catatan..."></textarea></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-top:6px">
                  <button type="button" class="btn btn-sm btn-success" onclick="approve('verifikator_m_2', <?php echo $dt_detail_brg[0]['id_tc_permohonan']; ?>,'Y','medis')"><i class="fa fa-check-circle"></i> Setuju</button>
                  <button type="button" class="btn btn-sm btn-danger" onclick="approve('verifikator_m_2', <?php echo $dt_detail_brg[0]['id_tc_permohonan']; ?>,'N','medis')"><i class="fa fa-times-circle"></i> Tidak</button>
                </td>
              </tr>
              <?php endif;?>

            <?php endif;?>

            <?php if( $flag == 'non_medis') :?>

              <?php if($dt_detail_brg[0]['tgl_pemeriksa']==NULL) :?>
              <tr>
                <td>Pemeriksa</td>
                <td>
                  <?php echo $this->master->get_ttd_data('verifikator_nm_1','label'); ?> (<?php echo $this->master->get_ttd_data('verifikator_nm_1','value'); ?>)
                  <span style="color:red; font-style:italic; font-size:11px"> &mdash; Menunggu persetujuan..</span>
                  <input type="hidden" name="pemeriksa" id="verifikator_nm_1" value="<?php echo $this->master->get_ttd_data('verifikator_nm_1','label'); ?>">
                </td>
              </tr>
              <tr>
                <td>Catatan</td>
                <td><textarea class="form-control" style="height:60px; width:100%" name="catatan_penyetuju" placeholder="Masukan catatan..."></textarea></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-top:6px">
                  <button type="button" class="btn btn-sm btn-success" onclick="approve('verifikator_nm_1', <?php echo $dt_detail_brg[0]['id_tc_permohonan']; ?>,'Y','non_medis')"><i class="fa fa-check-circle"></i> Setuju</button>
                  <button type="button" class="btn btn-sm btn-danger" onclick="approve('verifikator_nm_1', <?php echo $dt_detail_brg[0]['id_tc_permohonan']; ?>,'N','non_medis')"><i class="fa fa-times-circle"></i> Tidak</button>
                </td>
              </tr>
              <?php endif?>

              <?php if($dt_detail_brg[0]['tgl_pemeriksa']!=NULL AND $dt_detail_brg[0]['tgl_penyetuju']==NULL) :?>
              <tr>
                <td>Menyetujui</td>
                <td>
                  <?php echo $this->master->get_ttd_data('verifikator_nm_2','label'); ?> (<?php echo $this->master->get_ttd_data('verifikator_nm_2','value'); ?>)
                  <span style="color:red; font-style:italic; font-size:11px"> &mdash; Menunggu persetujuan..</span>
                  <input type="hidden" name="pemeriksa" id="verifikator_nm_2" value="<?php echo $this->master->get_ttd_data('verifikator_nm_2','label'); ?>">
                </td>
              </tr>
              <tr>
                <td>Catatan</td>
                <td><textarea class="form-control" style="height:60px; width:100%" name="catatan_penyetuju" placeholder="Masukan catatan..."></textarea></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-top:6px">
                  <button type="button" class="btn btn-sm btn-success" onclick="approve('verifikator_nm_2', <?php echo $dt_detail_brg[0]['id_tc_permohonan']; ?>,'Y','non_medis')"><i class="fa fa-check-circle"></i> Setuju</button>
                  <button type="button" class="btn btn-sm btn-danger" onclick="approve('verifikator_nm_2', <?php echo $dt_detail_brg[0]['id_tc_permohonan']; ?>,'N','non_medis')"><i class="fa fa-times-circle"></i> Tidak</button>
                </td>
              </tr>
              <?php endif?>

            <?php endif?>

          </table>
        </div>
      </div>
    </div>
    <div class="col-xs-4" style="padding-right:0; padding-left:6px">
      <div class="det-wrap" style="margin:0; height:100%">
        <div class="det-hdr"><i class="fa fa-info-circle"></i> Keterangan</div>
        <div class="apv-notice">
          <strong>Keterangan dan Pemberitahuan!</strong>
          <ol>
            <li>Verifikator harus mengecek terlebih dahulu permintaan pembelian dari gudang.</li>
            <li>Dengan menyetujui, verifikator bertanggung jawab atas keputusan yang telah dibuat.</li>
            <li>Persetujuan yang sudah diproses tidak dapat diubah kembali.</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="det-wrap">
    <div class="det-hdr"><i class="fa fa-list"></i> Rincian Permintaan Barang</div>
    <table class="det-tbl">
      <thead>
        <tr>
          <th width="30px"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer"></th>
          <th width="40px">No</th>
          <th width="100px">Kode Barang</th>
          <th>Nama Barang</th>
          <th width="90px">Stok Akhir</th>
          <th width="100px">Jml Permohonan</th>
          <th width="100px">Jml yang di ACC</th>
          <th width="80px">Satuan Besar</th>
          <th width="60px">Rasio</th>
          <th width="100px">Keterangan</th>
        </tr>
      </thead>
      <tbody id="table-rincian-barang">
        <?php
          $no=0;

          $kode_count = array();
          $kode_first_index = array();
          $current_index = 0;

          foreach($dt_detail_brg as $row) {
            $row = (object)$row;
            $kode = isset($row->kode_brg) ? $row->kode_brg : $row->id;
            if (!isset($kode_count[$kode])) {
              $kode_count[$kode] = 0;
              $kode_first_index[$kode] = $current_index;
            }
            $kode_count[$kode]++;
            $current_index++;
          }

          $loop_index = 0;
          foreach($dt_detail_brg as $row_dt) :
          $no++;
          $row_dt = (object)$row_dt;
          $kode_brg = isset($row_dt->kode_brg) ? $row_dt->kode_brg : '<span class="red">[free text]</span>';
          $ex_kode_brg = isset($row_dt->kode_brg) ? $row_dt->kode_brg : $row_dt->id;
          $jumlah_stok = isset($row_dt->jumlah_stok_sebelumnya) ? $row_dt->jumlah_stok_sebelumnya : 0;
          $satuan = isset($row_dt->satuan_kecil) ? $row_dt->satuan_kecil : $row_dt->satuan_besar;
          $rasio = isset($row_dt->rasio) ? $row_dt->rasio : 1;

          $is_duplicate = isset($kode_count[$ex_kode_brg]) && $kode_count[$ex_kode_brg] > 1;
          $is_first_occurrence = ($loop_index === $kode_first_index[$ex_kode_brg]);
          $should_disable_duplicate = ($is_duplicate && !$is_first_occurrence);

          $jml_acc_pemeriksa = isset($row_dt->jml_acc_pemeriksa) ? $row_dt->jml_acc_pemeriksa : null;
          $tgl_pemeriksa = isset($row_dt->tgl_pemeriksa) ? $row_dt->tgl_pemeriksa : null;
          $should_disable_reviewed = (!empty($tgl_pemeriksa) && ($jml_acc_pemeriksa == 0 || $jml_acc_pemeriksa == null)) ? true : false;

          $should_disable = ($should_disable_duplicate || $should_disable_reviewed);
          $duplicate_label = ($is_duplicate && isset($row_dt->kode_brg)) ? '<span class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Duplikat</span>' : '';
          $review_label = ($should_disable_reviewed) ? '<span class="label label-danger"><i class="fa fa-ban"></i> Ditolak</span>' : '';
          $disabled_attr = $should_disable ? 'disabled' : '';
          $disabled_class = $should_disable ? 'class="row-disabled"' : '';

          $tooltip_msg = '';
          if($should_disable_duplicate) {
            $tooltip_msg = 'Baris ini di-disable karena duplikat';
          } elseif($should_disable_reviewed) {
            $tooltip_msg = 'Baris ini di-disable, barang di-reject oleh pemeriksa';
          }
        ?>
        <tr <?php echo $disabled_class; ?>>
          <td class="center">
            <?php if(isset($row_dt->id_tc_permohonan_det)) : ?>
              <?php if($row_dt->status_po == NULL) : ?>
                <input type="checkbox" class="checkbox_brg_<?php echo $flag?>_<?php echo $id?>"
                  id="checkbox_brg_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->kode_brg?>"
                  value="<?php echo $row_dt->kode_brg?>"
                  data-id="<?php echo $row_dt->id_tc_permohonan_det; ?>"
                  onClick="checkOne(this);" style="cursor:pointer"
                  <?php echo $disabled_attr; ?> title="<?php echo $tooltip_msg; ?>">
              <?php
                else:
                  echo '<i class="fa fa-check-circle bigger-150 green"></i>';
                endif;
              ?>
            <?php else:?>
              <input type="checkbox" class="checkbox_brg_<?php echo $flag?>_<?php echo $id?>"
                id="checkbox_brg_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id?>"
                value="<?php echo $row_dt->id?>"
                data-id="<?php echo $row_dt->id; ?>"
                onClick="checkOne(this);" style="cursor:pointer"
                <?php echo $disabled_attr; ?> title="<?php echo $tooltip_msg; ?>">
            <?php endif;?>
          </td>
          <td class="center"><?php echo $no?></td>
          <td><?php echo $kode_brg; echo $duplicate_label; echo $review_label; ?></td>
          <td><?php echo $row_dt->nama_brg?></td>
          <td class="center"><?php echo $jumlah_stok.' '.$satuan?></td>
          <td class="center" id="jml_permohonan_<?php echo $flag?>_<?php echo $id?>_<?php echo $ex_kode_brg?>">
            <input type="hidden" id="jml_permohonan_hidden_<?php echo $flag?>_<?php echo $id?>_<?php echo $ex_kode_brg?>" value="<?php echo $row_dt->jml_besar?>">
            <?php echo number_format($row_dt->jml_besar, 2)?>
          </td>
          <td class="center">
            <?php if(isset($row_dt->id_tc_permohonan_det)) : ?>
            <?php
              if($row_dt->status_po == NULL) :
                if($row_dt->jml_acc_pemeriksa > 0 ){
                  echo '<input type="hidden" id="jml_acc_pemeriksa_'.$flag.'_'.$id.'_'.$ex_kode_brg.'" value="'.$row_dt->jml_acc_pemeriksa.'">';
                }
            ?>
              <input type="number" name="jml_acc[<?php echo $ex_kode_brg?>]"
                id="form_input_<?php echo $flag?>_<?php echo $id?>_<?php echo $ex_kode_brg?>"
                style="width:70px;height:38px;text-align:center"
                <?php echo $disabled_attr; ?> title="<?php echo $tooltip_msg; ?>">
              <input type="hidden" class="is_free_text" name="is_free_text[<?php echo $ex_kode_brg?>]"
                id="is_free_text<?php echo $flag?>_<?php echo $id?>_<?php echo $ex_kode_brg?>"
                value="N">
            <?php
              else:
                echo number_format($row_dt->jml_acc_penyetuju, 2);
              endif;
              ?>
            <?php else: ?>
              <input type="number" name="jml_acc[<?php echo $ex_kode_brg?>]"
                id="form_input_<?php echo $flag?>_<?php echo $id?>_<?php echo $ex_kode_brg?>"
                style="width:70px;height:38px;text-align:center"
                <?php echo $disabled_attr; ?> title="<?php echo $tooltip_msg; ?>">
              <input type="hidden" class="is_free_text" name="is_free_text[<?php echo $ex_kode_brg?>]"
                id="is_free_text<?php echo $flag?>_<?php echo $id?>_<?php echo $ex_kode_brg?>"
                value="Y">
            <?php endif; ?>
          </td>
          <td class="center"><?php echo $satuan?></td>
          <td class="center"><?php echo $rasio?></td>
          <td class="center"><?php echo $row_dt->keterangan?></td>
        </tr>
        <?php $loop_index++; ?>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>

</form>
