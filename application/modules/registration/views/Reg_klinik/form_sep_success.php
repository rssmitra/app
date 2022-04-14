<!-- form hidden -->
<input name="cetak_kartu" type="hidden" class="ace" value="N"/>
<input id="InputKeyNasabah" class="form-control" name="kelompok_nasabah" type="hidden" value="Jaminan Perusahaan" />
<input type="hidden" name="kode_kelompok_hidden" value="3" id="kode_kelompok_hidden">
<input id="InputKeyPenjamin" class="form-control" name="penjamin" type="hidden" value="BPJS Kesehatan"/>
<input type="hidden" name="kode_perusahaan_hidden" value="120" id="kode_perusahaan_hidden">


<div class="center" style="padding-top: 10px">
    <span>Nomor SEP</span><br>
    <span style="font-weight: bold; font-size: 18px"><?php echo $no_sep; ?></span>
    <hr>
    <button type="submit" name="submit" class="btn btn-xs btn-primary" style="height: 35px !important; font-size: 16px">
    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
    Proses Pendaftaran Pasien
    </button>
</div>

