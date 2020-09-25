<div class="row">

  <div class="col-xs-12">

    <!-- breadcrumbs -->
    <div class="page-header">  
      <h1>
        <?php echo $title?>        
        <small> <i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div> 

    <center><span style="font-size: 12px;"><strong><u>LOG PENGAMBILAN OBAT</u></strong><br>
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

    <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
      <thead>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
            <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
            <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah Tebus</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Total Mutasi</td>
            <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Sisa Obat</td>
          </tr>
      </thead>
          <?php 
            $no=0; 
            foreach($resep as $key_dt=>$row_dt) : $no++; 
            $sisa = $row_dt->jumlah - $row_dt->log_jml_mutasi;
          ?>

            <tr>
              <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
              <td style="border-collapse: collapse"><?php echo $row_dt->nama_brg?></td>
              <td style="text-align:center; border-collapse: collapse"><?php echo number_format($row_dt->jumlah);?></td>
              <td style="text-align: center; border-collapse: collapse"><?php echo $row_dt->satuan_kecil; ?></td>
              <td style="text-align:center; border-collapse: collapse"><?php echo number_format($row_dt->log_jml_mutasi);?></td>
              <td style="text-align:center; border-collapse: collapse"><?php echo number_format((int)$sisa)?></td>
            </tr>

          <?php endforeach;?>

    </table>

    Catatan : Khusus resep PRB
    <table style="width: 100% !important; text-align: center">
      <tr>
        <td style="text-align: left; width: 30%">&nbsp;</td>
        <td style="text-align: center; width: 40%">&nbsp;</td>
        <td style="text-align: center; width: 30%">
          <span style="font-size: 14px"><b>Petugas</b></span><br>
          <?php echo isset($resep[0]->log_user_mutasi)?$resep[0]->log_user_mutasi:'-';?>
          <br>
          <?php echo isset($resep[0]->log_tgl_mutasi)?$this->tanggal->formatDateTime($resep[0]->log_tgl_mutasi):'-';?>

        </td>
      </tr>
      
    </table>

    <!-- input hidden -->
    <input type="hidden" name="no_resep" id="no_resep" value="<?php echo $value->kode_pesan_resep?>">
    <input type="hidden" name="no_mr" id="no_mr" value="<?php echo $value->no_mr?>">

    <button onclick="getMenu('farmasi/Proses_resep_prb');" class="btn btn-xs btn-purple" title="Lihat Riwayat Resep">
        <i class="fa fa-history dark"></i> Riwayat Resep PRB
    </button>
    <button onclick="PopupCenter('farmasi/Verifikasi_resep_prb/nota_farmasi/<?php echo $value->kode_trans_far?>?flag=RJ')" class="btn btn-xs btn-success" title="Nota Farmasi">
        <i class="fa fa-print dark"></i> Nota Farmasi
    </button>

  </div>

</div>
