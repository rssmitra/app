<!-- jquery number -->
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>
<script type="text/javascript">

$(function(){
        
  $('.format_number').number( true, 2 );
  
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

function click_edit(kode_brg){
  $("#row_kd_brg_"+kode_brg+" input[type=text], select").attr('readonly', false); 
  $('#btn_submit_'+kode_brg+'').show();
  $('#btn_edit_'+kode_brg+'').hide();
}

function saveRow(kode_brg){

  preventDefault();
  var data = {
    // dosis_start : $("#row_kd_brg_"+kode_brg+" input[name=dosis_start_"+kode_brg+"]").val(),
    // dosis_end : $("#row_kd_brg_"+kode_brg+" input[name=dosis_end_"+kode_brg+"]").val(),
    // satuan_obat : $("#row_kd_brg_"+kode_brg+" select[name=satuan_obat_"+kode_brg+"]").val(),
    // anjuran_pakai : $("#row_kd_brg_"+kode_brg+" select[name=anjuran_pakai_"+kode_brg+"]").val(),
    // catatan : $("#row_kd_brg_"+kode_brg+" input[name=catatan_"+kode_brg+"]").val(),
    jumlah_obat : $("#row_kd_brg_"+kode_brg+" input[name=jumlah_"+kode_brg+"]").val(),
    kd_tr_resep : $("#row_kd_brg_"+kode_brg+" input[name=kd_tr_resep_"+kode_brg+"]").val(),
    kode_brg : $("#row_kd_brg_"+kode_brg+" input[name=kode_brg_"+kode_brg+"]").val(),
    kode_trans_far : $("#kode_trans_far").val(),
  };

  $.ajax({
      url: "farmasi/Proses_resep_prb/process",
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

$("#btn_copy_resep").click(function(event){
      event.preventDefault();
      var kode = $('#kode_trans_far').val();
      getMenu('farmasi/Proses_resep_prb/form_copy_resep/'+kode+'');
});

function get_kode_eticket(myid){

  $.ajax({
        url: 'farmasi/Proses_resep_prb/get_kode_eticket',
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
          PopupCenter('farmasi/Proses_resep_prb/preview_etiket?'+jsonResponse.params+'', 'Etiket Obat' , 600 , 600);
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

      <!-- <div class="col-sm-12">
        <div class="pull-right">
          <button type="button" onclick="getMenu('farmasi/Proses_resep_prb')" class="btn btn-default btn-xs">
            <span class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></span>
            Kembali ke Halaman Utama
          </button>
        </div>
      </div> -->
      
      <div class="row">
        <div class="col-md-6">
          <table>
            <tr style="">
              <td width="100px">No. SEP</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $value->no_sep ?></td>
            </tr>
            <tr style="">
              <td width="100px">No. MR</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $value->no_mr?></td>
            </tr>
            <tr style="">
              <td width="100px">Nama Pasien</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $value->nama_pasien?></td>
            </tr>
          </table>
        </div>

        <div class="col-md-6">
          <table>
          
            <tr style="">
              <td width="100px">Tanggal</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($value->tgl_trans) ?></td>
            </tr>
            <tr style="">
              <td width="100px">Dokter</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $value->dokter_pengirim?></td>
            </tr>
            <tr style="">
              <td width="100px">Poli Asal</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep[0]['nama_bagian']?></td>
            </tr>
          </table>
        </div>
      </div>
      
      <hr class="separator">
      
      <p><b>RESEP OBAT FARMASI</b></p>
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
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Subtotal</th>
            <th>Signa</th>
            <th>Catatan</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0;
            foreach($resep as $row) { $no++;
              $readonly = (empty($row['id_fr_tc_far_detail_log']))?'':'readonly';
              
              if($row['flag_resep'] == 'biasa') :
                echo '<tr id="row_kd_brg_'.$row['kode_brg'].'">';
                echo '<td>';
                  echo '<label class="pos-rel">
                            <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="'.$row['id_fr_tc_far_detail_log'].'" id="checkbox_id_'.$row['id_fr_tc_far_detail_log'].'" />
                            <span class="lbl"></span>
                        </label>';
                  // hidden form
                  echo '<input type="hidden" name="kd_tr_resep_'.$row['kode_brg'].'" value="'.$row['id_fr_tc_far_detail_log'].'" >';
                  echo '<input type="hidden" name="kode_brg_'.$row['kode_brg'].'" value="'.$row['kode_brg'].'" >';

                echo '</td>';
                echo '<td align="center">'.$no.'</td>';
                echo '<td>'.$row['kode_brg'].'</td>';
                $nama_obat = ($row['nama_brg'])?$row['nama_brg']:$row['nama_racikan'];
                echo '<td>'.$nama_obat.'</td>';

                // jumlah
                echo '<td align="center">';
                echo '<input style="width:50px;height:25px;text-align:center" type="text" name="jumlah_'.$row['kode_brg'].'" value="'.$row['jumlah_obat_23'].'" '.$readonly.'>';
                echo '</td>';

                // harga satuan
                echo '<td align="right">';
                  echo number_format($row['harga_jual_satuan']).',-';
                echo '</td>';

                // sub total
                echo '<td align="right">';
                  $subtotal = $row['harga_jual_satuan'] * $row['jumlah_obat_23'];
                  echo number_format($subtotal).',-';
                echo '</td>';

                // Signa
                echo '<td align="left">';
                  echo $row['dosis_obat'].' x '.$row['dosis_per_hari'].' '.ucfirst(strtolower($row['satuan_obat'])).' ('.ucwords($row['anjuran_pakai']).')';
                echo '</td>';
                
                // catatan
                echo '<td align="center">';
                  echo '<input type="text" style="width: 100%" name="catatan_'.$row['kode_brg'].'" '.$readonly.' value="'.$row['catatan_lainnya'].'">' ;
                echo '</td>';
                // aksi
                echo '<td align="center">';
                  
                $hidden = (empty($row['id_fr_tc_far_detail_log'])) ? '' : 'style="display: none"' ;
                  echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$row['kode_brg'].'" onclick="saveRow('."'".$row['kode_brg']."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                  
                  echo '<a href="#" onclick="click_edit('."'".$row['kode_brg']."'".')" id="btn_edit_'.$row['kode_brg'].'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                echo '</td>';

                echo '</tr>';
              else:
                foreach ($row['racikan'][0] as $key => $value) {
                  echo '<tr id="row_kd_brg_'.$value->kode_brg.'">';
                  echo '<td>';
                    echo '<label class="pos-rel">
                              <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="'.$value->id_tc_far_racikan_detail.'" id="checkbox_id_'.$value->id_tc_far_racikan_detail.'" />
                              <span class="lbl"></span>
                          </label>';
                    // hidden form
                    echo '<input type="hidden" name="kd_tr_resep_'.$value->kode_brg.'" value="'.$value->id_tc_far_racikan_detail.'" >';
                    echo '<input type="hidden" name="kode_brg_'.$value->kode_brg.'" value="'.$value->kode_brg.'" >';

                  echo '</td>';
                  echo '<td align="center">'.$no.'</td>';
                  echo '<td>'.$value->kode_brg.'</td>';
                  echo '<td>'.$value->nama_brg.'</td>';

                  // jumlah
                  echo '<td align="center">';
                    echo '<input style="width:50px;height:25px;text-align:center" type="text" name="jumlah_'.$value->kode_brg.'" value="'.$value->jumlah_obat_23.'" '.$readonly.'>';
                  echo '</td>';

                    // harga satuan
                  echo '<td align="right">';
                  echo number_format($value->harga_jual).',-';
                  echo '</td>';
                  
                  // sub total
                  echo '<td align="right">';
                    $subtotal = $value->harga_jual * $value->jumlah_obat_23;
                    echo number_format($subtotal).',-';
                  echo '</td>';
                  
                  // Signa
                  echo '<td align="left">';
                    echo $value->dosis_obat.' x '.$value->dosis_per_hari.' '.ucfirst(strtolower($value->satuan)).' ('.ucwords($value->anjuran_pakai).')';
                  echo '</td>';

                  // catatan
                  echo '<td align="center">';
                    echo '<input type="text" style="width: 100%" name="catatan_'.$value->kode_brg.'" '.$readonly.' value="'.$value->catatan_lainnya.'">' ;
                  echo '</td>';
                  // aksi
                  echo '<td align="center">';
                    
                  $hidden = (empty($value->id_tc_far_racikan_detail)) ? '' : 'style="display: none"' ;
                    echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$value->kode_brg.'" onclick="saveRow('."'".$value->kode_brg."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                    
                    echo '<a href="#" onclick="click_edit('."'".$value->kode_brg."'".')" id="btn_edit_'.$value->kode_brg.'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                  echo '</td>';

                  echo '</tr>';
                }
              endif;
                      
              
            }
          ?>
        </tbody>
      </table>
          

    </form>


  </div>

</div><!-- /.row -->

