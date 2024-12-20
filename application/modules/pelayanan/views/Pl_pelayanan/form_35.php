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

  $('#dokter_bedah_1').typeahead({
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
        $('#dokter_bedah_1').val(label_item);
      }

  });

});

</script>

<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 18px;"><b>PEMBERIAN INFORMASI TINDAKAN KEDOKTERAN</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">


<p style="text-align:center; font-weight: bold; font-size: 20px">
    ASUHAN KEPERAWATAN PERIOPERATIF <br>
    RAWAT JALAN / RANAP INSTALASI <br>
    KAMAR BEDAH RS. SETIA MITRA
</p>
<table class="table" border="1" width="100%">
    <tbody>
        <tr>
            <td style="text-align:center; font-weight: bold; font-size: 13px">
                Diagnosa Medis
            </td>
            <td style="text-align:center; font-weight: bold; font-size: 13px">
                Tanggal
            </td>
            <td style="text-align:center; font-weight: bold; font-size: 13px">
                Bagian
            </td>
            <td style="text-align:center; font-weight: bold; font-size: 13px">
                Jenis Operasi / Bius <br>
                Operator / Anestesi
            </td>
        </tr>
        <tr>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="se1" id="se1" onchange="fillthis('se1')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="se2" id="se2" onchange="fillthis('se2')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="se3" id="se3" onchange="fillthis('se3')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="se4" id="se4" onchange="fillthis('se4')" class="input_type" value=""></td>
        </tr>
    </tbody>
</table>
<br>
<table class="table" border="0" width="100%">
    <tr>
        <td style="font-weight: bold;">
            ANMESIA :
        </td>
    </tr>
    <tr>
        <td>
            1. Riwayat operasi (kapan dan jenis operasi) : <input type="text" style="text-align: center" name="se5" id="se5" onchange="fillthis('se5')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>
            2. Riwayat penyakit terdahulu : <label><input type="checkbox" class="ace" name="a1" id="a1" onclick="checkthis('a1')"><span class="lbl"> Asma</span></label>
            <label><input type="checkbox" class="ace" name="a2" id="a2" onclick="checkthis('a2')"><span class="lbl"> Jantung/hipertensi</span></label>
            <label><input type="checkbox" class="ace" name="a3" id="a3" onclick="checkthis('a3')"><span class="lbl"> Giinjal</span></label>
            <label><input type="checkbox" class="ace" name="a4" id="a4" onclick="checkthis('a4')"><span class="lbl"> DM : lain</span></label>
            <input type="text" style="text-align: center" name="se6" id="se6" onchange="fillthis('se6')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>
            3. Riwayat alergi : <input type="checkbox" class="ace" name="a5" id="a5" onclick="checkthis('a5')">Y 
            <input type="checkbox" class="ace" name="a6" id="a6" onclick="checkthis('a6')">T, jenis :
            <input type="text" style="text-align: center" name="se7" id="se7" onchange="fillthis('se7')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>
            4. Kebiasaan : Merokok :  <input type="checkbox" class="ace" name="a7" id="a7" onclick="checkthis('a7')">Y 
            <input type="checkbox" class="ace" name="a8" id="a8" onclick="checkthis('a8')">T
            Minum alcohol : <input type="checkbox" class="ace" name="a9" id="a9" onclick="checkthis('a9')">Y 
            <input type="checkbox" class="ace" name="a10" id="a10" onclick="checkthis('a10')">T;
            Intra Vena drug abuse : <input type="checkbox" class="ace" name="a11" id="a11" onclick="checkthis('a11')">Y 
            <input type="checkbox" class="ace" name="a12" id="a12" onclick="checkthis('a12')">T;
            jenis <input type="text" style="text-align: center" name="se8" id="se8" onchange="fillthis('se8')" class="input_type" value="">
        </td>
    </tr>
</table>
<table class="table" border="1" width="100%">
    <tr style="text-align:center; font-weight: bold; font-size: 13px">
        <td colspan="5">PRE OPERATIF</td>
    </tr>
    <tr style="text-align:center; font-weight: bold; font-size: 13px">
        <td>TGL/<br> JAM</td>
        <td>PENGKAJIAN</td>
        <td>DIAGNOSA KEPERAWATAN</td>
        <td>TINDAKAN</td>
        <td>NAMA / TTD<br>PERAWAT</td>
    </tr>
    <tr>
        <td></td>
        <td><input type="checkbox" class="ace" name="b1" id="b1" onclick="checkthis('b1')"> TD jam 
            <input type="text" style="text-align: center" name="se10" id="se10" onchange="fillthis('se10')" class="input_type" value="">;
            <input type="text" style="text-align: center" name="se11" id="se11" onchange="fillthis('se11')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="b2" id="b2" onclick="checkthis('b2')"> Nadi :  
            <input type="text" style="text-align: center" name="z1" id="z1" onchange="fillthis('z1')" class="input_type" value=""> x/mt;
            <input type="text" style="text-align: center" name="z2" id="z2" onchange="fillthis('z2')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="b3" id="b3" onclick="checkthis('b3')"> Pernafasan :
            <input type="text" style="text-align: center" name="z3" id="z3" onchange="fillthis('z3')" class="input_type" value=""> x/mt;
            <input type="text" style="text-align: center" name="z4" id="z4" onchange="fillthis('z4')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="b4" id="b4" onclick="checkthis('b4')"> Ronchi : <input type="text" style="text-align: center" name="z3" id="z3" onchange="fillthis('z3')" class="input_type" value="">;
            sesak : <input type="checkbox" class="ace" name="b5" id="b5" onclick="checkthis('b5')">Y 
            <input type="checkbox" class="ace" name="b6" id="b6" onclick="checkthis('b6')">T
            <br>

            <input type="checkbox" class="ace" name="b7" id="b7" onclick="checkthis('b7')"> Gigi goyang
            <br>

            <input type="checkbox" class="ace" name="b8" id="b8" onclick="checkthis('b8')"> Nyeri : 
            <input type="checkbox" class="ace" name="b9" id="b9" onclick="checkthis('b9')">Y 
            <input type="checkbox" class="ace" name="b10" id="b10" onclick="checkthis('b10')">T
            skala  : <input type="text" style="text-align: center" name="z5" id="z5" onchange="fillthis('z5')" class="input_type" value="">
            <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lokasi: <input type="text" style="text-align: center" name="z6" id="z6" onchange="fillthis('z6')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="b11" id="b11" onclick="checkthis('b11')"> Hb :
            <br>

            <input type="checkbox" class="ace" name="b11" id="b11" onclick="checkthis('b11')"> leko :
            <br>

            <input type="checkbox" class="ace" name="b11" id="b11" onclick="checkthis('b11')"> Psikososial : <input type="text" style="text-align: center" name="z7" id="z7" onchange="fillthis('z7')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="b12" id="b12" onclick="checkthis('b12')"> Sukar tidur
            <br>

            <input type="checkbox" class="ace" name="b13" id="b13" onclick="checkthis('b13')"> Pandangan kabur :
            <input type="checkbox" class="ace" name="b14" id="b14" onclick="checkthis('b14')">OD / 
            <input type="checkbox" class="ace" name="b15" id="b15" onclick="checkthis('b15')">OS
            <br>

            <input type="checkbox" class="ace" name="b16" id="b16" onclick="checkthis('b16')">
            <input type="text" style="text-align: center" name="z8" id="z8" onchange="fillthis('z8')" class="input_type" value="">
        </td>
        <td style="text-align: left;" valign="top">
            <input type="checkbox" class="ace" name="c1" id="c1" onclick="checkthis('c1')"> Cemas
            <br>

            <input type="checkbox" class="ace" name="c2" id="c2" onclick="checkthis('c2')"> 
            Nyeri <input type="checkbox" class="ace" name="c9" id="c9" onclick="checkthis('c9')">Ringan / 
            <input type="checkbox" class="ace" name="c10" id="c10" onclick="checkthis('c10')">Sedang / 
            <input type="checkbox" class="ace" name="c11" id="c11" onclick="checkthis('c11')">Berat
            <br>

            <input type="checkbox" class="ace" name="c3" id="c3" onclick="checkthis('c3')"> Gangguan perfusi 
            <input type="checkbox" class="ace" name="c12" id="c12" onclick="checkthis('c12')">actual / resti
            <br>

            <input type="checkbox" class="ace" name="c4" id="c4" onclick="checkthis('c4')"> Ketidakseimbangan cairan tubuh
            <br>

            <input type="checkbox" class="ace" name="c5" id="c5" onclick="checkthis('c5')"> Gg body image
            <br>

            <input type="checkbox" class="ace" name="c6" id="c6" onclick="checkthis('c6')"> Resiko jatuh
            <br>

            <input type="checkbox" class="ace" name="c7" id="c7" onclick="checkthis('c7')"> Resiko Aspirasi
            <br>

            <input type="checkbox" class="ace" name="c8" id="c8" onclick="checkthis('c8')">
            <input type="text" style="text-align: center" name="y1" id="y1" onchange="fillthis('y1')" class="input_type" value="">
        </td>
        <td style="text-align: left;" valign="top">
            <input type="checkbox" class="ace" name="d1" id="d1" onclick="checkthis('d1')"> Memperkenalkan Diri
            <br>

            <input type="checkbox" class="ace" name="d2" id="d2" onclick="checkthis('d2')"> Melakukan sign In
            <br>

            <input type="checkbox" class="ace" name="d3" id="d3" onclick="checkthis('d3')"> Informasi Gambar
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Situasi Operasi / Tindakan
            <br>

            <input type="checkbox" class="ace" name="d4" id="d4" onclick="checkthis('d4')"> Pencukuran dan pencucian area insisi
            <br>

            <input type="checkbox" class="ace" name="d5" id="d5" onclick="checkthis('d5')"> Menyiapkan Suction
            <br>

            <input type="checkbox" class="ace" name="d6" id="d6" onclick="checkthis('d6')"> Menyiapkan alat intubasi
            <br>

            <input type="checkbox" class="ace" name="d7" id="d7" onclick="checkthis('d7')"> Menyiapkan mesin 
            <br>

            <input type="checkbox" class="ace" name="d8" id="d8" onclick="checkthis('d8')"> Membimbing Berdo'a
            <br>

            <input type="checkbox" class="ace" name="d9" id="d9" onclick="checkthis('d9')"> Membantu pembiusan SA / GA 
            <br>

            <input type="checkbox" class="ace" name="d10" id="d10" onclick="checkthis('d10')"> Katerisasi urine
            <br>

            <input type="checkbox" class="ace" name="d11" id="d11" onclick="checkthis('d11')"> Mengatur posisi pasien 
        </td>
        <td></td>
    </tr>
    <tr style="text-align:center; font-weight: bold; font-size: 13px">
        <td colspan="5">INTRA OPERATIF</td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: left;" valign="top">
            <input type="checkbox" class="ace" name="e1" id="e1" onclick="checkthis('e1')"> Pendarahan :
            <input type="text" style="text-align: center" name="y2" id="y2" onchange="fillthis('y2')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="e2" id="e2" onclick="checkthis('e2')"> Pernapasan :
            <input type="text" style="text-align: center" name="y3" id="y3" onchange="fillthis('y3')" class="input_type" value=""> x/mt;
            <input type="text" style="text-align: center" name="y4" id="y4" onchange="fillthis('y4')" class="input_type" value="">
            <br> 
            
            <input type="checkbox" class="ace" name="e3" id="e3" onclick="checkthis('e3')"> Nadi pkl :  
            <input type="text" style="text-align: center" name="y5" id="y5" onchange="fillthis('y5')" class="input_type" value=""> x/mt;
            <input type="text" style="text-align: center" name="y6" id="y6" onchange="fillthis('y6')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="e4" id="e4" onclick="checkthis('e4')"> Pendarahan :
            <input type="text" style="text-align: center" name="y7" id="y7" onchange="fillthis('y7')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="e5" id="e5" onclick="checkthis('e5')"> TD pkl:<input type="text" style="text-align: center" name="y8" id="y8" onchange="fillthis('y8')" class="input_type" value=""> mmHg<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="text-align: center" name="y9" id="y9" onchange="fillthis('y9')" class="input_type" value=""> mmHg<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="text-align: center" name="y10" id="y10" onchange="fillthis('y10')" class="input_type" value=""> mmHg<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="text-align: center" name="y11" id="y11" onchange="fillthis('y11')" class="input_type" value=""> mmHg
            <br>

            <input type="checkbox" class="ace" name="e6" id="e6" onclick="checkthis('e6')"> Mual
            <br>

            <input type="checkbox" class="ace" name="e7" id="e7" onclick="checkthis('e7')"> Menggigil
            <br>
        </td>
        <td style="text-align: left;" valign="top">
            <input type="checkbox" class="ace" name="f1" id="f1" onclick="checkthis('f1')"> Resiko Injuri
            <br>

            <input type="checkbox" class="ace" name="f2" id="f2" onclick="checkthis('f2')"> Resiko Infeksi
            <br>

            <input type="checkbox" class="ace" name="f3" id="f3" onclick="checkthis('f3')"> Gangguan Perfusi Jaringan / cerebral
            <br>

            <input type="checkbox" class="ace" name="f4" id="f4" onclick="checkthis('f4')"> Gangguan Keseimbangan Cairan
            <br>

            <input type="checkbox" class="ace" name="f5" id="f5" onclick="checkthis('f5')"> Resiko Aspirasi
            <br>

            <input type="checkbox" class="ace" name="f6" id="f6" onclick="checkthis('f6')"> Infeksi Termogulasi
            <br>

            <input type="checkbox" class="ace" name="f7" id="f7" onclick="checkthis('f7')">
            <input type="text" style="text-align: center" name="y12" id="y12" onchange="fillthis('y12')" class="input_type" value="">
            <br>
        </td>
        <td>
            <input type="checkbox" class="ace" name="g1" id="g1" onclick="checkthis('g1')"> Memasang Restrain
            <br>

            <input type="checkbox" class="ace" name="g2" id="g2" onclick="checkthis('g2')"> Memasang Penetral
            <br>

            <input type="checkbox" class="ace" name="g3" id="g3" onclick="checkthis('g3')"> Melakukan prosedur aseptik 
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(scrubing, gloving, dressing, drapping) 
            <br>

            <input type="checkbox" class="ace" name="g4" id="g4" onclick="checkthis('g4')"> Melakukan A/antiseptik area incisi
            <br>

            <input type="checkbox" class="ace" name="g5" id="g5" onclick="checkthis('g5')"> Melakukan Time Out
            <br>

            <input type="checkbox" class="ace" name="g6" id="g6" onclick="checkthis('g6')"> Monitor TTV tiap 3 menit 
            <br>

            <input type="checkbox" class="ace" name="g7" id="g7" onclick="checkthis('g7')"> Mempertahankan Sterilitas 
            <br>

            <input type="checkbox" class="ace" name="g8" id="g8" onclick="checkthis('g8')"> Monitor Perdarahan 
            <br>

            <input type="checkbox" class="ace" name="g9" id="g9" onclick="checkthis('g9')"> Menghitung kasa / alat
            <br>

            <input type="checkbox" class="ace" name="g10" id="g10" onclick="checkthis('g10')"> Menutup luka operasi
            <br>

            <input type="checkbox" class="ace" name="g11" id="g11" onclick="checkthis('g11')"> Menyiapkan bahan pemeriksaan 
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;laboratorium
            <br>
        </td>
        <td></td>
    </tr>
    <tr style="text-align:center; font-weight: bold; font-size: 13px">
        <td colspan="5">POST OPERATIF</td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type="checkbox" class="ace" name="h1" id="h1" onclick="checkthis('h1')"> Post pembiusan :
            <input type="text" style="text-align: center" name="x1" id="x1" onchange="fillthis('x1')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="h2" id="h2" onclick="checkthis('h2')"> Kesadaran :
            <input type="text" style="text-align: center" name="x2" id="x2" onchange="fillthis('x2')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="h3" id="h3" onclick="checkthis('h3')"> Cianosis /
            <input type="checkbox" class="ace" name="h4" id="h4" onclick="checkthis('h4')"> Pucat /
            <input type="checkbox" class="ace" name="h5" id="h5" onclick="checkthis('h5')"> Gelisah
            <br>

            <input type="checkbox" class="ace" name="h6" id="h6" onclick="checkthis('h6')"> Pernapasan :
            <input type="text" style="text-align: center" name="x3" id="x3" onchange="fillthis('x3')" class="input_type" value=""> x/mt;
            <input type="text" style="text-align: center" name="x4" id="x4" onchange="fillthis('x4')" class="input_type" value="">
            <br> 
            
            <input type="checkbox" class="ace" name="h7" id="h7" onclick="checkthis('h7')"> Nadi pkl :  
            <input type="text" style="text-align: center" name="x5" id="x5" onchange="fillthis('x5')" class="input_type" value=""> x/mt;
            <input type="text" style="text-align: center" name="x6" id="x6" onchange="fillthis('x6')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="h8" id="h8" onclick="checkthis('h8')"> TD pkl:<input type="text" style="text-align: center" name="x7" id="x7" onchange="fillthis('x7')" class="input_type" value=""> mmHg
            <br>

            <input type="checkbox" class="ace" name="h9" id="h9" onclick="checkthis('h9')"> SPO2 : <input type="text" style="text-align: center" name="x8" id="x8" onchange="fillthis('x8')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="h10" id="h10" onclick="checkthis('h10')"> In take parenteral  : 
            <input type="text" style="text-align: center" name="x9" id="x9" onchange="fillthis('x9')" class="input_type" value="">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IVFD : <input type="text" style="text-align: center" name="x10" id="x10" onchange="fillthis('x10')" class="input_type" value="">cc
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Spoel : <input type="text" style="text-align: center" name="x11" id="x11" onchange="fillthis('x11')" class="input_type" value="">cc
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Transfusi : <input type="text" style="text-align: center" name="x12" id="x12" onchange="fillthis('x12')" class="input_type" value="">cc
            <br>

            <input type="checkbox" class="ace" name="h11" id="h11" onclick="checkthis('h11')"> Out Put : 
            <input type="text" style="text-align: center" name="x13" id="x13" onchange="fillthis('x13')" class="input_type" value="">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Perdarahan : <input type="text" style="text-align: center" name="x14" id="x14" onchange="fillthis('x14')" class="input_type" value="">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ngt/muntah : <input type="text" style="text-align: center" name="x15" id="x15" onchange="fillthis('x15')" class="input_type" value="">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Drain : <input type="text" style="text-align: center" name="x16" id="x16" onchange="fillthis('x16')" class="input_type" value="">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Urine : <input type="text" style="text-align: center" name="x17" id="x17" onchange="fillthis('x17')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="h12" id="h12" onclick="checkthis('h12')"> Pandangan mata kabur
            <br>

            <input type="checkbox" class="ace" name="h13" id="h13" onclick="checkthis('h13')"> Extrimitas : 
            <input type="text" style="text-align: center" name="x18" id="x18" onchange="fillthis('x18')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="h14" id="h14" onclick="checkthis('h14')"> Nyeri : 
            <input type="checkbox" class="ace" name="h15" id="h15" onclick="checkthis('h15')">Y 
            <input type="checkbox" class="ace" name="h16" id="h16" onclick="checkthis('h16')">T
            skala  : <input type="text" style="text-align: center" name="x19" id="x19" onchange="fillthis('x19')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="h17" id="h17" onclick="checkthis('h17')"> Nilai Aldrette score : 
            <input type="text" style="text-align: center" name="x20" id="x20" onchange="fillthis('x20')" class="input_type" value="">
            <br>
        </td>
        <td style="text-align: left;" valign="top">
            <input type="checkbox" class="ace" name="i1" id="i1" onclick="checkthis('i1')"> Resiko Aspirasi
            <br>

            <input type="checkbox" class="ace" name="i2" id="i2" onclick="checkthis('i2')"> Kekurangan Cairan
            <br>

            <input type="checkbox" class="ace" name="i3" id="i3" onclick="checkthis('i3')"> Gangguan Pertukaran Gas
            <br>

            <input type="checkbox" class="ace" name="i4" id="i4" onclick="checkthis('i4')"> Inefektif Bersihan Jalan Nafas
            <br>

            <input type="checkbox" class="ace" name="i5" id="i5" onclick="checkthis('i5')"> Inefektif Termogulasi
            <br>

            <input type="checkbox" class="ace" name="i6" id="i6" onclick="checkthis('i6')"> Retensi Urine
            <br>

            <input type="checkbox" class="ace" name="i7" id="i7" onclick="checkthis('i7')"> Gangguan Mobilitas Fisik
            <br>

            <input type="checkbox" class="ace" name="i8" id="i8" onclick="checkthis('i8')"> Nyeri
            <br>

            <input type="checkbox" class="ace" name="i9" id="i9" onclick="checkthis('i9')"> Gg Integritas kulit
            <br>

            <input type="checkbox" class="ace" name="i10" id="i10" onclick="checkthis('i10')"> Resiko Jatuh
            <br>

            <input type="checkbox" class="ace" name="i11" id="i11" onclick="checkthis('i11')">
            <input type="text" style="text-align: center" name="w1" id="w1" onchange="fillthis('w1')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="i12" id="i12" onclick="checkthis('i12')">
            <input type="text" style="text-align: center" name="w2" id="w2" onchange="fillthis('w2')" class="input_type" value="">
            <br>
        </td>
        <td style="text-align: left;" valign="top">
            <input type="checkbox" class="ace" name="j1" id="j1" onclick="checkthis('j1')"> Monitor TTV tiap 5 Menghitung
            <br>

            <input type="checkbox" class="ace" name="j2" id="j2" onclick="checkthis('j2')"> Mempertahankan Adekuasi Jalan Nafas
            <br>

            <input type="checkbox" class="ace" name="j3" id="j3" onclick="checkthis('j3')"> Menghisap Lendir
            <br>

            <input type="checkbox" class="ace" name="j4" id="j4" onclick="checkthis('j4')"> Menghitung I / O cairan
            <br>

            <input type="checkbox" class="ace" name="j5" id="j5" onclick="checkthis('j5')"> Memberi Oksigenasi
            <br>

            <input type="checkbox" class="ace" name="j6" id="j6" onclick="checkthis('j6')"> Memberi Selimut Ekstra
            <br>

            <input type="checkbox" class="ace" name="j7" id="j7" onclick="checkthis('j7')"> Relaksasi
            <br>

            <input type="checkbox" class="ace" name="j8" id="j8" onclick="checkthis('j8')"> Melatih napas dalam dan batuk efektif
            <br>

            <input type="checkbox" class="ace" name="j9" id="j9" onclick="checkthis('j9')"> Penkes perawatan post operasi di rumah
            <br>

            <input type="checkbox" class="ace" name="j10" id="j10" onclick="checkthis('j10')"> Menyarankan pendampingan
            <br>

            <input type="checkbox" class="ace" name="j11" id="j11" onclick="checkthis('j11')"> Menjelaskan kontro ke dr
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="text-align: center" name="w3" id="w3" onchange="fillthis('w3')" class="input_type" value="">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tgl <input type="text" style="text-align: center" name="w4" id="w4" onchange="fillthis('w4')" class="input_type" value="">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;jam<input type="text" style="text-align: center" name="w5" id="w5" onchange="fillthis('w5')" class="input_type" value="">
            <br>

            <input type="checkbox" class="ace" name="j12" id="j12" onclick="checkthis('j12')"> Memulangkan / merujuk pasien
            <br>

            <input type="checkbox" class="ace" name="j12" id="j12" onclick="checkthis('j12')"> Melakukan timbang terima
            <br>
        </td>
        <td></td>
    </tr>
</table>

<hr>
<?php echo $footer; ?>
