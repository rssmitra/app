<script>

$(document).ready(function(){

  $('#submit_periksa').click(function (e) {   

      if(confirm('Apakah anda yakin periksa?')){
        e.preventDefault();
        achtungShowLoader();

        $.ajax({
          url: "pelayanan/Pl_pelayanan_mcu/process_periksa_bagian",
          data: $('#form_periksa_bagian').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
              
            if(response.status==200) {

              achtungHideLoader();
            
              $.achtung({message: response.message, timeout:5});
          
              $("#page-area-content").load("pelayanan/Pl_pelayanan_mcu/form/"+response.id_pl_tc_poli+"/"+response.no_kunjungan+"")

              $('.modal').modal('hide');
              
            }

            console.log(response)
          }
        });

      }else{
        return false;
      }

  });

});    

</script>
<div class="row">

<form class="form-horizontal" method="post" id="form_periksa_bagian" action="#" enctype="multipart/form-data" autocomplete="off" >      
    <!-- div.dataTables_borderWrap -->
    <div>
      <table id="dynamic-table" base-url="pelayanan/Pl_pelayanan_mcu" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="70%">Nama Tindakan</th>
          <th>Dokter</th>          
        </tr>
      </thead>
      <tbody>
        <input type="hidden" value="<?php echo isset($no_registrasi)?$no_registrasi:0 ?>" name="no_registrasi" id="no_registrasi">
          <?php $i=0; 

              foreach ($group_bag as $key_gb=>$row_gb) {
                
                  echo '<tr>';
                  echo '<td><b>'.$key_gb.'</b></td>';
                  echo '<td>';
                    if(isset($row_gb[0]->dokter)){
                      if($row_gb[0]->kode_bagian=='010901'){
                        echo $dokter_asal;
                        echo '<input name="dokter['.$row_gb[0]->kode_bagian.']" type="hidden" value="'.$kode_dokter_asal.'">';
                      }else{
                        echo'
                          <select class="form-control" name="dokter['.$row_gb[0]->kode_bagian.']" id="dokter['.$row_gb[0]->id_mt_mcu_detail.']" >
                          ';
                          $sel='';
                          foreach($row_gb[0]->dokter as $row):
                            if($row_gb[0]->kode_bagian=='050101')$sel=$row->kode_dokter==55?'selected':'';
                            if($row_gb[0]->kode_bagian=='050201')$sel=$row->kode_dokter==39?'selected':'';
                            echo '<option value="'.$row->kode_dokter.'" '.$sel.'>'.strtoupper($row->nama_pegawai).'</option>';
                          endforeach;
                        echo '</select>';
                      }
                    }
                  echo '</td>';
                  echo '</tr>';

                  foreach($row_gb as $key_list=>$row_list){
                    echo'<tr>';
                    echo '<td colspan="2">'.$row_list->nama_tindakan.'</td>';   
                    if($row_list->kode_bagian=='010901') {
                      echo '<input name="dokter['.$row_list->kode_bagian.']" type="hidden" value="'.$kode_dokter_asal.'">';
                    }
                    echo '</tr>'; 
                    // hidden form
                    echo '<input type="hidden" value="'.$row_list->id_mt_mcu_detail.'" name="id_mt_mcu_detail['.$row_gb[0]->kode_bagian.'][]" id="id_mt_mcu_detail['.$row_list->id_mt_mcu_detail.']">';  
                  }
                  
                  echo '<input type="hidden" name="bagian_tujuan[]" value="'.$row_gb[0]->kode_bagian.'">';
              }
                
          ?>
            <input type="hidden" id="" name="no_kunjungan" value="<?=$no_kunjungan?>" />
            <input type="hidden" id="" name="no_registrasi" value="<?=$no_registrasi?>" />
            <input type="hidden" id="" name="no_mr" value="<?=$no_mr?>" />
            <input type="hidden" id="" name="kode_bagian_asal" value="010901" />
            <input type="hidden" id="" name="kode_gcu" value="<?=$kode_gcu?>" />
            <input type="hidden" id="" name="kode_tarif" value="<?=$kode_tarif?>" />
            <input type="hidden" id="" name="kode_dokter_asal" value="<?=$kode_dokter_asal?>" />
            <input type="hidden" id="" name="dokter_asal" value="<?=$dokter_asal?>" />
            <input type="hidden" id="" name="nama_pasien_hidden" value="<?=$nama_pasien_hidden?>" />
            <input type="hidden" id="" name="id_pl_tc_poli" value="<?=$id_pl_tc_poli?>" />
        </form>

      </tbody>
    </table>
    </div>
    <center><a href="#" id="submit_periksa" class="btn btn-xs btn-primary"><i class="fa fa-check-circle"></i> Submit</a></center>         
    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<!-- <script src="<?php echo base_url().'assets/js/custom/als_datatable_no_style.js'?>"></script> -->



