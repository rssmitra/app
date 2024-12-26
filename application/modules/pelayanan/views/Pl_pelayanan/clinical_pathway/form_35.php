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
<div style="text-align: center; font-size: 18px;"><b>ASUHAN KEPERAWATAN PERIOPERATIF <br>
    RAWAT JALAN / INAP INSTALASI KAMAR BEDAH RS. SETIA MITRA</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<br>
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
            3. Riwayat alergi : 
            <label>
                <input type="checkbox" class="ace" name="alergi_no" id="alergi_no" onclick="checkthis('alergi_no')">
                <span class="lbl"> Tidak</span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="alergi_ya" id="alergi_ya" onclick="checkthis('alergi_ya')">
                <span class="lbl"> Ya</span>
            </label>, 
            &nbsp; Jenis Alergi :
            <input type="text" style="text-align: center" name="jenis_alergi" id="jenis_alergi" onchange="fillthis('jenis_alergi')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>
            4. Kebiasaan : 
            <label>
                <input type="checkbox" class="ace" name="merokok" id="merokok" onclick="checkthis('merokok')">
                <span class="lbl"> Merokok </span>
            </label>

            <label>
                <input type="checkbox" class="ace" name="mnm_alkohol" id="mnm_alkohol" onclick="checkthis('mnm_alkohol')">
                <span class="lbl"> Minum Alkohol </span>
            </label>

            <label>
                <input type="checkbox" class="ace" name="drugs_abuse" id="drugs_abuse" onclick="checkthis('drugs_abuse')">
                <span class="lbl"> Intra Vena Drugs Abuse </span>
            </label>,
            Jenis : <input type="text" style="text-align: center" name="jenis_drugs" id="jenis_drugs" onchange="fillthis('jenis_drugs')" class="input_type" value="">
        </td>
    </tr>
</table>
<table class="table" border="1" width="100%">
    <tr style="text-align:center; font-weight: bold; font-size: 13px">
        <td colspan="4">PRE OPERATIF</td>
    </tr>
    <tr style="text-align:center; font-weight: bold; font-size: 13px">
        <td style="width: 10%">TGL/<br> JAM</td>
        <td style="width: 30%">PENGKAJIAN</td>
        <td style="width: 30%">DIAGNOSA KEPERAWATAN</td>
        <td style="width: 30%">TINDAKAN</td>
    </tr>
    <tr>
        <td>
            Tanggal : <br>
            <input type="text" style="text-align: center; width: 50px" name="tgl_pengkajian_pre_opr" id="tgl_pengkajian_pre_opr" onchange="fillthis('tgl_pengkajian_pre_opr')" class="input_type" value="">
            <br>
            Jam : <br>
            <input type="text" style="text-align: center; width: 50px" name="jam_pengkajian_pre_opr" id="jam_pengkajian_pre_opr" onchange="fillthis('jam_pengkajian_pre_opr')" class="input_type" value="">
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="ceklist_td" id="ceklist_td" onclick="checkthis('ceklist_td')">
                <span class="lbl"> TD : </span>
            </label>
            <input type="text" style="text-align: center; width: 50px" name="td_hasil" id="td_hasil" onchange="fillthis('td_hasil')" class="input_type" value="">
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_nadi" id="ceklist_nadi" onclick="checkthis('ceklist_nadi')">
                <span class="lbl"> ND : </span>
            </label>
            <input type="text" style="text-align: center; width: 50px" name="nd_hasil" id="nd_hasil" onchange="fillthis('nd_hasil')" class="input_type" value="">x/mt;
            <br>

            <label>
                <input type="checkbox" class="ace" name="ceklist_nfs" id="ceklist_nfs" onclick="checkthis('ceklist_nfs')">
                <span class="lbl"> Pernafasan : </span>
            </label>
            <input type="text" style="text-align: center; width: 50px" name="nfs_hasil" id="nfs_hasil" onchange="fillthis('nfs_hasil')" class="input_type" value="">x/mt; 
            <br>

            <label>
                <input type="checkbox" class="ace" name="ceklist_ronchi" id="ceklist_ronchi" onclick="checkthis('ceklist_ronchi')">
                <span class="lbl"> Ronchi : </span>
            </label>
            <input type="text" style="text-align: center; width: 50px" name="ronchi_hasil" id="ronchi_hasil" onchange="fillthis('ronchi_hasil')" class="input_type" value="">x/mt; 
            <br>
            <div style="padding-left: 20px">
                Sesak :
                <label>
                    <input type="checkbox" class="ace" name="sesak_ya" id="sesak_ya" onclick="checkthis('sesak_ya')">
                    <span class="lbl"> Ya </span>
                </label>
                <label>
                    <input type="checkbox" class="ace" name="sesak_no" id="sesak_no" onclick="checkthis('sesak_no')">
                    <span class="lbl"> Tidak </span>
                </label>
            </div>

            <label>
                <input type="checkbox" class="ace" name="sesak_ya" id="sesak_ya" onclick="checkthis('sesak_ya')">
                <span class="lbl"> Gigi Goyang </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_nyeri" id="ceklist_nyeri" onclick="checkthis('ceklist_nyeri')">
                <span class="lbl"> Nyeri </span>
            </label>, Skala : <input type="text" style="text-align: center; width: 50px" name="skala_nyeri" id="skala_nyeri" onchange="fillthis('skala_nyeri')" class="input_type" value="">
            <br>
            <div style="padding-left: 20px">
                Lokasi :
                <input type="text" style="text-align: center; width: 100px" name="lokasi_nyeri" id="lokasi_nyeri" onchange="fillthis('lokasi_nyeri')" class="input_type" value="">
            </div>

            <label>
                <input type="checkbox" class="ace" name="ceklist_hb" id="ceklist_hb" onclick="checkthis('ceklist_hb')">
                <span class="lbl"> HB : </span>
            </label>
            <input type="text" style="text-align: center; width: 50px" name="hb_hasil" id="hb_hasil" onchange="fillthis('hb_hasil')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="ceklist_leko" id="ceklist_leko" onclick="checkthis('ceklist_leko')">
                <span class="lbl"> Leko : </span>
            </label>
            <input type="text" style="text-align: center; width: 50px" name="leko_hasil" id="leko_hasil" onchange="fillthis('leko_hasil')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="ceklist_psikososial" id="ceklist_psikososial" onclick="checkthis('ceklist_psikososial')">
                <span class="lbl"> Psikososial : </span>
            </label>
            <input type="text" style="text-align: center; width: 50px" name="psiko_hasil" id="psiko_hasil" onchange="fillthis('psiko_hasil')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="ceklist_sukar_tdr" id="ceklist_sukar_tdr" onclick="checkthis('ceklist_sukar_tdr')">
                <span class="lbl"> Sukar Tidur  </span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="ceklist_pandangan_kabur" id="ceklist_pandangan_kabur" onclick="checkthis('ceklist_pandangan_kabur')">
                <span class="lbl"> Pandangan Kabur </span>
            </label>,
            <input type="text" style="text-align: center; width: 50px" name="pandangan_hasil" id="pandangan_hasil" onchange="fillthis('pandangan_hasil')" class="input_type" value="" placeholder="OD/ OS">
            <br>

            <label>
                <input type="checkbox" class="ace" name="ceklist_lainnya" id="ceklist_lainnya" onclick="checkthis('ceklist_lainnya')">
                <span class="lbl"> Lainnya </span>
            </label>
            <input type="text" style="text-align: center; width: 100px" name="td_hasil" id="td_hasil" onchange="fillthis('td_hasil')" class="input_type" value="">
            <br>
        </td>

        <td style="text-align: left;" valign="top">
            <label>
                <input type="checkbox" class="ace" name="ceklist_cemas" id="ceklist_cemas" onclick="checkthis('ceklist_cemas')">
                <span class="lbl"> Cemas </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_nyr_ringan" id="ceklist_nyr_ringan" onclick="checkthis('ceklist_nyr_ringan')">
                <span class="lbl"> Nyeri ringan </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_nyr_sdg" id="ceklist_nyr_sdg" onclick="checkthis('ceklist_nyr_sdg')">
                <span class="lbl"> Nyeri Sedang </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_nyr_brt" id="ceklist_nyr_brt" onclick="checkthis('ceklist_nyr_brt')">
                <span class="lbl"> Nyeri Berat </span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="ceklist_gguan_perfusi" id="ceklist_gguan_perfusi" onclick="checkthis('ceklist_gguan_perfusi')">
                <span class="lbl"> Gangguan Perfusi actual/ resti </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_cairan_tubuh" id="ceklist_cairan_tubuh" onclick="checkthis('ceklist_cairan_tubuh')">
                <span class="lbl"> Ketidakseimbangan cairan tubuh </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_gg_bdy_img" id="ceklist_gg_bdy_img" onclick="checkthis('ceklist_gg_bdy_img')">
                <span class="lbl"> Gg body Image </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_rsk_jth" id="ceklist_rsk_jth" onclick="checkthis('ceklist_rsk_jth')">
                <span class="lbl"> Resiko Jatuh </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_rsk_asp" id="ceklist_rsk_asp" onclick="checkthis('ceklist_rsk_asp')">
                <span class="lbl"> Resiko Aspirasi </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_lainnya_2" id="ceklist_lainnya_2" onclick="checkthis('ceklist_lainnya_2')">
                <span class="lbl"> Lainnya </span>
            </label>, <input type="text" style="text-align: center; width: 100px" name="txt_lainnya_2" id="txt_lainnya_2" onchange="fillthis('txt_lainnya_2')" class="input_type" value="">

        </td>

        <td style="text-align: left;" valign="top">
            <label>
                <input type="checkbox" class="ace" name="ceklist_mmprknlkn_dr" id="ceklist_mmprknlkn_dr" onclick="checkthis('ceklist_mmprknlkn_dr')">
                <span class="lbl"> Memperkenalkan diri </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_signin" id="ceklist_signin" onclick="checkthis('ceklist_signin')">
                <span class="lbl"> Melakukan sign In </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_info_gbr" id="ceklist_info_gbr" onclick="checkthis('ceklist_info_gbr')">
                <span class="lbl"> Informasi Gambaran Situasi Operasi/ Tindakan </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_ckr" id="ceklist_ckr" onclick="checkthis('ceklist_ckr')">
                <span class="lbl"> Pencukuran dan pencucian area insisi </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_suction" id="ceklist_suction" onclick="checkthis('ceklist_suction')">
                <span class="lbl"> Menyiapkan suction </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_alt_intubasi" id="ceklist_alt_intubasi" onclick="checkthis('ceklist_alt_intubasi')">
                <span class="lbl"> Menyiapkan alat intubasi </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_cauter" id="ceklist_cauter" onclick="checkthis('ceklist_cauter')">
                <span class="lbl"> Menyiapkan mesin cauter </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_doa" id="ceklist_doa" onclick="checkthis('ceklist_doa')">
                <span class="lbl"> Membimbing berdoa </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_bius" id="ceklist_bius" onclick="checkthis('ceklist_bius')">
                <span class="lbl"> Membantu pembiusan SA/ GA </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_kttr_urin" id="ceklist_kttr_urin" onclick="checkthis('ceklist_kttr_urin')">
                <span class="lbl"> Katerisasi Urine </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="ceklist_atur_posisi" id="ceklist_atur_posisi" onclick="checkthis('ceklist_atur_posisi')">
                <span class="lbl"> Mengatur posisi pasien </span>
            </label>
            <br> 
        </td>
        <td></td>
    </tr>
    <tr style="text-align:center; font-weight: bold; font-size: 13px">
        <td colspan="4">INTRA OPERATIF</td>
    </tr>
    <tr>
        <td>
            Tanggal : <br>
            <input type="text" style="text-align: center; width: 50px" name="tgl_pengkajian_intra_opr" id="tgl_pengkajian_intra_opr" onchange="fillthis('tgl_pengkajian_intra_opr')" class="input_type" value="">
            <br>
            Jam : <br>
            <input type="text" style="text-align: center; width: 50px" name="jam_pengkajian_intra_opr" id="jam_pengkajian_intra_opr" onchange="fillthis('jam_pengkajian_intra_opr')" class="input_type" value="">
        </td>

        <td style="text-align: left;" valign="top">
            <label>
                <input type="checkbox" class="ace" name="e1" id="e1" onclick="checkthis('e1')">
                <span class="lbl"> Pendarahan</span>
            </label>,
            <input type="text" style="text-align: center; width: 50px" name="y2" id="y2" onchange="fillthis('y2')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="e2" id="e2" onclick="checkthis('e2')">
                <span class="lbl"> Pernapasan</span>
            </label>,
            <input type="text" style="text-align: center; width: 50px" name="y3" id="y3" onchange="fillthis('y3')" class="input_type" value=""> x/mt;
            <br> 
            
            <label>
                <input type="checkbox" class="ace" name="e3" id="e3" onclick="checkthis('e3')">
                <span class="lbl"> Nadi </span>
            </label>,
            <input type="text" style="text-align: center; width: 50px" name="y5" id="y5" onchange="fillthis('y5')" class="input_type" value=""> x/mt;
            <br>

            <label>
                <input type="checkbox" class="ace" name="e4" id="e4" onclick="checkthis('e4')">
                <span class="lbl"> SPO2</span>
            </label>,
            <input type="text" style="text-align: center; width: 50px" name="y7" id="y7" onchange="fillthis('y7')" class="input_type" value="">
            <br>
            <label>
                <input type="checkbox" class="ace" name="e4" id="e4" onclick="checkthis('e4')">
                <span class="lbl"> TD</span>
            </label>,
            <input type="text" style="text-align: center; width: 50px" name="y7" id="y7" onchange="fillthis('y7')" class="input_type" value="">
            <br>
            <label>
                <input type="checkbox" class="ace" name="e4" id="e4" onclick="checkthis('e4')">
                <span class="lbl"> Mual</span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="e4" id="e4" onclick="checkthis('e4')">
                <span class="lbl"> Menggigil</span>
            </label>

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
        <td colspan="4">POST OPERATIF</td>
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
