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
        $('#kode_brg_td_'+num+'').text(val_item);
        getDetailObatByKodeBrg(val_item, '060101', num);
      }
  });
}

function getDetailObatByKodeBrg(kode_brg,kode_bag, num){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok="+$('#kode_kelompok').val()+"&kode_perusahaan="+$('#kode_perusahaan').val()+"&bag="+kode_bag+"&type=html&type_layan=Rajal", '' , function (response) {
    
    $('#stok_brg_'+num+'').val(response.sisa_stok);
    $('#td_stok_akhir_depo_'+num+'').text(response.sisa_stok);
    $('#jumlah_'+num+'').attr('max', response.sisa_stok);

    if(response.sisa_stok <= 0){
      $('#checked_id_'+num+'').html('<span style="color: red; font-weight: bold; font-style:italic">n/a</span>');
    }else{
      $('#checked_id_'+num+'').html('<label class="pos-rel"><input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="'+num+'" id="checkbox_id_'+num+'" /><span class="lbl"></span></label>');
      if( response.sisa_stok < $('#jumlah_'+num+'').val() ){
        $('#jumlah_'+num+'').val(response.sisa_stok);
      }else{
        $('#jumlah_'+num+'').val($('#sisa_hutang_'+num+'').text());
      }
    }

    return response;

  })

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
      $('#page-area-content').load('farmasi/Process_entry_resep/preview_entry/'+jsonResponse.kode_trans_far+'?flag=ITR&status_lunas=0');
      
      achtungHideLoader();
    }

  });

}

function click_edit(num){
  preventDefault();
  $("#row_kd_brg_"+num+" input[type=number], select").attr('readonly', false); 
  $('#btn_submit_'+num+'').show();
  $('#btn_edit_'+num+'').hide();
}

function saveRow(num){

  preventDefault();
  $("#row_kd_brg_"+num+" input[type=number], select").attr('readonly', true); 
  var entry = $('#jumlah_'+num+'').val();
  var stok = $('#stok_brg_'+num+'').val();

  if( stok < entry){
    if(stok < 0){
      $('#jumlah_'+num+'').val(0);
    }else{
      $('#jumlah_'+num+'').val(stok);
    }
  }else{
    $('#jumlah_'+num+'').val(entry);
  }

  $('#btn_submit_'+num+'').hide();
  $('#btn_edit_'+num+'').show();

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
      <input type="hidden" name="no_mr" id="no_mr" value="<?php echo isset($value)?ucwords($value->no_mr):''?>">
      <input type="hidden" name="no_registrasi" id="no_registrasi" value="<?php echo isset($value)?ucwords($value->no_registrasi):''?>">
      <input type="hidden" name="no_kunjungan" id="no_kunjungan" value="<?php echo isset($value)?ucwords($value->no_kunjungan):''?>">
      <input type="hidden" name="kode_dokter" id="kode_dokter" value="<?php echo isset($value)?ucwords($value->kode_dokter):''?>">
      <input type="hidden" name="dokter_pengirim" id="dokter_pengirim" value="<?php echo isset($value)?ucwords($value->dokter_pengirim):''?>">
      <input type="hidden" name="nama_pasien" id="nama_pasien" value="<?php echo isset($value)?ucwords($value->nama_pasien):''?>">
      <input type="hidden" name="tlp_pasien" id="tlp_pasien" value="<?php echo isset($value)?ucwords($value->telpon_pasien):''?>">
      <input type="hidden" name="last_iter" id="last_iter" value="<?php echo isset($value)?ucwords($value->iter):''?>">
      <input type="hidden" name="flag_trans" id="flag_trans" value="ITR">
      <input type="hidden" name="id_iter" id="id_iter" value="<?php echo isset($value)?ucwords($value->id_iter):''?>">
      
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
            Kode Referensi : <br>
            <h4><?php echo isset($value)?ucwords($value->kode_trans_far):''?> - (<?php echo isset($value)?ucwords($value->no_resep):''?>) </h4>
          </div>

          <div class="pull-right">

            <button type="button" onclick="getMenu('farmasi/Pengambilan_resep_iter')" class="btn btn-xs btn-default" title="Kembali ke Sebelumnya">
                <i class="fa fa-arrow-left dark"></i> Kembali sebelumnya
            </button>

            <!-- <button type="button" onclick="PopupCenter('farmasi/Verifikasi_resep_prb/nota_farmasi/<?php echo $value->kode_trans_far?>?flag=<?php echo $flag?>')" class="btn btn-xs btn-success" title="Nota Farmasi">
                <i class="fa fa-print dark"></i> Nota Farmasi
            </button> -->
            <!-- <?php //if($value->status_iter != 1):?> -->
            <button type="button" id="btn_submit_pengambilan_obat" class="btn btn-primary btn-xs">
                  <span class="ace-icon fa fa-check-circle dark icon-on-right bigger-110"></span>
                  Proses Pengambilan Obat
            </button>
            <!-- <?php// endif; ?> -->

          </div>

      </div>
      
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
            <th width="110px">Stok Depo</th>
            <!-- <th width="110px">Jml Obat</th>
            <th width="110px">Ttl Hutang</th>
            <th width="100px">Sisa Hutang</th> -->
            <th class="center" width="100px">Jumlah<br>Obat</th>
            <!-- <th width="100px">Harga Satuan</th>
            <th width="100px">Subtotal</th> -->
            <!-- <th>Catatan</th> -->
            <th class="center" width="50px">Jumlah<br>Diambil</th>
            <th width="50px"></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0;
            foreach($resep as $row) { $no++;
              $readonly = (empty($row->id_fr_tc_far_detail_log))?'':'readonly';
              $jml_tebus = $row->jumlah_tebus ;
              $jml_23 = $row->jumlah_obat_23 ;
              $txt_color = ($row->resep_ditangguhkan == 1) ? 'red' : 'blue' ;
              $txt_color_prb = ($row->prb_ditangguhkan == 1) ? 'red' : 'blue' ;
              $sisa = ($row->jumlah_obat_23 + $jml_tebus) - $row->jumlah_mutasi_obat;
              $total_hutang = $jml_tebus + $jml_23;
              $txt_msg = '';
              echo '<tr id="row_kd_brg_'.$row->id_fr_tc_far_detail_log.'" >';
              echo '<td align="center" id="checked_id_'.$row->id_fr_tc_far_detail_log.'">';
                if($sisa > 0 ) :
                  if( $row->stok_akhir_depo > 0 ) :
                    echo '<label class="pos-rel">
                              <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="'.$row->id_fr_tc_far_detail_log.'" id="checkbox_id_'.$row->id_fr_tc_far_detail_log.'" />
                              <span class="lbl"></span>
                          </label>';
                    
                  else:
                    $txt_msg = '<span style="color: red; font-weight: bold; font-style:italic">(out of stock)</span>';
                    echo '<span style="color: red; font-weight: bold">n/a</span>';
                  endif;

                else:
                  echo '-';
                endif; 
              echo '</td>';

                echo '<td align="center">'.$no.'</td>';
                echo '<td><span id="kode_brg_td_'.$row->id_fr_tc_far_detail_log.'">'.$row->kode_brg.'</span></td>';
                echo '<td><input type="text" class="nama_brg form-control" value="'.$row->nama_brg.'" name="nama_brg_'.$row->id_fr_tc_far_detail_log.'" id="inputKeyObat_'.$row->id_fr_tc_far_detail_log.'" onclick="searchObat('.$row->id_fr_tc_far_detail_log.')"></td>';
                
                // sisa stok depo
                echo '<td align="center" id="td_stok_akhir_depo_'.$row->id_fr_tc_far_detail_log.'">';
                  echo '<span>'.number_format($row->stok_akhir_depo).'</span>';
                echo '</td>';

                // jumlah obat biasa
                // echo '<td align="center">';
                //   echo '<span style="color: '.$txt_color.'; font-weight: bold">'.number_format($jml_tebus).'</span>';
                //   echo '<input style="width:80px;height:25px;text-align:center"  class="format_number form-control" type="hidden" name="jumlah_tebus_biasa_'.$row->id_fr_tc_far_detail_log.'" id="jumlah_tebus_biasa_'.$row->id_fr_tc_far_detail_log.'" value="'.$jml_tebus.'" '.$readonly.'>';
                // echo '</td>';

                // jumlah
                // echo '<td align="center">';
                //   echo '<span style="color: '.$txt_color_prb.'; font-weight: bold">'.number_format($row->jumlah_obat_23).'</span>';
                //   echo '<input style="width:80px;height:25px;text-align:center"  class="format_number form-control" type="hidden" name="jumlah_tebus_'.$row->id_fr_tc_far_detail_log.'" id="jumlah_tebus_'.$row->id_fr_tc_far_detail_log.'" value="'.$row->jumlah_obat_23.'" '.$readonly.'>';
                //   // last mutasi total
                //   echo '<input type="hidden" name="log_jml_mutasi_'.$row->id_fr_tc_far_detail_log.'" id="log_jml_mutasi_'.$row->id_fr_tc_far_detail_log.'" value="'.$row->jumlah_mutasi_obat.'">';
                // echo '</td>';

                // total hutang
                // echo '<td align="center">';
                //   echo ( $total_hutang == 0 ) ? '<span style="color: green; font-weight: bold">Lunas</span>' : '<span style="color: red; font-weight: bold">'.number_format($total_hutang).'</span>';
                // echo '</td>';

                // jumlah tebus
                echo '<td align="center" id="sisa_hutang_'.$row->id_fr_tc_far_detail_log.'">';
                  echo ( $sisa == 0 ) ? '<span style="color: green; font-weight: bold">Lunas</span>' : number_format($sisa);
                echo '</td>';

                // jumlah mutasi
                echo '<td align="center">';
                echo '<input style="width:80px;height:25px;text-align:center" type="number" name="jumlah_'.$row->id_fr_tc_far_detail_log.'" max="'.$sisa.'" id="jumlah_'.$row->id_fr_tc_far_detail_log.'" value="'.$sisa.'" '.$readonly.' onkeypres="pressEnter('.$row->id_fr_tc_far_detail_log.')" onchange="saveRow('.$row->id_fr_tc_far_detail_log.')">';
                echo '</td>';

                // catatan
                // echo '<td align="center">';
                //   echo '<input type="text" style="width: 100%" name="catatan_'.$row->id_fr_tc_far_detail_log.'" '.$readonly.' value="'.$row->catatan_lainnya.'">' ;
                // echo '</td>';

                // aksi
                echo '<td align="center">';

                // hidden form
                echo '<input type="hidden" name="id_fr_tc_far_detail_log[]" value="'.$row->id_fr_tc_far_detail_log.'" >';
                echo '<input type="hidden" name="kode_brg_'.$row->id_fr_tc_far_detail_log.'" id="kode_brg_'.$row->id_fr_tc_far_detail_log.'" value="'.$row->kode_brg.'" >';
                echo '<input type="hidden" name="stok_brg_'.$row->id_fr_tc_far_detail_log.'" id="stok_brg_'.$row->id_fr_tc_far_detail_log.'" value="'.$row->stok_akhir_depo.'" >';

                echo '<input type="hidden" name="kd_tr_resep_'.$row->id_fr_tc_far_detail_log.'" value="'.$row->kd_tr_resep.'" >';

                $hidden = (empty($row->id_fr_tc_far_detail_log)) ? '' : 'style="display: none"' ;
                if($sisa > 0):
                  echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$row->id_fr_tc_far_detail_log.'" onclick="saveRow('."'".$row->id_fr_tc_far_detail_log."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                
                  echo '<a href="#" onclick="click_edit('."'".$row->id_fr_tc_far_detail_log."'".')" id="btn_edit_'.$row->id_fr_tc_far_detail_log.'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                else:
                    echo '-';
                endif;

                echo '</td>';

                echo '</tr>';
              
            }
          ?>
        </tbody>
      </table>
      <br>
      <b>Keterangan : </b><br>
      <span style="color: red; font-weight: bold">n/a</span> : (Not Available) / Stok kosong / Tidak dapat dipilih untuk dilanjutkan transaksi<br>
      Jumlah obat yang akan diambil <b>tidak bisa melebihi stok depo.</b>
      <br>
      <br>
    </div>

    </form>


  </div>

</div><!-- /.row -->

