<div class="row">

  <div class="col-xs-12">

    <!-- breadcrumbs -->
    <div class="page-header">  
      <h1>
        <?php echo $title?>        
        <small> <i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div> 

    <center><span style="font-size: 12px;"><strong><u>PENGAMBILAN OBAT</u></strong><br>
    No. PBLOG-<?php echo $value->kode_trans_far?> - <?php echo $value->no_resep?>
    </span></center>

    <table>
      <tr>
        <td width="100px">Tanggal</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($value->tgl_trans) ?></td>
      </tr>
      <tr>
        <td width="100px">Nama Pasien</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($value->nama_pasien)?></td>
      </tr>
      <tr>
        <td width="100px">No. MR</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $value->no_mr?></td>
      </tr>
      <tr>
        <td width="100px">Dokter</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($value->dokter_pengirim)?></td>
      </tr>
      <tr>
        <td width="100px">Unit/Bagian</td>
        <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $value->kode_bagian_asal) )?></td>
      </tr>
    </table>

    <table style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
      <thead>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
            <td style="text-align:center; width: 5%; border-bottom: 1px solid black; border-collapse: collapse">No</td>
            <td style="border-bottom: 1px solid black; width: 60%; border-collapse: collapse">Nama Obat</td>
            <td style="text-align:center; width: 10%; border-bottom: 1px solid black; border-collapse: collapse">Jumlah</td>
            <td style="text-align:center; width: 10%; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
            <td style="text-align:center; width: 15%; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</td>
          </tr>
      </thead>
          <?php 
            $no=0; 
            foreach($log_mutasi as $key_dt=>$row_dt) : 
            $dt_header = $log_mutasi[$key_dt][0]; 
              foreach ($row_dt as $key_rd => $value_rd) :
                 $no++;  
            $sub_total = $value_rd->harga_satuan * $value_rd->jumlah_mutasi_obat;
            $nama_brg = ($value_rd->nama_brg == $value_rd->nama_brg_update) ? $value_rd->nama_brg : '<i><s>'.$value_rd->nama_brg.'</s></i> &nbsp; '.$value_rd->nama_brg_update.'';
            $arr_total[] = $sub_total;
          ?>

            <tr>
              <td style="text-align:center; width: 5%; border-collapse: collapse"><?php echo $no?>.</td>
              <td style="border-collapse: collapse; width: 60%; "><?php echo $nama_brg;?></td>
              <td style="text-align:center; width: 10%; border-collapse: collapse"><?php echo number_format($value_rd->jumlah_mutasi_obat);?></td>
              <td style="text-align:left; width: 10%; border-collapse: collapse"><?php echo $value_rd->satuan_kecil?></td>
              <td style="text-align:right; width: 15%; border-collapse: collapse"><?php echo number_format($sub_total)?></td>
            </tr>
            
          <?php endforeach; endforeach;?>

            <tr>
              <td colspan="4" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
            </tr>
            <tr>
              <td colspan="5" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
              <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
              </td>
            </tr>

    </table>
    <p style="font-size: 12px; font-style: italic">Kode Log : <?php echo $dt_header->kode_log_mutasi_obat?></p>

    Catatan : Khusus Resep Obat Kronis
    <table style="width: 100% !important; text-align: center">
      <tr>
        <td style="text-align: left; width: 30%">&nbsp;</td>
        <td style="text-align: center; width: 40%">&nbsp;</td>
         <td style="text-align: center; width: 30%">
            <span style="font-size: 14px"><b>Petugas</b></span><br>
            <?php echo isset($dt_header->created_by)?$dt_header->created_by:'-';?>
            <br>
            <?php echo isset($dt_header->created_date)?$this->tanggal->formatDateTime($dt_header->created_date):'-';?>

          </td>
      </tr>
      
    </table>

    <!-- input hidden -->
    <input type="hidden" name="no_resep" id="no_resep" value="<?php echo $value->kode_pesan_resep?>">
    <input type="hidden" name="no_mr" id="no_mr" value="<?php echo $value->no_mr?>">

    <button onclick="getMenu('farmasi/Proses_resep_prb');" class="btn btn-xs btn-purple" title="Lihat Riwayat Resep">
        <i class="fa fa-history dark"></i> Riwayat Resep PRB
    </button>
    <button onclick="PopupCenter('farmasi/Proses_resep_prb/nota_farmasi/<?php echo $value->kode_trans_far?>?flag=<?php echo $flag; ?>&kode_log_mutasi=<?php echo $dt_header->kode_log_mutasi_obat?>')" class="btn btn-xs btn-success" title="Nota Farmasi">
        <i class="fa fa-print dark"></i> Nota Farmasi
    </button>

  </div>

</div>
