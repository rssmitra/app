<div class="widget-box effect8">
    <div class="widget-body">
        <div class="widget-main">
            <table class="table" style="font-size: 14px">
                <tr>
                    <td>Nama Dokter</td>
                    <td>: <?php echo $value->nama_pegawai?></td>
                </tr>
                <tr>
                    <td>Poli/Klinik</td>
                    <td>: <?php echo ucwords($value->nama_bagian)?></td>
                </tr>
                <tr>
                    <td>Hari</td>
                    <td>: <?php echo $value->jd_hari?></td>
                </tr>
                <tr>
                    <td>Jam Praktek</td>
                    <td>: <?php echo $this->tanggal->formatTime($value->jd_jam_mulai)?> s/d <?php echo $this->tanggal->formatTime($value->jd_jam_selesai)?></td>
                </tr>
                <tr>
                    <td>Kuota</td>
                    <td>: <?php echo $value->jd_kuota?></td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>: <?php echo $value->jd_keterangan?></td>
                </tr>
            </table>
            <br>
            <div class="center">
                <p style="font-size: 14px; color: red; font-style: italic">Mohon maaf saat ini belum bisa membuat perjanjian pasien.<br>Perjanjian pasien ini hanya untuk Pasien BPJS Kesehatan.</p>
                <!-- <a href="#" class="btn btn-primary" onclick="setAppointment(<?php echo $value->jd_id?>)"><i class="fa fa-calendar bigger-150"></i> BUAT PERJANJIAN PASIEN</a> -->
            </div>
            
        </div>
    </div>
</div>