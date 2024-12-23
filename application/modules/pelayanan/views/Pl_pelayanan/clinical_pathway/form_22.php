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

<div style="text-align: center; font-size: 16px;"><b>RIWAYAT PENYAKIT PASIEN KASUS OBSTETRI ANAMNESIS</b></div>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<span><b><u>ANAMNESIS</b></u></span>
<br>
Riwayat Haid : <br>
Menarche <input type="text" id="menarche" name="form_22[menarche]" class="input_type" style="width: 100px !important"> th &nbsp;&nbsp;&nbsp; 
Haid : &nbsp; 
<label>
    <input type="checkbox" class="ace" name="form_22[haid_1]" id="haid_1"  onclick="checkthis('haid_1')">
    <span class="lbl">  Teratur</span>
</label>
<label>
    <input type="checkbox" class="ace" name="form_22[haid_2]" id="haid_2"  onclick="checkthis('haid_2')">
    <span class="lbl">  Tidak Teratur</span>
</label>
<label>
    <input type="checkbox" class="ace" name="form_22[haid_3]" id="haid_3"  onclick="checkthis('haid_3')">
    <span class="lbl">  Nyeri Haid</span>
</label>
<br>
Haid terakhir : <input type="text" id="haid_last" name="form_22[haid_last]" class="input_type" > 
Taksiran Partus : <input type="text" id="partus" name="form_22[partus]" class="input_type">

<br>
<br>

<span><b><u>KELUHAN:</b></u></span>
<br>
Mules-mules sejak tanggal <input type="text" id="tgl_mules" name="form_22[tgl_mules]" class="input_type" style="width: 70px !important"> jam <input type="text" id="jam_mules" name="form_22[jam_mules]" class="input_type" style="width: 70px !important"> <br>
Keluar air-air : <input type="text" id="keluar_air" name="form_22[keluar_air]" class="input_type" style="width: 500px !important"><br>
<br>
Keluar darah lender<br>
<label>
    <input type="checkbox" class="ace" name="form_22[kdl_tidak]" id="kdl_tidak"  onclick="checkthis('kdl_tidak')">
    <span class="lbl">  Tidak </span>
</label>
<label>
    <input type="checkbox" class="ace" name="form_22[kdl_ya]" id="kdl_ya"  onclick="checkthis('kdl_ya')">
    <span class="lbl">  Ya </span>
</label>, sejak <input type="text" id="kdl_sejak" name="form_22[kdl_sejak]" class="input_type">
<br>
<br>
Pendarahan<br>
<label>
    <input type="checkbox" class="ace" name="form_22[pendarahan_1]" id="pendarahan_1"  onclick="checkthis('pendarahan_1')">
    <span class="lbl">  Tidak </span>
</label>
<label>
    <input type="checkbox" class="ace" name="form_22[pendarahan_2]" id="pendarahan_2"  onclick="checkthis('pendarahan_2')">
    <span class="lbl">  Ya </span>
</label>, sejak <input type="text" id="pendarahan_sejak" name="form_22[pendarahan_sejak]" class="input_type">
<br>
<div style="padding-left: 30px">
    Jumlah : 
    <label>
        <input type="checkbox" class="ace" name="form_22[jml_banyak]" id="jml_banyak"  onclick="checkthis('jml_banyak')">
        <span class="lbl">  Banyak </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[jml_sedang]" id="jml_sedang"  onclick="checkthis('jml_sedang')">
        <span class="lbl">  Sedang </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[jml_sedikit]" id="jml_sedikit"  onclick="checkthis('jml_sedikit')">
        <span class="lbl">  Sedikit </span>
    </label>
</div>
<br>
Gerak Janin<br>
<label>
    <input type="checkbox" class="ace" name="form_22[gerak_janin_no]" id="gerak_janin_no"  onclick="checkthis('gerak_janin_no')">
    <span class="lbl">  Tidak </span>
</label>
<label>
    <input type="checkbox" class="ace" name="form_22[gerak_janin_y]" id="gerak_janin_y"  onclick="checkthis('gerak_janin_y')">
    <span class="lbl"> Ya </span>
</label>, sejak <input type="text" id="gerak_sejak" name="form_22[gerak_sejak]" class="input_type">
<br>
<div style="padding-left: 30px">
    Pusing : 
    <label>
        <input type="checkbox" class="ace" name="form_22[gj_1]" id="gj_1"  onclick="checkthis('gj_1')">
        <span class="lbl"> Pingsan </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[gj_2]" id="gj_2"  onclick="checkthis('gj_2')">
        <span class="lbl"> Muntah </span>
    </label>, <input type="text" id="menarche" name="form_22[menarche]" class="input_type">
    <br>
    Kejang : 
    <label>
        <input type="checkbox" class="ace" name="form_22[gj_3]" id="gj_3"  onclick="checkthis('gj_3')">
        <span class="lbl"> Rasa mengedan </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[gj_4]" id="gj_4"  onclick="checkthis('gj_4')">
        <span class="lbl"> Berkunang- kunang </span>
    </label>
    <br>
    Sesak Nafas : 
    <label>
        <input type="checkbox" class="ace" name="form_22[gj_5]" id="gj_5"  onclick="checkthis('gj_5')">
        <span class="lbl"> Jantung Berdebar </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[gj_6]" id="gj_6"  onclick="checkthis('gj_6')">
        <span class="lbl"> Edema </span>
    </label>, <input type="text" id="edema" name="form_22[edema]" class="input_type" style="width: 100px !important">
    <label>
        <input type="checkbox" class="ace" name="form_22[gj_7]" id="gj_7"  onclick="checkthis('gj_7')">
        <span class="lbl"> Lain-lain </span>
    </label>, <input type="text" id="txt_lain_snfs" name="form_22[txt_lain_snfs]" class="input_type" style="width: 100px !important">
</div>
<br>
Pemeriksaan Antenatal<br>
<label>
    <input type="checkbox" class="ace" name="form_22[pa_no]" id="pa_no"  onclick="checkthis('pa_no')">
    <span class="lbl"> Tidak </span>
</label>
<label>
    <input type="checkbox" class="ace" name="form_22[pa_ya]" id="pa_ya"  onclick="checkthis('pa_ya')">
    <span class="lbl"> Ya </span>
</label>, di <input type="text" id="ya_dftr" name="form_22[ya_dftr]" class="input_type"> terdaftar 
<label>
    <input type="checkbox" class="ace" name="form_22[pa_no_daftr]" id="pa_no_daftr"  onclick="checkthis('pa_no_daftr')">
    <span class="lbl"> Tidak terdaftar </span>
</label>
<br>
<br>


<span><u>Riwayat Kehamilan:</u></span><br>

Gravida <input type="text" id="gravida" name="form_22[gravida]" class="input_type" style="width: 70px !important">,  
Prematur <input type="text" id="prematur" name="form_22[prematur]" class="input_type" style="width: 70px !important">, 
Abortus <input type="text" id="abortus" name="form_22[abortus]" class="input_type" style="width: 70px !important">,
Anak hidup <input type="text" id="anak_hidup" name="form_22[anak_hidup]" class="input_type" style="width: 70px !important">
<br>
<br>
<table border="1" width="100%" class="table">
    <tr>
        <th class="center" style="vertical-align: middle" width="30px" colspan="1">No.</th>
        <th class="center" style="vertical-align: middle" width="100px">Tahun</th>
        <th class="center" style="vertical-align: middle" width="100px">Lamanya kehamilan</th>
        <th class="center" style="vertical-align: middle" width="200px">Jenis persalinan <br> (spontan / tindakan)</th>
        <th class="center" style="vertical-align: middle" width="200px">Bayi <br>(sex,berat,keadaan)</th>
    </tr>
    <?php for($i=1; $i<6; $i++) :?>
    <tr>
        <td align="center"><?php echo $i?></td>
        <td><input type="text" id="tahun_<?php echo $i?>" name="form_22[tahun_<?php echo $i?>]" class="input_type" style="width: 100% !important"></td>
        <td><input type="text" id="lama_hamil_<?php echo $i?>" name="form_22[lama_hamil_<?php echo $i?>]" class="input_type" style="width: 100% !important"></td>
        <td><input type="text" id="jenis_persalinan_<?php echo $i?>" name="form_22[jenis_persalinan_<?php echo $i?>]" class="input_type" style="width: 100% !important"></td>
        <td><input type="text" id="bayi_<?php echo $i?>" name="form_22[bayi_<?php echo $i?>]" class="input_type" style="width: 100% !important"></td>
    </tr>
    <?php endfor; ?>
</table>
<br>
Kelainan yang terdapat pada bayi (jika ada):<br><input type="text" id="kelainan_bayi" name="form_22[kelainan_bayi]" class="input_type" style="width: 100% !important"> <br>
<br>
Penyakit yang pernah diderita: <br>
<div class="checkbox">
    <label>
        <input type="checkbox" class="ace" name="form_22[hps]" id="hps" onclick="checkthis('hps')">
        <span class="lbl"> Hiperemesis</span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[eg]" id="eg" onclick="checkthis('eg')">
        <span class="lbl"> EPH Gestoses</span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[mh]" id="mh" onclick="checkthis('mh')">
        <span class="lbl"> Mola hydatidosa</span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[vc]" id="vc" onclick="checkthis('vc')">
        <span class="lbl"> Vitum Cordis</span>
    </label>
</div>
<div class="checkbox">
    <label>
        <input type="checkbox" class="ace" name="form_22[dm]" id="dm" onclick="checkthis('dm')">
        <span class="lbl"> Diabetes Melitus</span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[ab]" id="ab" onclick="checkthis('ab')">
        <span class="lbl"> Asma Bronkiale</span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[mg]" id="mg" onclick="checkthis('mg')">
        <span class="lbl"> Herpes genitalia</span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[hiv]" id="hiv" onclick="checkthis('hiv')">
        <span class="lbl"> Hiv/Aids</span>
    </label>
</div>
<div class="checkbox">
    <label>
        <input type="checkbox" class="ace" name="form_22[herpes]" id="herpes" onclick="checkthis('herpes')">
        <span class="lbl"> Herpes genitalia</span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[cakuminata]" id="cakuminata" onclick="checkthis('cakuminata')">
        <span class="lbl"> C akuminata</span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[lainnya]" id="lainnya" onclick="checkthis('lainnya')">
        <span class="lbl"> Lain-lain</span>
        <input type="text" id="txt_lainnya" name="form_22[txt_lainnya]" class="input_type">
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_22[rpk]" id="rpk" onclick="checkthis('rpk')">
        <span class="lbl"> Riwayat penyakit keluarga</span>
        <input type="text" id="txt_riwayat_pnykt_klrg" name="form_22[txt_riwayat_pnykt_klrg]" class="input_type">
    </label>
</div>
<br>
Riwayat penyakit dalam keluarga :<br>
<input type="text" id="rwayt_pnykt_dlm_klrg" name="form_22[rwayt_pnykt_dlm_klrg]" class="input_type" style="width: 100% !important">
<br>
<br>
<b><u>Riwayat Resiko Kehamilan:</u></b><br>
<table>
    <?php 
        for($i=1; $i<9; $i++) :
            $title = '';
            switch ($i) {
                case 1 : $title = 'Umur < 18 atau >'; break;
                case 2 : $title = 'Tinggi Badan < 143 cm'; break;
                case 3 : $title = 'Anemia'; break;
                case 4 : $title = 'Preklamsia Ringan'; break;
                case 5 : $title = 'Preklamsia Berat'; break;
                case 6 : $title = 'Eklamsia'; break;
                case 7 : $title = 'Riwayat Infertilitas'; break;
                case 8 : $title = 'Lain-lain'; break;
            }
        ?>
        <tr>
            <td width="30px"><?php echo $i?>.</td>
            <td width="200px"><?php echo $title?></td>
            <td width="100px">
                <label>
                    <input type="checkbox" class="ace" name="form_22[ans_ya_<?php echo $i?>]" id="ans_ya_<?php echo $i?>" onclick="checkthis('ans_ya_<?php echo $i?>')">
                    <span class="lbl"> Ya </span>
                </label>
                <label>
                    <input type="checkbox" class="ace" name="form_22[ans_no_<?php echo $i?>]" id="ans_no_<?php echo $i?>" onclick="checkthis('ans_no_<?php echo $i?>')">
                    <span class="lbl"> Tidak </span>
                </label>
            </td>
        </tr>
    <?php endfor; ?>
</table>
<br>
<br>
<b> <u>PEMERIKSAAN FISIK</u> </b> <br>
<b> <u>Status generalis :</u> </b> <br>
TD : <input type="text" style="width: 70px !important" id="sg_td" name="form_22[sg_td]" class="input_type">  mmHgg &nbsp; &nbsp; &nbsp;
N : <input type="text" style="width: 70px !important" id="sg_n" name="form_22[sg_n]" class="input_type"> x/mnt&nbsp; &nbsp; &nbsp;
Pernafasan : <input type="text" style="width: 70px !important" id="sg_nfs" name="form_22[sg_nfs]" class="input_type"> x/menit&nbsp; &nbsp; &nbsp;
SH : <input type="text" style="width: 70px !important" id="sg_sh" name="form_22[sg_sh]" class="input_type"> &deg;C&nbsp; &nbsp; &nbsp;
BB : <input type="text" style="width: 70px !important" id="sg_bb" name="form_22[sg_bb]" class="input_type"> Kg &nbsp; &nbsp; &nbsp;
TB : <input type="text" style="width: 70px !important" id="sg_tb" name="form_22[sg_tb]" class="input_type"> Cm &nbsp; &nbsp; &nbsp;
<br>
Kesadaran umum : <input type="text" id="sg_sdr_um" name="form_22[sg_sdr_um]" class="input_type"><br>
Oedema tungkai : <input type="text" id="sg_oedema_tungkai" name="form_22[sg_oedema_tungkai]" class="input_type">


<br>
<hr>
<?php echo $footer; ?>