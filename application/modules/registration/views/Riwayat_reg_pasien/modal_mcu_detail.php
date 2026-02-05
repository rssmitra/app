<div class="row">
  <div class="col-xs-12">
    <h4>Detail Paket MCU Pasien<br><small><?php echo $from?> s/d <?php echo $to?></small></h4>

    <p style="font-weight:bold">Rekap Total Berdasarkan Paket MCU</p>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th class="center" width="30px">No</th>
            <th>Paket MCU</th>
            <th class="center" width="120px">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($rekap_paket)) : $n = 0; $total_all = 0; foreach($rekap_paket as $rp) : $n++; $total_all += isset($rp->total)?$rp->total:0; ?>
            <tr>
              <td class="center"><?php echo $n?></td>
              <td><?php echo isset($rp->nama_tarif)?$rp->nama_tarif:'-'; ?></td>
              <td class="center"><?php echo isset($rp->total)?$rp->total:0; ?></td>
            </tr>
          <?php endforeach; ?>
            <tr style="font-weight: bold;">
              <td colspan="2" class="center">Total</td>
              <td class="center"><?php echo $total_all; ?></td>
            </tr>
          <?php else: ?>
            <tr>
              <td colspan="3" class="center">Tidak ada rekap paket MCU pada rentang tanggal ini.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <hr>

    <p style="font-weight:bold">Daftar Paket MCU per Pasien</p>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="30px" class="center">No</th>
            <th>No Reg</th>
            <th>No MR</th>
            <th>Nama Pasien</th>
            <th>Paket MCU</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($details)) : $no = 0; foreach($details as $r) : $no++; ?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo isset($r->no_registrasi)?$r->no_registrasi:'-'?></td>
            <td><?php echo isset($r->no_mr)?$r->no_mr:'-'?></td>
            <td><?php echo isset($r->nama_pasien)?strtoupper($r->nama_pasien):'-'?></td>
            <td><?php echo isset($r->nama_tarif)?$r->nama_tarif:'-'?></td>
            <td><?php echo isset($r->tgl_daftar)?$this->tanggal->formatDateTime($r->tgl_daftar):'-'?></td>
          </tr>
          <?php endforeach; else: ?>
          <tr>
            <td colspan="6" class="center">Tidak ada data paket MCU pada rentang tanggal ini.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>