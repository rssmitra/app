<html>
    <head>
        <title>
            FORM EMR (Electronic Medical Record)
        </title>
    </head>
    
    
    <body>

        <style>
            .title {
                font-size: 20px; 
                font-weight: bold; 
                text-align: center;
                color: black;
                font-style: italic;
            }
        </style>

        <p class="title">
            <b>FORMULIR EDUKASI PASIEN DAN KELUARGA</b>
        </p>

        <p>
            Beri ceklis	(&#x2714;) untuk pengisian formulir dibawah ini
        </p>


        <span class="title_data"><b>A. ASSESMENT KEBUTUHAN EDUKASI </b></span><br>
        <br>
        <table border="0" width="100%">

            <tr>
                <td width="20%">Pendidikan</td>
                <td width="20%">
                    <label>
                        <input type="checkbox" class="ace" name="p_sd" id="p_sd" onclick="checkthis('p_sd')">
                        <span class="lbl"> SD </span>
                    </label>
                </td>
                <td width="20%">
                    <label>
                        <input type="checkbox" class="ace" name="p_smp" id="p_smp" onclick="checkthis('p_smp')">
                        <span class="lbl"> SMP </span>
                    </label>
                </td>
                <td width="20%">
                    <label>
                        <input type="checkbox" class="ace" name="p_s1" id="p_s1" onclick="checkthis('p_s1')">
                        <span class="lbl"> S1 </span>
                    </label>
                </td>
                <td width="20%">
                    <label>
                        <input type="checkbox" class="ace" name="p_dll" id="p_dll" onclick="checkthis('p_dll')">
                        <span class="lbl"> DLL </span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Tinggal Bersama</td>
                <td>
                    <label>
                        <input type="checkbox" class="ace" name="tb_anak" id="tb_anak" onclick="checkthis('tb_anak')">
                        <span class="lbl"> Anak</span>
                    </label>
                </td>
                <td>
                    <label>
                        <input type="checkbox" class="ace" name="tb_ot" id="tb_ot" onclick="checkthis('tb_ot')">
                        <span class="lbl"> Orang Tua</span>
                    </label>
                </td>
                <td>
                    <label>
                        <input type="checkbox" class="ace" name="tb_s" id="tb_s" onclick="checkthis('tb_s')">
                        <span class="lbl"> Sendiri</span>
                    </label>
                </td>
                <td>
                    <label>
                        <input type="checkbox" class="ace" name="tb_si" id="tb_si" onclick="checkthis('tb_si')">
                        <span class="lbl"> Suami/Istri</span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Hambatan Edukasi</td>
                <td><input type="checkbox"> Ada</td>
                <td colspan="2"><input type="checkbox"> Tidak</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox"> Gangguan Pendengaran</td>
                <td><input type="checkbox"> Gangguan Emosi</td>
                <td><input type="checkbox"> Gangguan Penglihatan</td>
                <td><input type="checkbox"> Gangguan Bicara</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox"> Motivasi Kurang/Buruk</td>
                <td><input type="checkbox"> Memori Hilang</td>
                <td colspan="1"><input type="checkbox"> Fisik Lemah</td>
            </tr>
             <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox"> Motivasi Kurang/Buruk</td>
                <td><input type="checkbox"> Memori Hilang</td>
                <td colspan="1"><input type="checkbox"> Fisik Lemah</td>
            </tr>
            <tr>
                <td>Merokok</td>
                <td><input type="checkbox"> Pasif</td>
                <td><input type="checkbox"> Aktif</td>
                <td><input type="checkbox"> Tidak</td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td>Minum Alkohol</td>
                <td><input type="checkbox"> Ya</td>
                <td><input type="checkbox"> Tidak</td>
                
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Edukasi Diberikan Kepada</td>
                <td><input type="checkbox"> Pasien</td>
                <td><input type="checkbox"> Orang Tua (Ayah/Ibu)</td>
                <td><input type="checkbox"> Keluarga (Suami/Istri/Kakak/Adik)</td>
            </tr>
            <tr>
                <td>Kemampuan Bahasa</td>
                <td><input type="checkbox"> Indonesia</td>
                <td>
                    <label>
                        <input type="checkbox" class="ace" name="tb_s" id="tb_s" onclick="checkthis('tb_s')">
                        <span class="lbl"> Daerah</span>
                    </label>
                    <input type="text" class="input_type" placeholder="masukan daerah">
                </td>
                <td><input type="checkbox"> Asing <input type="text" class="input_type"></td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td>Perlu Penterjemah</td>
                <td><input type="checkbox"> Ya</td>
                <td><input type="checkbox"> Tidak</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Baca & Tulis</td>
                <td><input type="checkbox"> Bisa</td>
                <td><input type="checkbox"> Tidak</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Kepercayaan Lainnya</td>
                <td><input type="checkbox"> Ada : ...............</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox"> Tidak Ada</td>  
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>Kesediaan Menerima Edukasi</td>
                <td><input type="checkbox"> Ya</td>
                <td><input type="checkbox"> Tidak</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Cara Edukasi</td>
                <td><input type="checkbox"> Lisan</td>
                <td><input type="checkbox"> Tulisan</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Kebutuhan Edukasi</td>
                <td><input type="checkbox"> Hak Untuk Berpastisipasi Pada Proses Pelayanan</td>
                <td><input type="checkbox"> Prosedur Pemeriksaan Penunjang</td>
                <td><input type="checkbox"> Kondisi Kesehatan, Diagnosa Pasti dan penatalaksanaannya</td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox"> Proses Pemberian Informed Consent</td>
                <td><input type="checkbox"> Diet dan Nutrisi</td>
                <td><input type="checkbox"> Pengguna Obat Secara Efektif dan Aman, Efek Samping Serta Interaksi</td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox"> \Pengunaan Alat Medis Yang Aman</td>
                <td><input type="checkbox"> Manajemen Nyeri</td>
                <td><input type="checkbox"> Teknik Rehabilitasi</td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox"> Cuci Tangan Yang Benar</td>
                <td><input type="checkbox"> Bahaya Merokok</td>
                <td><input type="checkbox"> Lain-lain :.........</td>
                <td colspan="1"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox"> Rujukan Edukasi</td>
                <td colspan="3"></td>
            </tr>
        </table>
    </body>
</html>

