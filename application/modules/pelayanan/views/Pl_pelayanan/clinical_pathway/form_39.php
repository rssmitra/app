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
<div style="text-align: center; font-size: 18px;"><b>PENGKAJIAN AWAL RAWAT INAP LANJUTAN</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!--MAIN CONTENT -->


<table border="1" width="100%" class="table">
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
        <td rowspan="4" style="text-align:center;width: 100px">Umur</td>
        <td>&nbsp;dari 3 tahun</td>
        <td align="center">4</td>
        <td style="width:100px">
                <input type="text" style="text-align: center"  name="form_39[a1]" id="a1" onchange="fillthis('a1')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>&nbsp;3 tahun - 7 tahun</td>
        <td align="center">3</td>
        <td><input type="text" style="text-align: center"  name="form_39[a2]" id="a2" onchange="fillthis('a2')" class="input_type" value="">
    </tr>
    <tr>
        <td>&nbsp;7 tahun - 13 tahun</td>
        <td align="center">2</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a3]" id="a3" onchange="fillthis('a3')" class="input_type" value="">
         </td>
    </tr>
    <tr>
        <td>&nbsp;&gt; 13 tahun</td>
        <td align="center">1</td>
        <td>
        <input type="text" style="text-align: center"  name="form_39[a4]" id="a4" onchange="fillthis('a4')" class="input_type" value="">
         </td>
    </tr>
    <tr>
        <td rowspan="2" style="text-align:center">Jenis Kelamin</td>
        <td>&nbsp;Laki-laki</td>
        <td align="center">2</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a5]" id="a5" onchange="fillthis('a5')" class="input_type" value="">
         </td>
    </tr>
    <tr>
        <td>&nbsp;Wanita</td>
        <td align="center">1</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a6]" id="a6" onchange="fillthis('a6')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td rowspan="4" style="text-align: center">Diagnosa</td>
        <td>&nbsp;Neurologi</td>
        <td align="center">4</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a7]" id="a7" onchange="fillthis('a7')" class="input_type" value="">
         </td>
    </tr>
    <tr>
        <td>&nbsp;Respiratory,dehidrasi,anemia,anoreksia,syncope</td>
        <td align="center">3</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a8]" id="a8" onchange="fillthis('a8')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>&nbsp;Perilaku</td>
        <td align="center">2</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a9]" id="a9" onchange="fillthis('a9')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>&nbsp;Lain-lain</td>
        <td align="center">1</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a10]" id="a10" onchange="fillthis('a10')" class="input_type" value=""> 
        </td>
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center">Gangguan Kognitif</td>
        <td>&nbsp;Keterbatasan daya pikir</td>
        <td align="center">3</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a11]" id="a11" onchange="fillthis('a11')" class="input_type" value=""> 
        </td>
    </tr>   
    <tr>
        <td>&nbsp;Pelupa, berkurangnya orientasi sekitar</td>
        <td align="center">2</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a12]" id="a12" onchange="fillthis('a12')" class="input_type" value="">
        </td>
    </tr> 
    <tr>
        <td>&nbsp;Dapat menggunakan daya pikir tanpa Hambatan</td>
        <td align="center">1</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a13]" id="a13" onchange="fillthis('a13')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td rowspan="4" style="text-align: center">Faktor Lingkungan</td>
        <td>&nbsp;Riwayat jatuh atau bayi / balita yang ditempatkan di tempat tidur</td>
        <td align="center">4</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a14]" id="a14" onchange="fillthis('a14')" class="input_type" value=""> 
        </td>
    </tr>
    <tr>
        <td>&nbsp;Pasien menggunakan alat bantu / bayi balita dalam ayunan</td>
        <td align="center">3</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a15]" id="a15" onchange="fillthis('a15')" class="input_type" value=""> 
        </td>
    </tr>
    <tr>
        <td>&nbsp;Pasien ditempat tidur standar</td>
        <td align="center">2</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a16]" id="a16" onchange="fillthis('a16')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>&nbsp;Area pasien rawat jalan</td>
        <td align="center">1</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a17]" id="a17" onchange="fillthis('a17')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center">Respon terhadap pembedahan, sedasi dan anestesi</td>
        <td>&nbsp;Dalam 24 jam</td>
        <td align="center">3</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a18]" id="a18" onchange="fillthis('a18')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>&nbsp;Dalam 48 jam</td>
        <td align="center">2</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a19]" id="a19" onchange="fillthis('a19')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>&nbsp;&gt; 48 jam / tidak ada respon</td>
        <td align="center">1</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a20]" id="a20" onchange="fillthis('a20')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td rowspan="3" style="text-align: center">Penggunaan obat-obatan</td>
        <td>&nbsp;Penggunaan bersama sedative, barbiturate, anti depresan, diuretik</td>
        <td align="center">3</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a21]" id="a21" onchange="fillthis('a21')" class="input_type" value=""> 
        </td>
    </tr>
    <tr>
        <td>&nbsp;Salah satu dari obat diatas </td>
        <td align="center">2</td>
        <td>
        <input type="text" style="text-align: center"  name="form_39[a22]" id="a22" onchange="fillthis('a22')" class="input_type" value=""> 
        </td>
    </tr>
    <tr>
        <td>&nbsp;Obat-obatan lainnya / tanpa obat </td>
        <td align="center">1</td>
        <td>
            <input type="text" style="text-align: center"  name="form_39[a23]" id="a23" onchange="fillthis('a23')" class="input_type" value=""> 
        </td>
    </tr>
    <tr>
        <td style="text-align: center">Kategori:</td>
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
        <td>
            <input type="text" style="text-align: center"  name="form_39[a24]" id="a24" onchange="fillthis('a24')" class="input_type" value="">
        </td>
    </tr>
</table>
<br>

<table border="1" width="100%" class="table">
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
                <input type="checkbox" class="ace" name="form_39[bjnte]" id="bjnte" onclick="checkthis('bjnte')">
                <span class="lbl"> Bersihan Jalan Nafas Tidak Efektif </span>
            </label>
        </td>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox" class="ace" name="form_39[pnte]" id="pnte" onclick="checkthis('pnte')">
                <span class="lbl"> Pola Nafas Tidak Efektif </span>
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox" class="ace" name="form_39[rp]" id="rp" onclick="checkthis('rp')">
                <span class="lbl"> Resiko Pendarahan </span>
            </label>
        </td>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox" class="ace" name="form_39[rpp]" id="rpp" onclick="checkthis('rpp')">
                <span class="lbl"> Resiko Perfusi Perifer </span>
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox" class="ace" name="form_39[d]" id="d" onclick="checkthis('d')">
                <span class="lbl"> Diare</span>
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox" class="ace" name="form_39[in]" id="in" onclick="checkthis('in')">
                <span class="lbl"> Ikterik Neonatus </span>
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox" class="ace" name="form_39[rksc]" id="rksc" onclick="checkthis('rksc')">
                <span class="lbl"> Resiko Ketidak Seimbangan Cairan </span>
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox" class="ace" name="form_39[h]" id="h" onclick="checkthis('h')">
                <span class="lbl"> Hipertermia </span>
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox" class="ace" name="form_39[gss]" id="gss" onclick="checkthis('gss')">
                <span class="lbl"> Gangguan Sirkulasi Spontan </span>
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox" class="ace" name="form_39[dmg]" id="dmg" onclick="checkthis('dmg')">
                <span class="lbl"> Disfungsi MOtilitas Gastrointestinal </span>
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <label>
                <input type="checkbox"  class="ace" name="form_39[a50]" id="a50" onclick="checkthis('a50')">
                <span class="lbl"><input type="text" name="form_39[mj2]" id="mj2" onchange="fillthis('mj2')" class="input_type"
                value=""></span>
            </label> 
        </td>   
    </tr>
    <tr>
        <td colspan="5">
            <input type="checkbox"  class="ace" name="form_39[a51]" id="a51" onclick="checkthis('a51')">
                <span class="lbl"><input type="text" name="form_39[mj3]" id="mj3" onchange="fillthis('mj3')" class="input_type"
                value=""></span>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <input type="checkbox"  class="ace" name="form_39[a52]" id="a52" onclick="checkthis('a52')">
                <span class="lbl"><input type="text" name="form_39[mj4]" id="mj4" onchange="fillthis('mj4')" class="input_type"
                value=""></span>
        </td>
    </tr>
    <tr>
        <td colspan="5"><input type="checkbox"  class="ace" name="form_39[a53]" id="a53" onclick="checkthis('a53')">
            <span class="lbl"><input type="text" name="form_39[mj5]" id="mj5" onchange="fillthis('mj5')" class="input_type"
            value=""></span>
        </td>
    </tr>
</tbody>
</table>


    

<!--END MAIN CONTENT -->

<br><br>
<hr>
<?php echo $footer; ?>
