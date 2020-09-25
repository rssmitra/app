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

$("#btn_submit_resep").click(function(event){
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
    url: $('#form_verifikasi_resep').attr('action'),
    type: "post",
    data: $('#form_verifikasi_resep').serialize(),
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();  
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);
      $('#page-area-content').load('farmasi/Verifikasi_resep_prb/preview_verifikasi/'+$('#kode_trans_far').val()+'?flag=RJ');
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

function inputHargaSatuan(kode_brg){
  var input = $('#harga_satuan_'+kode_brg+'').val();
  var input_hidden = $('#hidden_harga_satuan_'+kode_brg+'').val(formatNumberFromCurrency(input));
  // alert(input); return false;
  hitungSubTotalBarang(kode_brg);
}

function inputJumlah(kode_brg){
  var input = $('#jumlah_'+kode_brg+'').val();
  var input_hidden = $('#hidden_jumlah_'+kode_brg+'').val(formatNumberFromCurrency(input));
  hitungSubTotalBarang(kode_brg);
}

function hitungSubTotalBarang(kode_brg){

  saveRow(kode_brg);
  // original price
  var price = $('#hidden_harga_satuan_'+kode_brg+'').val();
  // qty pesan
  var qty = parseFloat($('#jumlah_'+kode_brg+'').val()).toFixed(2);
  console.log(price);
  console.log(qty);

  // jumlah sub total
  var sub_total = (parseFloat(price) * parseFloat(qty));
  $('#sub_total_'+kode_brg+'').val( sub_total );
  $('#hidden_sub_total_'+kode_brg+'').val( sub_total );

  var total = sumClass('sub_total');

  $('#total_biaya').text( formatMoney(parseInt(total)) );

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
        
    <form class="form-horizontal" method="post" id="form_verifikasi_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/Verifikasi_resep_prb/process">      
      
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
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $detail_obat[0]['nama_bagian']?></td>
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

            <button type="button" onclick="getMenu('farmasi/Verifikasi_resep_prb')" class="btn btn-xs btn-default" title="Kembali ke Sebelumnya">
                <i class="fa fa-arrow-left dark"></i> Kembali sebelumnya
            </button>

            <button type="button" onclick="getMenu('farmasi/Etiket_obat/form_copy_resep/<?php echo $value->kode_trans_far; ?>?flag=<?php echo isset($value->flag_trans) ? $value->flag_trans : 'RJ'; ?>')" class="btn btn-success btn-xs">
                <span class="ace-icon fa fa-print dark icon-on-right bigger-110"></span>
                Copy Resep
            </button>

            <button type="button" onclick="PopupCenter('farmasi/Process_entry_resep/nota_farmasi/<?php echo $value->kode_trans_far; ?>?flag=<?php echo isset($value->flag_trans) ? $value->flag_trans : 'RJ'; ?>')" class="btn btn-xs btn-warning" title="create_copy_resep">
                <i class="fa fa-print dark"></i> Nota Farmasi
            </button>

            <button type="button" id="btn_submit_resep" class="btn btn-primary btn-xs">
                  <span class="ace-icon fa fa-check-circle dark icon-on-right bigger-110"></span>
                  Submit Resep
            </button>

          </div>

      </div>
      
      <p><b>VERIFIKASI RESEP PRB</b></p>
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
            <th width="250px">Signa</th>
            <th width="50px">Jumlah</th>
            <th width="100px">Harga Satuan</th>
            <th width="100px">Subtotal</th>
            <!-- <th>Catatan</th> -->
            <th width="50px"></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0;
            foreach($detail_obat as $row) { $no++;
              $readonly = (empty($row['kd_tr_resep']))?'':'readonly';
              
              if($row['flag_resep'] == 'biasa') :
                echo '<tr id="row_kd_brg_'.$row['kode_brg'].'">';
                echo '<td>';
                  echo '<label class="pos-rel">
                            <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="b-'.$row['kd_tr_resep'].'" id="checkbox_id_'.$row['kd_tr_resep'].'" />
                            <span class="lbl"></span>
                        </label>';
                  // hidden form
                  echo '<input type="hidden" name="kd_tr_resep[]" value="'.$row['kd_tr_resep'].'" >';
                  echo '<input type="hidden" name="kode_brg[]" value="'.$row['kode_brg'].'" >';

                echo '</td>';
                echo '<td align="center">'.$no.'</td>';
                echo '<td>'.$row['kode_brg'].'</td>';
                $nama_obat = ($row['nama_brg'])?$row['nama_brg']:$row['nama_racikan'];
                echo '<td>'.$nama_obat.'</td>';

                // Signa
                echo '<td align="left">';
                  echo 'Sehari '.$row['dosis_per_hari'].' x '.$row['dosis_obat'].' '.ucfirst(strtolower($row['satuan_obat'])).' ('.ucwords($row['anjuran_pakai']).')';
                echo '</td>';

                // jumlah
                echo '<td align="center">';
                echo '<input style="width:50px;height:25px;text-align:center"  class="format_number form-control" type="text" name="jumlah_'.$row['kode_brg'].'" id="jumlah_'.$row['kode_brg'].'" value="'.$row['jumlah_obat_23'].'" '.$readonly.' onkeypres="pressEnter('."'".$row['kode_brg']."'".')" onchange="inputJumlah('."'".$row['kode_brg']."'".')">';

                echo '<input style="width:50px;height:25px;text-align:center" type="hidden" name="hidden_jumlah_'.$row['kode_brg'].'" id="hidden_jumlah_'.$row['kode_brg'].'"  value="'.$row['jumlah_obat_23'].'" '.$readonly.'>';

                echo '</td>';

                // harga satuan
                echo '<td align="right">';
                  // echo number_format($row['harga_jual']);
                  echo '<input style="width:100px;height:25px;text-align:right" type="text" id="harga_satuan_'.$row['kode_brg'].'" class="format_number form-control" name="harga_jual_'.$row['kode_brg'].'" value="'.$row['harga_jual'].'" '.$readonly.' onkeypres="pressEnter('."'".$row['kode_brg']."'".')" onchange="inputHargaSatuan('."'".$row['kode_brg']."'".')">';

                  echo '<input style="width:100px;height:25px;text-align:right" type="hidden" id="hidden_harga_satuan_'.$row['kode_brg'].'" name="hidden_harga_satuan_'.$row['kode_brg'].'" value="'.$row['harga_jual'].'" '.$readonly.'>';

                echo '</td>';

                // sub total
                echo '<td align="right">';
                  $subtotal = $row['harga_jual'] * $row['jumlah_obat_23'];
                  $arr_subtotal[] = $subtotal;
                  // echo number_format($subtotal);
                  echo '<input type="text" name="sub_total_'.$row['kode_brg'].'" id="sub_total_'.$row['kode_brg'].'" class="format_number form-control" style="height:45px;text-align:right" value="'.$subtotal.'" readonly>';

                  echo '<input type="hidden" name="hidden_sub_total_'.$row['kode_brg'].'" id="hidden_sub_total_'.$row['kode_brg'].'" class="form-control sub_total" style="height:45px;text-align:right" value="'.$subtotal.'" readonly>';
                echo '</td>';

                // catatan
                // echo '<td align="center">';
                //   echo '<input type="text" style="width: 100%" name="catatan_'.$row['kode_brg'].'" '.$readonly.' value="'.$row['catatan_lainnya'].'">' ;
                // echo '</td>';

                // aksi
                echo '<td align="center">';
                  
                $hidden = (empty($row['kd_tr_resep'])) ? '' : 'style="display: none"' ;
                  echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$row['kode_brg'].'" onclick="saveRow('."'".$row['kode_brg']."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                  
                  echo '<a href="#" onclick="click_edit('."'".$row['kode_brg']."'".')" id="btn_edit_'.$row['kode_brg'].'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                echo '</td>';

                echo '</tr>';
              else:
                foreach ($row['racikan'][0] as $key => $row_dt_racikan) {
                  echo '<tr id="row_kd_brg_'.$row_dt_racikan->kode_brg.'">';
                  echo '<td>';
                    echo '<label class="pos-rel">
                              <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="r-'.$row_dt_racikan->id_tc_far_racikan_detail.'" id="checkbox_id_'.$row_dt_racikan->id_tc_far_racikan_detail.'" />
                              <span class="lbl"></span>
                          </label>';
                    // hidden form
                    echo '<input type="hidden" name="kd_tr_resep[]" value="'.$row_dt_racikan->id_tc_far_racikan_detail.'" >';
                    echo '<input type="hidden" name="kode_brg[]" value="'.$row_dt_racikan->kode_brg.'" >';

                  echo '</td>';
                  echo '<td align="center">'.$no.'</td>';
                  echo '<td>'.$row_dt_racikan->kode_brg.'</td>';
                  echo '<td>'.$row_dt_racikan->nama_brg.'</td>';

                  // Signa
                  echo '<td align="left">';
                    echo 'Sehari '.$row_dt_racikan->dosis_per_hari.' x '.$row_dt_racikan->dosis_obat.' '.ucfirst(strtolower($row_dt_racikan->satuan)).' ('.ucwords($row_dt_racikan->anjuran_pakai).')';
                  echo '</td>';

                  // jumlah
                echo '<td align="center">';
                echo '<input style="width:50px;height:25px;text-align:center"  class="format_number form-control" type="text" name="jumlah_'.$row_dt_racikan->kode_brg.'" id="jumlah_'.$row_dt_racikan->kode_brg.'" value="'.$row_dt_racikan->jumlah_obat_23.'" '.$readonly.' onkeypres="pressEnter('."'".$row_dt_racikan->kode_brg."'".')" onchange="inputJumlah('."'".$row_dt_racikan->kode_brg."'".')">';

                echo '<input style="width:50px;height:25px;text-align:center" type="hidden" name="hidden_jumlah_'.$row_dt_racikan->kode_brg.'" id="hidden_jumlah_'.$row_dt_racikan->kode_brg.'"  value="'.$row_dt_racikan->jumlah_obat_23.'" '.$readonly.'>';

                echo '</td>';

                // harga satuan
                echo '<td align="right">';
                  // echo number_format($row_dt_racikan->harga_jual);
                  echo '<input style="width:100px;height:25px;text-align:right" type="text" id="harga_satuan_'.$row_dt_racikan->kode_brg.'" class="format_number form-control" name="harga_jual_'.$row_dt_racikan->kode_brg.'" value="'.$row_dt_racikan->harga_jual.'" '.$readonly.' onkeypres="pressEnter('."'".$row_dt_racikan->kode_brg."'".')" onchange="inputHargaSatuan('."'".$row_dt_racikan->kode_brg."'".')">';

                  echo '<input style="width:100px;height:25px;text-align:right" type="hidden" id="hidden_harga_satuan_'.$row_dt_racikan->kode_brg.'" name="hidden_harga_satuan_'.$row_dt_racikan->kode_brg.'" value="'.$row_dt_racikan->harga_jual.'" '.$readonly.'>';

                echo '</td>';

                // sub total
                echo '<td align="right">';
                  $subtotal = $row_dt_racikan->harga_jual * $row_dt_racikan->jumlah_obat_23;
                  $arr_subtotal[] = $subtotal;
                  // echo number_format($subtotal);
                  echo '<input type="text" name="sub_total_'.$row_dt_racikan->kode_brg.'" id="sub_total_'.$row_dt_racikan->kode_brg.'" class="format_number form-control" style="height:45px;text-align:right" value="'.$subtotal.'" readonly>';

                  echo '<input type="hidden" name="hidden_sub_total_'.$row_dt_racikan->kode_brg.'" id="hidden_sub_total_'.$row_dt_racikan->kode_brg.'" class="form-control sub_total" style="height:45px;text-align:right" value="'.$subtotal.'" readonly>';
                echo '</td>';

                  // // jumlah
                  // echo '<td align="center">';
                  //   echo '<input style="width:50px;height:25px;text-align:center" type="text" name="jumlah_'.$row_dt_racikan->kode_brg.'" value="'.$row_dt_racikan->jumlah_obat_23.'" '.$readonly.'>';
                  // echo '</td>';

                  //   // harga satuan
                  // echo '<td align="right">';
                  // // echo number_format($row_dt_racikan->harga_jual);
                  // echo '<input style="width:100px;height:25px;text-align:right" type="text" name="harga_jual_'.$row_dt_racikan->kode_brg.'" value="'.$row_dt_racikan->harga_jual.'" '.$readonly.'>';
                  // echo '</td>';
                  
                  // // sub total
                  // echo '<td align="right">';
                  //   $subtotal = $row_dt_racikan->harga_jual * $row_dt_racikan->jumlah_obat_23;
                  //   echo number_format($subtotal);
                  // echo '</td>';
                  
                  // // catatan
                  // echo '<td align="center">';
                  //   echo '<input type="text" style="width: 100%" name="catatan_'.$row_dt_racikan->kode_brg.'" '.$readonly.' value="'.$row_dt_racikan->catatan_lainnya.'">' ;
                  // echo '</td>';
                  // aksi
                  echo '<td align="center">';
                    
                  $hidden = (empty($row_dt_racikan->id_tc_far_racikan_detail)) ? '' : 'style="display: none"' ;
                    echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$row_dt_racikan->kode_brg.'" onclick="saveRow('."'".$row_dt_racikan->kode_brg."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                    
                    echo '<a href="#" onclick="click_edit('."'".$row_dt_racikan->kode_brg."'".')" id="btn_edit_'.$row_dt_racikan->kode_brg.'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                  echo '</td>';

                  echo '</tr>';
                }
              endif;
                      
              
            }
          ?>
        </tbody>
      </table>
      <div class="pull-right">
            <h4>Total Biaya, <span id="total_biaya"><?php echo number_format(array_sum($arr_subtotal))?></span> </h4>
      </div>

    </form>


  </div>

</div><!-- /.row -->

