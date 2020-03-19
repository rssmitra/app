<script type="text/javascript">

  function checkAll(elm) {

    if($(elm).prop("checked") == true){
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').each(function(){
        var kode_brg = $(this).val();
        var jml_permohonan = $('#jml_permohonan_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').text();
        var jml_pemeriksa = $('#jml_acc_pemeriksa_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val();
        var jml_input = (jml_pemeriksa > 0) ? jml_pemeriksa : jml_permohonan;
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( jml_input );
          $(this).prop("checked", true);
      });
    }else{
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').prop("checked", false);
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').each(function(){
        var kode_brg = $(this).val();
        $('#form_input_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').val( '' );
      });
    }

  }

  function checkOne(elm) {

    if($(elm).prop("checked") == true){
        var kode_brg = $(elm).val();
        var jml_permohonan = $('#jml_permohonan_<?php echo $flag?>_<?php echo $id?>_'+kode_brg+'').text();
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
    var selected_data = $("#table-rincian-barang input:checkbox:checked").map(function(){
      return $(this).val();
    }).toArray();
    var input_data = $("#table-rincian-barang input[type=number]").map(function(){
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
                    acc_value : input_data, 
                    approval_by : $('#'+field).val(),
                    verifikator : field,
                    flag_approval : app,
                    catatan : $('#catatan_'+field+'').val(),
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
<div class="row">
  <div class="col-xs-12">
    
    

    <div class="page-header">
      <h1><?php echo 'Nomor Permintaan : '.$dt_detail_brg[0]->kode_permohonan?></h1>
    </div>

    <?php
      // verifikator 1
      if( $this->session->userdata('user')->user_id != 1){
        if($dt_detail_brg[0]->tgl_pemeriksa == NULL) {
          $verifikator = ($flag=='medis')?'verifikator_m_1':'verifikator_nm_1';
          $user_ttd = $this->master->get_ttd_data($verifikator, 'reff_id');
          if ($user_ttd != $this->session->userdata('user')->user_id) {
            echo '<div class="alert alert-danger"><strong>Peringatan!</strong> Anda bukan sebagai verifikator.</div>'; exit;
          }
        }
        // verifikator 2
        if($dt_detail_brg[0]->tgl_pemeriksa!=NULL AND $dt_detail_brg[0]->tgl_penyetuju==NULL) {
          $verifikator = ($flag=='medis')?'verifikator_m_2':'verifikator_nm_2';
          $user_ttd = $this->master->get_ttd_data($verifikator, 'reff_id');
          if ($user_ttd != $this->session->userdata('user')->user_id) {
            echo '<div class="alert alert-danger"><strong>Peringatan!</strong> Anda bukan sebagai verifikator.</div>'; exit;
          }
        }
      }
      

    ?>

    <form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/persetujuan_pemb/App_persetujuan_pemb/process')?>" enctype="multipart/form-data" >

      <!-- hidden form -->
      <input type="hidden" name="id_tc_permohonan" id="id_tc_permohonan" value="<?php echo $dt_detail_brg[0]->id_tc_permohonan?>">

      <div class="col-xs-8 no-padding"> 
        <table border="0">

          <tr>
            <td width="35%" style="padding-bottom:5px;padding-top:5px;background-color: #aeccd03b;padding-left: 4px;"> Tanggal</td>
            <td> <input type="text" name="" class="form-control" value="<?php echo date('Y-m-d H:i:s')?>" style="width: 150px; margin-left: 5px"></td>
          </tr>
          <tr>
            <td width="35%" style="padding-bottom:5px;padding-top:5px;background-color: #aeccd03b;padding-left: 4px;"> Nomor Persetujuan</td>
            <td><input type="text" name="" class="form-control" placeholder="<?php echo '____ '.$format_no_acc?>" readonly style="width: 200px; margin-left: 5px"></td>
          </tr>
          
          <?php if( $flag == 'medis') : ?>

            <?php if($dt_detail_brg[0]->tgl_pemeriksa==NULL) :?>

              <tr>
                <td width="35%" style="padding-bottom:5px;padding-top:5px;vertical-align: top; background-color: #aeccd03b; padding-left: 4px;">Pemeriksa</td>
                <td style="padding-left:5px;padding-top: 5px; width: 350px" >
                  <?php echo $this->master->get_ttd_data('verifikator_m_1','label'); ?> (<?php echo $this->master->get_ttd_data('verifikator_m_1','value'); ?>)<br><span style="color: red"><i>Menunggu persetujuan..</i></span> 
                  <input type="hidden" name="pemeriksa" id="verifikator_m_1" value="<?php echo $this->master->get_ttd_data('verifikator_m_1','label'); ?>">
                </td>
              </tr>

              <tr>
                <td width="35%" style="padding-bottom:5px;padding-top:5px;vertical-align: top; background-color: #aeccd03b;padding-left: 4px;"></td>
                <td style="padding-left:5px;">
                <textarea class="form-control" name="catatan_verifikator_m_1" id="catatan_verifikator_m_1" style="height:70px !important; width: 100%" placeholder="Masukan catatan..."></textarea>
                </td>
              </tr>

              <tr>
                <td colspan="2" align="center" style="padding-top: 5px !important">
                  <button type="button" class="btn btn-xs btn-success" onclick="approve('verifikator_m_1', <?php echo $dt_detail_brg[0]->id_tc_permohonan; ?>,'Y','medis')"><i class="fa fa-check-circle"></i> Setuju</button>
                  <button type="button" class="btn btn-xs btn-danger" onclick="approve('verifikator_m_1', <?php echo $dt_detail_brg[0]->id_tc_permohonan; ?>,'N','medis')"><i class="fa fa-times-circle"></i> Tidak </button>
                </td>
              </tr>

            <?php endif;?>
            
            <?php if($dt_detail_brg[0]->tgl_pemeriksa!=NULL AND $dt_detail_brg[0]->tgl_penyetuju==NULL) :?>

                <tr>
                  <td width="35%" style="padding-bottom:5px;padding-top:5px;vertical-align: top; background-color: #aeccd03b; padding-left: 4px;">Menyetujui</td>
                  <td style="padding-left:5px;padding-top: 5px; width: 350px" ><?php echo $this->master->get_ttd_data('verifikator_m_2','label'); ?> (<?php echo $this->master->get_ttd_data('verifikator_m_2','value'); ?>)<br><span style="color: red"><i>Menunggu persetujuan..</i></span>
                  <input type="hidden" name="pemeriksa" id="verifikator_m_2" value="<?php echo $this->master->get_ttd_data('verifikator_m_2','label'); ?>">
                  </td>
                </tr>

                <tr>
                  <td width="35%" style="padding-bottom:5px;padding-top:5px;vertical-align: top; background-color: #aeccd03b;padding-left: 4px;"></td>
                  <td style="padding-left:5px;">
                    <textarea class="form-control" style="height:70px !important; width: 100%" placeholder="Masukan catatan..."></textarea>
                  </td>
                </tr>

                <tr>
                  <td colspan="2" align="center" style="padding-top: 5px !important">
                  <button type="button" class="btn btn-xs btn-success" onclick="approve('verifikator_m_2', <?php echo $dt_detail_brg[0]->id_tc_permohonan; ?>,'Y','medis')"><i class="fa fa-check-circle"></i> Setuju</button>
                    <button type="button" class="btn btn-xs btn-danger" onclick="approve('verifikator_m_2', <?php echo $dt_detail_brg[0]->id_tc_permohonan; ?>,'N', 'medis')"><i class="fa fa-times-circle"></i> Tidak </button>
                  </td>
                </tr>

            <?php endif;?>
          
          <?php endif;?>

          <?php if( $flag == 'non_medis') :?>

            <?php if($dt_detail_brg[0]->tgl_pemeriksa==NULL) :?>
            
            <tr>
              <td width="35%" style="padding-bottom:5px;padding-top:5px;vertical-align: top; background-color: #aeccd03b; padding-left: 4px;">Pemeriksa</td>
              <td style="padding-left:5px;padding-top: 5px; width: 350px" ><?php echo $this->master->get_ttd_data('verifikator_nm_1','label'); ?> (<?php echo $this->master->get_ttd_data('verifikator_nm_1','value'); ?>)<br><span style="color: red"><i>Menunggu persetujuan..</i></span>
              <input type="hidden" name="pemeriksa" id="verifikator_nm_1" value="<?php echo $this->master->get_ttd_data('verifikator_nm_1','label'); ?>">
              </td>
            </tr>

            <tr>
              <td width="35%" style="padding-bottom:5px;padding-top:5px;vertical-align: top; background-color: #aeccd03b;padding-left: 4px;"></td>
              <td style="padding-left:5px;" >
              <textarea class="form-control" style="height:70px !important; width: 100%" name="catatan_penyetuju" placeholder="Masukan catatan..."></textarea>
              </td>
            </tr>
            
            <tr>
              <td colspan="2" align="center" style="padding-top: 5px !important">
                <button type="button" class="btn btn-xs btn-success" onclick="approve('verifikator_nm_1', <?php echo $dt_detail_brg[0]->id_tc_permohonan; ?>,'Y','non_medis')"><i class="fa fa-check-circle"></i> Setuju</button>
                <button type="button" class="btn btn-xs btn-danger" onclick="approve('verifikator_nm_1', <?php echo $dt_detail_brg[0]->id_tc_permohonan; ?>,'N','non_medis')"><i class="fa fa-times-circle"></i> Tidak </button>
              </td>
            </tr>
            
          <?php endif?>

          <?php if($dt_detail_brg[0]->tgl_pemeriksa!=NULL AND $dt_detail_brg[0]->tgl_penyetuju==NULL) :?>
            <tr>
              <td width="35%" style="padding-bottom:5px;padding-top:5px;vertical-align: top; background-color: #aeccd03b; padding-left: 4px;">Menyetujui</td>
              <td style="padding-left:5px;padding-top: 5px; width: 350px" ><?php echo $this->master->get_ttd_data('verifikator_nm_2','label'); ?> (<?php echo $this->master->get_ttd_data('verifikator_nm_2','value'); ?>)<br><span style="color: red"><i>Menunggu persetujuan..</i></span>
              <input type="hidden" name="pemeriksa" id="verifikator_nm_2" value="<?php echo $this->master->get_ttd_data('verifikator_nm_2','label'); ?>">
              </td>
            </tr>

            <tr>
              <td width="35%" style="padding-bottom:5px;padding-top:5px;vertical-align: top; background-color: #aeccd03b;padding-left: 4px;"></td>
              <td style="padding-left:5px;" >
              <textarea class="form-control" style="height:70px !important; width: 100%" name="catatan_penyetuju" placeholder="Masukan catatan..."></textarea>
              </td>
            </tr>
            
            <tr>
              <td colspan="2" align="center" style="padding-top: 5px !important">
                <button type="button" class="btn btn-xs btn-success" onclick="approve('verifikator_nm_2', <?php echo $dt_detail_brg[0]->id_tc_permohonan; ?>,'Y','non_medis')"><i class="fa fa-check-circle"></i> Setuju</button>
                <button type="button" class="btn btn-xs btn-danger" onclick="approve('verifikator_nm_2', <?php echo $dt_detail_brg[0]->id_tc_permohonan; ?>,'N','non_medis')"><i class="fa fa-times-circle"></i> Tidak </button>
              </td>
            </tr>
          <?php endif?>

          <?php endif?>
          
        </table>
      </div>
      <div class="col-xs-4 no-padding"> 
      <blockquote style="font-size: 12px">
        <strong>Keterangan dan Pemberitahuan !</strong>
        <br>
        <ol>
          <li>Verifikator harus mengecek terlebih dahulu permintaan pembelian dari gudang </li>
          <li>Dengan menyetujui permintaan pembelian tersebut, maka verifikator bertanggung jawab atas keputusan yang telah dibuat</li>
          <li>Persetujuan yang sudah diproses tidak dapat diubah kembali.</li>
        </ol>
      </blockquote>
      </div>
      <div class="row">
      <div class="col-xs-12 no-padding"> 
      <!-- PAGE CONTENT BEGINS -->
        <h4>Rincian Permintaan Barang</h4>
        <table class="table table-bordered table-hovered" style="font-size:11px">
          <tr style="background-color: #87b87f3d;">
            <th width="30px" class="center"><input type="checkbox" class="form-control" onClick="checkAll(this);" style="cursor:pointer"></th>
            <th width="50px" class="center">No</th>
            <th width="100px">Kode Barang</th>
            <th>Nama Barang</th>
            <th width="100px" class="center">Stok Akhir</th>
            <th width="100px" class="center">Jumlah<br>Permohonan</th>
            <th width="100px" class="center">Jumlah Brg<br>yang di ACC</th>
            <th width="100px" class="center">Satuan Besar</th>
            <th width="100px" class="center">Rasio</th>
          </tr>
          <tbody id="table-rincian-barang">
          <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
            <tr>
              <td class="center">
                <?php
                  if($row_dt->status_po == NULL) :
                ?>
                <input type="checkbox" class="checkbox_brg_<?php echo $flag?>_<?php echo $id?>" id="checkbox_brg_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->kode_brg?>" class="form-control" value="<?php echo $row_dt->kode_brg?>" onClick="checkOne(this);" style="cursor:pointer">
                <?php 
                  else: 
                    echo '<i class="fa fa-check-circle bigger-150 green"></i>';
                  endif;
                ?>
              </td>
              <td class="center"><?php echo $no?></td>
              <td><?php echo $row_dt->kode_brg?></td>
              <td><?php echo $row_dt->nama_brg?></td>
              <td class="center"><?php echo $row_dt->jumlah_stok_sebelumnya.' '.$row_dt->satuan_kecil?></td>
              <td class="center" id="jml_permohonan_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->kode_brg?>"><?php echo number_format($row_dt->jml_besar, 2)?></td>
              <td class="center">
                <?php
                  if($row_dt->status_po == NULL) :
                    if($row_dt->jml_acc_pemeriksa > 0 ){
                      echo '<input type="hidden" id="jml_acc_pemeriksa_'.$flag.'_'.$id.'_'.$row_dt->kode_brg.'" value="'.$row_dt->jml_acc_pemeriksa.'">';
                    }
                ?>
                <input type="number" name="jml_acc[<?php echo $row_dt->kode_brg?>]" id="form_input_<?php echo $flag?>_<?php echo $id?>_<?php echo $row_dt->kode_brg?>" style="width:70px;height:45px;text-align:center">
              <?php 
                else:
                  echo number_format($row_dt->jml_acc_penyetuju, 2);
                endif;
              ?>
              </td>
              <td class="center"><?php echo $row_dt->satuan_besar?></td>
              <td class="center"><?php echo $row_dt->rasio?></td>
            </tr>
          <?php endforeach;?>
          </tbody>
        </table>
        <!-- PAGE CONTENT ENDS -->
        <!-- <center>
          <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-times-circle"></i> Tidak Disetujui</button>
          <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check-circle"></i> Disetujui</button>
        </center> -->
      </div>
      </div>
    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


