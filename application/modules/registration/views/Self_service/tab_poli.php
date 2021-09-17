<div class="row">
    <p style="font-size:14px; font-style: italic">Jadwal Praktek Dokter Tanggal <?php echo date('d/M/Y')?>, Silahkan pilih Poli/Klinik Spesialis dibawah ini : </p>

    <?php 
        $no=0; foreach($klinik as $key=>$row) : $no++; 
        $arr_color = array('bg-red','bg-yellow','bg-aqua','bg-blue','bg-light-blue','bg-green','bg-navy','bg-teal','bg-olive','bg-lime','bg-orange','bg-fuchsia','bg-purple','bg-maroon','bg-black'); 
        shuffle($arr_color);
    ?>
    <div class="col-lg-3 col-xs-4 no-padding" style="margin-top:5px; padding: 5px !important">
    <!-- small box -->
        <div class="small-box <?php echo array_shift($arr_color)?>" style="min-height: 115px; border-radius: 10px !important">
            <div class="inner" style="line-height: 13px; min-height: 90px">
                <h3 style="font-size:14px;word-wrap: break-word;"><?php echo strtoupper($row->nama_bagian)?></h3>
                <p style="font-size:12px">
                    <?php echo $row->nama_pegawai?><br>
                    <?php echo $this->tanggal->formatTime($row->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row->jd_jam_selesai);?>
                </p>
            </div>
            <div class="icon" style="margin-top:-10px">
                <i class="fa fa-stethoscope"></i>
            </div>
            <?php 
            echo '<a href="#" onclick="select_dokter_poli('.$row->jd_kode_dokter.', '.$row->jd_kode_spesialis.', '.$row->jd_id.')" class="small-box-footer"><b>DAFTAR POLI/KLINIK</b> <i class="fa fa-arrow-circle-right"></i></a>';
            ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>