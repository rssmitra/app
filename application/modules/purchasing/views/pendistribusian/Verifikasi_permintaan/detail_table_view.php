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
      alert('Silahkan ceklis rincian barang yang disetujui');
    }else{
      // proses approve
      if(confirm('Are you sure?')){
        $.ajax({
            url: 'purchasing/pendistribusian/Verifikasi_permintaan/prosess_approval',
            type: "post",
            data: formData,
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
                $('#page-area-content').load('purchasing/pendistribusian/Verifikasi_permintaan?flag='+flag+'&status='+app+'');
                reload_table();
              }else{
                $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});
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
          foreach($dt_detail_brg as $row_dt) : $no++;
          $is_bhp = $row_dt->is_bhp == 1 ? '<i class="fa fa-check green"></i>' : '';
        ?>

          <!-- input hidden kode brg -->
           <input type="hidden" name="list_brg[<?php echo $row_dt->id_tc_permintaan_inst_det?>]" class="form-control" value="<?php echo $row_dt->id_tc_permintaan_inst_det?>" >

          <tr>
            <td class="center"><input type="checkbox" class="checkbox_brg_<?php echo $flag?>_<?php echo $id?>" id="checkbox_brg_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id_tc_permintaan_inst_det?>" name="selected[<?php echo $row_dt->id_tc_permintaan_inst_det?>]" class="form-control" value="<?php echo $row_dt->id_tc_permintaan_inst_det?>" onClick="checkOne(this);" style="cursor:pointer; width: 17px" <?php echo ($row_dt->status_verif == 1) ? 'checked': ''?>></td>
            <td class="center" style="vertical-align: middle"><?php echo $no?></td>
            <td style="vertical-align: middle"><?php echo $row_dt->kode_brg?></td>
            <td style="vertical-align: middle"><?php echo $row_dt->nama_brg?></td>
            <td style="vertical-align: middle; text-align: center"><?php echo $is_bhp?></td>
            <td style="vertical-align: middle; text-align: center"><?php echo $row_dt->jumlah_stok_sebelumnya?></td>
            <td class="center" style="vertical-align: middle"><span id="jml_diminta_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id_tc_permintaan_inst_det?>"><?php echo $row_dt->jumlah_permintaan;?></span> <?php echo $row_dt->satuan_kecil?></td>
            <td class="center" style="vertical-align: middle">
              <input type="text" name="jml_acc[<?php echo $row_dt->id_tc_permintaan_inst_det?>]" id="jml_acc_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->id_tc_permintaan_inst_det?>" value="<?php echo $row_dt->jml_acc_atasan;?>" style="width: 50px; text-align: center; border: none !important; border-bottom: 1px solid grey !important; font-size: 14px; font-weight: bold;" class="form-control">
            </td>
            <td class="center" style="vertical-align: middle">
              <input type="text" name="keterangan_verif[<?php echo $row_dt->id_tc_permintaan_inst_det?>]" id="keterangan_verif_jml_acc_<?php echo $row_dt->id_tc_permintaan_inst_det?>" value="<?php echo $row_dt->keterangan_verif;?>" style="text-align: left; border: none !important; border-bottom: 1px solid grey !important; width: 100% !important" class="form-control">
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


