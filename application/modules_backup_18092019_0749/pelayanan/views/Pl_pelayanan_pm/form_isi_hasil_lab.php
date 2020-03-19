<script type="text/javascript">

jQuery(function($) {  

  $('.date-picker').datepicker({    

    autoclose: true,    

    todayHighlight: true    

  })  

  //show datepicker when clicking on the icon

  .next().on(ace.click_event, function(){    

    $(this).prev().focus();    

  });  

});

function reset_table(){
    oTable.ajax.url('pelayanan/Pl_pelayanan_lab/get_hasil_pm?kode_penunjang=<?php echo $id?>&mktime=<?php echo $mktime?>').load();
}


function delete_transaksi(myid,type){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/delete',
        type: "post",
        data: {ID:myid,type:type},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            reset_table();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}

function prosesIsiHasilEdit() {
  
  $.ajax({
      url: "pelayanan/Pl_pelayanan_pm/process_isi_hasil",
      data: $('#form_isi_hasil').serialize(),            
      dataType: "json",
      type: "POST",
      success: function (response) {
        if(response.status==200) {
          $.achtung({message: response.message, timeout:5});
          $('.hasil_pm').attr('readonly', true);
          $('.keterangan_pm').attr('readonly', true);
          $('#cetak_isi_hasil').show('fast');
        }else{
          $.achtung({message: response.message, timeout:5});
        }
        
      }
  }); 

}

</script>

<div class="row">

  <?php if(isset($is_edit) AND $is_edit=='Y'): ?>   
  <form class="form-horizontal" method="post" id="form_isi_hasil" action="#" enctype="multipart/form-data" autocomplete="off" >      
  <input type="hidden" name="kode_penunjang" id="kode_penunjang" value="<?php echo ($id)?$id:''?>">
  <?php endif ?>

  <div class="col-md-12">

    <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal</label>
          <div class="col-md-3">
                
            <div class="input-group">
                
                <input name="pl_tgl_pm" id="pl_tgl_pm" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                <span class="input-group-addon">
                  
                  <i class="ace-icon fa fa-calendar"></i>
                
                </span>
              </div>
          
          </div>
    </div>

    <div>
      <table id="dynamic-table" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th>Pemeriksaan</th>
            <th>Detail Item 1</th>
            <th>Detail Item 2</th>
            <th>Nilai Standar</th>
            <th class="center">Hasil</th>
            <th class="center">Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=0; 
          $nama_tindakan='';
          $detail_item_1 = '';
          $detail_item_2 = '';
     
            foreach ($list as $row_list) {

              if($pasien->jen_kelamin=='L'){
                  $nilai_std = $row_list->standar_hasil_pria;
              }else{
                  $nilai_std = $row_list->standar_hasil_wanita;
              }
              $hasil = (isset($row_list->hasil))?stripslashes($row_list->hasil):'';
              $ket = (isset($row_list->keterangan))?$row_list->keterangan:'';
              $kode_tc_hasilpenunjang =  (isset($row_list->kode_tc_hasilpenunjang))?$row_list->kode_tc_hasilpenunjang:0;

              if($row_list->nama_tindakan!=$nama_tindakan){
                echo
                  '<tr>
                    <td colspan="6"><b>'.$row_list->nama_tindakan.'</b></td>
                  </tr>';
                  $nama_tindakan = $row_list->nama_tindakan;
              }
              
                if(($row_list->detail_item_1!=' ') OR ($row_list->detail_item_1=='' OR $row_list->detail_item_1==' ')){

                  echo
                    '<tr>
                      <td>'.$row_list->nama_pemeriksaan.'</td>
                      <td>'.$row_list->detail_item_1.'</td>
                      <td>'.$row_list->detail_item_2.'</td>
                      <td>'. $nilai_std.' '.$row_list->satuan.'</td> 
                      <td width="100px"> <textarea name="hasil_pm'.$i.'" class="hasil_pm" style="overflow:hidden;resize:none">'.$hasil.'</textarea></td>    
                      <td width="100px"><input type="text" name="keterangan_pm'.$i.'" class="keterangan_pm" value="'.$ket.'"></td>           
                    </tr>
                    <input type="hidden" name="kode_tc_hasilpenunjang'.$i.'" value="'.$kode_tc_hasilpenunjang.'" >
                    <input type="hidden" name="kode_mt_hasilpm'.$i.'" value="'.$row_list->kode_mt_hasilpm.'" >
                    <input type="hidden" name="kode_trans_pelayanan'.$i.'" value="'.$row_list->kode_trans_pelayanan.'" >
                    <input type="hidden" name="jumlah_hasilpm" value="'.$i.'" >
                    ';
                  $detail_item_1 = $row_list->detail_item_1;
                  $detail_item_2 = $row_list->detail_item_2;
                  $i++;
                }  
            }
          ?>
        </tbody>
      </table>

      <div class="form-group">
                      
        <label class="control-label col-sm-2">Catatan</label>
        
        <div class="col-md-3">
          
          <textarea name="catatan_hasil" id="catatan_hasil" cols="50" style="height:100px !important;"><?php echo isset($catatan_hasil)?$catatan_hasil:'';?></textarea>
        
        </div>
      
      
      </div>

      <?php if(isset($is_edit) AND $is_edit=='Y'): ?>   
      </form>
      <?php endif ?>

      <div class="form-group">
          <div class="col-sm-8">
              <?php if(isset($is_edit) AND $is_edit=='Y'): ?>
                <a href="#" class="btn btn-xs btn-primary" onclick="prosesIsiHasilEdit()" ><i class="fa fa-save"></i> Submit</a>
              <?php elseif((!isset($is_edit))): if( !isset($is_mcu) OR (isset($is_mcu) AND $is_mcu!=1)){?>
                <button type="submit" href="#" id="btn_submit_isihasil" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Submit</button>
                <?php } endif ?>
              <span id="cetak_isi_hasil" style="display:none">
                <a href="<?php $flag_mcu = isset($is_mcu)?'&flag_mcu=1':''; echo base_url() ?>Templates/Export_data/export?type=pdf&flag=LAB&noreg=<?php echo isset($no_registrasi)?$no_registrasi:''?>&pm=<?php echo ($id)?$id:''?>&kode_pm=050101&no_kunjungan=<?php echo ($no_kunjungan)?$no_kunjungan:''?><?php echo $flag_mcu ?>" target="blank" class="btn btn-xs btn-info" >Cetak Hasil</a>
              </span>
          </div>
      </div>

    </div>

    </div>

  </div>
    

     
</div>







