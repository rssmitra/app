<center><p><b>ELEKTRONIK REKAM MEDIS PASIEN</b></p></center>
<!-- <marquee behavior="left" direction="left" width="100%" style="background-color: #95c1e6">
    <strong>Pemberitahuan !</strong> Data yang ditampilkan hanya untuk contoh. Fitur ini akan segera diimplementasi setelah <b>Status Pasien</b> di scan semua. Terima Kasih
</marquee> -->
<div id="accordion" class="accordion-style1 panel-group">

    <?php if( count($file_emr) > 0 ) : foreach($file_emr as $key_emr=>$row_emr) :?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key_emr?>" style="color: #4e4b4b;">
                    <i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                    &nbsp;<?php echo $row_emr->no_registrasi.'. '.ucwords($row_emr->nama_bagian).' - '.$row_emr->nama_dokter.'. Tanggal '.$this->tanggal->formatDatedmY($row_emr->tgl_kunjungan); ?>
                </a>
            </h4>
        </div>

        <div class="panel-collapse collapse <?php echo ($key_emr==0)?'in':'collapsed'?>" id="collapse<?php echo $key_emr?>">
            <div class="panel-body">
            <iframe src="<?php echo BASE_FILE_RM.'uploaded/rekam_medis/'.$row_emr->no_mr.'/'.$row_emr->filename.'.pdf'?>" frameborder="0" width="100%" style="height: 900px !important"></iframe>
            </div>
        </div>
    </div>
    <?php endforeach; else: ?>
        <div class="alert alert-warning">
            <strong>Pemberitahuan !</strong> Tidak ada File Rerkam Medis ditemukan.
        </div>
    <?php endif; ?>

</div>
