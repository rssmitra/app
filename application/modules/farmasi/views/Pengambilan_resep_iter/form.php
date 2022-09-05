<!-- jquery number -->
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>
<script type="text/javascript">

$(function(){
        
  $('.format_number').number( true, 0 );
  
});

function checkAll(elm) {

  if($(elm).prop("checked") == true){

    $('.checkbox_resep').each(function(){
      $(this).prop("checked", true);      
    });

  }else{
    $('.checkbox_resep').each(function(){
      $(this).prop("checked", false);      
    });
  }

}

function pressEnter(kode_brg){
  var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      saveRow(kode_brg);
      return false;       
    }
}

$("#btn_submit_pengambilan_obat").click(function(event){
      event.preventDefault();
      var searchIDs = $("#verifikasi-resep-obat tbody input:checkbox:checked").map(function(){
        return $(this).val();
      }).toArray();

      if(searchIDs.length == 0){
        alert('Tidak ada item yang dipilih !'); 
        return false;
      }
      console.log(searchIDs);
      submit_form(searchIDs);
});

function submit_form(arrDataChecklist){

  $.ajax({
    url: $('#form_proses_resep').attr('action'),
    type: "post",
    data: $('#form_proses_resep').serialize(),
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();  
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);
      $('#page-area-content').load('farmasi/Pengambilan_resep_iter/preview_mutasi/'+$('#kode_trans_far').val()+'?flag=RJ&kode_log_mutasi='+jsonResponse.kode_log_mutasi_obat+'');
      achtungHideLoader();
    }

  });

}

function click_edit(kode_brg){
  preventDefault();
  $("#row_kd_brg_"+kode_brg+" input[type=text], select").attr('readonly', false); 
  $('#btn_submit_'+kode_brg+'').show();
  $('#btn_edit_'+kode_brg+'').hide();
}

function saveRow(kode_brg){

  preventDefault();
  $("#row_kd_brg_"+kode_brg+" input[type=text], select").attr('readonly', true); 
  $('#btn_submit_'+kode_brg+'').hide();
  $('#btn_edit_'+kode_brg+'').show();

  return false;

}


</script>

<div class="row">

  <div class="col-xs-12">

    <!-- breadcrumbs -->
    <div class="page-header">  
      <h1>
        <?php echo $title?>        
        <small><i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div> 
        
    <form class="form-horizontal" method="post" id="form_proses_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/Pengambilan_resep_iter/process">      
      
      <!-- hidden form -->
      <input type="hidden" name="kode_trans_far" id="kode_trans_far" value="<?php echo isset($value)?ucwords($value->kode_trans_far):''?>">
      <input type="hidden" name="no_sep" id="no_sep" value="<?php echo isset($value)?ucwords($value->no_sep):''?>">
      
      <div class="row">
        <div class="col-md-4">
          <table>
            <tr style="">
              <td width="100px">No. SEP</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo isset($value->no_sep)?$value->no_sep:'' ?></td>
            </tr>
            <tr style="">
              <td width="100px">No. MR</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo isset($value->no_mr) ? $value->no_mr : ''?></td>
            </tr>
            <tr style="">
              <td width="100px">Nama Pasien</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo isset($value->nama_pasien) ? $value->nama_pasien : ''?></td>
            </tr>
          </table>
        </div>

        <div class="col-md-4">
          <table>
          
            <tr style="">
              <td width="100px">Tanggal</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($value->tgl_trans) ?></td>
            </tr>
            <tr style="">
              <td width="100px">Dokter</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo isset($value->dokter_pengirim) ? $value->dokter_pengirim : ''?></td>
            </tr>
            <tr style="">
              <td width="100px">Poli Asal</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $value->kode_bagian_asal) )?></td>
            </tr>
          </table>
        </div>
      </div>
      <hr class="separator">

      <div class="row">

          <div class="col-sm-6">
            <span>Kode Referensi :</span><br>
            <h4 style="margin-top: 0px"><?php echo isset($value)?ucwords($value->kode_trans_far):''?> - (<?php echo isset($value)?ucwords($value->no_resep):''?>) | Iter <?php echo $value->iter?>x</h4>
          </div>

          <div class="col-sm-6">
            <div class="pull-right">
            <button type="button" onclick="getMenu('farmasi/Pengambilan_resep_iter')" class="btn btn-xs btn-default" title="Kembali ke Sebelumnya">
                <i class="fa fa-arrow-left dark"></i> Kembali sebelumnya
            </button>

            <!-- <button type="button" onclick="PopupCenter('farmasi/Pengambilan_resep_iter/nota_farmasi/<?php echo $value->kode_trans_far?>?flag=<?php echo $flag?>')" class="btn btn-xs btn-success" title="Nota Farmasi">
                <i class="fa fa-print dark"></i> Nota Farmasi
            </button> -->

            <button type="button" id="btn_submit_pengambilan_obat" class="btn btn-primary btn-xs">
                  <span class="ace-icon fa fa-check-circle dark icon-on-right bigger-110"></span>
                  Proses Pengambilan Obat
            </button>
            </div>
          </div>

      </div>
      <hr>
      <!-- <p><b>PENGAMBILAN RESEP ITER</b></p> -->
      <table id="verifikasi-resep-obat" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th class="center" width="20px">
              <label class="pos-rel">
                  <input type="checkbox" class="ace" name="checked_all" value="" onclick="checkAll(this)"/>
                  <span class="lbl"></span>
              </label>
            </th>
            <th class="center" width="30px">No</th>
            <th width="70px">Kode</th>
            <th>Nama Obat</th>
            <th width="250px">Dosis</th>
            <th width="110px">Jml Obat</th>
            <th width="100px">Sisa Obat</th>
            <th width="100px">Jml Diambil</th>
            <th width="50px"></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0;
            foreach($resep as $row) { $no++;
              $readonly = (empty($row->id_fr_tc_far_detail_log_prb))?'':'readonly';
              $jml_tebus = $row->jumlah_tebus;
              $jml_23 = $row->jumlah;
              $txt_color = ($row->resep_ditangguhkan == 1) ? 'red' : 'blue' ;
              $txt_color_prb = ($row->prb_ditangguhkan == 1) ? 'red' : 'blue' ;
              $sisa = ($row->jumlah + $jml_tebus) - $row->log_jml_mutasi;
              $total_hutang = $jml_tebus + $jml_23;
              echo '<tr id="row_kd_brg_'.$row->id_fr_tc_far_detail_log_prb.'" >';
                  echo '<td>';
                    echo '<label class="pos-rel">
                              <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="'.$row->id_fr_tc_far_detail_log_prb.'" id="checkbox_id_'.$row->id_fr_tc_far_detail_log_prb.'" />
                              <span class="lbl"></span>
                          </label>';
                    // hidden form
                    echo '<input type="hidden" name="id_fr_tc_far_detail_log_prb[]" value="'.$row->id_fr_tc_far_detail_log_prb.'" >';
                    echo '<input type="hidden" name="kode_brg_'.$row->id_fr_tc_far_detail_log_prb.'" value="'.$row->kode_brg.'" >';
                  echo '</td>';
                

                echo '<td align="center">'.$no.'</td>';
                echo '<td>'.$row->kode_brg.'</td>';
                echo '<td>'.$row->nama_brg.'</td>';
                echo '<td><span>Sehari '.$row->dosis_per_hari.' x '.$row->dosis_obat.' '.$row->satuan_obat.'
                '.$row->anjuran_pakai.' </span></td>';

                // jumlah obat
                echo '<td align="center">';
                  echo ( $total_hutang == 0 ) ? '<span style="color: green; font-weight: bold">Lunas</span>' : '<span style="color: red; font-weight: bold">'.number_format($total_hutang).'</span>';
                echo '</td>';

                // sisa belum diambil
                echo '<td align="center">';
                  echo number_format($sisa);
                echo '</td>';

                // jumlah akan diambil
                echo '<td align="center">';
                  echo '<input style="width:80px;height:25px;text-align:center"  class="format_number form-control" type="text" name="jumlah_'.$row->id_fr_tc_far_detail_log_prb.'" id="jumlah_'.$row->id_fr_tc_far_detail_log_prb.'" value="'.$sisa.'" '.$readonly.' onkeypres="pressEnter('.$row->id_fr_tc_far_detail_log_prb.')" onchange="saveRow('.$row->id_fr_tc_far_detail_log_prb.')">';
                echo '</td>';

                // aksi
                echo '<td align="center">';
                  $hidden = (empty($row->id_fr_tc_far_detail_log_prb)) ? '' : 'style="display: none"' ;
                  echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$row->id_fr_tc_far_detail_log_prb.'" onclick="saveRow('."'".$row->id_fr_tc_far_detail_log_prb."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                  echo '<a href="#" onclick="click_edit('."'".$row->id_fr_tc_far_detail_log_prb."'".')" id="btn_edit_'.$row->id_fr_tc_far_detail_log_prb.'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                echo '</td>';

                echo '</tr>';
            }
          ?>
        </tbody>
      </table>
      <br>

      <!-- <div class="col-md-12">
        <left>
          <span style="font-size: 12px;"><strong><u>RIWAYAT PENGAMBILAN RESEP ITER</u></strong><br>
          </span>
          <br>
        </left>
        <?php 
          foreach($riwayat as $key_riwayat=>$val_riwayat) :
            $dt_header = $riwayat[$key_riwayat][0];
            echo 'PBLOG - '.$key_riwayat.' | '.$this->tanggal->formatDateTimeFormDmy($dt_header->created_date).' | '.$dt_header->created_by.' | <a href="#" onclick="PopupCenter('."'farmasi/Pengambilan_resep_iter/nota_farmasi/".$value->kode_trans_far."?flag=".$flag."&kode_riwayat=".$key_riwayat."'".')"><i class="fa fa-print dark bigger-150"></i></a>';
            
        ?>
        <table class="table-utama" style="width: 50% !important;margin-top: 10px; margin-bottom: 10px">
              <?php 
                $no=0; 
                foreach ($val_log_mutasi as $key_vlm => $val_vlm) : $no++; 
              ?>

                <tr>
                  <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
                  <td style="border-collapse: collapse"><?php echo $val_vlm->nama_brg?></td>
                  <td style="text-align:center; border-collapse: collapse"><?php echo number_format($val_vlm->jumlah_mutasi_obat);?></td>
                  <td style="text-align: center; border-collapse: collapse"><?php echo $val_vlm->satuan_kecil; ?></td>
                </tr>

              <?php endforeach;?>

        </table>
        <?php endforeach;?>
      </div> -->

    </form>


  </div>

</div><!-- /.row -->

