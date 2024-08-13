<p style="text-align: center"><strong>EVALUASAI AWAL<br>MANAJEMEN PELAYANAN PASIEN (FORMULIR A) </strong></p>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<p style="padding-top: 10px"><b>A. IDENTIFIKASI PASIEN</b></p>
<ol>
  <li>Usia Pasien<br>
    <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" name="form_7[a11]" id="a11"  onclick="checkthis('a11')">
            <span class="lbl"> 0 - 10 tahun</span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[a12]" id="a12" onclick="checkthis('a12')" >
            <span class="lbl"> 21 - 31 tahun </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[a13]" id="a13" onclick="checkthis('a13')" >
            <span class="lbl"> 41 - 50 tahun </span>
        </label>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" name="form_7[a14]" id="a14" onclick="checkthis('a14')" >
            <span class="lbl"> 61 tahun </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[a15]" id="a15" onclick="checkthis('a15')" >
            <span class="lbl"> 11 - 20 tahun </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[a16]" id="a16" onclick="checkthis('a16')" >
            <span class="lbl"> 31 - 40 tahun </span>
        </label>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" name="form_7[a17]" id="a17" onclick="checkthis('a17')" >
            <span class="lbl"> 51 - 60 tahun </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[a18]" id="a18" onclick="checkthis('a18')" >
            <span class="lbl"> Lainnya, <input type="text" class="input_type" name="form_7[a19]" id="a19" onclick="fillthis('a19')" placeholder=".........."></span>
        </label>
    </div>
</li>	

<li>Fungsi Kognitif/pendidikan<br>

	<label>
        <input type="checkbox" class="ace" name="form_7[a21]" id="a21" onclick="checkthis('a21')" >
        <span class="lbl"> Tidak Sekolah </span>
    </label>

    <label>
    <input type="checkbox" class="ace" name="form_7[a22]" id="a22" onclick="checkthis('a22')" >
    <span class="lbl"> SMP </span>
    </label>

    <label>
        <input type="checkbox" class="ace" name="form_7[a23]" id="a23" onclick="checkthis('a23')" >
        <span class="lbl"> Perguruan Tinggi </span>
    </label>

	<label>
        <input type="checkbox" class="ace" name="form_7[a24]" id="a24" onclick="checkthis('a24')" >
        <span class="lbl"> SD </span>
    </label>

    <label>
        <input type="checkbox" class="ace" name="form_7[a25]" id="a25" onclick="checkthis('a25')" >
        <span class="lbl"> SMA </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a26]" id="a26" onclick="checkthis('a26')" >
        <span class="lbl"> Lainnya, <input type="text" class="input_type" name="form_7[27]" id="27" onclick="fillthis('27')" placeholder=".........."> </span>
    </label>

</li> 	
<li>Potensi Komplain<br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a31]" id="a31" onclick="checkthis('a31')" >
        <span class="lbl">	Ringan </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a32]" id="a32" onclick="checkthis('a32')" >
        <span class="lbl"> Berat</span>
    </label>
</li>				
<li>Kasus Penyakit <br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a41]" id="a41" onclick="checkthis('a41')" >
        <span class="lbl"> Akut </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a42]" id="a42" onclick="checkthis('a42')" >
        <span class="lbl"> Kronis <br></li></span>
    </label>
<li>Status Fungsional <br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a43]" id="a43" onclick="checkthis('a43')" >
        <span class="lbl"> Baik </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a44]" id="a44" onclick="checkthis('a44')" >
        <span class="lbl"> Ketergantungan</span>
    </label>
</li>
<li>Riwayat Penggunaan Alat Medis<br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a51]" id="a51" onclick="checkthis('a51')" >
        <span class="lbl"> Ada </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a52]" id="a52" onclick="checkthis('a52')" >
        <span class="lbl"> Tidak Ada</span>
    </label>
</li>
<li>Riwayat Gangguan Mental <br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a61]" id="a61" onclick="checkthis('a61')" >
        <span class="lbl"> Ada </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a62]" id="a62" onclick="checkthis('a62')" >
        <span class="lbl"> Tidak Ada</span>
    </label>
</li>
<li>Readmisi <br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a71]" id="a71" onclick="checkthis('a71')" >
        <span class="lbl"> Ada </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a72]" id="a72" onclick="checkthis('a72')" >
        <span class="lbl"> Tidak Ada</span>
    </label>
</li>
<li>Perkiraan Biaya <br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a81]" id="a81" onclick="checkthis('a81')" >
        <span class="lbl"> Ringan  </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a82]" id="a82" onclick="checkthis('a82')" >
        <span class="lbl"> Berat</span>
    </label>
</li>
<li>Sistem Pembiayaan <br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a91]" id="a91" onclick="checkthis('a91')" >
        <span class="lbl"> BPJS </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a92]" id="a92" onclick="checkthis('a92')" >
        <span class="lbl"> Umum </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a93]" id="a93" onclick="checkthis('a93')" >
        <span class="lbl"> Asuransi lain : <input type="text" class="input_type" name="form_7[a94]" id="a94" onclick="fillthis('a94')" placeholder=".........."> </span>
    </label>
</li>
<li>Perkiraan Lama Rawat <br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a101]" id="a101" onclick="checkthis('a101')" >
        <span class="lbl"> 1 - 7 hari </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a102]" id="a102" onclick="checkthis('a102')" >
        <span class="lbl"> 8 - 14 hari </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a103]" id="a103" onclick="checkthis('a103')" >
        <span class="lbl"> &gt; 14 hari</span>
    </label>
</li>
<li>Discharge Planning<br>
	<label>
        <input type="checkbox" class="ace" name="form_7[a111]" id="a111" onclick="checkthis('a111')" >
        <span class="lbl"> Secara Kontinyu  </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[a112]" id="a112" onclick="checkthis('a112')" >
        <span class="lbl"> Tidak Kontinyu</span>
    </label>
</li>
</ol>


<p style="padding-top: 10px"><b>B. ASESMEN MPP</b></p>
<ol>
	<li>Fisik<br>
		a. 	Penggunaan kemampuan dan kemandirian pasien dalam 				
			pemenuhan kebutuhan Activity Daily Living (ADL)	<br>			
			<label>
                <input type="checkbox" class="ace" name="form_7[b11]" id="b11" onclick="checkthis('b11')" >
                <span class="lbl"> Ada </span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="form_7[b12]" id="b12" onclick="checkthis('b12')" >
                <span class="lbl"> Tidak Ada</span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="form_7[b14]" id="b14" onclick="checkthis('b14')" >
                <span class="lbl"> Lainnya, <input type="text" class="input_type" name="form_7[b13]" id="b13" onclick="fillthis('b13')" placeholder=".........."></span>
            </label>
    </li>
	<li>Riwayat Kesehatan/Kebiasaan<br>
		<label>
            <input type="checkbox" class="ace" name="form_7[b21]" id="b21" onclick="checkthis('b21')" >
            <span class="lbl"> Diabetes </span>
    </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[b22]" id="b22" onclick="checkthis('b22')" >
            <span class="lbl"> Hipertensi </span>
    </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[b23]" id="b23" onclick="checkthis('b23')" >
            <span class="lbl"> Merokok	<br></span>
    </label>
    </li>
	<li>Prilaku Sosial-Spiritual-Kultural<br>
		<label>
            <input type="checkbox" class="ace" name="form_7[b31]" id="b31" onclick="checkthis('b31')" >
            <span class="lbl"> Sosial : <input type="text" class="input_type" name="form_7[a34]" id="a34" onclick="fillthis('a34')" placeholder=".........."><br>	</span>
    </label>
		<label>
            <input type="checkbox" class="ace" name="form_7[b32]" id="b32" onclick="checkthis('b32')" >
            <span class="lbl"> Spiritual (nilai dan keyakinan terhadap kesehatan) : <input type="text" class="input_type" name="form_7[a35]" id="a35" onclick="fillthis('a35')" placeholder=".........."></span>
    </label>
		<label>
            <input type="checkbox" class="ace" name="form_7[b33]" id="b33" onclick="checkthis('b33')" >
            <span class="lbl"> Kultural (budaya) : <input type="text" class="input_type" name="form_7[a36]" id="a36" onclick="fillthis('a36')" placeholder=".........."><br></span>
    </label>
    </li>
	<li>Kesehatan Mental dan Kognitif<br>
		<label>
            <input type="checkbox" class="ace" name="form_7[b41]" id="b41" onclick="checkthis('b41')" >
            <span class="lbl"> Mental : <input type="text" class="input_type" name="form_7[b43]" id="b43" onclick="fillthis('b43')" placeholder=".........."></span>
        </label>
		<label>
            <input type="checkbox" class="ace" name="form_7[b42]" id="b42" onclick="checkthis('b42')" >
            <span class="lbl"> Kognitif : <input type="text" class="input_type" name="form_7[b44]" id="b44" onclick="fillthis('b44')" placeholder=".........."></span>
        </label>
    </li>
	<li>Lingkungan dan Tempat Tinggal<br>
		<div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b51]" id="b51" onclick="checkthis('b51')" >
            <span class="lbl"> Masyarakat tidak menerima pasien</span>
            </label>
        </div>
		<div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b52]" id="b52" onclick="checkthis('b52')" >
            <span class="lbl"> Rumah jauh dari fasilitas pelayanan kesehatan</span>
            </label>
        </div>
		<div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b53]" id="b53" onclick="checkthis('b53')" >
            <span class="lbl"> Tinggal Sendirian</span>
            </label>
        </div>
		<div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b54]" id="b54" onclick="checkthis('b54')" >
            <span class="lbl"> Dikembalikan ke panti sosial<br></span>
            </label>
        </div>
    </li>

    <li> Tersedia dukungan keluarga, kemampuan merawat diri pemberi asuhan<br>
        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b61]" id="b61" onclick="checkthis('b61')" >
            <span class="lbl"> Keluarga tidak pernah menunggui</span>
            </label>
            </div>
        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b62]" id="b62" onclick="checkthis('b62')" >
            <span class="lbl"> Keluarga tidak mau menerima kondisi pasien</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b63]" id="b63" onclick="checkthis('b63')" >
            <span class="lbl"> Keluarga tidak bisa dihubungi</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b64]" id="b64" onclick="checkthis('b64')" >
            <span class="lbl"> Lainnya, <input type="text" class="input_type" name="form_7[b65]" id="b65" onclick="fillthis('b65')" placeholder=".........."></span>
            </label>
        </div>
    </li>
    <li> Finansial<br>
        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b71]" id="b71" onclick="checkthis('b71')" >
            <span class="lbl"> Pasien tidak memiliki biaya sendiri</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b72]" id="b72" onclick="checkthis('b72')" >
            <span class="lbl"> Pasien belum memiliki asuransi</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b73]" id="b73" onclick="checkthis('b73')" >
            <span class="lbl"> Masalah asuransi</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
            <input type="checkbox" class="ace" name="form_7[b74]" id="b74" onclick="checkthis('b74')" >
                    <span class="lbl"> Lainnya, <input type="text" class="input_type" name="form_7[b75]" id="b75" onclick="fillthis
                </div>('b75')" placeholder=".........."><br></span>
    </label>
    </li>
    <li> Masalah Asuransi<br>
        <label>
            <input type="checkbox" class="ace" name="form_7[b81]" id="b81" onclick="checkthis('b81')" >
            <span class="lbl"> Aktif </span>
    </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[b82]" id="b82" onclick="checkthis('b82')" >
            <span class="lbl"> Tidak Aktif<br></span>
    </label>
    </li>
    <li> Riwayat Penggunaan Obat<br>
        <label>
            <input type="checkbox" class="ace" name="form_7[b91]" id="b91" onclick="checkthis('b91')" >
            <span class="lbl"> Ada </span>
    </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[b92]" id="b92" onclick="checkthis('b92')" >
            <span class="lbl"> Tidak Ada<br></span>
    </label>
    </li>
    <li> Pemahaman terhadap Kesehatan<br>
        <label>
            <input type="checkbox" class="ace" name="form_7[b101]" id="b101" onclick="checkthis('b101')" >
            <span class="lbl"> Baik </span>
    </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[b102]" id="b102" onclick="checkthis('b102')" >
            <span class="lbl"> Kurang</span>
    </label>
    </li>
    <li> Harapan terhadap Asuhan<br>
        <label>
            <input type="checkbox" class="ace" name="form_7[b103]" id="b103" onclick="checkthis('b103')" >
            <span class="lbl"> Perilaku pasien lebih baik</span>
    </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[b104]" id="b104" onclick="checkthis('b104')" >
            <span class="lbl"> Keluarga pasrah dengan Rumah Sakit</span>
    </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[b105]" id="b105" onclick="checkthis('b105')" >
            <span class="lbl"> Lainnya, <input type="text" class="input_type" name="form_7[b106]" id="b106" onclick="fillthis('b106')" placeholder=".........."></span>
    </label>
    </li>
    <li> Discharge Planning<br>
        <label>
            <input type="checkbox" class="ace" name="form_7[b111]" id="b111" onclick="checkthis('b111')" >
            <span class="lbl"> Dibutuhka</span>
    </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[b112]" id="b112" onclick="checkthis('b112')" >
            <span class="lbl"> Tidak Dibutuhkan<br></span>
    </label>
    </li>

</ol>
<p style="padding-top: 10px"><b>C. IDENTIFIKASI PASIEN</b></p>
    <label>
        <input type="checkbox" class="ace" name="form_7[c11]" id="c11" onclick="checkthis('c11')" >
        <span class="lbl"> Ketidakpatuhan Pasien </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[c12]" id="c12" onclick="checkthis('c12')" >
        <span class="lbl"> Kendala finansial </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[c13]" id="c13" onclick="checkthis('c13')" >
        <span class="lbl"> Kurang Pengetahuan </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[c14]" id="c14" onclick="checkthis('c14')" >
        <span class="lbl"> Kurang dukungan keluarga </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[c15]" id="c15" onclick="checkthis('c15')" >
        <span class="lbl"> Pemulangan Pasien Bermasalah </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[c16]" id="c16" onclick="checkthis('c16')" >
        <span class="lbl"> Rujukan bermasalah </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[c17]" id="c17" onclick="checkthis('c17')" >
        <span class="lbl"> Over utilitas </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[c18]" id="c18" onclick="checkthis('c18')" >
        <span class="lbl"> Tingkat asuhan yang tidak sesuai dengan PPA </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[c19]" id="c19" onclick="checkthis('c19')" >
        <span class="lbl"> Penurunan determinasi pasien sehubungan dengan penyakit </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[c10]" id="c10" onclick="checkthis('c10')" >
        <span class="lbl"> Lainnya, <input type="text" class="input_type" name="form_7[c20]" id="c20" onclick="fillthis('c20')" placeholder=".........."></span>
    </label>

    <p style="padding-top: 10px"><b>D. PERENCANAAN MPP</b></p>
    <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" name="form_7[d11]" id="d11" onclick="checkthis('d11')" >
            <span class="lbl"> Berkoordinasi dengan PPA </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[d12]" id="d12" onclick="checkthis('d12')" >
            <span class="lbl"> Fasilitasi koordinasi, kamunikasi, dan kolaborasi </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[d13]" id="d13" onclick="checkthis('d13')" >
            <span class="lbl"> Terminasi berkoordinasi dengan PPA </span>
        </label>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" name="form_7[d14]" id="d14" onclick="checkthis('d14')" >
            <span class="lbl"> Edukasi kepada Pasien dan Keluarga </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[d15]" id="d15" onclick="checkthis('d15')" >
            <span class="lbl"> Maksimalkan dukungan keluarga </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[d16]" id="d16" onclick="checkthis('d16')" >
            <span class="lbl"> Koordinasi dengan Managemen RS </span>
        </label>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" name="form_7[d17]" id="d17" onclick="checkthis('d17')" >
            <span class="lbl"> Advokasi </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[d18]" id="d18" onclick="checkthis('d18')" >
            <span class="lbl"> Hasil Pelayanan </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[d19]" id="d19" onclick="checkthis('d19')" >
            <span class="lbl"> Monitoring </span>
        </label>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" class="ace" name="form_7[d20]" id="d20" onclick="checkthis('d20')" >
            <span class="lbl"> Kompetensi Budaya </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[d21]" id="d21" onclick="checkthis('d21')" >
            <span class="lbl"> Pemenuhan ADL </span>
        </label>
        <label>
            <input type="checkbox" class="ace" name="form_7[d22]" id="d22" onclick="checkthis('d22')" >
            <span class="lbl"> Manajemen SDM </span>
        </label>
    </div>
    <div class="checkbox">
    <label>
        <input type="checkbox" class="ace" name="form_7[d23]" id="d23" onclick="checkthis('d23')" >
        <span class="lbl"> Lainnya, <input type="text" class="input_type" name="form_7[d24]" id="d24" onclick="fillthis('d24')" placeholder=".........."></span>
    </label>
    </div>
<p style="padding-top: 10px"><b>E. KEPUTUSAN KELUARGA</b></p>
    <label>
        <input type="checkbox" class="ace" name="form_7[e11]" id="e11" onclick="checkthis('e11')" >
        <span class="lbl"> Melanjutkan perawatan </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[e12]" id="e12" onclick="checkthis('e12')" >
        <span class="lbl"> Menghentikan pengobatan </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[e13]" id="e13" onclick="checkthis('e13')" >
        <span class="lbl"> Menghentikan alat kesehatan/penggunaan ventilator </span>
    </label>
    <label>
        <input type="checkbox" class="ace" name="form_7[e14]" id="e14" onclick="checkthis('e14')" >
        <span class="lbl"> Lainnya, <input type="text" class="input_type" name="form_7[e15]" id="e15" onclick="fillthis('e15')" placeholder=".........."> </span>
    </label>
