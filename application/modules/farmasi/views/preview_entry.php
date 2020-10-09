<div class="row">

  <div class="col-xs-12">

    <!-- breadcrumbs -->
    <div class="page-header">  
      <h1>
        <?php echo $title?>        
        <small> <i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div> 

    <center><span style="font-size: 12px;"><strong><u>TRANSAKSI FARMASI</u></strong><br>
    No. <?php echo $resep[0]['kode_trans_far']?> - <?php echo strtoupper($resep[0]['no_resep'])?>
    </span></center>

    <table>
      <tr>
        <td width="100px">Tanggal</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($resep[0]['tgl_trans']) ?></td>
      </tr>
      <tr>
        <td width="100px">Nama Pasien</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep[0]['nama_pasien'])?></td>
      </tr>
      <tr>
        <td width="100px">No. MR</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['no_mr']?></td>
      </tr>
      <tr>
        <td width="100px">Dokter</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep[0]['dokter_pengirim'])?></td>
      </tr>
      <tr>
        <td width="100px">Unit/Bagian</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['nama_bagian']?></td>
      </tr>
    </table>

    <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
      <thead>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
            <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
            <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah Tebus</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Harga Satuan</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jasa R</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</td>
          </tr>
      </thead>
          <?php 
            $no=0; 
            foreach($resep as $key_dt=>$row_dt) : $no++; 
              $subtotal = ($row_dt['flag_resep'] == 'racikan') ? $row_dt['jasa_r'] : ($row_dt['harga_jual'] * $row_dt['jumlah_tebus']) + $row_dt['jasa_r']; 
              $arr_total[] = $subtotal;
              $desc = ($row_dt['flag_resep'] == 'racikan') ? 'Jasa Racikan Obat' : $row_dt['nama_brg'];
              $satuan = ($row_dt['satuan_kecil'] != null) ? $row_dt['satuan_kecil'] : $row_dt['satuan_brg'];
          ?>

            <tr>
              <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
              <td style="border-collapse: collapse"><?php echo $desc?></td>
              <td style="text-align:center; border-collapse: collapse"><?php echo ($row_dt['flag_resep'] == 'racikan') ? '' : $row_dt['jumlah_tebus'];?></td>
              <td style="text-align: center; border-collapse: collapse"><?php echo $satuan?></td>
              <td style="text-align:right; border-collapse: collapse"><?php echo ($row_dt['flag_resep'] == 'racikan') ? 0 : number_format($row_dt['harga_jual']);?></td>
              <td style="text-align:right; border-collapse: collapse"><?php echo number_format($row_dt['jasa_r'])?></td>
              <td style="text-align:right; border-collapse: collapse"><?php echo number_format($subtotal)?></td>
            </tr>
            <?php 
              if($row_dt['flag_resep'] == 'racikan') :
                foreach ($row_dt['racikan'][0] as $key => $value) {
                  $arr_total[] = ($value->harga_jual * $value->jumlah);
                  $subtotal_racikan = ($value->harga_jual * $value->jumlah);
                  echo '<tr>
                        <td style="text-align:center; border-collapse: collapse">&nbsp;</td>
                        <td style="border-collapse: collapse"> - '.$value->nama_brg.'</td>
                        <td style="text-align: center; border-collapse: collapse">'.$value->jumlah.'</td>
                        <td style="text-align: center; border-collapse: collapse">'.$value->satuan.'</td>
                        <td style="text-align: right; border-collapse: collapse">'.number_format($value->harga_jual).'</td>
                        <td style="text-align: right; border-collapse: collapse">0</td>
                        <td style="text-align: right; border-collapse: collapse">'.number_format($subtotal_racikan).'</td>
                      </tr>';
                }
              endif; 
            ?>

          <?php endforeach;?>

            <tr>
              <td colspan="6" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
            </tr>
            <tr>
              <td colspan="7" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
              <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
              </td>
            </tr>

    </table>

    Catatan : Obat yang sudah dibeli tidak bisa dikembalikan
    <table style="width: 100% !important; text-align: center">
      <tr>
        <td style="text-align: left; width: 30%">&nbsp;</td>
        <td style="text-align: center; width: 40%">&nbsp;</td>
        <td style="text-align: center; width: 30%">
          <span style="font-size: 14px"><b>Petugas</b></span><br>
          <?php $decode = json_decode($resep[0]['created_by']); echo isset($decode->fullname)?$decode->fullname:$this->session->userdata('user')->fullname;?>
          <br>

        </td>
      </tr>
      
    </table>

    <!-- input hidden -->
    <input type="hidden" name="no_resep" id="no_resep" value="<?php echo $resep[0]['kode_pesan_resep']?>">
    <input type="hidden" name="no_mr" id="no_mr" value="<?php echo $resep[0]['no_mr']?>">

    <button onclick="getMenu('farmasi/Entry_resep_ri_rj?flag=RJ');" class="btn btn-xs btn-default" title="Kembali ke Resep Rawat Jalan">
        <i class="fa fa-arrow-left dark"></i> Kembali ke Resep Rawat Jalan
    </button>
    <button onclick="getMenu('farmasi/Retur_obat');" class="btn btn-xs btn-purple" title="Lihat Riwayat Resep">
        <i class="fa fa-history dark"></i> Lihat Riwayat Resep
    </button>
    <button onclick="PopupCenter('farmasi/Process_entry_resep/nota_farmasi/<?php echo $resep[0]['kode_trans_far']?>')" class="btn btn-xs btn-warning" title="Nota Farmasi">
        <i class="fa fa-print dark"></i> Nota Farmasi
    </button>
    <button onclick="getMenu('farmasi/Etiket_obat/form_copy_resep/<?php echo $resep[0]['kode_trans_far']?>?flag=<?php echo $flag; ?>')" class="btn btn-xs btn-success" title="Copy Resep">
        <i class="fa fa-copy dark"></i> Copy Resep
    </button>
    <button onclick="getMenu('farmasi/Etiket_obat/form/<?php echo $resep[0]['kode_trans_far']?>?flag=<?php echo $flag; ?>')" class="btn btn-xs btn-primary" title="etiket">
      <i class="fa fa-ticket dark"></i> Etiket Obat
    </button>
    <?php if($status_lunas == 0) : ?>
    <button onclick="rollback_by_kode_trans_far(<?php echo $resep[0]['kode_trans_far']?>, '<?php echo strtolower($flag); ?>')" class="btn btn-xs btn-danger" title="rollback">
      <i class="fa fa-undo dark"></i> Rollback Resep
    </button>
  <?php else:
          echo '<span style="margin-left:-13%;position:absolute;transform: rotate(-25deg) !important; margin-top: -15%" class="stamp is-approved">Lunas</span>';
        endif;
  ?>

  </div>

</div>

<script type="text/javascript">
  
  function rollback_by_kode_trans_far(id, flag){
    preventDefault();
    if(confirm('Are you sure?')){
      $.ajax({
          url: 'farmasi/process_entry_resep/rollback_by_kode_trans_far',
          type: "post",
          data: { ID : id },
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
              if(flag == 'rj'){
                $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form/'+$('#no_resep').val()+'?mr='+$('#no_mr').val()+'&tipe_layanan='+flag+'');
              }

              if(flag == 'rl'){
                $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form_create?jenis_resep='+flag+'');
              }
              

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

</script>

