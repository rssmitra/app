<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <p>
      <b>DATA RUJUKAN FASKES</b>
      <br>Silahkan pilih Nomor Rujukan dengan meng Klik pada Data Nomor Rujukan
    </p>
    <table class="table table-bordered table-hover">
      <thead>
      <tr>
        <th class="center">No</th>
        <th>No Rujukan</th>
        <th>Tgl Rujukan</th>
        <th>No MR</th>
        <th>Nama pasien</th>
        <th>Jk</th>
        <th>Rujukan Asal</th>
        <th>Poli Tujuan</th>
        <th>Pelayanan</th>
        <th>Hak Kelas</th>
        <th>Diagnosa</th>
      </tr>
      </thead>

      <tbody>
        <?php 
          $no = 0;
          foreach ($rujukan as $key => $value) {
            $no++;
            $perujuk = $value->provPerujuk;
            $peserta = $value->peserta;
            $poli = $value->poliRujukan;
            $pelayanan = $value->pelayanan;
            echo "<tr>";
            echo "<td align='center'>".$no."</td>";
            echo '<td><a href="#" class="label label-default" onclick="copyNoRujukan('."'".$value->noKunjungan."'".')">'.$value->noKunjungan.'</td>';
            echo "<td>".$value->tglKunjungan."</td>";
            echo "<td>".$peserta->mr->noMR."</td>";
            echo "<td>".$peserta->nama."</td>";
            echo "<td>".$peserta->sex."</td>";
            echo "<td>".$perujuk->nama."</td>";
            echo "<td>".$poli->nama."</td>";
            echo "<td>".$pelayanan->nama."</td>";
            echo "<td>".$peserta->hakKelas->keterangan."</td>";
            echo "<td>".$value->diagnosa->nama."</td>";
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


