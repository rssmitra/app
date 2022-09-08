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

function searchObat(num){
  $('#inputKeyObat_'+num+'').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getObatByBagianAutoComplete",
              data: { keyword:query, bag: '060101'},            
              dataType: "json",
              type: "POST",
              success: function (response) {
                result($.map(response, function (item) {
                    return item;
                }));
              }
          });
      },
      afterSelect: function (item) {
        // do what is needed with item
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#inputKeyObat_'+num+'').val(label_item);
        $('#kode_brg_'+num+'').val(val_item);

      }
  });
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
      $('#page-area-content').load('farmasi/Proses_resep_prb/preview_mutasi/'+$('#kode_trans_far').val()+'?flag=RJ&kode_log_mutasi='+jsonResponse.kode_log_mutasi_obat+'');
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
        
    <form class="form-horizontal" method="post" id="form_proses_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/Proses_resep_prb/process">      
      
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
            <h4><?php echo isset($value)?ucwords($value->kode_trans_far):''?> - (<?php echo isset($value)?ucwords($value->no_resep):''?>) </h4>
          </div>

          <div class="pull-right">

            <button type="button" onclick="getMenu('farmasi/Proses_resep_prb')" class="btn btn-xs btn-default" title="Kembali ke Sebelumnya">
                <i class="fa fa-arrow-left dark"></i> Kembali sebelumnya
            </button>

            <button type="button" onclick="PopupCenter('farmasi/Verifikasi_resep_prb/nota_farmasi/<?php echo $value->kode_trans_far?>?flag=<?php echo $flag?>')" class="btn btn-xs btn-success" title="Nota Farmasi">
                <i class="fa fa-print dark"></i> Nota Farmasi
            </button>

            <button type="button" id="btn_submit_pengambilan_obat" class="btn btn-primary btn-xs">
                  <span class="ace-icon fa fa-check-circle dark icon-on-right bigger-110"></span>
                  Proses Pengambilan Obat
            </button>

          </div>

      </div>
      
      <p><b>PENGAMBILAN RESEP KRONIS</b></p>
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
            <th width="110px">Jml Obat Biasa</th>
            <th width="110px">Jml Obat Kronis</th>
            <th width="110px">Ttl Hutang</th>
            <th width="100px">Sisa Hutang</th>
            <th width="100px">Jml Diambil</th>
            <!-- <th width="100px">Harga Satuan</th>
            <th width="100px">Subtotal</th> -->
            <!-- <th>Catatan</th> -->
            <th width="50px"></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0;
            foreach($resep as $row) { $no++;
              $readonly = (empty($row->id_fr_tc_far_detail_log_prb))?'':'readonly';
              $jml_tebus = ($row->resep_ditangguhkan == 1) ? $row->jumlah_tebus : 0 ;
              $jml_23 = ($row->prb_ditangguhkan == 1) ? $row->jumlah : 0 ;
              $txt_color = ($row->resep_ditangguhkan == 1) ? 'red' : 'blue' ;
              $txt_color_prb = ($row->prb_ditangguhkan == 1) ? 'red' : 'blue' ;
              $sisa = ($row->jumlah + $jml_tebus) - $row->jumlah_mutasi_obat;
              $total_hutang = $jml_tebus + $jml_23;
              $txt_msg = '';
              echo '<tr id="row_kd_brg_'.$row->id_fr_tc_far_detail_log_prb.'" >';
                if( $row->prb_ditangguhkan == 0 ){
                  echo '<td align="center">-</td>';
                }else{
                  echo '<td align="center">';
                    if($sisa > 0 ) :
                      if( $row->stok_akhir_depo > 0 ) :
                        echo '<label class="pos-rel">
                                  <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="'.$row->id_fr_tc_far_detail_log_prb.'" id="checkbox_id_'.$row->id_fr_tc_far_detail_log_prb.'" />
                                  <span class="lbl"></span>
                              </label>';
                        // hidden form
                        echo '<input type="hidden" name="id_fr_tc_far_detail_log_prb[]" value="'.$row->id_fr_tc_far_detail_log_prb.'" >';
                        echo '<input type="hiddenxx" name="kode_brg_'.$row->id_fr_tc_far_detail_log_prb.'" id="kode_brg_'.$row->id_fr_tc_far_detail_log_prb.'" value="'.$row->kode_brg.'" >';

                        echo '<input type="hidden" name="kd_tr_resep_'.$row->id_fr_tc_far_detail_log_prb.'" value="'.$row->kd_tr_resep.'" >';
                      else:
                        $txt_msg = '<span style="color: red; font-weight: bold; font-style:italic">(out of stock)</span>';
                        echo '<span style="color: red; font-weight: bold">n/a</span>';
                      endif;

                    else:
                      echo '-';
                    endif; 
                  echo '</td>';
                }

                echo '<td align="center">'.$no.'</td>';
                echo '<td>'.$row->kode_brg.'</td>';
                echo '<td><input type="text" class="nama_brg form-control" value="'.$row->nama_brg.'" name="nama_brg" id="inputKeyObat_'.$row->id_fr_tc_far_detail_log_prb.'" onclick="searchObat('.$row->id_fr_tc_far_detail_log_prb.')"></td>';

                // jumlah obat biasa
                echo '<td align="center">';
                  
                  echo '<span style="color: '.$txt_color.'; font-weight: bold">'.number_format($jml_tebus).'</span>';
                  echo '<input style="width:80px;height:25px;text-align:center"  class="format_number form-control" type="hidden" name="jumlah_tebus_biasa_'.$row->id_fr_tc_far_detail_log_prb.'" id="jumlah_tebus_biasa_'.$row->id_fr_tc_far_detail_log_prb.'" value="'.$jml_tebus.'" '.$readonly.'>';
                echo '</td>';

                // jumlah
                echo '<td align="center">';
                  echo '<span style="color: '.$txt_color_prb.'; font-weight: bold">'.number_format($row->jumlah).'</span>';
                  echo '<input style="width:80px;height:25px;text-align:center"  class="format_number form-control" type="hidden" name="jumlah_tebus_'.$row->id_fr_tc_far_detail_log_prb.'" id="jumlah_tebus_'.$row->id_fr_tc_far_detail_log_prb.'" value="'.$row->jumlah.'" '.$readonly.'>';
                  // last mutasi total
                  echo '<input type="hidden" name="log_jml_mutasi_'.$row->id_fr_tc_far_detail_log_prb.'" id="log_jml_mutasi_'.$row->id_fr_tc_far_detail_log_prb.'" value="'.$row->jumlah_mutasi_obat.'">';
                echo '</td>';

                // total hutang
                echo '<td align="center">';
                  echo ( $total_hutang == 0 ) ? '<span style="color: green; font-weight: bold">Lunas</span>' : '<span style="color: red; font-weight: bold">'.number_format($total_hutang).'</span>';
                echo '</td>';

                // jumlah tebus
                echo '<td align="center">';
                  echo ( $sisa == 0 ) ? '<span style="color: green; font-weight: bold">Lunas</span>' : number_format($sisa);
                echo '</td>';

                // jumlah mutasi
                if( $row->prb_ditangguhkan == 0 ){
                  echo '<td align="center">-</td>';
                }else{
                  echo '<td align="center">';
                  echo '<input style="width:80px;height:25px;text-align:center"  class="format_number form-control" type="text" name="jumlah_'.$row->id_fr_tc_far_detail_log_prb.'" id="jumlah_'.$row->id_fr_tc_far_detail_log_prb.'" value="'.$sisa.'" '.$readonly.' onkeypres="pressEnter('.$row->id_fr_tc_far_detail_log_prb.')" onchange="saveRow('.$row->id_fr_tc_far_detail_log_prb.')">';
                  echo '</td>';
                }

                // catatan
                // echo '<td align="center">';
                //   echo '<input type="text" style="width: 100%" name="catatan_'.$row->id_fr_tc_far_detail_log_prb.'" '.$readonly.' value="'.$row->catatan_lainnya.'">' ;
                // echo '</td>';

                // aksi
                echo '<td align="center">';
                  
                $hidden = (empty($row->id_fr_tc_far_detail_log_prb)) ? '' : 'style="display: none"' ;
                  if( $row->prb_ditangguhkan == 0 ){
                    echo '-';
                  }else{
                    if($sisa > 0):
                    echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$row->id_fr_tc_far_detail_log_prb.'" onclick="saveRow('."'".$row->id_fr_tc_far_detail_log_prb."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                  
                    echo '<a href="#" onclick="click_edit('."'".$row->id_fr_tc_far_detail_log_prb."'".')" id="btn_edit_'.$row->id_fr_tc_far_detail_log_prb.'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                    else:
                      echo '-';
                    endif;
                  }
                echo '</td>';

                echo '</tr>';
                      
              
            }
          ?>
        </tbody>
      </table>
      <br>
      <div class="col-md-12">
      <left>
        <span style="font-size: 12px;"><strong><u>LOG PENGAMBILAN OBAT</u></strong><br>
        </span>
        <br>
      </left>
      <?php 
        foreach($log_mutasi as $key_log_mutasi=>$val_log_mutasi) :
          $dt_header = $log_mutasi[$key_log_mutasi][0];
          echo 'PBLOG - '.$key_log_mutasi.' | '.$this->tanggal->formatDateTimeFormDmy($dt_header->created_date).' | '.$dt_header->created_by.' | <a href="#" onclick="PopupCenter('."'farmasi/Proses_resep_prb/nota_farmasi/".$value->kode_trans_far."?flag=".$flag."&kode_log_mutasi=".$key_log_mutasi."'".')"><i class="fa fa-print dark bigger-150"></i></a>';
          
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
    </div>

    </form>


  </div>

</div><!-- /.row -->

