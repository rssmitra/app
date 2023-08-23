
<div class="row">
  <div class="col-xs-12">
    
    <!-- PAGE CONTENT BEGINS -->
      <?php

        if(empty($value)){
          echo '<p style="padding: 30px; color: red; font-style: italic; font-weight: bold">~ Tidak ada data ditemukan ~</p>';
          exit;
        }
        $tunjangan = $value->t_keluarga + $value->t_kerja + $value->t_jabatan + $value->t_shift + $value->t_khusus + $value->t_fungsional;
        $potongan = $value->p_absensi + $value->p_ppni + $value->p_biaya_perawatan + $value->p_apotik + $value->p_koperasi + $value->p_jamsostek + $value->p_pph21 + $value->p_bpjs;
        $lainnya = $value->lain_lain + $value->lembur + $value->insentif + $value->dkk + $value->cito + $value->case_manager + $value->oncall + $value->transport + $value->pjgk_prwt + $value->home_care + $value->fee_agent;
      ?>
      <hr>
      <div class="widget-body">
        <div class="widget-main no-padding">
          <p style="padding: 15px; font-size: 14px; font-weight: bold; text-align: center">
            <span>RINCIAN GAJI PEGAWAI</span><br>
            BULAN <?php echo strtoupper($this->tanggal->getBulan($value->kg_periode_bln))?> TAHUN <?php echo $value->kg_periode_thn?><br><br>
            <span style="font-size: 13px; font-weight: bold"><?php echo $value->nip.' - '.$value->nama_pegawai; ?></span>
          </p>
          <div class="col-md-6">
            <table class="table">
              <!-- <tr>
                <td>NIP</td><td><?php echo $value->nip?></td>
              </tr>
              <tr>
                <td>Nama Pegawai</td><td><?php echo $value->nama_pegawai?></td>
              </tr> -->
              <tr>
                <th width="30px" align="center">NO</th>
                <th>RINCIAN GAJI</th>
                <th>TOTAL</th>
              </tr>
              <tr>
                <td align="center"><b>1. </b></td>
                <td colspan="2"><b>GAJI DASAR</b></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Gaji Dasar Karyawan</td>
                <td align="right"><?php echo number_format($value->gaji_dasar)?></td>
              </tr>
              <tr>
                <td colspan="2" align="right"><b>TOTAL GAJI DASAR</b></td>
                <td align="right"><b><?php echo number_format($value->gaji_dasar)?><b></td>
              </tr>

              <tr>
                <td align="center"><b>2.</b></td>
                <td colspan="2"><b>TUNJANGAN</b></td>
              </tr>

              <tr>
                <td></td>
                <td style="padding-left: 30px">Tunjangan Keluarga</td>
                <td align="right"><?php echo number_format($value->t_keluarga)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Tunjangan Kerja</td>
                <td align="right"><?php echo number_format($value->t_kerja)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Tunjangan Jabatan</td>
                <td align="right"><?php echo number_format($value->t_jabatan)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Tunjangan Shift</td>
                <td align="right"><?php echo number_format($value->t_shift)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Tunjangan Khusus</td>
                <td align="right"><?php echo number_format($value->t_khusus)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Tunjangan Fungsional</td>
                <td align="right"><?php echo number_format($value->t_fungsional)?></td>
              </tr>
              <tr>
                <td colspan="2" align="right"><b>TOTAL TUNJANGAN</b></td>
                <td align="right"><b><?php echo number_format($tunjangan)?></b></td>
              </tr>

              <tr>
                <td align="center"><b>3.</b></td>
                <td colspan="2"><b>PENDAPATAN LAINNYA</b></td>
              </tr>

              <tr>
                <td></td>
                <td style="padding-left:30px ">Lain-Lain</td>
                <td align="right"><?php echo number_format($value->lain_lain)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left:30px ">Lembur</td>
                <td align="right"><?php echo number_format($value->lembur)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left:30px ">Insentif</td>
                <td align="right"><?php echo number_format($value->insentif)?></td>
              </tr>
              
              <tr>
                <td></td>
                <td style="padding-left:30px ">Cito</td>
                <td align="right"><?php echo number_format($value->cito)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left:30px ">Case Manager</td>
                <td align="right"><?php echo number_format($value->case_manager)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left:30px ">On Call</td>
                <td align="right"><?php echo number_format($value->oncall)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left:30px ">Transport</td>
                <td align="right"><?php echo number_format($value->transport)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left:30px ">PJGK PRWT</td>
                <td align="right"><?php echo number_format($value->pjgk_prwt)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left:30px ">Home Care</td>
                <td align="right"><?php echo number_format($value->home_care)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left:30px ">Fee Agent</td>
                <td align="right"><?php echo number_format($value->fee_agent)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left:30px ">Dana Kesejahteraan Karyawan</td>
                <td align="right"><?php echo number_format($value->dkk)?></td>
              </tr>

              <tr>
                <td colspan="2" align="right"><b>TOTAL PENDAPATAN LAINNYA</b></td>
                <td align="right"><b><?php echo number_format($lainnya)?></b></td>
              </tr>

              <tr>
                <td align="center"><b>4.</b></td>
                <td colspan="2"><b>POTONGAN</b></td>
              </tr>

              <tr>
                <td></td>
                <td style="padding-left: 30px">Sanksi Absensi</td>
                <td align="right"><?php echo number_format($value->p_absensi)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">PPNI/IBI</td>
                <td align="right"><?php echo number_format($value->p_ppni)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Biaya Perawatan</td>
                <td align="right"><?php echo number_format($value->p_biaya_perawatan)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Bon Karyawan / Apotik</td>
                <td align="right"><?php echo number_format($value->p_apotik)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Koperasi</td>
                <td align="right"><?php echo number_format($value->p_koperasi)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">Jamsostek</td>
                <td align="right"><?php echo number_format($value->p_jamsostek)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">PPh 21 Karyawan</td>
                <td align="right"><?php echo number_format($value->p_pph21)?></td>
              </tr>
              <tr>
                <td></td>
                <td style="padding-left: 30px">BPJS Kesehatan</td>
                <td align="right"><?php echo number_format($value->p_bpjs)?></td>
              </tr>

              <tr>
                <td colspan="2" align="right"><b>TOTAL POTONGAN</b></td>
                <td align="right" style="font-weight: bold"><?php echo number_format($potongan)?></td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table" width="80%">
              <tr>
                <th>GAJI DASAR</th>
                <th style="text-align: right"><?php echo number_format($value->gaji_dasar)?></th>
              <tr>
              <tr>
                <th>TUNJANGAN</th>
                <th style="text-align: right"><?php echo number_format($tunjangan)?></th>
              <tr>
              <tr>
                <th>PENDAPATAN LAINNYA</th>
                <th style="text-align: right"><?php echo number_format($lainnya)?></th>
              <tr>
              <tr>
                <th style="text-align: right">GAJI KOTOR</th>
                <th style="text-align: right"><?php echo number_format($value->ttl_pendapatan)?></th>
              <tr>
              <tr>
                <th>POTONGAN</th>
                <th style="text-align: right"><?php echo number_format($potongan)?></th>
              <tr>
              <tr>
                <th style="text-align: right">GAJI DITERIMA</th>
                <th style="text-align: right"><?php echo number_format($value->gaji_diterima)?></th>
              <tr>
            </table>
            <br>
            <a href="#" class="btn btn-xs btn-danger" onclick="PopupCenter('<?php echo base_url().'kepegawaian/Kepeg_slip_gaji/slip_gaji_view?bulan='.$_GET['bulan'].'&tahun='.$_GET['tahun'].''?>','SLIP GAJI BULAN <?php echo strtoupper($this->tanggal->getBulan($value->kg_periode_bln))?> ', 900, 650)">Cetak PDF</a>
          </div>
        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


