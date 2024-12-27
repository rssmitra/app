<script>

jQuery(function($) {  

  $('.date-picker').datepicker({    
    autoclose: true,    
    todayHighlight: true    
  })  
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){    
    $(this).prev().focus();    
  });  

    $('#dokter_igd').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query },            
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
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#dokter_igd').val(label_item);
      }
    });

    $('#dokter_dpjp').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query },            
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
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#dokter_dpjp').val(label_item);
      }
    });

    $('#diagnosa_utama_form_36').typeahead({
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
            $('#diagnosa_utama_form_36').val(label_item);
        }

    });

});

</script>

<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 18px;"><b>FORMULIR INFORMASI / PENJELASAN</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!--MAIN CONTENT -->


<p class="title">
        <b>PENGKAJIAN AWAL RAWAT INAP (LANJUTAN)</b>
    </p>

    <table border="1" width="100%" style="border-collapse: collapse;">
        <tbody>
            <tr>
                <td style="text-align:center; font-weight: bold; font-size: 20px; color: white; background-color: black" colspan="4">
                SKRINING RISIKO JATUH (HUMPTY DUMPTY)
                </td>
            </tr>
        <tr>
            <td style="text-align:center;" width="90%" colspan="2">
                <strong>FAKTOR RISIKO </strong>
            </td>
            <td style="text-align:center;" width="5%">
                <strong>POIN</strong>
            </td>
            <td style="text-align:center; width:5%" rowspan="1">
                <strong>SKOR</strong>
            </td>
        </tr>
        <tr>
            <td rowspan="4" style="width: 100px">Umur</td>
            <td class="sub_data">dari 3 tahun</td>
            <td align="center">4</td>
            <td style="width:100px"> <input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">3 tahun - 7 tahun</td>
            <td align="center">3</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">7 tahun - 13 tahun</td>
            <td align="center">2</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">&gt; 13 tahun</td>
            <td align="center">1</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td rowspan="2">Jenis Kelamin</td>
            <td class="sub_data">Laki-laki</td>
            <td align="center">2</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">Wanita</td>
            <td align="center">1</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td rowspan="4">Diagnosa</td>
            <td class="sub_data">Neurologi</td>
            <td align="center">4</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">Respiratory,dehidrasi,anemia,anoreksia,syncope</td>
            <td align="center">3</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">Perilaku</td>
            <td align="center">2</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">Lain-lain</td>
            <td align="center">1</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td rowspan="3">Gangguan Kognitif</td>
            <td class="sub_data">Keterbatasan daya pikir</td>
            <td align="center">3</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>   
        <tr>
            <td class="sub_data">Pelupa, berkurangnya orientasi sekitar</td>
            <td align="center">2</td>
            <td><input type="text" style="width:100px"> &nbsp;</td>
        </tr> 
        <tr>
            <td class="sub_data">Dapat menggunakan daya pikir tanpa Hambatan</td>
            <td align="center">1</td>
            <td><input type="text" style="width:100px"> &nbsp;</td>
        </tr>
        <tr>
            <td rowspan="4">Faktor Lingkungan</td>
            <td class="sub_data">Riwayat jatuh atau bayi / balita yang ditempatkan di tempat tidur</td>
            <td align="center">4</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">Pasien menggunakan alat bantu / bayi balita dalam ayunan</td>
            <td align="center">3</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">Pasien ditempat tidur standar</td>
            <td align="center">2</td>
            <td><input type="text" style="width:100px"> &nbsp;</td>
        </tr>
        <tr>
            <td class="sub_data">Area pasien rawat jalan</td>
            <td align="center">1</td>
            <td><input type="text" style="width:100px"> &nbsp;</td>
        </tr>
        <tr>
            <td rowspan="3">Respon terhadap pembedahan, sedasi dan anestesi</td>
            <td class="sub_data">Dalam 24 jam</td>
            <td align="center">3</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">Dalam 48 jam</td>
            <td align="center">2</td>
            <td><input type="text" style="width:100px"> &nbsp;</td>
        </tr>
        <tr>
            <td class="sub_data">&gt; 48 jam / tidak ada respon</td>
            <td align="center">1</td>
            <td><input type="text" style="width:100px"> &nbsp;</td>
        </tr>
        <tr>
            <td rowspan="3">Penggunaan obat-obatan</td>
            <td class="sub_data">Penggunaan bersama sedative, barbiturate, anti depresan, diuretik</td>
            <td align="center">3</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
        <tr>
            <td class="sub_data">Salah satu dari obat diatas </td>
            <td align="center">2</td>
            <td><input type="text" style="width:100px"> &nbsp;</td>
        </tr>
        <tr>
            <td class="sub_data">Obat-obatan lainnya / tanpa obat </td>
            <td align="center">1</td>
            <td><input type="text" style="width:100px"> &nbsp;</td>
        </tr>
        <tr>
            <td>Kategori:</td>
            <td colspan="2">
                <label>
                    <input type="checkbox" class="ace" name="rt" id="rt" onclick="checkthis('rt')">
                    <span class="lbl"> Risiko Tinggi &gt;12 </span>
                </label> 
                <label>
                    <input type="checkbox" class="ace" name="rr" id="rr" onclick="checkthis('rr')">
                    <span class="lbl"> Risiko rendah 7 - 11 </span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="right" style="font-weight: bold; height: 100px">TOTAL SKOR</td>
            <td><input type="text" style="width:100px"> </td>
        </tr>
    </table>
    <br>

    <table border="1" width="100%" style="border-collapse: collapse;">
        <tr>
            <td style="text-align:center; font-weight: bold; font-size: 20px; color: white; background-color: black" width="100%" colspan="5">
                PENCEGAHAN PASIEN JATUH
                </td>
        </tr>
        <tr>
            <td align="center" colspan="2">RESIKO RENDAH</td>
            <td align="center" colspan="3">RESIKO TINGGI</td>
        </tr>
        <tr>
            <td colspan="2">
                <ol>
                    <li>Pastikan bel mudah dijangkau</li>
                    <li>Roda tempat tidur pada posisi terkunci</li>
                    <li>Pagar pengaman tempat tidur dinaikan</li>
                    <li>Lampu toilet cukup terang</li>
                    <li>Lakukan asesmen ulang setiap ada perubahan kondisi pasien</li>
                </ol>
            </td>
            <td colspan="3">
                <ol>
                    <li>Lakukan semua pedoman pencegahan untuk resiko rendah</li>
                    <li>Pasangkan tanda resiko jatuh pada pergelangan tangan</li>
                    <li>Tempatkan tanda resiko jatuh pada daftar nama pasien di nurse staition</li>
                    <li>Beri tanda resiko jatuh pada tempat tidur pasien</li>
                    <li>Posisi tempat tidur pada posisi terendah</li>
                    <li>Kunjungi dan monitor pasien per 2 jam</li>
                    <li>Tempatkan pasien di kamar yang paling dekat denga nurse station(jika mungkin)</li>
                    <li>Beritahu pasien bila ingin BAK / Kencing supaya minta bantuan</li>
                    <li>Lakukan asesmen resiko jatuh sebelum di transfer</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td style="text-align:center; font-weight: bold; font-size: 20px; width:100%" colspan="5">
                DAFTAR MASALAH KEPERAWATAN
                </td>
        </tr>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="bjnte" id="bjnte" onclick="checkthis('bjnte')">
                    <span class="lbl"> Bersihan Jalan Nafas Tidak Efektif </span>
                </label>
            </td>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="pnte" id="pnte" onclick="checkthis('pnte')">
                    <span class="lbl"> Pola Nafas Tidak Efektif </span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="rp" id="rp" onclick="checkthis('rp')">
                    <span class="lbl"> Resiko Pendarahan </span>
                </label>
            </td>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="rpp" id="rpp" onclick="checkthis('rpp')">
                    <span class="lbl"> Resiko Perfusi Perifer </span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="d" id="d" onclick="checkthis('d')">
                    <span class="lbl"> Diare</span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="in" id="in" onclick="checkthis('in')">
                    <span class="lbl"> Ikterik Neonatus </span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="rksc" id="rksc" onclick="checkthis('rksc')">
                    <span class="lbl"> Resiko Ketidak Seimbangan Cairan </span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="h" id="h" onclick="checkthis('h')">
                    <span class="lbl"> Hipertermia </span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="gss" id="gss" onclick="checkthis('gss')">
                    <span class="lbl"> Gangguan Sirkulasi Spontan </span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox" class="ace" name="dmg" id="dmg" onclick="checkthis('dmg')">
                    <span class="lbl"> Disfungsi MOtilitas Gastrointestinal </span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <label>
                    <input type="checkbox"  class="ace" name="a2" id="a2" onclick="checkthis('a2')">
                    <span class="lbl"><input type="text" placeholder="masukkan jawaban anda" name="mj2" id="mj2" onchange="fillthis('mj2')" class="input type"
                    value=""></span>
                </label> 
            </td>   
        </tr>
        <tr>
            <td colspan="5">
                <input type="checkbox"  class="ace" name="a3" id="a3" onclick="checkthis('a3')">
                    <span class="lbl"><input type="text" placeholder="masukkan jawaban anda" name="mj3" id="mj3" onchange="fillthis('mj3')" class="input type"
                    value=""></span>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <input type="checkbox"  class="ace" name="a4" id="a4" onclick="checkthis('a4')">
                    <span class="lbl"><input type="text" placeholder="masukkan jawaban anda" name="mj4" id="mj4" onchange="fillthis('mj4')" class="input type"
                    value=""></span>
            </td>
        </tr>
        <tr>
            <td colspan="5"><input type="checkbox"  class="ace" name="a5" id="a5" onclick="checkthis('a5')">
                <span class="lbl"><input type="text" placeholder="masukkan jawaban anda" name="mj5" id="mj5" onchange="fillthis('mj5')" class="input type"
                value=""></span>
            </td>
        </tr>
    </tbody>
    </table>
    <br>
    <table border="1" width="100%" style="border-collapse: collapse;">
        <tr>
            <td style="text-align:center; font-weight: bold; font-size: 20px; width:100%" colspan="5">
                Yang Melakukan Pengkajian 
                </td>
        </tr>
        <tr width="100%">
            <td class="sub_data"  align="center" style="width: 25%">Tanggal</td>
            <td class="sub_data"  align="center" style="width: 25%">Jam</td>
            <td class="sub_data"  align="center" style="width: 25%">Nama</td>
            <td class="sub_data"  align="center" style="width: 25%">Tanda Tangan</td>
        </tr>
        <tr>
            <td style="height: 100px"></td>
            <td style="height: 100px"></td>
            <td style="height: 100px"></td>
            <td style="height: 100px"></td>
        </tr>
    </table>
   


<!--END MAIN CONTENT -->

<br><br>
<hr>
<?php echo $footer; ?>
