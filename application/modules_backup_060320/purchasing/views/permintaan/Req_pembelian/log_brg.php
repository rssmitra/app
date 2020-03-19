<?php
  // echo '<pre>';print_r($log);die;
  $log_dt = json_decode($log['permohonan']->created_by);
  $petugas = isset($log_dt->fullname)?$log_dt->fullname:$log['permohonan']->username;
?>
<div class="page-header">
  <h1>
    Riwayat Aktifitas Pengguna
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      Log flow process
    </small>
  </h1>
</div><!-- /.page-header -->
<div class="row">
  <div class="col-xs-12">
    <div class="timeline-container timeline-style2">
      <span class="timeline-label">
        <b>Nomor : <?php echo $log['permohonan']->kode_permohonan?></b>
      </span>

      <div class="timeline-items">

        <!-- start begin from permohonan -->
        <div class="timeline-item clearfix">
          <div class="timeline-info">
            <span class="timeline-date"><?php echo $this->tanggal->formatDateTime($log['permohonan']->tgl_permohonan)?></span>

            <i class="timeline-indicator btn btn-info no-hover"></i>
          </div>

          <div class="widget-box transparent">
            <div class="widget-body">
              <div class="widget-main no-padding">
                <span class="bigger-110">
                  <a href="#" class="purple bolder"><?php echo ucwords($petugas)?></a>
                  mengajukan permohonan barang <br> <b>"<?php echo $log['permohonan']->nama_brg?>"</b> sebanyak <b><?php echo number_format($log['permohonan']->jml_acc_penyetuju, 2).' '.$log['permohonan']->satuan_besar?></b>
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- pemeriksa -->
        <?php if(!empty($log['permohonan']->tgl_pemeriksa) || !empty($log['permohonan']->pemeriksa) ) : ?>
        <div class="timeline-item clearfix">
          <div class="timeline-info">
            <span class="timeline-date"><?php echo $this->tanggal->formatDateTime($log['permohonan']->tgl_pemeriksa)?></span>
            <i class="timeline-indicator btn btn-info no-hover"></i>
          </div>
          <div class="widget-box transparent">
            <div class="widget-body">
              <div class="widget-main no-padding">
                Diperiksa oleh <b><?php echo $log['permohonan']->pemeriksa; ?></b>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- approval -->
        <?php if(!empty($log['permohonan']->tgl_penyetuju) || !empty($log['permohonan']->penyetuju) || !empty($log['permohonan']->tgl_acc) ) : ?>
        <div class="timeline-item clearfix">
          <div class="timeline-info">
            <span class="timeline-date">
              <?php echo ($log['permohonan']->tgl_penyetuju) ? $this->tanggal->formatDateTime($log['permohonan']->tgl_penyetuju) : $this->tanggal->formatDateShort($log['permohonan']->tgl_acc)?></span>
            <i class="timeline-indicator btn btn-info no-hover"></i>
          </div>
          <div class="widget-box transparent">
            <div class="widget-body">
              <div class="widget-main no-padding">
                Disetujui oleh <b><?php echo ($log['permohonan']->penyetuju) ? ucfirst($log['permohonan']->penyetuju) : ucfirst($log['permohonan']->user_acc_name); ?></b> sebanyak <b><?php echo number_format($log['permohonan']->jml_acc_penyetuju,2); ?> <?php echo $log['permohonan']->satuan_besar; ?></b>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- log po -->
        <?php $no=0; foreach($log['po'] as $row_po) : $no++; ?>
        <div class="timeline-item clearfix">
          <div class="timeline-info">
            <span class="timeline-date">
              <?php echo ($row_po->tgl_po) ? $this->tanggal->formatDateShort($row_po->tgl_po) : '' ?></span>
            <i class="timeline-indicator btn btn-info no-hover"></i>
          </div>
          <div class="widget-box transparent">
            <div class="widget-body">
              <div class="widget-main no-padding">
                Penerbitan PO oleh <b>"<?php echo $row_po->diajukan_oleh; ?>"</b> kepada supplier <b>"<?php echo $row_po->namasupplier; ?>"</b>dengan Nomor PO <b>"<?php echo $row_po->no_po; ?>"</b> <br>dengan rincian sebagai berikut :<br>
                <table class="table table-bordered table-hover" border="1">
                  <thead>
                    <tr class="orange" style="background-color: blanchedalmond">
                      <th class="center" width="30px">No</th>
                      <th>Nama Barang</th>
                      <th width="100px">Rasio</th>
                      <th width="100px">Jumlah</th>
                      <th width="100px">Harga Satuan</th>
                      <th width="100px" align="center">Diskon (%)</th>
                      <th width="100px" align="center">PPN</th>
                      <th width="100px">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr style="background-color: #a9a9a91f;">
                      <td align="center"><?php echo $no; ?></td>
                      <td><?php echo $log['permohonan']->kode_brg.' - '.$log['permohonan']->nama_brg; ?></td>
                      <td align="center"><?php echo $row_po->content; ?></td>
                      <td align="center"><?php echo number_format($row_po->jumlah_besar_acc, 2).' '.$log['permohonan']->satuan_besar; ?></td>
                      <td align="right"><?php echo number_format($row_po->harga_satuan); ?></td>
                      <td align="center"><?php echo $row_po->discount; ?></td>
                      <td align="right"><?php echo number_format($row_po->ppn); ?></td>
                      <td align="right"><?php echo number_format($row_po->jumlah_harga_netto); ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach;?>
        
        <!-- log penerimaan -->
        <?php 
          $no=1; 
          foreach($log['penerimaan'] as $row_penerimaan) : 
           
          $jumlah_dikirim[$no] = $row_penerimaan->jumlah_kirim;
          $total_dikirim = array_sum($jumlah_dikirim);
          $sisa[$no] = $row_penerimaan->jumlah_pesan - $total_dikirim;
          $jumlah_pesan_akhir[$no] = ( $no == 1 ) ? $row_penerimaan->jumlah_pesan : $sisa[$no-1] ;

          $field = ( $no == 1 ) ? 'Jumlah Pesan' : 'Sisa Barang Terakhir' ;
          $status = ( $sisa[$no] != 0 ) ? '<span class="red">Belum Sesuai PO</span>' : '<span class="green">Selesai</span>' ;
        ?>
        <div class="timeline-item clearfix">
          <div class="timeline-info">
            <span class="timeline-date">
              <?php echo ($row_penerimaan->tgl_penerimaan) ? $this->tanggal->formatDateShort($row_penerimaan->tgl_penerimaan) : '' ?></span>
            <i class="timeline-indicator btn btn-info no-hover"></i>
          </div>
          <div class="widget-box transparent">
            <div class="widget-body">
              <div class="widget-main no-padding">
                Barang diterima oleh <b>"<?php echo $row_penerimaan->petugas; ?>"</b> dari supplier <b>"<?php echo $row_penerimaan->dikirim; ?>"</b> <br>dengan rincian sebagai berikut :<br>
                <table class="table table-bordered table-hover" border="1">
                  <thead>
                    <tr class="blue" style="background-color: lightblue">
                      <th class="center">No</th>
                      <th>Kode Penerimaan</th>
                      <th>Nomor Faktur</th>
                      <th width="100px" class="center"><?php echo $field; ?></th>
                      <th width="100px" class="center">Jumlah Dikirim</th>
                      <th width="100px" class="center">Sisa Blm Dikirim</th>
                      <th width="100px" class="center">Status</th>
                      <th width="100px" class="center">Diketahui</th>
                      <th width="100px" class="center">Disetujui</th>
                    </tr>
                  </thead>
                  <?php 
                    
                  ?>
                  <tbody>
                    <tr style="background-color: #a9a9a91f;">
                      <td align="center" width="30px"><?php echo $no; ?></td>
                      <td><?php echo $row_penerimaan->kode_penerimaan; ?></td>
                      <td><?php echo $row_penerimaan->no_faktur; ?></td>
                      <td align="center"><?php echo number_format($jumlah_pesan_akhir[$no]).' '.$log['permohonan']->satuan_besar; ?></td>
                      <td align="center"><?php echo number_format($row_penerimaan->jumlah_kirim).' '.$log['permohonan']->satuan_besar; ?></td>
                      <td align="center"><?php echo number_format($sisa[$no]).' '.$log['permohonan']->satuan_besar; ?></td>
                      <td><?php echo $status; ?></td>
                      <td><?php echo $row_penerimaan->diketahui; ?></td>
                      <td><?php echo $row_penerimaan->disetujui; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <?php $no++; endforeach;?>
        


      </div><!-- /.timeline-items -->
    </div>
     
  </div><!-- /.col -->
</div><!-- /.row -->


