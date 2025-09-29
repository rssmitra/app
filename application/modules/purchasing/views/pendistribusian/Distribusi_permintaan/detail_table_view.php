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
          <th class="center" width="30px">No</th>
          <th style="width: 100px">Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center" style="width: 200px">Jumlah Permintaan</th>
        </tr>
        <?php 
          $no=0; 
          $arr_total_biaya = array();
          foreach($dt_detail_brg as $row_dt) : $no++;
        ?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center"><?php echo $row_dt->jumlah_permintaan;?> <?php echo $row_dt->satuan_kecil?></td>
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
    Status Verifikasi. <?php echo isset($dt_detail_brg[0])?($dt_detail_brg[0]->status_acc == 1)?'<span style="color: green">Disetujui</span>':'<span style="color: red">Ditolak</span>':''?><br>

    <hr>
    <span style="font-weight: bold; font-size: 14px">Distribusi & Penerimaan Barang</span><br><br>
    No. Pengiriman. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->nomor_pengiriman:''?><br>
    Yang Menyerahkan. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->yg_serah:''?><br>
    Tanggal Diterima. <?php echo isset($dt_detail_brg[0])?($dt_detail_brg[0]->tgl_input_terima !='0000-00-00 00:00:00')?$this->tanggal->formatDateDmy($dt_detail_brg[0]->tgl_input_terima):'-':''?><br>
    Diterima Oleh. <?php echo isset($dt_detail_brg[0])?$dt_detail_brg[0]->yg_terima:''?><br>
    <br>



    </form>

  </div><!-- /.col -->
</div><!-- /.row -->


