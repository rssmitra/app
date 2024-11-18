<script type="text/javascript">

  $(document).ready(function(){
    $('#img_tagging_div').html('');
  })

  $('#pl_diagnosa').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getICD10",
              data: 'keyword=' + query,            
              dataType: "json",
              type: "POST",
              success: function (response) {
                result($.map(response, function (item) {
                      return item;
                  }));
                
              }
          });
      },
      afterSelect: function (item) {
        // do what is needed with item
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#pl_diagnosa').val(label_item);
        $('#pl_diagnosa_hidden').val(val_item);
      }

  });

  function refreshIframe() {
      var ifr = document.getElementsByName('ifr_img_tagging')[0];
      ifr.src = ifr.src;
  }

</script>

<div style="text-align: center; font-size: 14px"><b>PENGKAJIAN DOKTER INSTALASI GAWAT DARURAT</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<div id="html_pengkajian_dr">
  <button onclick="refreshIframe();" type="button" class="btn btn-xs btn-primary">Reload Image</button>
  <iframe name="ifr_img_tagging" src="<?php echo base_url()?>pelayanan/Pl_pelayanan_igd/form_img_tagging/<?php echo $no_kunjungan?>" style="width: 100%; height: 650px; border: none"></iframe>
</div>
<hr>
<table class="table">
  <tr>
    <td width="150px">KELUHAN UTAMA</td>
    <td><textarea style="width: 100% !important; height: 50px !important" name="form_28[igd_keluhan_utama]" id="igd_keluhan_utama" onchange="fillthis('igd_keluhan_utama')"></textarea></td>
  </tr>
  <tr>
    <td width="150px">KELUHAN TAMBAHAN</td>
    <td><textarea style="width: 100% !important; height: 50px !important" name="form_28[igd_keluhan_tambahan]" id="igd_keluhan_tambahan" onchange="fillthis('igd_keluhan_tambahan')"></textarea></td>
  </tr>
  <tr>
    <td width="150px">Riwayat Penyakit Terdahulu</td>
    <td>
        <div class="checkbox">
          <label>
            <input  name="form_28[riwayat_penyakit_1]" id="riwayat_penyakit_1"  onclick="checkthis('riwayat_penyakit_1')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Tidak Ada</span>
          </label>
          <label>
            <input  name="form_28[riwayat_penyakit_2]" id="riwayat_penyakit_2"  onclick="checkthis('riwayat_penyakit_2')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; DM</span>
          </label>
          <label>
            <input  name="form_28[pasien_hamil_3]" id="pasien_hamil_3"  onclick="checkthis('pasien_hamil_3')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Hipertensi</span>
          </label>
          <label>
            <input  name="form_28[pasien_hamil_4]" id="pasien_hamil_4"  onclick="checkthis('pasien_hamil_4')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Jantung</span>
          </label>
          <label>
            <input  name="form_28[pasien_hamil_5]" id="pasien_hamil_5"  onclick="checkthis('pasien_hamil_5')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Asma</span>
          </label>
          <label>
            <input  name="form_28[pasien_hamil_6]" id="pasien_hamil_6"  onclick="checkthis('pasien_hamil_6')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Lainnya</span>
          </label>
        </div>
      </td>
  </tr>
  <tr>
    <td width="150px">Riwayat Obat-obatan</td>
    <td>
      <table class="table">
        <tr>
          <th class="center" width="30px">No</th>
          <th>Nama Obat</th>
          <th>Dosis</th>
        </tr>
        <?php for($i=1; $i<6; $i++) :?>
        <tr>
          <td align="center"><?php echo $i?>.</td>
          <td><input type="text" style="width: 100% !important" name="form_28[riwayat_obat_<?php echo $i?>]" id="riwayat_obat_<?php echo $i?>" onchange="fillthis('riwayat_obat_<?php echo $i?>')"></td>
          <td><input type="text" style="width: 100% !important" name="form_28[riwayat_dosis_obat_<?php echo $i?>]" id="riwayat_dosis_obat_<?php echo $i?>" onchange="fillthis('riwayat_dosis_obat_<?php echo $i?>')"></td>
        </tr>
        <?php endfor; ?>
      </table>
    </td>
  </tr>
  <tr>
    <td width="150px">Riwayat Alergi</td>
    <td><input type="text" style="width: 100% !important" name="form_28[riwayat_alergi_28]" id="riwayat_alergi_28" onchange="fillthis('riwayat_alergi_28')"></td>
  </tr>
</table>

<table class="table">
  <tr><td colspan="3"><b>PEMERIKSAAN FISIK</b></td></tr>
  <tr>
    <td width="150px">Keluhan Tambahan</td>
    <td><textarea style="width: 100% !important; height: 50px !important" name="form_28[igd_keluhan_tambahan_pf]" id="igd_keluhan_tambahan_pf" onchange="fillthis('igd_keluhan_tambahan_pf')"></textarea></td>
  </tr>
  
  <tr>
    <td width="150px">Diagnosa Kerja</td>
    <td><textarea style="width: 100% !important; height: 50px !important" name="form_28[igd_diagnosa_kerja]" id="igd_diagnosa_kerja" onchange="fillthis('igd_diagnosa_kerja')"></textarea></td>
  </tr>
  <tr>
    <td width="150px">Diagnosa Banding</td>
    <td><textarea style="width: 100% !important; height: 50px !important" name="form_28[igd_diagnosa_banding]" id="igd_diagnosa_banding" onchange="fillthis('igd_diagnosa_banding')"></textarea></td>
  </tr>
</table>
<br>
<table class="table">
  <tr>
    <th class="center" width="30px">No</th>
    <th class="center" width="100px">Jam Tindakan</th>
    <th>Penatalaksanaan IGD</th>
    <th width="150px">Nama Dokter</th>
  </tr>
  <?php for($i=1; $i<6; $i++ ):?>
  <tr>
    <td align="center"><?php echo $i;?>.</td>
    <td><input type="text" style="width: 100% !important; text-align: center" name="form_28[igd_jam_tindakan_<?php echo $i?>]" id="igd_jam_tindakan_<?php echo $i?>" onchange="fillthis('igd_jam_tindakan_<?php echo $i?>')"></td>
    <td><input type="text" style="width: 100% !important" name="form_28[penatalaksanaan_igd_<?php echo $i?>]" id="penatalaksanaan_igd_<?php echo $i?>" onchange="fillthis('penatalaksanaan_igd_<?php echo $i?>')"></td>
    <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>" name="form_28[igd_nama_dokter_<?php echo $i?>]" id="igd_nama_dokter_<?php echo $i?>" onchange="fillthis('igd_nama_dokter_<?php echo $i?>')"></td>
  </tr>
  <?php endfor;?>
</table>
<br>
<table class="table">
  <tr>
    <th class="center" width="30px">No</th>
    <th class="center" width="100px">Jam Tindakan</th>
    <th class="center" width="150px">Jenis Penunjang</th>
    <th>Pemeriksaan Penunjang</th>
    <th width="150px">Nama Dokter</th>
  </tr>
  <?php for($i=1; $i<6; $i++ ):?>
  <tr>
    <td align="center"><?php echo $i;?>.</td>
    <td>
      <input type="text" class="form-control" name="form_28[igd_jam_tindakan_pm_<?php echo $i?>]" id="igd_jam_tindakan_pm_<?php echo $i?>" onchange="fillthis('igd_jam_tindakan_pm_<?php echo $i?>')" style="width: 100% !important; text-align: center">
    </td>
    <td>
      <select name="form_28[igd_jenis_pm_<?php echo $i?>]" id="igd_jenis_pm_<?php echo $i?>" onchange="fillthis('igd_jenis_pm_<?php echo $i?>')" class="form-control">
        <option>-Pilih-</option>
        <option value="lab">Laboratorium</option>
        <option value="rad">Radiologi</option>
      </select>
    </td>
    <td><input type="text" style="width: 100% !important" name="form_28[igd_pemeriksaan_pm_<?php echo $i?>]" id="igd_pemeriksaan_pm_<?php echo $i?>" onchange="fillthis('igd_pemeriksaan_pm_<?php echo $i?>')"></td>
    <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>" name="form_28[igd_nama_dokter_pm_<?php echo $i?>]" id="igd_nama_dokter_pm_<?php echo $i?>" onchange="fillthis('igd_nama_dokter_pm_<?php echo $i?>')"></td>
  </tr>
  <?php endfor;?>
</table>
<br>
<table class="table">
  <tr>
    <th class="center" width="100px">Jam Tindakan</th>
    <th class="center" width="200px">Tindakan</th>
    <th>Keterangan</th>
    <th width="80px">Ukuran</th>
    <th width="120px">Nama Dokter</th>
  </tr>
  <tr>
    <td><input type="text" style="width: 100% !important" name="form_28[jam_tindakan_kateter]" id="jam_tindakan_kateter" onchange="fillthis('jam_tindakan_kateter')"></td>
    <td>
      <div class="checkbox">
        <label>
          <input  name="form_28[pasang_kateter]" id="pasang_kateter"  onclick="checkthis('pasang_kateter')" type="checkbox" class="ace">
          <span class="lbl" > &nbsp; Pemasangan kateter urine</span>
        </label>
      </div>
    </td>
    <td><input type="text" style="width: 100% !important" name="form_28[keterangan_kateter]" id="keterangan_kateter" onchange="fillthis('keterangan_kateter')"></td>
    <td><input type="text" style="width: 80px" name="form_28[ukuran_kateter]" id="ukuran_kateter" onchange="fillthis('ukuran_kateter')"></td>
    <td><input type="text" style="width: 100% !important" name="form_28[dokter_kateter]" id="dokter_kateter" onchange="fillthis('dokter_kateter')" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
  </tr>
  <tr>
    <td><input type="text" style="width: 100% !important" name="form_28[jam_tindakan_ngt]" id="jam_tindakan_ngt" onchange="fillthis('jam_tindakan_ngt')"></td>
    <td>
      <div class="checkbox">
        <label>
          <input  name="form_28[periksa_ngt]" id="periksa_ngt"  onclick="checkthis('periksa_ngt')" type="checkbox" class="ace">
          <span class="lbl" > &nbsp; NGT</span>
        </label>
      </div>
    </td>
    <td><input type="text" style="width: 100% !important" name="form_28[ket_ngt]" id="ket_ngt" onchange="fillthis('ket_ngt')"></td>
    <td><input type="text" style="width: 80px" name="form_28[ukuran_ngt]" id="ukuran_ngt" onchange="fillthis('ukuran_ngt')"></td>
    <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>" name="form_28[dokter_ngt]" id="dokter_ngt" onchange="fillthis('dokter_ngt')"></td>
  </tr>
  <tr>
    <td><input type="text" style="width: 100% !important" name="form_28[jam_intubasi]" id="jam_intubasi" onchange="fillthis('jam_intubasi')"></td>
    <td>
      <div class="checkbox">
        <label>
          <input  name="form_28[periksa_intubasi]" id="periksa_intubasi"  onclick="checkthis('periksa_intubasi')" type="checkbox" class="ace">
          <span class="lbl" > &nbsp; Intubasi</span>
        </label>
      </div>
    </td>
    <td><input type="text" style="width: 100% !important" name="form_28[ket_intubasi]" id="ket_intubasi" onchange="fillthis('ket_intubasi')"></td>
    <td><input type="text" style="width: 80px" name="form_28[ukuran_intubasi]" id="ukuran_intubasi" onchange="fillthis('ukuran_intubasi')"></td>
    <td><input type="text" style="width: 100% !important" name="form_28[dokter_intubasi]" id="dokter_intubasi" onchange="fillthis('dokter_intubasi')" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
  </tr>
  <tr>
    <td><input type="text" style="width: 100% !important" name="form_28[jam_jahit_luka]" id="jam_jahit_luka" onchange="fillthis('jam_jahit_luka')"></td>
    <td>
      <div class="checkbox">
        <label>
          <input  name="form_28[tindakan_jahit_luka]" id="tindakan_jahit_luka"  onclick="checkthis('tindakan_jahit_luka')" type="checkbox" class="ace">
          <span class="lbl" > &nbsp; Jahit Luka</span>
        </label>
      </div>
    </td>
    <td><input type="text" style="width: 100% !important" name="form_28[ket_jahit_luka]" id="ket_jahit_luka" onchange="fillthis('ket_jahit_luka')"></td>
    <td><input type="text" style="width: 80px" name="form_28[ukuran_jahit_luka]" id="ukuran_jahit_luka" onchange="fillthis('ukuran_jahit_luka')"></td>
    <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>" name="form_28[dokter_jahit_luka]" id="dokter_jahit_luka" onchange="fillthis('dokter_jahit_luka')"></td>
  </tr>
  <tr>
    <td><input type="text" style="width: 100% !important" name="form_28[jam_lain]" id="jam_lain" onchange="fillthis('jam_lain')"></td>
    <td>
      <div class="checkbox">
        <label>
          <input  name="form_28[periksa_lain]" id="periksa_lain"  onclick="checkthis('periksa_lain')" type="checkbox" class="ace">
          <span class="lbl" > &nbsp; Lain-lain</span>
        </label>
      </div>
    </td>
    <td><input type="text" style="width: 100% !important" name="form_28[ket_lain]" id="ket_lain" onchange="fillthis('ket_lain')"></td>
    <td><input type="text" style="width: 80px" name="form_28[ukuran_lain]" id="ukuran_lain" onchange="fillthis('ukuran_lain')"></td>
    <td><input type="text" style="width: 100% !important" name="form_28[dokter_lain]" id="dokter_lain" onchange="fillthis('dokter_lain')" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
  </tr>
</table>
<br>
<table class="table">
  <tr>
    <th class="center" width="30px">No</th>
    <th class="center" width="100px">Jam Tindakan</th>
    <th class="center">Penanganan dan Penilaian Ulang</th>
    <th width="150px">Nama Dokter</th>
  </tr>
  <?php for($i=1; $i<6; $i++ ):?>
  <tr>
    <td align="center"><?php echo $i;?>.</td>
    <td><input type="text" style="width: 100% !important; text-align: center" name="form_28[jam_tindakan_pu_<?php echo $i?>]" id="jam_tindakan_pu_<?php echo $i?>" onchange="fillthis('jam_tindakan_pu_<?php echo $i?>')"></td>
    <td><input type="text" style="width: 100% !important" name="form_28[periksa_pu_<?php echo $i?>]" id="periksa_pu_<?php echo $i?>" onchange="fillthis('periksa_pu_<?php echo $i?>')"></td>
    <td><input type="text" style="width: 100% !important" name="form_28[dokter_pu_<?php echo $i?>]" id="dokter_pu_<?php echo $i?>" onchange="fillthis('dokter_pu_<?php echo $i?>')" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
  </tr>
  <?php endfor;?>
</table>
<br>
<table class="table">
  <tr>
    <td width="150px">Kesimpulan</td>
    <td>
        <div class="checkbox">
          <label>
            <input  name="form_28[kesimpulan_perbaikan]" id="kesimpulan_perbaikan"  onclick="checkthis('kesimpulan_perbaikan')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Perbaikan</span>
          </label>
          <label>
            <input  name="form_28[kesimpulan_stabil]" id="kesimpulan_stabil"  onclick="checkthis('kesimpulan_stabil')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Stabil</span>
          </label>
          <label>
            <input  name="form_28[kesimpulan_buruk]" id="kesimpulan_buruk"  onclick="checkthis('kesimpulan_buruk')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Perburukan</span>
          </label>
          <label>
            <input  name="form_28[kesimpulan_death]" id="kesimpulan_death"  onclick="checkthis('kesimpulan_death')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Death on arrival</span>
          </label>
          <label>
            <input  name="form_28[kesimpulan_death_emergency]" id="kesimpulan_death_emergency"  onclick="checkthis('kesimpulan_death_emergency')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Death on emergency</span>
          </label>
        </div>
      </td>
  </tr>
  <tr>
    <td width="150px">Tindak Lanjut</td>
    <td>
        <div class="checkbox">
          <label>
            <input  name="form_28[tl_rawat]" id="tl_rawat"  onclick="checkthis('tl_rawat')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Rawat</span>
          </label>
          <label>
            <input  name="form_28[tl_rujuk]" id="tl_rujuk"  onclick="checkthis('tl_rujuk')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Rujuk</span>
          </label>
          <label>
            <input  name="form_28[tl_pulang]" id="tl_pulang"  onclick="checkthis('tl_pulang')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Pulang</span>
          </label>
          <label>
            <input  name="form_28[tl_pulang_sendiri]" id="tl_pulang_sendiri"  onclick="checkthis('tl_pulang_sendiri')" type="checkbox" class="ace">
            <span class="lbl" > &nbsp; Pulang atas permintaan sendiri</span>
          </label>
        </div>
      </td>
  </tr>
</table>


