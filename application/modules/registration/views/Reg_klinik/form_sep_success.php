<!-- form hidden -->
<input name="cetak_kartu" type="hidden" class="ace" value="N"/>
<input id="InputKeyNasabah" class="form-control" name="kelompok_nasabah" type="hidden" value="Jaminan Perusahaan" />
<input type="hidden" name="kode_kelompok_hidden" value="3" id="kode_kelompok_hidden">
<input id="InputKeyPenjamin" class="form-control" name="penjamin" type="hidden" value="BPJS Kesehatan"/>
<input type="hidden" name="kode_perusahaan_hidden" value="120" id="kode_perusahaan_hidden">


<div class="center" style="padding-top: 10px">
    <div class="alert alert-success">
        <center>
            <i class="fa fa-check-circle"></i> <b>Proses berhasil ...</b><br>
            <span>Nomor SEP (Surat Elegibilitas Pasien)</span><br>
            <span style="font-weight: bold; font-size: 20px"><?php echo strtoupper($no_sep); ?></span>
        </center>
    </div>
</div>

