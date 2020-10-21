<script type="text/javascript">

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

function click_edit(kode_brg){
  $("#row_kd_brg_"+kode_brg+" input[type=text], select").attr('readonly', false); 
  $('#btn_submit_'+kode_brg+'').show();
  $('#btn_edit_'+kode_brg+'').hide();
}

function saveRow(kode_brg){

  preventDefault();
  var data = {
    dosis_start : $("#row_kd_brg_"+kode_brg+" input[name=dosis_start_"+kode_brg+"]").val(),
    dosis_end : $("#row_kd_brg_"+kode_brg+" input[name=dosis_end_"+kode_brg+"]").val(),
    jumlah_obat : $("#row_kd_brg_"+kode_brg+" input[name=jumlah_"+kode_brg+"]").val(),
    satuan_obat : $("#row_kd_brg_"+kode_brg+" select[name=satuan_obat_"+kode_brg+"]").val(),
    anjuran_pakai : $("#row_kd_brg_"+kode_brg+" select[name=anjuran_pakai_"+kode_brg+"]").val(),
    catatan : $("#row_kd_brg_"+kode_brg+" input[name=catatan_"+kode_brg+"]").val(),
    relation_id : $("#row_kd_brg_"+kode_brg+" input[name=relation_id_"+kode_brg+"]").val(),
    kode_brg : $("#row_kd_brg_"+kode_brg+" input[name=kode_brg_"+kode_brg+"]").val(),
    kode_trans_far : $("#kode_trans_far").val(),
  };

  $.ajax({
      url: "farmasi/Etiket_obat/process",
      data: data,            
      dataType: "json",
      type: "POST",
      beforeSend: function() {        
        achtungShowLoader();          
      },  
      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});  
          $("#row_kd_brg_"+kode_brg+" input[type=text]").attr('readonly', true); 
          $('#btn_submit_'+kode_brg+'').hide();
          $('#btn_edit_'+kode_brg+'').show();
          
        }else{

          $.achtung({message: jsonResponse.message, timeout:5}); 

        }    

        achtungHideLoader();  

      }

  });

  return false;

}

$("#btn_cetak_etiket").click(function(event){
      event.preventDefault();
      var searchIDs = $("#resep_obat_etiket tbody input:checkbox:checked").map(function(){
        return $(this).val();
      }).toArray();
      if(searchIDs.length == 0){
        alert('Tidak ada item yang dipilih !'); 
        return false;
      }
      get_kode_eticket(searchIDs);
      console.log(searchIDs);
});

function get_kode_eticket(myid){

  $.ajax({
        url: 'farmasi/Etiket_obat/get_kode_eticket',
        type: "post",
        data: {ID:myid},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          PopupCenter('farmasi/Etiket_obat/preview_etiket?'+jsonResponse.params+'', 'Etiket Obat' , 600 , 600);
          achtungHideLoader();
        }

    });

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
        
    <form class="form-horizontal" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      
      
      <!-- hidden form -->
      <input type="hidden" name="kode_trans_far" id="kode_trans_far" value="<?php echo isset($value)?ucwords($value->kode_trans_far):''?>">

      <left><span style="font-size: 12px;"><strong><u>TRANSAKSI FARMASI</u></strong><br>
      No. <?php echo $value->kode_trans_far; ?> - <?php echo $value->no_resep; ?>
      </span></left>

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
      <hr>

      <div class="row">

          <div class="col-sm-6">
            <h4><?php echo isset($value)?ucwords($value->kode_trans_far):''?> - (<?php echo isset($value)?ucwords($value->no_resep):''?>) </h4>
          </div>

          <div class="pull-right">

            <button type="button" onclick="getMenu('farmasi/Process_entry_resep/preview_entry/<?php echo $value->kode_trans_far; ?>?flag=<?php echo $flag; ?>');" class="btn btn-xs btn-default" title="Kembali ke Resep Rawat Jalan">
                <i class="fa fa-arrow-left dark"></i> Kembali sebelumnya
            </button>

            <button onclick="getMenu('farmasi/Etiket_obat/form_copy_resep/<?php echo $value->kode_trans_far; ?>?flag=<?php echo $flag; ?>')" class="btn btn-success btn-xs">
                <span class="ace-icon fa fa-print dark icon-on-right bigger-110"></span>
                Copy Resep
            </button>

            <button type="button" onclick="PopupCenter('farmasi/Process_entry_resep/nota_farmasi/<?php echo $value->kode_trans_far; ?>?flag=<?php echo $flag; ?>')" class="btn btn-xs btn-warning" title="create_copy_resep">
                <i class="fa fa-print dark"></i> Nota Farmasi
            </button>

            <button type="button" id="btn_cetak_etiket" class="btn btn-primary btn-xs">
                  <span class="ace-icon fa fa-print dark icon-on-right bigger-110"></span>
                  Print Etiket Obat
            </button>
          </div>

      </div>

      <hr>

      <div class="row">
        <div class="col-md-12">

          <table id="resep_obat_etiket" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th class="center" width="20px">
                  <label class="pos-rel">
                      <input type="checkbox" class="ace" name="checked_all" value="" onclick="checkAll(this)"/>
                      <span class="lbl"></span>
                  </label>
                </th>
                <th class="center" width="50px">No</th>
                <th>Kode</th>
                <th>Nama Obat</th>
                <th>Jml Obat</th>
                <th width="150px">Aturan Pakai</th>
                <th>Satuan</th>
                <th>Penggunaan</th>
                <th>Catatan</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $no = 0;
                if(count($detail_obat) > 0) :
                foreach($detail_obat as $row) { $no++;

                  $count_racikan = (count($row['racikan'][0]) > 0) ? $row['racikan'][0] : array();
                  $nama_obat = (count($count_racikan) > 0) ? $count_racikan[0]->nama_racikan : $row['nama_brg'];
                  
                  $jml_obat = ( $row['jumlah_tebus'] ) ? ($row['prb_ditangguhkan'] == 0) ? $row['jumlah_tebus'] + $row['jumlah_obat_23'] : $row['jumlah_tebus'] : $row['jumlah_tebus'];

                  $dosis_per_hari = (count($count_racikan) > 0) ? $count_racikan[0]->dosis_per_hari : $row['dosis_per_hari'];
                  $dosis_obat = (count($count_racikan) > 0) ? $count_racikan[0]->dosis_obat : $row['dosis_obat'];
                  $satuan_obat = (count($count_racikan) > 0) ? $count_racikan[0]->satuan_racikan : $row['satuan_obat'];
                  $anjuran_pakai = (count($count_racikan) > 0) ? $count_racikan[0]->anjuran_pakai : $row['anjuran_pakai'];
                  $catatan_lainnya = (count($count_racikan) > 0) ? $count_racikan[0]->catatan_lainnya : $row['catatan_lainnya'];

                  $readonly = (empty($row['kd_tr_resep']))?'':'readonly';
                  if( $jml_obat > 0 ) :
                  echo '<tr id="row_kd_brg_'.$row['kode_brg'].'">';
                  echo '<td>';
                    echo '<label class="pos-rel">
                              <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="'.$row['relation_id'].'" id="checkbox_id_'.$row['relation_id'].'" />
                              <span class="lbl"></span>
                          </label>';
                    // hidden form
                    echo '<input type="hidden" name="relation_id_'.$row['kode_brg'].'" value="'.$row['relation_id'].'" >';
                    echo '<input type="hidden" name="kode_brg_'.$row['kode_brg'].'" value="'.$row['kode_brg'].'" >';

                  echo '</td>';
                  echo '<td align="center">'.$no.'</td>';
                  echo '<td>'.$row['kode_brg'].'</td>';
                  echo '<td>'.$nama_obat.'</td>';
                  echo '<td align="center">'.$jml_obat.' '.$row['satuan_kecil'].'</td>';
                  // dosis form
                  echo '<td align="center">';
                    echo '<input style="width:50px;height:45px;text-align:center" type="text" name="dosis_start_'.$row['kode_brg'].'" value="'.$dosis_per_hari.'" '.$readonly.'> &nbsp; x &nbsp; <input style="width:50px;height:45px;text-align:center" type="text" name="dosis_end_'.$row['kode_brg'].'" value="'.$dosis_obat.'" '.$readonly.'>';
                  echo '</td>';
                  // jumlah
                  // echo '<td align="center">';
                  //   echo '<input style="width:50px;height:45px;text-align:center" type="text" name="jumlah_'.$row['kode_brg'].'" value="'.$row['jumlah_obat'].'" '.$readonly.'>';
                  // echo '</td>';
                  // satuan
                  echo '<td align="center">';
                    echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), ($satuan_obat)?$satuan_obat:'TAB' , 'satuan_obat_'.$row['kode_brg'].'', 'satuan_obat_'.$row['kode_brg'].'', 'form-control', '', ''.$readonly.'');
                  echo '</td>';

                  // penggunaan
                  echo '<td align="center">';
                    echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), ($anjuran_pakai)?$anjuran_pakai:'Sesudah Makan' , 'anjuran_pakai_'.$row['kode_brg'].'', 'anjuran_pakai_'.$row['kode_brg'].'', 'form-control', '', ''.$readonly.'');
                  echo '</td>';
                  // catatan
                  echo '<td align="center">';
                    echo '<input type="text" style="width: 100%" name="catatan_'.$row['kode_brg'].'" '.$readonly.' value="'.$catatan_lainnya.'">' ;
                  echo '</td>';
                  // aksi
                  echo '<td align="center">';
                    
                  $hidden = (empty($row['kd_tr_resep'])) ? '' : 'style="display: none"' ;
                    echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$row['kode_brg'].'" onclick="saveRow('."'".$row['kode_brg']."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                    
                    echo '<a href="#" onclick="click_edit('."'".$row['kode_brg']."'".')" id="btn_edit_'.$row['kode_brg'].'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                  echo '</td>';

                  echo '</tr>';

                  endif;
                }
                else:
                  echo '<tr>';
                  echo '<td colspan="9"><div class="alert alert-warning"><strong>Tidak ada data ditemukan !</strong> Anda harus menggunakan aplikasi terbaru ketika melakukan entry resep.</div></td>';
                  echo '</tr>';
                endif;
              ?>
            </tbody>
          </table>
          * Silahkan ceklis etiket yang akan dicetak.
        </div>
      </div>

    </form>


  </div>

</div><!-- /.row -->

