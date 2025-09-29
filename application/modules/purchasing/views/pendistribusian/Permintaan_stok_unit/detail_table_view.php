<div class="row">
  <div class="col-xs-12" style="margin-left: 10px">

    <form class="form-horizontal" method="post" id="form_permintaan" action="#" enctype="multipart/form-data" >

    <!-- PAGE rasio BEGINS -->
     <span style="font-weight: bold; font-size: 14px">Permintaan Stok Unit</span><br><br>
      <span>No Permintaan. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->nomor_permintaan:''?></span><br>
      <span>Tanggal. <?php echo isset($dt_detail_brg[0])?$this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_permintaan):''?></span><br>
      <span>Bagian/Unit. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->nama_bagian:''?></span><br><br>
      Daftar Barang yang diminta :<br>
      <table class="table table-bordered" width="100%">
        <tr style="background-color: #0d528021">
          <th rowspan="2" class="center" width="30px">No</th>
          <th rowspan="2" style="width: 100px">Kode Barang</th>
          <th rowspan="2">Nama Barang</th>
          <th rowspan="2" width="50px">BHP?</th>
          <th rowspan="2" class="center" style="width: 100px">Stok Akhir Unit</th>
          <th class="center" style="width: 100px" colspan="2">Permintaan</th>
          <th class="center" style="width: 100px" colspan="2">Verifikasi</th>
          <th class="center" style="width: 100px" colspan="2">Distribusi</th>
          <th class="center" style="width: 100px" colspan="2">Penerimaan</th>
        </tr>
        <tr style="background-color: #0d528021">
          <th class="center" style="width: 70px">Qty</th>
          <th class="center" style="width: 120px">Note</th>
          <th class="center" style="width: 70px">Qty</th>
          <th class="center" style="width: 120px">Note</th>
          <th class="center" style="width: 70px">Qty</th>
          <th class="center" style="width: 120px">Petugas</th>
          <th class="center" style="width: 70px">Qty</th>
          <th class="center" style="width: 120px">Penerima</th>
        </tr>
        <?php 
          $no=0; 
          $arr_total_biaya = array();
          foreach($dt_detail_brg as $row_dt) : $no++;
          $is_bhp = ($row_dt->is_bhp == 1)?'<i class="fa fa-check green bigger-120"></i>':'';
          $kode_brg = ($row_dt->rev_kode_brg != null && $row_dt->rev_kode_brg != '') ? '<s style="color: red">'.$row_dt->kode_brg.'</s> &nbsp; '.$row_dt->rev_kode_brg : $row_dt->kode_brg;
          $nama_brg = ($row_dt->rev_kode_brg != null && $row_dt->rev_kode_brg != '') ? '<s style="color: red">'.$row_dt->nama_brg.'</s> &nbsp; '.$row_dt->revisi_nama_brg : $row_dt->nama_brg;
          $qty = ($row_dt->rev_kode_brg != null && $row_dt->rev_kode_brg != '') ? '<s style="color: red">'.$row_dt->jumlah_permintaan.'</s> &nbsp; '.$row_dt->rev_qty : $row_dt->jumlah_permintaan;
          $txt_verif = ($row_dt->status_verif == 1) ? '' : '<span style="color: red">[Ditolak]</span><br>';
        ?>
          <tr <?php echo ($row_dt->status_verif != 1) ? 'style="background-color: #efdad3ff"' : '' ?>>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $kode_brg?></td>
            <td><?php echo $nama_brg?></td>
            <td align="center"><?php echo $is_bhp?></td>
            <td class="center"><?php echo $row_dt->jumlah_stok_sebelumnya;?> <?php echo $row_dt->satuan_kecil?></td>
            <td class="center"><?php echo $qty;?> <?php echo $row_dt->satuan_kecil?></td>
            <td><?php echo $row_dt->keterangan_permintaan?></td>
            <td align="center"><?php echo $row_dt->jml_acc_atasan?></td>
            <td><?php echo $txt_verif.''.$row_dt->keterangan_verif?></td>
            <td align="center"><?php echo $row_dt->jumlah_kirim?></td>
            <td><?php echo $row_dt->petugas_kirim?></td>
            <td align="center"><?php echo $row_dt->jumlah_penerimaan?></td>
            <td><?php echo $row_dt->petugas_terima?></td>
          </tr>
        <?php endforeach;?>
      </table>
      <br>
      <p>
        Keterangan :<br> <?php echo isset($dt_detail_brg[0])?ucfirst($dt_detail_brg[0]->catatan):''?>
      </p>
    <!-- PAGE rasio ENDS -->
    <hr>
    <span style="font-weight: bold; font-size: 14px">Verifikasi Permintaan</span><br><br>
    No Verifikasi. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->no_acc:''?><br>
    Tanggal Verifikasi. <?php echo isset($dt_detail_brg[0])?($dt_detail_brg[0]->tgl_acc !='0000-00-00 00:00:00')?$this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_acc):'-':''?><br>
    Disetujui Oleh. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->acc_by:''?><br>
    Status Verifikasi. 
    <?php
      if($dt_detail_brg[0]->tgl_acc == null){
        echo '<span style="color: red">Belum diverifikasi</span>';
      }else{
        echo isset($dt_detail_brg[0])?($dt_detail_brg[0]->status_acc == 1)?'<span style="color: green">Disetujui</span>':'<span style="color: red">Ditolak</span>':'';
      }
    ?>
    <br>
    Catatan Verifikator, <?php echo isset($dt_detail_brg[0])?ucfirst($dt_detail_brg[0]->acc_note):''?>

    <hr>
    <span style="font-weight: bold; font-size: 14px">Distribusi & Penerimaan Barang</span><br><br>
    Tgl. Kirim. <?php echo isset($dt_detail_brg[0])?($dt_detail_brg[0]->tgl_pengiriman !='0000-00-00 00:00:00')?$this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_pengiriman):'-':''?><br>
    Yang Menyerahkan. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->yg_serah:''?><br>
    <br>
    Tanggal Diterima. <?php echo isset($dt_detail_brg[0])?($dt_detail_brg[0]->tgl_input_terima !='0000-00-00 00:00:00')?$this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_input_terima):'-':''?><br>
    Diterima Oleh. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->yg_terima:''?><br>
    <br>



    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


