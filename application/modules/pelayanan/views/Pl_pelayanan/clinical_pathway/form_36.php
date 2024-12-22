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
<div style="text-align: center; font-size: 18px;"><b>TRANSFER PASIEN INTERNAL RAWAT INAP</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<table class="table" border="1" width="100%">
    <tbody>
        <tr>
            <td style="width: 20%; align: center" valign="top" width="8%">
                Tgl. Masuk / Jam :
                <input style="width: 100% !important" type="text" name="form_36[t_j]" id="t_j" onchange="fillthis('t_j')" class="input_type"
                    value="">
            </td>
            <td style="width: 20%; align: center" valign="top" width="8%">
                Tgl. Keluar / Jam :
                <input style="width: 100% !important" type="text" name="form_36[t_j1]" id="t_j1" onchange="fillthis('t_j1')" class="input_type"
                    value="">
            </td>
            <td style="width: 20%; align: center" valign="top" width="8%">
                Asal ruang rawat :
                <input style="width: 100% !important" type="text" name="form_36[ar_r]" id="ar_r" onchange="fillthis('ar_r')" class="input_type"
                    value="">
            </td>
            <td style="width: 40%; align: center" valign="top" width="20%">
                <strong>Ruang rawat selanjutnya :</strong>
                <input style="width: 100% !important" type="text" name="form_36[rr_s]" id="rr_s" onchange="fillthis('rr_s')" class="input_type"
                    value="">
            </td>
        </tr>
    </tbody>
</table>
<table class="table" border="1" width="100%">
    <tbody>
        <tr>
            <td width="30%" valign="top">
                Dokter IGD: <br>
                <input style="width: 100%" type="text" name="form_36[m_d]" id="m_d" onchange="fillthis('m_d')" class="input_type"
                    value="">
            </td>
            <td width="70%" valign="top">
                Dokter Penanggung Jawab Pelayanan (DPJP):<br>
                <input style="width: 100%" type="text" name="form_36[p_j]" id="p_j" onchange="fillthis('p_j')" class="input_type"
                    value="">
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;" width="30%">
                Diagnosa Utama:
                <input type="text" class="input_type" id="d_u" name="form_36[d_u]" onchange="fillthis('d_u')" style="width: 100%">
            </td>
            <td width="50%">
                Perlu menjadi perhatian:<br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_36[a_i]" id="a_i" onclick="checkthis('r_r')">
                        <span class="lbl">  Alergi, sebutkan <input type="text" name="form_36[m_a]" id="m_a" onchange="fillthis('m_a')" class="input_type" value="" style="width: 50%"><br></span>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="ace" name="form_36[mr_sa]" id="mr_sa" onclick="checkthis('r_r')">
                        <span class="lbl">  MRSA </span>
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <td rowspan="2" width="30%">
                Diagnosa Sekunder:<br>
                <ol>
                    <li><input type="text" name="form_36[pj_a]" id="pj_a" onchange="fillthis('pj_a')" class="input_type"
                    value=""></li>
                    <li><input type="text" name="form_36[pj_a1]" id="pj_a1" onchange="fillthis('pj_a1')" class="input_type"
                    value=""></li>
                    <li><input type="text" name="form_36[pj_a2]" id="pj_a2" onchange="fillthis('pj_a2')" class="input_type"
                    value=""></li>
                    <li><input type="text" name="form_36[pj_a3]" id="pj_a3" onchange="fillthis('pj_a3')" class="input_type"
                    value=""></li>
                    <li><input type="text" name="form_36[pj_a4]" id="pj_a4" onchange="fillthis('pj_a4')" class="input_type"
                    value=""></li>
                </ol>
            </td>
            <td style="vertical-align: top;" width="50%">
                Alasan pemindahan pasien:<br>
                1. Kondisi pasien : 
                <label>
                    <input type="checkbox" class="ace" name="form_36[m_k]" id="m_k" onclick="checkthis('r_r')">
                    <span class="lbl"> Memburuk</span>
                </label>
                <label>
                    <input type="checkbox" class="ace" name="form_36[s_l]" id="s_l" onclick="checkthis('r_r')">
                    <span class="lbl"> Stabil</span>
                </label>  
                <label>
                    <input type="checkbox" class="ace" name="form_36[ta_p]" id="ta_p" onclick="checkthis('r_r')">
                    <span class="lbl"> Tidak ada perubahan<br></span>
                </label> 
                <br>
                2. Fasilitas :
                <label>
                    <input type="checkbox" class="ace" name="form_36[k_m]" id="k_m" onclick="checkthis('r_r')">
                    <span class="lbl"> Kurang memadai</span>
                </label> 
                <label>
                    <input type="checkbox" class="ace" name="form_36[mp_ylb]" id="mp_ylb" onclick="checkthis('r_r')">
                    <span class="lbl"> Membutuhkan peralatan yang lebih baik<br></span>
                </label> 
                <br>
                3. Tenaga :
                <label>
                    <input type="checkbox" class="ace" name="form_36[mt_ylh]" id="mt_ylh" onclick="checkthis('r_r')">
                    <span class="lbl"> Membutuhkan tenaga yang lebih ahli</span>
                </label>
                <label>
                    <input type="checkbox" class="ace" name="form_36[jt_k]" id="jt_k" onclick="checkthis('r_r')">
                    <span class="lbl"> Jumlah tenaga kurang<br></span>
                </label>
                <br>
                4. Lain-lain : sebutkan <input type="text" name="form_36[mj_a]" id="mj_a" onchange="fillthis('mj_a')" class="input_type"
                value="">
            </td>
        </tr>
        <tr>
            <td>
                Metode pemindahan pasien:<br>
                <label>
                    <input type="checkbox" class="ace" name="form_36[k_r]" id="k_r" onclick="checkthis('r_r')">
                    <span class="lbl"> Kursi roda<span style="margin-left: 10px;"></span></span>
                </label>
                <label>
                    <input type="checkbox" class="ace" name="form_36[t_t]" id="t_t" onclick="checkthis('r_r')">
                    <span class="lbl">  Tempat tidur<span style="margin-left: 10px;"></span></span>
                </label>
                <label>
                    <input type="checkbox" class="ace" name="form_36[bk_r]" id="bk_r" onclick="checkthis('r_r')">
                    <span class="lbl"> Brankar</span>
                </label>
            </td> 
        </tr>
    </tbody>
</table>
<table class="table" border="1" width="100%">
    <tbody>
        <tr>
            <td width="60%">
                Pasien / Keluarga mengetahui dan menyetujui alasan pemindahan<br>
                <i class="fa fa-check bigger-120"></i> Ceklis pada pernyataan yang tidak perlu 
                <label>
                    <input type="checkbox" class="ace" name="form_36[y_a]" id="y_a" onclick="checkthis('r_r')">
                    <span class="lbl"> Ya <span style="margin-left: 20px;"></span>
                </label> 
                </span>
                    <label>
                    <input type="checkbox" class="ace" name="form_36[td_k]" id="td_k" onclick="checkthis('r_r')">
                    <span class="lbl"> Tidak<br></span>
                </label>
                <br>
                Bila keluarga pasien memberikan persetujuan, lengkapi isian berikut :<br>
                <table width="100%">
                    <tr>
                        <td width="20%">Nama </td>
                        <td width="80%"><input type="text"  name="form_36[mn_a]" id="mn_a" onchange="fillthis('mn_a')" style="width: 100%" class="input_type" value=""></td>
                    </tr>
                    <tr>
                        <td>Hubungan </td>
                        <td><input type="text" name="form_36[s_h]" id="s_h" onchange="fillthis('s_h')" style="width: 100%" class="input_type" value=""></td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                Peralatan yang menyertai pasien saat pindah:<br>
                <table width="100%">
                    <tr>
                        <td colspan="2">
                            <label>
                                <input type="checkbox" class="ace" name="form_36[po_k]" id="po_k" onclick="checkthis('r_r')">
                                <span class="lbl">  Potion O2, kebutuhan <input type="text" name="form_36[mj_a2]" id="mj_a2" onchange="fillthis('mj_a2')" class="input_type" value="" style="width: 50px">1 /menit<br></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="ace" name="form_36[a_p]" id="a_p" onclick="checkthis('r_r')">
                                    <span class="lbl"> Alat penghisap <span style="margin-left: 50px;"></span></span>
                                </label> 
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="ace" name="form_36[v_r]" id="v_r" onclick="checkthis('r_r')">
                                    <span class="lbl"> Vebtilator<br></span>
                                </label> 
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="ace" name="form_36[b_g]" id="b_g" onclick="checkthis('r_r')">
                                    <span class="lbl"> Bangging <span style="margin-left: 84px;"></span></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="ace" name="form_36[c_r]" id="c_r" onclick="checkthis('r_r')">
                                    <span class="lbl"> Catheter<br></span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="ace" name="form_36[ng_t]" id="ng_t" onclick="checkthis('r_r')">
                                    <span class="lbl"> NGT <span style="margin-left: 114px;"></span></span>
                                </label> 
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="ace" name="form_36[p_i]" id="p_i" onclick="checkthis('r_r')">
                                    <span class="lbl">  Popa infus</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
                
                
                
            </td>
    </tbody>
</table>

<table class="table" border="1" width="100%">
    <tbody>
        <tr>
            <td style="text-align: center;" colspan="3">
                <strong>KEADAAN PASIEN SAAT PINDAH</strong>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; width: 30%" valign="top" > Keadaan umum <br>
                <textarea class="textarea-type" name="form_36[k_u]" id="k_u" onchange="fillthis('k_u')" style="height: 100px !important"></textarea></td>
            <td style="text-align: center; width: 30%" valign="top" > Kesadaran <br>
                <textarea class="textarea-type" name="form_36[k_n]" id="k_n" onchange="fillthis('k_n')" style="height: 100px !important"></textarea></td>
            <td style="width: 40%">
                <table>
                    <tr>
                        <td width="120px">Tekanan Darah</td>
                        <td><input type="text" name="form_36[t_d]" id="t_d" onchange="fillthis('t_d')" class="input_type" value="" style="width: 100px"></td>
                    </tr>
                    <tr>
                        <td width="120px">Nadi</td>
                        <td><input type="text" name="form_36[ndi]" id="ndi" onchange="fillthis('ndi')" class="input_type" value="" style="width: 100px"></td>
                    </tr>
                    <tr>
                        <td width="120px">Pernafasan</td>
                        <td><input type="text" name="form_36[nfs]" id="nfs" onchange="fillthis('nfs')" class="input_type" value="" style="width: 100px"></td>
                    </tr>
                    <tr>
                        <td width="120px">Nyeri</td>
                        <td><input type="text" name="form_36[nyri]" id="nyri" onchange="fillthis('nyri')" class="input_type" value="" style="width: 100px"></td>
                    </tr>
                    <tr>
                        <td width="120px">Suhu</td>
                        <td><input type="text" name="form_36[shu]" id="shu" onchange="fillthis('shu')" class="input_type" value="" style="width: 100px"></td>
                    </tr>
                </table>
            </td>
            </tr>
        </tr>
    </tbody>
</table>

<table class="table" border="1" width="100%">
    <tbody>
        <tr>
            <td colspan="2" style="text-align: center; color: white; background-color: black" colspan="2">
                <strong>INFORMASI TRANSFER</strong>
            </td>
        </tr>
        <tr>
            <td valign="top" style="width: 50%">
                *) Ceklis pada kondisi yang paling sesuai<br>
                <br>

                <div width="100%" style="display: flex">
                    <div width="50%" style="float: left; width: 50%">
                        <label>
                            <input type="checkbox" class="ace" name="form_36[p_i]" id="p_i" onclick="checkthis('r_r')">
                            <span class="lbl"> Disabilitas</span>
                        </label> <br>
                        <label>
                            <input type="checkbox" class="ace" name="form_36[p_i]" id="p_i" onclick="checkthis('r_r')">
                            <span class="lbl">  Amputasi</span>
                        </label> <br>
                        <label>
                            <input type="checkbox" class="ace" name="form_36[p_i]" id="p_i" onclick="checkthis('r_r')">
                            <span class="lbl"> Paralisis</span>
                        </label>
                    </div>
                    <div width="50%" style="float: left; width: 50%">
                        <label>
                            <input type="checkbox" class="ace" name="form_36[p_i]" id="p_i" onclick="checkthis('r_r')">
                            <span class="lbl"> Kontraktus <br></span>
                        </label>
                        <label>
                            <input type="checkbox" class="ace" name="form_36[p_i]" id="p_i" onclick="checkthis('r_r')">
                            <span class="lbl"> Ulkus Dekubitus<br></span>
                        </label>
                    </div>
                </div>

                <br>
                <b>Gangguan</b><br>
                <div width="100%" style="display: flex">
                    <div width="50%" style="float: left; width: 50%">
                        <label>
                            <input type="checkbox" class="ace" name="form_36[m_l]" id="m_l" onclick="checkthis('r_r')">
                            <span class="lbl"> Mental<br></span>
                        </label>
                        <label>
                            <input type="checkbox" class="ace" name="form_36[p_n]" id="p_n" onclick="checkthis('r_r')">
                            <span class="lbl">  Pendengaran<br></span>
                        </label>
                        <label>
                            <input type="checkbox" class="ace" name="form_36[sn_i]" id="sn_i" onclick="checkthis('r_r')">
                            <span class="lbl"> Sensasi</span>
                        </label>
                    </div>
                    <div width="50%" style="float: left; width: 50%">
                        <label>
                            <input type="checkbox" class="ace" name="form_36[b_a]" id="b_a" onclick="checkthis('r_r')">
                            <span class="lbl"> Bicara <br></span>
                        </label>
                        <label>
                            <input type="checkbox" class="ace" name="form_36[pl_n]" id="pl_n" onclick="checkthis('r_r')">
                            <span class="lbl"> Penglihatan</span>
                        </label>
                    </div>
                </div>
                
                <br>
                <b>Inkontinensia</b><br>
                    <label>
                        <input type="checkbox" class="ace" name="form_36[al_i]" id="al_i" onclick="checkthis('r_r')">
                        <span class="lbl">  Alvi </span>
                    </label>
                    <label>
                        <input type="checkbox" class="ace" name="form_36[su_a]" id="su_a" onclick="checkthis('r_r')">
                        <span class="lbl"> Sauva </span>
                    </label>
                    
                    <label>
                        <input type="checkbox" class="ace" name="form_36[an_i]" id="an_i" onclick="checkthis('r_r')">
                        <span class="lbl">  Ani </span>
                    </label>
                
                <br>
                <br>
                <b>Potensial untuk dilakukan rehabilitasi</b><br>
                <div width="100%" style="display: flex">
                    <div width="50%" style="float: left; width: 50%">
                        <label>
                            <input type="checkbox" class="ace" name="form_36[bi_k]" id="bi_k" onclick="checkthis('r_r')">
                            <span class="lbl"> Baik <br></span>
                        </label>
                        <label>
                            <input type="checkbox" class="ace" name="form_36[s_g]" id="s_g" onclick="checkthis('r_r')">
                            <span class="lbl"> Sedang<br></span>
                        </label>
                        <label>
                            <input type="checkbox" class="ace" name="form_36[bu_k]" id="bu_k" onclick="checkthis('r_r')">
                            <span class="lbl"> Buruk</span>
                        </label>
                    </div>
                </div>
            </td>
            <td valign="top" style="width: 50%">
                Nama petugas yang mendampingi<br>
                <input type="text" name="form_36[p_s]" id="p_s" onchange="fillthis('p_s')" class="input_type" value="" style="width: 100%">
                <br>
                Pemerintahan fisik<br>
                Status Generalis (temuan yang signifikan)<br>
                <textarea id="s_g" name="form_36[s_g]" rows="4" cols="50" style="height: 100px !important" class="textarea_type"></textarea>
                <br>
                Status Lokalis(temuan yang signifikan)<br>
                <textarea id="s_l" name="form_36[s_l]" rows="4" cols="50"></textarea>
            </td>
        </tr>
    </tbody>
</table>
<br><br>
<table class="table" border="1" width="100%">
    <tr>
        <tb class="table" valign="top">
            Hari Laboratorium belum selesai (pending)<br>
            <textarea id="s_g" name="form_36[s_g]" rows="4" cols="150"></textarea><br>
            Diet<br>
            <textarea id="s_g" name="form_36[s_g]" rows="4" cols="150"></textarea><br>
            Rencana Perawatan Selanjutnya : Cara Plan<br>
            <textarea id="s_g" name="form_36[s_g]" rows="4" cols="150"></textarea><br><br><br>
            Terapi Saat Pindah :
        </tb>
    </tr>
</table>
<table border="1" width="100%">
        <tr>
            <td style="text-align:center;" width="10%">Nama Obat</td>
            <td style="text-align:center;" width="10%">Jumlah</td>
            <td style="text-align:center;" width="10%">Dosis</td>
            <td style="text-align:center;" width="10%">Frekuensi</td>
            <td style="text-align:center;" width="10%">Cara <br> Pemberian</td>
        </tr>
        <tr>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[de1]" id="de1" onchange="fillthis('de1')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[de2]" id="de2" onchange="fillthis('de2')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[de3]" id="de3" onchange="fillthis('de3')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[de4]" id="de4" onchange="fillthis('de4')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[de5]" id="de5" onchange="fillthis('de5')" class="input_type" value=""></td>
        </tr>
        <tr>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[di1]" id="di1" onchange="fillthis('di1')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[di2]" id="di2" onchange="fillthis('di2')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[di3]" id="di3" onchange="fillthis('di3')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[di4]" id="di4" onchange="fillthis('di4')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[di5]" id="di5" onchange="fillthis('di5')" class="input_type" value=""></td>
        </tr>
        <tr>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[du1]" id="du1" onchange="fillthis('du1')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[du2]" id="du2" onchange="fillthis('du2')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[du3]" id="du3" onchange="fillthis('du3')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[du4]" id="du4" onchange="fillthis('du4')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[du5]" id="du5" onchange="fillthis('du5')" class="input_type" value=""></td>
        </tr>
        <tr>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[do1]" id="do1" onchange="fillthis('do1')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[do2]" id="do2" onchange="fillthis('do2')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[do3]" id="do3" onchange="fillthis('do3')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[do4]" id="do4" onchange="fillthis('do4')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[do5]" id="do5" onchange="fillthis('do5')" class="input_type" value=""></td>
        </tr>
        <tr>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[da1]" id="da1" onchange="fillthis('da1')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[da2]" id="da2" onchange="fillthis('da2')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[da3]" id="da3" onchange="fillthis('da3')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[da4]" id="da4" onchange="fillthis('da4')" class="input_type" value=""></td>
            <td style="text-align:center;" width="10%"><input type="text" style="text-align: center" name="form_36[da5]" id="da5" onchange="fillthis('da5')" class="input_type" value=""></td>
        </tr>
</table>
<br><br>
<table border="0" width="100%">
    <tr>
        <td style="text-align:center;" width="10%">
            <input type="text" style="text-align: center" name="form_36[dk1]" id="dk1" onchange="fillthis('dk1')" class="input_type" value="">
            Jam <input type="text" style="text-align: center" name="form_36[dk2]" id="dk2" onchange="fillthis('dk2')" class="input_type" value=""><br>
            Nama Dokter / Perawat Yang Menerima<br><br><br><br><br>
            (.....................................................................)<br>
            Tanda Tangan, Nama Lengkap & Sampai RS
        </td>
        <td style="text-align:center;" width="10%">
            Jakarta, <input type="text" style="text-align: center" name="form_36[dk2]" id="dk2" onchange="fillthis('dk2')" class="input_type" value=""><br>
            Nama Dokter / Perawat Yang Menyerahkan<br><br><br><br><br>
            (.....................................................................)<br>
            Tanda Tangan, Nama Lengkap
        </td>
    </tr>
</table>

<hr>
<?php echo $footer; ?>
