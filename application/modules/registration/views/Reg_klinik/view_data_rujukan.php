<script>
$('input[name="tipe_faskes_rujukan"]').click(function (e) {
  var value = $(this).val();

  $.getJSON("<?php echo site_url('ws_bpjs/Ws_index/getRujukanList') ?>?no_kartu="+$('#noKartuBpjs').val()+"&tipe_faskes="+value, '', function (response) {
      $('#list-data-rujukan tbody').remove(); 
      var no=0;
      console.log(response.rujukan);
      $.each(response.rujukan, function (i, o) {     
          no++;
          perujuk = o.provPerujuk;
          peserta = o.peserta;
          mr = peserta.mr;
          hakKelas = peserta.hakKelas;
          poli = o.poliRujukan;
          pelayanan = o.pelayanan;
          diagnosa = o.diagnosa;
          html = '<tr>\
                  <td align="center">'+no+'</td>\
                  <td><a href="#" class="label label-default" onclick="copyNoRujukan('+"'"+o.noKunjungan+"'"+')">'+o.noKunjungan+'</a></td>\
                  <td>'+o.tglKunjungan+'</td>\
                  <td>'+mr.noMR+'</td>\
                  <td>'+peserta.nama+'</td>\
                  <td>'+peserta.sex+'</td>\
                  <td>'+perujuk.nama+'</td>\
                  <td>'+poli.nama+'</td>\
                  <td>'+pelayanan.nama+'</td>\
                  <td>'+hakKelas.keterangan+'</td>\
                  <td>'+diagnosa.nama+'</td>\
                  </tr>';             

                  $(html).appendTo($('#list-data-rujukan'));
        });               
  });
  
})

function updateJenisFaskesPasien(val) {
  $('#jenis_faskes_pasien').val(val);
}
</script>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <p>
      <b>DATA RUJUKAN FASKES</b>
      <br>Silahkan pilih Nomor Rujukan dengan meng Klik pada Data Nomor Rujukan
    </p>

    <label>Jenis Faskes</label><br>
    <div class="col-md-12 no-padding">
      <div class="radio">
          <label>
            <input name="tipe_faskes_rujukan" type="radio" class="ace" value="1" onchange="updateJenisFaskesPasien('pcare')" checked/>
            <span class="lbl"> Faskes Tingkat I</span>
          </label>
          <label>
            <input name="tipe_faskes_rujukan" type="radio" class="ace" value="2" onchange="updateJenisFaskesPasien('rs')"/>
            <span class="lbl"> Faskes Tingkat II</span>
          </label>
      </div>
    </div>
        
    <table class="table table-bordered table-hover" id="list-data-rujukan">
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


