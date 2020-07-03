<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      Log rincian barang hasil stok opname unit
    </small>
  </h1>
</div><!-- /.page-header -->
<div class="row">
  <div class="col-xs-12">
    <div class="timeline-container timeline-style2">
      <span class="timeline-label">
        <b></b>
      </span>

      <div class="timeline-items">

        <?php foreach($log_barang as $row) :?>
        <!-- start begin from permohonan -->
        <div class="timeline-item clearfix">
          <div class="timeline-info">
            <span class="timeline-date"><?php echo $this->tanggal->formatDateTime($row->tgl_stok_opname)?></span>
            <i class="timeline-indicator btn btn-info no-hover"></i>
          </div>

          <div class="widget-box transparent">
            <div class="widget-body">
              <div class="widget-main no-padding">
                <span class="bigger-110">
                  <a href="#" class="purple bolder"><?php echo ucwords($row->nama_bagian)?></a><br>
                  <table class="table" style="width:50%;">
                    <thead>
                      <tr style="background: grey; color: white; font-weight: bold">
                        <td>Stok Sebelum</td>
                        <td>Hasil SO</td>
                        <td>Harga Satuan</td>
                        <td>Total</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><?php echo number_format($row->stok_sebelum)?></td>
                        <td><?php echo number_format($row->stok_sekarang)?></td>
                        <td align="right">
                          <?php 
                            $harga_satuan_kecil = $row->harga_pembelian_terakhir / $row->content;
                            echo number_format($harga_satuan_kecil);
                          ?>
                        </td>
                        <td align="right">
                          <?php 
                            $total = $harga_satuan_kecil * $row->stok_sekarang;
                            echo number_format($total);
                          ?>
                        </td>
                      </tr>
                    </tbody>
                    
                  </table>
                  <span style="font-size: 11px">
                    Diinput oleh <b><?php echo ucwords($row->nama_petugas)?></b>, 
                    Status barang <?php echo ($row->set_status_aktif==0)?'<span style="color: red"><b>Tidak Aktif</b></span>':'<span style="color: green"><b>Aktif</b></span>'; ?>
                  </span>
                </span>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

        

      </div><!-- /.timeline-items -->
    </div>
     
  </div><!-- /.col -->
</div><!-- /.row -->


