<div class="row" style="padding:8px">

    <div class="col-sm-12">
        <center><span style="font-size: 14px"><b>CATATAN PERKEMBANGAN PASIEN TERINTEGRASI (CPPT)</b></span></center>
        <hr>
        <div style="height: 750px;overflow: scroll;">
        <table class="table" id="table-riwayat-cppt" style="padding: 5px">
            
            <tbody>
                <?php 
                    if(count($cppt) > 0 ) : 
                        foreach($cppt as $row_cppt) :
                            if($row_cppt->jenis_form == null) :
                ?>
                <tr>
                    <td style="padding: 5px">
                        <table class="table table-bordered" style="width: 100%">
                            <tr><td width="20%">Tgl/Jam</td><td width="80%"> : <?php echo $this->tanggal->formatDateTime($row_cppt->cppt_tgl_jam)?></td></tr>
                            <tr><td>PPA</td><td> : <?php echo $row_cppt->cppt_nama_ppa?> (<?php echo strtoupper($row_cppt->cppt_ppa)?>)</td></tr>
                            <tr><td>Verifikasi DPJP</td><td> : <?php echo ($row_cppt->is_verified == 1) ? '<label class="label label-success">Sudah diverifikasi</label> '.$row_cppt->verified_by.' - '.$row_cppt->verified_date.'' : '<label class="label label-danger">Belum diverifikasi</label>';?></td></tr>
                        </table>
                        <br>
                        <b>S <i>(Subjective)</i> : </b><br>
                        <?php echo nl2br($row_cppt->cppt_subjective)?><br>
                        <br>
                        <b>O <i>(Objective)</i> : </b><br>
                        <?php echo nl2br($row_cppt->cppt_objective)?><br>
                        <br>
                        <b>A <i>(Assesment)</i> : </b><br>
                        <?php echo nl2br($row_cppt->cppt_assesment)?><br>
                        <br>
                        <b>P <i>(Plan)</i> : </b><br>
                        <?php echo nl2br($row_cppt->cppt_plan)?><br>
                        <hr style="width: 100%">
                    </td>
                </tr>
                <?php else : ?>
                <tr>
                    <td style="padding: 5px">
                        <table class="table table-bordered" style="width: 100%">
                            <tr><td width="20%">Tgl/Jam</td><td width="80%"> : <?php echo $this->tanggal->formatDateTime($row_cppt->cppt_tgl_jam)?></td></tr>
                            <tr><td>PPA</td><td> : <?php echo $row_cppt->cppt_nama_ppa?> (<?php echo strtoupper($row_cppt->cppt_ppa)?>)</td></tr>
                            <tr><td>Verifikasi DPJP</td><td> : <?php echo ($row_cppt->is_verified == 1) ? '<label class="label label-success">Sudah diverifikasi</label> '.$row_cppt->verified_by.' - '.$row_cppt->verified_date.'' : '<label class="label label-danger">Belum diverifikasi</label>';?></td></tr>
                        </table>
                        <br>
                        <?php echo $row_cppt->catatan_pengkajian?>
                    </td>
                </tr>
                <?php endif; endforeach; else: echo '<span style="padding-top: 20px; color: red; font-weight: bold">Tidak ada ditemukan</span>'; endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>





