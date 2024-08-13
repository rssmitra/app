<p style="font-size: 12px; font-weight: bold; text-align: center">RIWAYAT PEMERIKSAAN PENUNJANG MEDIS (Laboratorium & Radiologi)</p>
<div style="padding: 3px">
  <b>Laboratorium</b>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>  
        <th width="30px">No</th>
        <th>Pemeriksaan</th>
        <th width="30px"></th>
      </tr>
    </thead>
    <tbody style="background: white">
      <?php 
        $no=0; 
        $data_lab = isset($penunjang['laboratorium'])?$penunjang['laboratorium']:[];
        foreach($data_lab as $key_p=>$row_p) : 
          if($key_p <= 9) :
            $no++;
      ?>
      <tr>
        <td align="center"><?php echo $no; ?></td>
        <td>
          <?php 
            echo '<b>'.$this->tanggal->formatDateTime($row_p->tgl_daftar).'</b><br>';
            $arr_str = explode("|",$row_p->nama_tarif);
            $html = '<ul class="no-padding">';
            foreach ($arr_str as $key => $value) {
                if(!empty($value)){
                    $html .= '<li>'.$value.'</li>';
                }
            }
            $html .= '</ul>';
            echo $html;
            $lampiran_file = isset($file[$row_p->kode_penunjang]) ? $file[$row_p->kode_penunjang] : [];
            echo (count($lampiran_file) > 0)?'<span>Lampiran :</span><br>' : '';
            foreach($lampiran_file as $row_lf){
              echo '<a href="#"  onclick="PopupCenter('."'".base_url().'/'.$row_lf->csm_dex_fullpath."'".', '."'LAMPIRAN HASIL PEMERIKSAAN LABORATORIUM'".', 1000, 850)">'.$row_lf->csm_dex_nama_dok.'</a><br>';
            }
          ?>
        </td>
        <td align="center"><a href="#" class="btn btn-xs btn-warning" onclick="show_modal_medium_return_json('registration/reg_pasien/form_modal_view_hasil_pm/<?php echo $row_p->no_registrasi?>/<?php echo $row_p->no_kunjungan?>/<?php echo $row_p->kode_penunjang?>/<?php echo $row_p->kode_bagian_tujuan?>?format=html', 'Hasil Penunjang Medis')"><i class="fa fa-eye"></i></a></td>
      </tr>
      <?php endif; endforeach; ?>
    </tbody>
  </table>

  <br>
  <b>Radiologi</b>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>  
        <th width="30px">No</th>
        <th>Pemeriksaan</th>
        <th width="30px"></th>
      </tr>
    </thead>
    <tbody style="background: white">
      <?php 
        $no=0; 
        $data_fisio = isset($penunjang['radiologi'])?$penunjang['radiologi']:[];
        foreach($data_fisio as $key_f=>$row_f) : 
          if($key_f <= 9) :
            $no++;
      ?>
      <tr>
        <td align="center"><?php echo $no; ?></td>
        <td>
          <?php 
            echo '<b>'.$this->tanggal->formatDateTime($row_f->tgl_daftar).'</b><br>';
            $arr_str = explode("|",$row_f->nama_tarif);
            $html = '<ul class="no-padding">';
            foreach ($arr_str as $key => $value) {
                if(!empty($value)){
                    $html .= '<li>'.$value.'</li>';
                }
            }
            $html .= '</ul>';
            echo $html;
          ?>
        </td>
        <td align="center"><a href="#" class="btn btn-xs btn-warning" onclick="show_modal_medium_return_json('registration/reg_pasien/form_modal_view_hasil_pm/<?php echo $row_f->no_registrasi?>/<?php echo $row_f->no_kunjungan?>/<?php echo $row_f->kode_penunjang?>/<?php echo $row_f->kode_bagian_tujuan?>?format=html', 'Hasil Penunjang Medis')"><i class="fa fa-eye"></i></a></td>
      </tr>
      <?php endif; endforeach; ?>
    </tbody>
  </table>

  <p>Untuk melihat hasil penunjang medis lainnya silahkan klik <b><i>"Selengkapnya"</i></b></p>
  <a href="#" class="btn btn-xs btn-success" style="width: 100% !important" onclick="show_modal('registration/riwayat_kunjungan_pm/riwayat_kunjungan_pm_by_mr?type=PM&no_mr=<?php echo $no_mr?>', 'Riwayat Penunjang Medis')">Lihat Selengkapnya</a>
</div>