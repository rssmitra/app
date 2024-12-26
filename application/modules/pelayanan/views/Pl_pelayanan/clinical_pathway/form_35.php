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
<div style="text-align: center; font-size: 14px;"><b>ASUHAN KEPERAWATAN PERIOPERATIF <br>
    RAWAT JALAN / INAP INSTALASI KAMAR BEDAH</b></div>

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
            <td style="text-align:center;" width="10%"><input type="text" name="form_35[se1]" id="se1" onchange="fillthis('se1')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" name="form_35[se2]" id="se2" onchange="fillthis('se2')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" name="form_35[se3]" id="se3" onchange="fillthis('se3')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" name="form_35[se4]" id="se4" onchange="fillthis('se4')" class="input_type" value=""></td>
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
            1. Riwayat operasi (kapan dan jenis operasi) : <input type="text" name="form_35[se5]" id="se5" onchange="fillthis('se5')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>
            2. Riwayat penyakit terdahulu : <label><input type="checkbox" class="ace" name="form_35[a1]" id="a1" onclick="checkthis('a1')"><span class="lbl"> Asma</span></label>
            <label><input type="checkbox" class="ace" name="form_35[a2]" id="a2" onclick="checkthis('a2')"><span class="lbl"> Jantung/hipertensi</span></label>
            <label><input type="checkbox" class="ace" name="form_35[a3]" id="a3" onclick="checkthis('a3')"><span class="lbl"> Giinjal</span></label>
            <label><input type="checkbox" class="ace" name="form_35[a4]" id="a4" onclick="checkthis('a4')"><span class="lbl"> DM : lain</span></label>
            <input type="text" name="form_35[se6]" id="se6" onchange="fillthis('se6')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>
            3. Riwayat alergi : 
            <label>
                <input type="checkbox" class="ace" name="form_35[alergi_no]" id="alergi_no" onclick="checkthis('alergi_no')">
                <span class="lbl"> Tidak</span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="form_35[alergi_ya]" id="alergi_ya" onclick="checkthis('alergi_ya')">
                <span class="lbl"> Ya</span>
            </label>, 
            &nbsp; Jenis Alergi :
            <input type="text" name="form_35[jenis_alergi]" id="jenis_alergi" onchange="fillthis('jenis_alergi')" class="input_type" value="">
        </td>
    </tr>
    <tr>
        <td>
            4. Kebiasaan : 
            <label>
                <input type="checkbox" class="ace" name="form_35[merokok]" id="merokok" onclick="checkthis('merokok')">
                <span class="lbl"> Merokok </span>
            </label>

            <label>
                <input type="checkbox" class="ace" name="form_35[mnm_alkohol]" id="mnm_alkohol" onclick="checkthis('mnm_alkohol')">
                <span class="lbl"> Minum Alkohol </span>
            </label>

            <label>
                <input type="checkbox" class="ace" name="form_35[drugs_abuse]" id="drugs_abuse" onclick="checkthis('drugs_abuse')">
                <span class="lbl"> Intra Vena Drugs Abuse </span>
            </label>,
            Jenis : <input type="text" name="form_35[jenis_drugs]" id="jenis_drugs" onchange="fillthis('jenis_drugs')" class="input_type" value="">
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
            <input type="text" style="width: 50px" name="form_35[tgl_pengkajian_pre_opr]" id="tgl_pengkajian_pre_opr" onchange="fillthis('tgl_pengkajian_pre_opr')" class="input_type" value="">
            <br>
            Jam : <br>
            <input type="text" style="width: 50px" name="form_35[jam_pengkajian_pre_opr]" id="jam_pengkajian_pre_opr" onchange="fillthis('jam_pengkajian_pre_opr')" class="input_type" value="">
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_td]" id="ceklist_td" onclick="checkthis('ceklist_td')">
                <span class="lbl"> TD : </span>
            </label>
            <input type="text" style="width: 50px" name="form_35[td_hasil]" id="td_hasil" onchange="fillthis('td_hasil')" class="input_type" value="">
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_nadi]" id="ceklist_nadi" onclick="checkthis('ceklist_nadi')">
                <span class="lbl"> ND : </span>
            </label>
            <input type="text" style="width: 50px" name="form_35[nd_hasil]" id="nd_hasil" onchange="fillthis('nd_hasil')" class="input_type" value="">x/mt;
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_nfs]" id="ceklist_nfs" onclick="checkthis('ceklist_nfs')">
                <span class="lbl"> Pernafasan : </span>
            </label>
            <input type="text" style="width: 50px" name="form_35[nfs_hasil]" id="nfs_hasil" onchange="fillthis('nfs_hasil')" class="input_type" value="">x/mt; 
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_ronchi]" id="ceklist_ronchi" onclick="checkthis('ceklist_ronchi')">
                <span class="lbl"> Ronchi : </span>
            </label>
            <input type="text" style="width: 50px" name="form_35[ronchi_hasil]" id="ronchi_hasil" onchange="fillthis('ronchi_hasil')" class="input_type" value="">x/mt; 
            <br>
            <div style="padding-left: 20px">
                Sesak :
                <label>
                    <input type="checkbox" class="ace" name="form_35[sesak_ya]" id="sesak_ya" onclick="checkthis('sesak_ya')">
                    <span class="lbl"> Ya </span>
                </label>
                <label>
                    <input type="checkbox" class="ace" name="form_35[sesak_no]" id="sesak_no" onclick="checkthis('sesak_no')">
                    <span class="lbl"> Tidak </span>
                </label>
            </div>

            <label>
                <input type="checkbox" class="ace" name="form_35[sesak_yas]" id="sesak_yas" onclick="checkthis('sesak_yas')">
                <span class="lbl"> Gigi Goyang </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_nyeri]" id="ceklist_nyeri" onclick="checkthis('ceklist_nyeri')">
                <span class="lbl"> Nyeri </span>
            </label>, Skala : <input type="text" style="width: 50px" name="form_35[skala_nyeri]" id="skala_nyeri" onchange="fillthis('skala_nyeri')" class="input_type" value="">
            <br>
            <div style="padding-left: 20px">
                Lokasi :
                <input type="text" style="text-align: center; width: 100px" name="form_35[lokasi_nyeri]" id="lokasi_nyeri" onchange="fillthis('lokasi_nyeri')" class="input_type" value="">
            </div>

            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_hb]" id="ceklist_hb" onclick="checkthis('ceklist_hb')">
                <span class="lbl"> HB : </span>
            </label>
            <input type="text" style="width: 50px" name="form_35[hb_hasil]" id="hb_hasil" onchange="fillthis('hb_hasil')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_leko]" id="ceklist_leko" onclick="checkthis('ceklist_leko')">
                <span class="lbl"> Leko : </span>
            </label>
            <input type="text" style="width: 50px" name="form_35[leko_hasil]" id="leko_hasil" onchange="fillthis('leko_hasil')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_psikososial]" id="ceklist_psikososial" onclick="checkthis('ceklist_psikososial')">
                <span class="lbl"> Psikososial : </span>
            </label>
            <input type="text" style="width: 50px" name="form_35[psiko_hasil]" id="psiko_hasil" onchange="fillthis('psiko_hasil')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_sukar_tdr]" id="ceklist_sukar_tdr" onclick="checkthis('ceklist_sukar_tdr')">
                <span class="lbl"> Sukar Tidur  </span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_pandangan_kabur]" id="ceklist_pandangan_kabur" onclick="checkthis('ceklist_pandangan_kabur')">
                <span class="lbl"> Pandangan Kabur </span>
            </label>,
            <input type="text" style="width: 50px" name="form_35[pandangan_hasil]" id="pandangan_hasil" onchange="fillthis('pandangan_hasil')" class="input_type" value="" placeholder="OD/ OS">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_lainnya]" id="ceklist_lainnya" onclick="checkthis('ceklist_lainnya')">
                <span class="lbl"> Lainnya </span>
            </label>
            <input type="text" style="text-align: center; width: 100px" name="form_35[td_lainnya]" id="td_lainnya" onchange="fillthis('td_lainnya')" class="input_type" value="">
            <br>
        </td>

        <td style="text-align: left;" valign="top">
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_cemas]" id="ceklist_cemas" onclick="checkthis('ceklist_cemas')">
                <span class="lbl"> Cemas </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_nyr_ringan]" id="ceklist_nyr_ringan" onclick="checkthis('ceklist_nyr_ringan')">
                <span class="lbl"> Nyeri ringan </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_nyr_sdg]" id="ceklist_nyr_sdg" onclick="checkthis('ceklist_nyr_sdg')">
                <span class="lbl"> Nyeri Sedang </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_nyr_brt]" id="ceklist_nyr_brt" onclick="checkthis('ceklist_nyr_brt')">
                <span class="lbl"> Nyeri Berat </span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_gguan_perfusi]" id="ceklist_gguan_perfusi" onclick="checkthis('ceklist_gguan_perfusi')">
                <span class="lbl"> Gangguan Perfusi actual/ resti </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_cairan_tubuh]" id="ceklist_cairan_tubuh" onclick="checkthis('ceklist_cairan_tubuh')">
                <span class="lbl"> Ketidakseimbangan cairan tubuh </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_gg_bdy_img]" id="ceklist_gg_bdy_img" onclick="checkthis('ceklist_gg_bdy_img')">
                <span class="lbl"> Gg body Image </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_rsk_jth]" id="ceklist_rsk_jth" onclick="checkthis('ceklist_rsk_jth')">
                <span class="lbl"> Resiko Jatuh </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_rsk_asp]" id="ceklist_rsk_asp" onclick="checkthis('ceklist_rsk_asp')">
                <span class="lbl"> Resiko Aspirasi </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_lainnya_2]" id="ceklist_lainnya_2" onclick="checkthis('ceklist_lainnya_2')">
                <span class="lbl"> Lainnya </span>
            </label>, <input type="text" style="text-align: center; width: 100px" name="form_35[txt_lainnya_2]" id="txt_lainnya_2" onchange="fillthis('txt_lainnya_2')" class="input_type" value="">

        </td>

        <td style="text-align: left;" valign="top">
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_mmprknlkn_dr]" id="ceklist_mmprknlkn_dr" onclick="checkthis('ceklist_mmprknlkn_dr')">
                <span class="lbl"> Memperkenalkan diri </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_signin]" id="ceklist_signin" onclick="checkthis('ceklist_signin')">
                <span class="lbl"> Melakukan sign In </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_info_gbr]" id="ceklist_info_gbr" onclick="checkthis('ceklist_info_gbr')">
                <span class="lbl"> Informasi Gambaran Situasi Operasi/ Tindakan </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_ckr]" id="ceklist_ckr" onclick="checkthis('ceklist_ckr')">
                <span class="lbl"> Pencukuran dan pencucian area insisi </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_suction]" id="ceklist_suction" onclick="checkthis('ceklist_suction')">
                <span class="lbl"> Menyiapkan suction </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_alt_intubasi]" id="ceklist_alt_intubasi" onclick="checkthis('ceklist_alt_intubasi')">
                <span class="lbl"> Menyiapkan alat intubasi </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_cauter]" id="ceklist_cauter" onclick="checkthis('ceklist_cauter')">
                <span class="lbl"> Menyiapkan mesin cauter </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_doa]" id="ceklist_doa" onclick="checkthis('ceklist_doa')">
                <span class="lbl"> Membimbing berdoa </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_bius]" id="ceklist_bius" onclick="checkthis('ceklist_bius')">
                <span class="lbl"> Membantu pembiusan SA/ GA </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_kttr_urin]" id="ceklist_kttr_urin" onclick="checkthis('ceklist_kttr_urin')">
                <span class="lbl"> Katerisasi Urine </span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[ceklist_atur_posisi]" id="ceklist_atur_posisi" onclick="checkthis('ceklist_atur_posisi')">
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
            <input type="text" style="width: 50px" name="form_35[tgl_pengkajian_intra_opr]" id="tgl_pengkajian_intra_opr" onchange="fillthis('tgl_pengkajian_intra_opr')" class="input_type" value="">
            <br>
            Jam : <br>
            <input type="text" style="width: 50px" name="form_35[jam_pengkajian_intra_opr]" id="jam_pengkajian_intra_opr" onchange="fillthis('jam_pengkajian_intra_opr')" class="input_type" value="">
        </td>

        <td style="text-align: left;" valign="top">
            <label>
                <input type="checkbox" class="ace" name="form_35[e1]" id="e1" onclick="checkthis('e1')">
                <span class="lbl"> Pendarahan</span>
            </label>,
            <input type="text" style="width: 50px" name="form_35[y2]" id="y2" onchange="fillthis('y2')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[e2]" id="e2" onclick="checkthis('e2')">
                <span class="lbl"> Pernapasan</span>
            </label>,
            <input type="text" style="width: 50px" name="form_35[y3]" id="y3" onchange="fillthis('y3')" class="input_type" value=""> x/mt;
            <br> 
            
            <label>
                <input type="checkbox" class="ace" name="form_35[e3]" id="e3" onclick="checkthis('e3')">
                <span class="lbl"> Nadi </span>
            </label>,
            <input type="text" style="width: 50px" name="form_35[y5]" id="y5" onchange="fillthis('y5')" class="input_type" value=""> x/mt;
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[e4]" id="e4" onclick="checkthis('e4')">
                <span class="lbl"> SPO2</span>
            </label>,
            <input type="text" style="width: 50px" name="form_35[y7]" id="y7" onchange="fillthis('y7')" class="input_type" value="">
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[e5]" id="e5" onclick="checkthis('e5')">
                <span class="lbl"> TD</span>
            </label>,
            <input type="text" style="width: 50px" name="form_35[y8]" id="y8" onchange="fillthis('y8')" class="input_type" value="">
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[e6]" id="e6" onclick="checkthis('e6')">
                <span class="lbl"> Mual</span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[e7]" id="e7" onclick="checkthis('e7')">
                <span class="lbl"> Menggigil</span>
            </label>

        </td>

        <td style="text-align: left;" valign="top">
            <label>
                <input type="checkbox" class="ace" name="form_35[f1]" id="f1" onclick="checkthis('f1')">
                <span class="lbl"> Resiko Injuri</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[f2]" id="f2" onclick="checkthis('f2')">
                <span class="lbl"> Resiko Infeksi</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[f3]" id="f3" onclick="checkthis('f3')">
                <span class="lbl"> Gangguan Perfusi Jaringan / cerebral</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[f4]" id="f4" onclick="checkthis('f4')">
                <span class="lbl"> Gangguan Keseimbangan Cairan</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[f5]" id="f5" onclick="checkthis('f5')">
                <span class="lbl"> Resiko Aspirasi</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[f6]" id="f6" onclick="checkthis('f6')">
                <span class="lbl"> Infeksi Termogulasi</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[f7]" id="f7" onclick="checkthis('f7')">
                <span class="lbl"></span>
            </label>
            <input type="text" name="form_35[y12]" id="y12" onchange="fillthis('y12')" class="input_type" value="">
            <br>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_35[g1]" id="g1" onclick="checkthis('g1')">
                <span class="lbl"> Memasang Restrain</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g2]" id="g2" onclick="checkthis('g2')">
                <span class="lbl"> Memasang Penetral</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g3]" id="g3" onclick="checkthis('g3')">
                <span class="lbl"> Melakukan prosedur aseptik (scrubing, gloving, dressing, drapping) </span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g4]" id="g4" onclick="checkthis('g4')">
                <span class="lbl"> Melakukan A/antiseptik area incisi</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g5]" id="g5" onclick="checkthis('g5')">
                <span class="lbl"> Melakukan Time Out</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g6]" id="g6" onclick="checkthis('g6')">
                <span class="lbl"> Monitor TTV tiap 3 menit </span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g7]" id="g7" onclick="checkthis('g7')">
                <span class="lbl"> Mempertahankan Sterilitas </span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g8]" id="g8" onclick="checkthis('g8')">
                <span class="lbl"> Monitor Perdarahan </span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g9]" id="g9" onclick="checkthis('g9')">
                <span class="lbl"> Menghitung kasa / alat</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g10]" id="g10" onclick="checkthis('g10')">
                <span class="lbl"> Menutup luka operasi</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[g11]" id="g11" onclick="checkthis('g11')">
                <span class="lbl"> Menyiapkan bahan pemeriksaan laboratorium</span>
            </label>
            <br>
        </td>
        <td></td>
    </tr>
    <tr style="text-align:center; font-weight: bold; font-size: 13px">
        <td colspan="4">POST OPERATIF</td>
    </tr>
    <tr>
        <td>
            Tanggal : <br>
            <input type="text" style="width: 50px" name="form_35[tgl_pengkajian_intra_opr]" id="tgl_pengkajian_intra_opr" onchange="fillthis('tgl_pengkajian_intra_opr')" class="input_type" value="">
            <br>
            Jam : <br>
            <input type="text" style="width: 50px" name="form_35[jam_pengkajian_intra_opr]" id="jam_pengkajian_intra_opr" onchange="fillthis('jam_pengkajian_intra_opr')" class="input_type" value="">
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_35[h1]" id="h1" onclick="checkthis('h1')">
                <span class="lbl"> Post pembiusan :</span>
            </label>
            <input type="text" style="width: 50px" name="form_35[x1]" id="x1" onchange="fillthis('x1')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h2]" id="h2" onclick="checkthis('h2')">
                <span class="lbl"> Kesadaran :</span>
            </label>
            <input type="text" style="width: 50px" name="form_35[x2]" id="x2" onchange="fillthis('x2')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h3]" id="h3" onclick="checkthis('h3')"> 
                <span class="lbl"> Cianosis /</span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="form_35[h4]" id="h4" onclick="checkthis('h4')">
                <span class="lbl"> Pucat /</span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="form_35[h5]" id="h5" onclick="checkthis('h5')">
                <span class="lbl">Gelisah</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h6]" id="h6" onclick="checkthis('h6')">
                <span class="lbl">Pernapasan :</span>
            </label>
            <input type="text" style="width: 50px" name="form_35[x3]" id="x3" onchange="fillthis('x3')" class="input_type" value=""> x/mt;
            <br> 
            
            <label>
                <input type="checkbox" class="ace" name="form_35[h7]" id="h7" onclick="checkthis('h7')"> 
                <span class="lbl">Nadi : </span>
            </label>
            <input type="text" style="width: 50px" name="form_35[x5]" id="x5" onchange="fillthis('x5')" class="input_type" value=""> x/mt;
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h8]" id="h8" onclick="checkthis('h8')">
                <span class="lbl">TD :</span>
            </label>
            <input type="text" style="width: 50px" name="form_35[x7]" id="x7" onchange="fillthis('x7')" class="input_type" value=""> mmHg
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h9]" id="h9" onclick="checkthis('h9')"> 
                <span class="lbl">SPO2 :</span>
            </label>
            <input type="text" style="width: 50px" name="form_35[x8]" id="x8" onchange="fillthis('x8')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h10]" id="h10" onclick="checkthis('h10')"> 
                <span class="lbl">In take parenteral  :</span>
            </label>
            <input type="text" style="width: 50px" name="form_35[x9]" id="x9" onchange="fillthis('x9')" class="input_type" value="">
            <br>

            <div style="padding-left: 20px">
                IVFD :
                <input type="text" style="text-align: center; width: 100px" name="form_35[IV_FD]" id="IV_FD" onchange="fillthis('IV_FD')" class="input_type" value="">cc
            </div>
            <br>

            <div style="padding-left: 20px">
                Spoel :
                <input type="text" style="text-align: center; width: 100px" name="form_35[label_spoel]" id="label_spoel" onchange="fillthis('label_spoel')" class="input_type" value="">cc
            </div>
            <br>
            
            <div style="padding-left: 20px">
                Transfusi :
                <input type="text" style="text-align: center; width: 100px" name="form_35[label_transfusi]" id="label_transfusi" onchange="fillthis('label_transfusi')" class="input_type" value="">cc
            </div>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h11]" id="h11" onclick="checkthis('h11')">
                <span class="lbl">Out Put :</span>
            </label>
            <input type="text" style="width: 50px" name="form_35[x13]" id="x13" onchange="fillthis('x13')" class="input_type" value="">
            <br>
            
            <div style="padding-left: 20px">
                Perdarahan :
                <input type="text" style="text-align: center; width: 100px" name="form_35[label_pendarahan]" id="label_pendarahan" onchange="fillthis('label_pendarahan')" class="input_type" value="">
            </div>
            <br>
            
            <div style="padding-left: 20px">
                Ngt/muntah :
                <input type="text" style="text-align: center; width: 100px" name="form_35[ngt_muntah]" id="ngt_muntah" onchange="fillthis('ngt_muntah')" class="input_type" value="">
            </div>
            <br>
            
            <div style="padding-left: 20px">
                Drain :
                <input type="text" style="text-align: center; width: 100px" name="form_35[label_drain]" id="label_drain" onchange="fillthis('label_drain')" class="input_type" value="">
            </div>
            <br>
            
            <div style="padding-left: 20px">
                Urine :
                <input type="text" style="text-align: center; width: 100px" name="form_35[label_urine]" id="label_urine" onchange="fillthis('label_urine')" class="input_type" value="">
            </div>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h12]" id="h12" onclick="checkthis('h12')"> 
                <span class="lbl">Pandangan mata kabur</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h13]" id="h13" onclick="checkthis('h13')">
                <span class="lbl">Extrimitas :</span> 
            </label>
            <input type="text" style="width: 50px" name="form_35[x18]" id="x18" onchange="fillthis('x18')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h14]" id="h14" onclick="checkthis('h14')">
                <span class="lbl"> Nyeri :</span>
            </label>  
            <label>
                <input type="checkbox" class="ace" name="form_35[h15]" id="h15" onclick="checkthis('h15')">
                <span class="lbl"> Y</span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="form_35[h16]" id="h16" onclick="checkthis('h16')">
                <span class="lbl"> T</span>
            </label>
            <br>
            skala  : <input type="text" style="width: 50px" name="form_35[x19]" id="x19" onchange="fillthis('x19')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[h17]" id="h17" onclick="checkthis('h17')">  
                <span class="lbl"> Nilai Aldrette score :</span>
            </label>
            <input type="text" style="width: 50px" name="form_35[x20]" id="x20" onchange="fillthis('x20')" class="input_type" value="">
            <br>
        </td>
        <td style="text-align: left;" valign="top">
            <label>
                <input type="checkbox" class="ace" name="form_35[i1]" id="i1" onclick="checkthis('i1')"> 
                <span class="lbl"> Resiko Aspirasi</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i2]" id="i2" onclick="checkthis('i2')"> 
                <span class="lbl"> Kekurangan Cairan</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i3]" id="i3" onclick="checkthis('i3')"> 
                <span class="lbl"> Gangguan Pertukaran Gas</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i4]" id="i4" onclick="checkthis('i4')"> 
                <span class="lbl"> Inefektif Bersihan Jalan Nafas</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i5]" id="i5" onclick="checkthis('i5')"> 
                <span class="lbl">Inefektif Termogulasi</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i6]" id="i6" onclick="checkthis('i6')"> 
                <span class="lbl"> Retensi Urine</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i7]" id="i7" onclick="checkthis('i7')"> 
                <span class="lbl"> Gangguan Mobilitas Fisik</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i8]" id="i8" onclick="checkthis('i8')"> 
                <span class="lbl"> Nyeri</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i9]" id="i9" onclick="checkthis('i9')"> 
                <span class="lbl"> Gg Integritas kulit</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i10]" id="i10" onclick="checkthis('i10')"> 
                <span class="lbl"> Resiko Jatuh</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i11]" id="i11" onclick="checkthis('i11')">
                <span class="lbl"></span>
            </label>
            <input type="text" name="form_35[w1]" id="w1" onchane="fillthis('w1')" class="input_type" value="">
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[i12]" id="i12" onclick="checkthis('i12')">
                <span class="lbl"></span>
            </label>    
            <input type="text" name="form_35[w2]" id="w2" onchane="fillthis('w2')" class="input_type" value="">
            <br>
        </td>
        
        <td style="text-align: left;" valign="top">
            <label>
                <input type="checkbox" class="ace" name="form_35[j1]" id="j1" onclick="checkthis('j1')"> 
                <span class="lbl">Monitor TTV tiap 5 Menghitung</span>
            </label>
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_35[j2]" id="j2" onclick="checkthis('j2')"> 
                <span class="lbl">Mempertahankan Adekuasi Jalan Nafas</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j3]" id="j3" onclick="checkthis('j3')"> 
                <span class="lbl">Menghisap Lendir</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j4]" id="j4" onclick="checkthis('j4')"> 
                <span class="lbl">Menghitung I / O cairan</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j5]" id="j5" onclick="checkthis('j5')"> 
                <span class="lbl">Memberi Oksigenasi</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j6]" id="j6" onclick="checkthis('j6')"> 
                <span class="lbl">Memberi Selimut Ekstra</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j7]" id="j7" onclick="checkthis('j7')"> 
                <span class="lbl">Relaksasi</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j8]" id="j8" onclick="checkthis('j8')"> 
                <span class="lbl">Melatih napas dalam dan batuk efektif</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j9]" id="j9" onclick="checkthis('j9')"> 
                <span class="lbl">Penkes perawatan post operasi di rumah</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j10]" id="j10" onclick="checkthis('j10')"> 
                <span class="lbl">Menyarankan pendampingan</span>
            </label>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j11]" id="j11" onclick="checkthis('j11')"> 
                <span class="lbl">Menjelaskan kontro ke dr</span>          
            </label>    
            <br>
            <input type="text" name="form_35[w3]" id="w3" onchange="fillthis('w3')" class="input_type" value="">
            <br>
            
            <div style="padding-left: 20px">
                tgl
                <input type="text" style="text-align: center; width: 100px" name="form_35[label_tgl]" id="label_tgl" onchange="fillthis('label_tgl')" class="input_type" value="">
            </div>
            <br>
            
            <div style="padding-left: 20px">
                jam
                <input type="text" style="text-align: center; width: 100px" name="form_35[label_jam]" id="label_jam" onchange="fillthis('label_jam')" class="input_type" value="">
            </div>
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j12]" id="j12" onclick="checkthis('j12')">
                <span class="lbl">Memulangkan / merujuk pasien</span>          
            </label>    
            <br>

            <label>
                <input type="checkbox" class="ace" name="form_35[j13]" id="j13" onclick="checkthis('j13')"> 
                <span class="lbl">Melakukan timbang terima</span>          
            </label>    
            <br>
        </td>
        <td></td>
    </tr>
</table>

<hr>
<?php echo $footer; ?>
