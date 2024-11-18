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

<div class="tabbable">

  <ul class="nav nav-tabs" id="myTab">
    <li class="active">
      <a data-toggle="tab" href="#pengkajian_perawat">
        PENGKAJIAN KEPERAWATAN
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#pengkajian_dr">
      PENGKAJIAN DOKTER
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#resume_medis">
      RESUME MEDIS
      </a>
    </li>
  </ul>

  <div class="tab-content">
    <div id="pengkajian_perawat" class="tab-pane fade in active">
      <div id="html_pengkajian_perawat">
        <p style="font-size: 14px; font-weight: bold; text-align: center">PENGKAJIAN KEPERAWATAN INSTALASI GAWAT DARURAT</p>

        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="4" align="center"><span style="font-size: 16px; font-weight: bold">T R I A S E</span></td></tr>
          <tr>
            <td colspan="4">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Trauma</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Non Trauma</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Kebidanan</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              Keterangan : <br>
              <textarea style="width: 100%; height: 40px !important"></textarea>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              Keluhan Utama Pasien: <br>
              <textarea style="width: 100%; height: 70px !important"></textarea>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <table class="table">
                <tr style="background: #f1f1f1; font-weight: bold">
                  <td class="center" style="width: 80px" rowspan="2">Pernafasan</td>
                  <td class="center" style="width: 80px" rowspan="2">Nadi</td>
                  <td class="center" style="width: 80px" rowspan="2">Suhu</td>
                  <td class="center" style="width: 80px" rowspan="2">SpO2</td>
                  <td class="center" style="width: 80px" colspan="3">GCS</td>
                  <td class="center" style="width: 80px" rowspan="2">Berat Badan</td>
                </tr>
                <tr style="background: #f1f1f1; font-weight: bold">
                  <td align="center">E</td>
                  <td align="center">M</td>
                  <td align="center">V</td>
                </tr>
                <tr>
                  <td align="center"><input type="text" style="width: 80px"></td>
                  <td align="center"><input type="text" style="width: 80px"></td>
                  <td align="center"><input type="text" style="width: 80px"></td>
                  <td align="center"><input type="text" style="width: 80px"></td>
                  <td align="center"><input type="text" style="width: 50px"></td>
                  <td align="center"><input type="text" style="width: 50px"></td>
                  <td align="center"><input type="text" style="width: 50px"></td>
                  <td align="center"><input type="text" style="width: 80px"></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <div class="checkbox">
                &nbsp; <b>Riwayat Alergi</b>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Makanan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Makanan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Lainnya</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="text" class="ace" style="width: 200px" placeholder="diisi jika pilih lainnya">
                </label>
              </div>
            </td>
          <tr>

          <tr>
            <td align="center" style="background: red; color: black; font-weight: bold">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" style="color: white; font-weight: bold"> &nbsp; MERAH</span>
                </label>
              </div>
            </td>
            <td align="center" style="background: yellow; color: black; font-weight: bold">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" style="color: black; font-weight: bold"> &nbsp; KUNING</span>
                </label>
              </div>
            </td>
            <td align="center" style="background: green; color: black; font-weight: bold">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" style="color: white; font-weight: bold"> &nbsp; HIJAU</span>
                </label>
              </div>
            </td>
            <td align="center" style="background: black; color: black; font-weight: bold">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" style="color: white; font-weight: bold"> &nbsp; HITAM</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="4" style="vertical-align: middle !important; font-weight: bold">Jalan Nafas</td>
          </tr>
          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Sumbatan</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Bebas</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Bebas</span>
                </label>
              </div>
            </td>
            <td>
              -
            </td>
          </tr>
          <tr>
            <td colspan="4" style="vertical-align: middle !important; font-weight: bold">Pernafasan</td>
          </tr>
          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Henti Nafas</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; < 10 x/mnt</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; > 32 x/mnt</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; 24-32 x/mnt</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; 10-24 x/mnt</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Henti Nafas</span>
                </label>
              </div>
            </td>
            
          </tr>
          <tr>
            <td colspan="4" style="vertical-align: middle !important; font-weight: bold">Sirkulasi</td>
          </tr>
          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Henti Jantung</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nadi Teraba Lemah</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nadi < 50 x/mnt</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nadi > 150 x/mnt</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Akal Dingin</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; CRT > 2 Detik</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nadi 120-150 x/mnt</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nadi 60-100 x/mnt</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Henti Jantung</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; EKG Flat</span>
                </label>
              </div>
            </td>
            
          </tr>
          <tr>
            <td colspan="4" style="vertical-align: middle !important; font-weight: bold">Kesadaran</td>
          </tr>
          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; GCS < 12</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Kejang</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada respon</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; GCS > 12</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nyeri Dada</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; GCS 15</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; GCS 3</span>
                </label>
              </div>
            </td>
            
          </tr>
        </table>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="2" align="center"><span style="font-size: 16px; font-weight: bold">P E N G K A J I A N</span></td></tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Tekanan Intrakranial</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada kelainan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Sakit Kepala</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Muntah</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Pusing</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Bingung</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Pupil</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Normal</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Miosis</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Midriasis</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Isokor</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Anisokor</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Mukosa Mulut</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Lembab</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Kering</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Gastrointestinal</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Mual</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Muntah</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nyeri Perut</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Lain-lain</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Neuro Sensoik</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada kelainan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Perubahan Sensorik</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Perubahan Motorik</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Spasme Otot</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Muskulo Skeletal</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Perubahan Bentuk</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ekstremitas</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Fraktur</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Dislokasi</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Luksasio</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Integumen</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada kelainan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Luka Bakar</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Luka Robek</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Lecet</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Decubitus</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Gangren</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Turgor Kulit</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Baik</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Menurun</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Edema</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada </span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ada</span>
                </label>
                <label>Keterangan : </label>
                <input type="text" style="width: 300px">
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Perdarahan</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada </span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ada</span>
                </label>
                <label>Keterangan : </label>
                <input type="text" style="width: 300px">
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Intoksikasi</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada </span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ada</span>
                </label>
                <label>Keterangan : </label>
                <input type="text" style="width: 300px">
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Eliminasi BAB</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada keluhan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ada</span>
                </label>
                <label>Frekuensi : </label>
                <input type="text" style="width: 50px"> &nbsp; x 
                <label>Konsistensi : </label>
                <input type="text" style="width: 50px"> 
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Eliminasi BAK</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada keluhan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ada</span>
                </label>
                <label>Frekuensi : </label>
                <input type="text" style="width: 50px"> &nbsp; x 
                <label>Konsistensi : </label>
                <input type="text" style="width: 50px"> 
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Keterangan Lainnya</td>
            <td>
              <textarea style="width: 100%; height: 50px !important"></textarea>
            </td>
          </tr>
        </table>
        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="2" align="center"><span style="font-size: 16px; font-weight: bold">PSIKOSOSAL DAN EKONOMI</span></td></tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Kecemasan</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada keluhan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Sedang</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Berat</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Panik</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Sulit dinilai</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Koping</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Merusak Diri</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Menarik Diri</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Pelaku Kekerasan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Sulit dinilai</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Kebiasaan</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Merokok</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Alkohol</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Lainnya</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle !important; font-weight: bold">Keterangan Lainnya</td>
            <td>
              <textarea style="width: 100%; height: 50px !important"></textarea>
            </td>
          </tr>
        </table>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="3" align="center"><span style="font-size: 16px; font-weight: bold">PEMERIKSAAN FISIK DAN SKRINING GIZI</span></td></tr>
          <tr>
            <td>1.</td>
            <td width="150px">Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir ?</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada penurunan berat badan (skor 0)</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak yakin / tidak tahu / terasa baju lebih longgar (skor 2)</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya, beberapa penurunan berat badan tersebut</span>
                </label>
                <div style="padding-left: 30px">
                  <label>
                    <input name="form-field-checkbox" type="checkbox" class="ace">
                    <span class="lbl" > &nbsp; 1 - 5 kg (skor 1)</span>
                  </label>
                  <br>
                  <label>
                    <input name="form-field-checkbox" type="checkbox" class="ace">
                    <span class="lbl" > &nbsp; 6 - 10 kg (skor 2)</span>
                  </label>
                  <br>
                  <label>
                    <input name="form-field-checkbox" type="checkbox" class="ace">
                    <span class="lbl" > &nbsp; 11 - 15 kg (skor 3)</span>
                  </label>
                  <br>
                  <label>
                    <input name="form-field-checkbox" type="checkbox" class="ace">
                    <span class="lbl" > &nbsp; > 15 kg (skor 4)</span>
                  </label>
                </div>

              </div>
            </td>
          </tr>
          <tr>
            <td>2.</td>
            <td width="150px">Apakah asupan makanan berkurang karena tidak nafsu makan?</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya (skor 0)</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak (skor 1)</span>
                </label>

              </div>
            </td>
          </tr>
          <tr>
            <td>3.</td>
            <td width="150px">Pasien dengan diagnosa khusus</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya </span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak </span>
                </label>
                <br>
                <div style="padding: 5px">
                  ( DM / Kemoterapi / Hemodialisa / Geriatri / Immunitas menurun )
                </div>
                
                <div style="padding: 5px; width: 100%">
                  <input type="text" style="padding: 5px; width: 100%" placeholder="sebutkan diagnosa khususnya">
                </div>

              </div>
            </td>
          </tr>
          <tr>
            <td colspan="3" align="center">( Bila skor > 2 dan atau pasien dengan diagnosis / kondisi khusus dilaporkan ke dokter pemeriksa )</td>
          </tr>
        </table>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="3" align="center"><span style="font-size: 16px; font-weight: bold">SKRINING STATUS FUNGSIONAL</span></td></tr>
          <tr>
            <td style="width: 33%">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Mandiri</span>
                </label>
              </div>
            </td>
            <td style="width: 33%">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Perlu bantuan</span>
                </label>
                <br>
                <div style="padding: 5px">
                  <textarea style="width: 100% !important; height: 50px !important; padding: 5px"></textarea>
                </div>
              </div>
            </td>
            <td style="width: 33%">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ketergantungan total</span>
                </label>
              </div>
            </td>
          </tr>
        </table>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="3" align="center"><span style="font-size: 16px; font-weight: bold">SKRINING RESIKO JATUH / CEDERA</span></td></tr>
          <tr>
            <td>1.</td>
            <td>Apakah pasien tampak tidak seimbang (sempoyongan / limbung) saat berjalan ?</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td>2.</td>
            <td>Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk ?</td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="2">
              Hasil :<br>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak Beresiko</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Resiko Rendah (ditemukan a atau b)</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Resiko Tinggi  (ditemukan a dan b)</span>
                </label>
              </div>
              <br>
              Dilaporkan ke dokter :
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak</span>
                </label>
                <label>
                  Pukul, <input type="text">
                </label>
              </div>
            </td>
            <td>
              
            </td>
          </tr>
        </table>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="2" align="center"><span style="font-size: 16px; font-weight: bold">PENILAIAN TINGKAT NYERI</span></td></tr>
          <tr>
            <td><b>Keluhan Nyeri</b></td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak ada</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ada</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Pencetus/Provoke</b></td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Benturan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tindakan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Proses Penyakit</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Lain-lain</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Kualitas/Quality</b></td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Seperti tertusuk tajam/tumpul</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Berdenyut</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Terbakar</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tertindih benda berat</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Diremas</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Terpelintir</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Teriris</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Radiasi/Region</b></td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Lokasi</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Menyebar</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Skala/Severity</b></td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; FLACSS</span>
                </label>
                <label>
                  Score <input type="text" style="width: 50px !important">
                </label>
                <label>
                  Wong Baker Faces, Score <input type="text" style="width: 50px !important">
                </label>
                <label>
                  VAS/NRS Score <input type="text" style="width: 50px !important">
                </label>

              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; BPS</span>
                </label>
                <label>
                  Score <input type="text" style="width: 50px !important">
                </label>

              </div>
            </td>
          </tr>
          <tr>
            <td><b>Durasi/Times</b></td>
            <td>
              <div style="width: 100% !important">
                  Kapan mulai dirasa : 
                  <input type="text" style="width: 100% !important">
              </div>
              <div style="width: 100% !important">
                  Berapa lama dirasa/ kekambuhan : 
                  <input type="text" style="width: 100% !important">
              </div>
            </td>
          </tr>

          
          
        </table>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="2" align="center"><span style="font-size: 16px; font-weight: bold">MASALAH KEPERAWATAN</span></td></tr>
          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Bersihin jalan nafas tidak efektif</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Pola nafas tidak efektif gangguan</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Gangguan pertukaran gas</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Sirkulasi</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Resiko keseimbangan cairan</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Resiko perfusi jaringan serebral</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Hipertermi</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nyeri akut</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nyeri kronik</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Syok hipovolemik</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Syok kardiogenik</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Syok anafilaktik</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Syok septic</span>
                </label>
              </div>
            </td>
          </tr>
        </table>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="5" align="center"><span style="font-size: 16px; font-weight: bold">KOLABORASI</span></td></tr>
          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Oksigenisasi</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Nebulizer</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; IVFD</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; EKG</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Transfusi Darah</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; NGT</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; DC Shock</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Rontgen</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Obat</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Bilas Lambung</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Laboratorium</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Suction</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Irigasi Mata</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Kateter</span>
                </label>
              </div>
            </td>
          </tr>
        </table>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="5" align="center"><span style="font-size: 16px; font-weight: bold">EVALUASI (SOAP)</span></td></tr>
        </table>

        <div>

          <span style="font-weight: bold; font-style: italic; color: blue">(Subjective)</span>
          <div style="margin-top: 6px">
              <label for="form-field-8"> Anamnesa / Keluhan Pasien <span style="color:red">* </span> <small>(minimal 8 karakter)</small> </label>
              <textarea class="form-control"  style="height: 100px !important"></textarea>
          </div>
          <br>

          <span style="font-weight: bold; font-style: italic; color: blue">(Objective)</span>

          <div style="margin-top: 6px">
              <label for="form-field-8"> Pemeriksaan Fisik </label>
              <textarea class="form-control" style="height: 100px !important"></textarea>
          </div>

          <span style="font-weight: bold; font-style: italic; color: blue">(Assesment)</span>
          <div style="margin-top: 6px">
              <label for="form-field-8">Diagnosa Primer(ICD10) <span style="color:red">* </span></label>
              <input type="text" class="form-control" placeholder="Masukan keyword ICD 10" value="">
          </div>

          <div style="margin-top: 6px">
              <label for="form-field-8">Diagnosa Sekunder (ICD10)</label>
              <input type="text" class="form-control" name="pl_diagnosa_sekunder" id="pl_diagnosa_sekunder" placeholder="Masukan keyword ICD 10" value="">
              <div id="pl_diagnosa_sekunder_hidden_txt" style="padding: 2px; line-height: 23px; border: 1px solid #d5d5d5; min-height: 25px; margin-top: 2px">
              </div>
          </div>
          <br>
          <span style="font-weight: bold; font-style: italic; color: blue">(Planning)</span>
          <div style="margin-top: 6px">
              <label for="form-field-8"> Rencana Asuhan / Anjuran Dokter </label>
              <textarea class="form-control" style="height: 100px !important"></textarea>
          </div>

        </div>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="5" align="center"><span style="font-size: 16px; font-weight: bold">HASIL PENANGANAN</span></td></tr>
          <tr>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Rawat Inap</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; ICU</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Kamar Bersalin</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Kamar Bedah</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; HD</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Perinatologi</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Menolak Rawat Inap</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Dirujuk</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Pulang</span>
                </label>
              </div>
            </td>
            <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Meninggal</span>
                </label>
              </div>
            </td>
          </tr>
        </table>

        <br>
        <table class="table">
          <tr><td style="background: #f4ae11; color: black;" colspan="5" align="center"><span style="font-size: 16px; font-weight: bold">DISCHARGE PLANNING</span></td></tr>
          <tr>
            <td width="50px" align="center">1.</td>
            <td>Umur > 65</td>
            <td width="200px">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya </span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td width="50px" align="center">2.</td>
            <td>Keterbatasan Mobilitas</td>
            <td width="200px">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya </span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td width="50px" align="center">3.</td>
            <td>Perawatan atau pengobatan lanjutan</td>
            <td width="200px">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya </span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak</span>
                </label>
              </div>
            </td>
          </tr>
          <tr>
            <td width="50px" align="center">4.</td>
            <td>Bantuan untuk melakukan aktifitas sehari-hari</td>
            <td width="200px">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Ya </span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak</span>
                </label>
              </div>
            </td>
          </tr>
        </table>

        <br>
        <table class="table">
          <tr>
            <td colspan="2">Bila salah satu jawaban "ya" dari kriteria perencanaan pulang diatas, maka akan dilanjutkan dengan perencanaan pulang sebagai berikut : </td>
          </tr>
          <tr>
            <td width="200px">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Perawatan diri (mandi, BAB, BAK) </span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Pemantauan pemberian obat</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Pemantauan diet</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Perawatan Luka</span>
                </label>
              </div>
            </td>
            <td width="300px">
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Latihan fisik lanjutan </span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Pendampingan tenaga khusus di rumah</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Bantuan medis/ perawatan dirumah (home care)</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Bantuan untuk melakukan aktifitas fisik (kursi roda, alat bantu jalan)</span>
                </label>
              </div>
            </td>
          </tr>
        </table>

      </div>
    </div>

    <div id="pengkajian_dr" class="tab-pane fade">
      <div id="html_pengkajian_dr">
        <p style="font-size: 14px; font-weight: bold; text-align: center">PENGKAJIAN DOKTER INSTALASI GAWAT DARURAT</p>
        <button onclick="refreshIframe();" type="button" class="btn btn-xs btn-primary">Reload Image</button>

        <iframe name="ifr_img_tagging" src="<?php echo base_url()?>pelayanan/Pl_pelayanan_igd/form_img_tagging" style="width: 100%; height: 650px; border: none"></iframe>
      </div>
      <table class="table">
        <tr>
          <td width="150px">KELUHAN UTAMA</td>
          <td><textarea style="width: 100% !important; height: 50px !important"></textarea></td>
        </tr>
        <tr>
          <td width="150px">KELUHAN TAMBAHAN</td>
          <td><textarea style="width: 100% !important; height: 50px !important"></textarea></td>
        </tr>
        <tr>
          <td width="150px">Riwayat Penyakit Terdahulu</td>
          <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak Ada</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; DM</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Hipertensi</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Jantung</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Asma</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
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
              <tr>
                <td align="center">1.</td>
                <td><input type="text" style="width: 100% !important"></td>
                <td><input type="text" style="width: 100% !important"></td>
              </tr>
              <tr>
                <td align="center">2.</td>
                <td><input type="text" style="width: 100% !important"></td>
                <td><input type="text" style="width: 100% !important"></td>
              </tr>
              <tr>
                <td align="center">3.</td>
                <td><input type="text" style="width: 100% !important"></td>
                <td><input type="text" style="width: 100% !important"></td>
              </tr>
              <tr>
                <td align="center">4.</td>
                <td><input type="text" style="width: 100% !important"></td>
                <td><input type="text" style="width: 100% !important"></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td width="150px">Riwayat Alergi</td>
          <td><input type="text" style="width: 100% !important"></td>
        </tr>
      </table>

      <table class="table">
        <tr><td colspan="3">PEMERIKSAAN FISIK</td></tr>
        <tr>
          <td width="150px">KELUHAN TAMBAHAN</td>
          <td><textarea style="width: 100% !important; height: 50px !important"></textarea></td>
        </tr>
        <tr>
          <td width="150px">Riwayat Penyakit Terdahulu</td>
          <td>
              <div class="checkbox">
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Tidak Ada</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; DM</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Hipertensi</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Jantung</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Asma</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
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
              <tr>
                <td align="center">1.</td>
                <td><input type="text" style="width: 100% !important"></td>
                <td><input type="text" style="width: 100% !important"></td>
              </tr>
              <tr>
                <td align="center">2.</td>
                <td><input type="text" style="width: 100% !important"></td>
                <td><input type="text" style="width: 100% !important"></td>
              </tr>
              <tr>
                <td align="center">3.</td>
                <td><input type="text" style="width: 100% !important"></td>
                <td><input type="text" style="width: 100% !important"></td>
              </tr>
              <tr>
                <td align="center">4.</td>
                <td><input type="text" style="width: 100% !important"></td>
                <td><input type="text" style="width: 100% !important"></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td width="150px">Riwayat Alergi</td>
          <td><input type="text" style="width: 100% !important"></td>
        </tr>

        <tr>
          <td width="150px">Diagnosa Kerja</td>
          <td><textarea style="width: 100% !important; height: 50px !important"></textarea></td>
        </tr>
        <tr>
          <td width="150px">Diagnosa Banding</td>
          <td><textarea style="width: 100% !important; height: 50px !important"></textarea></td>
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
          <td><input type="text" style="width: 100% !important; text-align: center"></td>
          <td><input type="text" style="width: 100% !important"></td>
          <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
        </tr>
        <?php endfor;?>
      </table>
      <br>
      <table class="table">
        <tr>
          <th class="center" width="30px">No</th>
          <th class="center" width="100px">Jam Tindakan</th>
          <th class="center" width="100px">Jenis Penunjang</th>
          <th>Pemeriksaan Penunjang</th>
          <th width="150px">Nama Dokter</th>
        </tr>
        <?php for($i=1; $i<6; $i++ ):?>
        <tr>
          <td align="center"><?php echo $i;?>.</td>
          <td><input type="text" style="width: 100% !important; text-align: center"></td>
          <td><input type="text" style="width: 100% !important; text-align: center" value="LAB/RAD"></td>
          <td><input type="text" style="width: 100% !important"></td>
          <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
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
          <td><input type="text" style="width: 100% !important"></td>
          <td>
            <div class="checkbox">
              <label>
                <input name="form-field-checkbox" type="checkbox" class="ace">
                <span class="lbl" > &nbsp; Pemasangan kateter urine</span>
              </label>
            </div>
          </td>
          <td><input type="text" style="width: 100% !important"></td>
          <td><input type="text" style="width: 80px"></td>
          <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
        </tr>
        <tr>
          <td><input type="text" style="width: 100% !important"></td>
          <td>
            <div class="checkbox">
              <label>
                <input name="form-field-checkbox" type="checkbox" class="ace">
                <span class="lbl" > &nbsp; NGT</span>
              </label>
            </div>
          </td>
          <td><input type="text" style="width: 100% !important"></td>
          <td><input type="text" style="width: 80px"></td>
          <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
        </tr>
        <tr>
          <td><input type="text" style="width: 100% !important"></td>
          <td>
            <div class="checkbox">
              <label>
                <input name="form-field-checkbox" type="checkbox" class="ace">
                <span class="lbl" > &nbsp; Intubasi</span>
              </label>
            </div>
          </td>
          <td><input type="text" style="width: 100% !important"></td>
          <td><input type="text" style="width: 80px"></td>
          <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
        </tr>
        <tr>
          <td><input type="text" style="width: 100% !important"></td>
          <td>
            <div class="checkbox">
              <label>
                <input name="form-field-checkbox" type="checkbox" class="ace">
                <span class="lbl" > &nbsp; Jahit Luka</span>
              </label>
            </div>
          </td>
          <td><input type="text" style="width: 100% !important"></td>
          <td><input type="text" style="width: 80px"></td>
          <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
        </tr>
        <tr>
          <td><input type="text" style="width: 100% !important"></td>
          <td>
            <div class="checkbox">
              <label>
                <input name="form-field-checkbox" type="checkbox" class="ace">
                <span class="lbl" > &nbsp; Lain-lain</span>
              </label>
            </div>
          </td>
          <td><input type="text" style="width: 100% !important"></td>
          <td><input type="text" style="width: 80px"></td>
          <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
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
          <td><input type="text" style="width: 100% !important; text-align: center"></td>
          <td><input type="text" style="width: 100% !important"></td>
          <td><input type="text" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>"></td>
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
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Perbaikan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Stabil</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Perburukan</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Death on arrival</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
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
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Rawat</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Rujuk</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Pulang</span>
                </label>
                <label>
                  <input name="form-field-checkbox" type="checkbox" class="ace">
                  <span class="lbl" > &nbsp; Pulang atas permintaan sendiri</span>
                </label>
              </div>
            </td>
        </tr>
      </table>
      

    </div>

    <div id="resume_medis" class="tab-pane fade">

      <p><b><i class="fa fa-edit"></i> DIAGNOSA DAN PEMERIKSAAN </b></p>

      <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">

      <div class="form-group">
          <label class="control-label col-sm-2" for="">Kategori Triase</label>
          <div class="col-sm-3">
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'kategori_tindakan')), 3 , 'kategori_tindakan', 'kategori_tindakan', 'form-control', '', '') ?>
          </div>

          <label class="control-label col-sm-2" for="">Jenis Kasus</label>
          <div class="col-sm-4">
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'jenis_kasus_igd')), '' , 'jenis_kasus_igd', 'jenis_kasus_igd', 'form-control', '', '') ?>
          </div>
      </div>

      <div class="form-group">
          <label class="control-label col-sm-2" for="">Diagnosa <span style="color:red">(*)</span></label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
            <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
          </div>
      </div>

      <div class="form-group" >
          <label class="control-label col-sm-2" for="">Anamnesa</label>
          <div class="col-sm-10">
            <textarea name="pl_anamnesa" id="pl_anamnesa" class="form-control" style="height: 150px !important"><?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?></textarea>
          
            <!-- <input type="text" class="form-control" name="pl_anamnesa" value="<?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?>"> -->
            
          </div>
      </div>

      <div class="form-group" style="padding-top: 6px">
          <label class="control-label col-sm-2" for="">Pemeriksaan</label>
          <div class="col-sm-10">
              <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 150px !important"><?php echo isset($riwayat->pemeriksaan)?$riwayat->pemeriksaan:''?></textarea>
          </div>
      </div>

      <div class="form-group" style="margin-top: 6px">
          <label class="control-label col-sm-2" for="">Anjuran Dokter</label>
          <div class="col-sm-10">
            <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 150px !important"><?php echo isset($riwayat->pengobatan)?$riwayat->pengobatan:''?></textarea>
          </div>
      </div>

      <div class="form-group" style="padding-top: 10px">
          <label class="control-label col-sm-2" for="">&nbsp;</label>
          <div class="col-sm-4" style="margin-left:6px">
            <button type="submit" class="btn btn-xs btn-primary" id="btn_save_data"> <i class="fa fa-save"></i> Simpan Data </button>
          </div>
      </div>
    </div>
  </div>
  
</div>






