
<p class="title" style="font-size: 16px; text-align: center">
    <b>FORMULIR EDUKASI PASIEN DAN KELUARGA</b>
</p>

<p>
    Beri ceklis	(&#x2714;) untuk pengisian formulir dibawah ini
</p>

<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<span class="title_data"><b>A. ASSESMENT KEBUTUHAN EDUKASI </b></span><br>
<br>
<table border="0" width="100%">

    <tr valign="top">
        <td width="20%">Pendidikan</td>
        <td width="20%">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[p_sd]" id="p_sd" onclick="checkthis('p_sd')" >
                <span class="lbl"> SD</span>
            </label>
        </td>
        <td width="20%">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[p_smp]" id="p_smp" onclick="checkthis('p_smp')"  >
                <span class="lbl"> SMP </span>
            </label>
        </td>
        <td width="20%">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[p_s1]" id="p_s1" onclick="checkthis('p_s1')"  >
                <span class="lbl"> S1 </span>
            </label>
        </td>
        <td width="20%">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[p_dll]" id="p_dll" onclick="checkthis('p_dll')" >
                <span class="lbl"> DLL </span>
            </label>
        </td>
    <tr valign="top">
        <td>Tinggal Bersama</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[tb_anak]" id="tb_anak" onclick="checkthis('tb_anak')">
                <span class="lbl"> Anak </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[tb_ot]" id="tb_ot" onclick="checkthis('tb_ot')">
                <span class="lbl"> Orang Tua </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[tb_s]" id="tb_s" onclick="checkthis('tb_s')">
                <span class="lbl"> Sendiri </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[tb_si]" id="tb_si" onclick="checkthis('tb_si')">
                <span class="lbl"> Suami/Istri </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>Hambatan Edukasi</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[he_ada]" id="he_ada" onclick="checkthis('he_ada')">
                <span class="lbl"> Ada </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[he_Tidak1]" id="he_Tidak1" onclick="checkthis('he_Tidak1')">
                <span class="lbl"> Tidak </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[he_gp]" id="he_gp" onclick="checkthis('he_gp')">
                <span class="lbl"> Gangguan Pendengaran </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[he_ge]" id="he_ge" onclick="checkthis('he_ge')">
                <span class="lbl"> Gangguan Emosi </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[he_gp2]" id="he_gp2" onclick="checkthis('he_gp2')">
                <span class="lbl"> Gangguan Penglihatan </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[he_gb]" id="he_gb" onclick="checkthis('he_gb')">
                <span class="lbl"> Gangguan Bicara </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[he_mkb]" id="he_mkb" onclick="checkthis('he_mkb')">
                <span class="lbl"> Motivasi Kurang/Buruk </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[he_mh]" id="he_mh" onclick="checkthis('he_mh')">
                <span class="lbl"> Memori Hilang </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[he_fl]" id="he_fl" onclick="checkthis('he_fl')">
                <span class="lbl"> Fisik Lemah </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>Merokok</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[m_pasif]" id="m_pasif" onclick="checkthis('m_pasif')">
                <span class="lbl"> Pasif </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[m_aktif]" id="m_aktif" onclick="checkthis('m_aktif')">
                <span class="lbl"> aktif </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[m_tidak2]" id="m_tidak2" onclick="checkthis('m_tidak2')">
                <span class="lbl"> tidak </span>
            </label>
        </td>
        <td colspan="1"></td>
    </tr>
    <tr valign="top">
        <td>Minum Alkohol</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ma_ya]" id="ma_ya" onclick="checkthis('ma_ya')">
                <span class="lbl"> Ya</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ma_tidak3]" id="ma_tidak3" onclick="checkthis('ma_tidak3')">
                <span class="lbl"> Tidak</span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Edukasi Diberikan Kepada</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[edk_pasien]" id="edk_pasien" onclick="checkthis('edk_pasien')">
                <span class="lbl"> Pasien </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[edk_ot] ab" id="edk_ot ab" onclick="checkthis('edk_ot ab')">
                <span class="lbl"> Orang Tua (Ayah/Ibu) </span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[edk_k]" id="edk_k" onclick="checkthis('edk_k')">
                <span class="lbl"> Keluarga (Suami/Istri/Kakak/Adik) </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>Kemampuan Bahasa</td>
        <td>
            <label>
                <input type="checkbox"  class="ace" name="form_20[kb_ind]" id="kb_ind" onclick="checkthis('kb_ind')" value="1">
                <span class="lbl"> Indonesia </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox"  class="ace" name="form_20[kb_daerah]" id="kb_daerah" onclick="checkthis('kb_daerah')" value="1">
                <span class="lbl"> Daerah <input type="text" placeholder="Masukan bahasa daerah" name="form_20[masukan_daerah]" id="masukan_daerah" onchange="fillthis('masukan_daerah')" class="input_type" value=""></span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox"  class="ace" name="form_20[kb_asing]" id="kb_asing" onclick="checkthis('kb_asing')" value="1">
                <span class="lbl"> Asing <input type="text" placeholder="Masukan bahasa asing" name="form_20[masukan_bahasa]" id="masukan_bahasa" onchange="fillthis('masukan_bahasa')" class="input_type" value=""></span>
            </label>
        </td>
        <td colspan="1"></td>
    </tr>
    <tr valign="top">
        <td>Perlu Penterjemah</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[pp_ya1]" id="pp_ya1" onclick="checkthis('pp_ya1')">
                <span class="lbl"> Ya</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[pp_tidak4]" id="pp_tidak4" onclick="checkthis('pp_tidak4')">
                <span class="lbl"> Tidak</span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Baca & Tulis</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[bt_bisa]" id="bt_bisa" onclick="checkthis('pp_bisa')">
                <span class="lbl"> Bisa</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[bt_tidak5]" id="bt_tidak5" onclick="checkthis('pp_tidak5')">
                <span class="lbl"> Tidak</span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Kepercayaan Lainnya</td>
        <td colspan="2">
            <label>
                <input type="checkbox"  class="ace" name="form_20[kl_ada1]" id="kl_ada1" onclick="checkthis('kl_ada1')" value="1">
                <span class="lbl"> Ada <input type="text" placeholder="masukkan jawaban anda" name="form_20[masukan_kepercayaan]" id="masukan_kepercayaan" onchange="fillthis('masukan_kepercayaan')" class="input_type"
                value=""></span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[kl_ta]" id="kl_ta" onclick="checkthis('kl_ta')">
                <span class="lbl"> Tidak Ada</span>
            </label>
        </td>
    </tr>
    
    <tr valign="top">
        <td>Kesediaan Menerima Edukasi</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[kme_ya2]" id="kme_ya2" onclick="checkthis('kme_ya2')">
                <span class="lbl"> Ya</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[kme_tidak6]" id="kme_tidak6" onclick="checkthis('kme_tidak6')">
                <span class="lbl"> Tidak </span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Cara Edukasi</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ce_lisan]" id="ce_lisan" onclick="checkthis('ce_lisan')">
                <span class="lbl"> Lisan</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ce_tulisan]" id="ce_tulisan" onclick="checkthis('ce_tulisan')">
                <span class="lbl"> Tulisan</span>
            </label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr valign="top">
        <td>Kebutuhan Edukasi</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_hubppp]" id="ke_hubppp" onclick="checkthis('ke_hubppp')">
                <span class="lbl"> Hak Untuk Berpastisipasi Pada Proses Pela2nan </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_ppp]" id="ke_ppp" onclick="checkthis('ke_ppp')">
                <span class="lbl"> Prosedur Pemeriksaan Penunjang </span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_kkdpp]" id="ke_kkdpp" onclick="checkthis('ke_kkdpp')">
                <span class="lbl"> Kondisi Kesehatan, Diagnosa Pasti dan penatalaksanaannya </span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_ppic]" id="ke_ppic" onclick="checkthis('ke_ppic')">
                <span class="lbl"> Proses Pemberian Informed Consent </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_dn]" id="ke_dn" onclick="checkthis('ke_dn')">
                <span class="lbl"> Diet dan Nutrisi </span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_posea]" id="ke_posea" onclick="checkthis('ke_posea')">
                <span class="lbl"> Pengguna Obat Secara Efektif dan Aman, Efek Samping Serta Interaksi</span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_pamya]" id="ke_pamya" onclick="checkthis('ke_pamya')">
                <span class="lbl"> Pengunaan Alat Medis Yang Aman</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_mn]" id="ke_mn" onclick="checkthis('ke_mn')">
                <span class="lbl"> Manajemen Nyeri</span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_tr]" id="ke_tr" onclick="checkthis('ke_tr')">
                <span class="lbl"> Teknik Rehabilitasi</span>
            </label>
        </td>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_ctyb]" id="ke_ctyb" onclick="checkthis('ke_cytb')">
                <span class="lbl"> Cuci Tangan Yang Benar</span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_bm]" id="ke_bm" onclick="checkthis('ke_bm')">
                <span class="lbl">Bahaya Merokok</span>
            </label>
        </td>
        <td colspan="2">
            <label>
                <input type="checkbox"  class="ace" name="form_20[ke_ll]" id="ke_ll" onclick="checkthis('ke_ll')" value="1">
                <span class="lbl"> Lain-lain <input type="text" placeholder="masukkan jawaban anda" name="form_20[masukan] jawaban" id="masukan jawaban" onchange="fillthis('masukan jawaban')" class="input_type" value=""></span>
            </label>
    </tr>
    <tr valign="top">
        <td>&nbsp;</td>
        <td colspan="3">
            <label>
                <input type="checkbox" class="ace" value="1" name="form_20[ke_re]" id="ke_re" onclick="checkthis('ke_re')">
                <span class="lbl">Rujukan Edukasi</span>
            </label>
        </td>
    </tr>
</table>

