<script>

  function checkAll(elm) {

      if($(elm).prop("checked") == true){
        $('.checkbox_brg').prop("checked", true);
      }else{
        $('.checkbox_brg').prop("checked", false);
      }

  }

  function loadDataFromTemplate(temp_name){
    preventDefault();
    $('#show_detail_selected_brg').load('purchasing/permintaan/Req_pembelian/load_data_from_template?name='+temp_name+'&flag='+$('#flag_string').val()+'');
  }

  function delete_template(id){
    preventDefault();
    $.ajax({
        url: 'purchasing/permintaan/Req_pembelian/delete_template',
        type: "post",
        data: {ID: id},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          var temp_name = $('#title_temp').val();
          console.log(temp_name);
          loadDataFromTemplate(temp_name);
          load_template();
        }
      });
  }

  function save_permintaan_from_template(){

    var searchIDs = $("#table-template-permintaan-brg input:checkbox:checked").map(function(){
        if ($(this).val() != '') { return $(this).val(); }
    }).toArray();
    
    var result = [];
    for (var i = 0; i < searchIDs.length; i++) {
        result[i] = {
          id : searchIDs[i],
          kode_brg  : $('#kode_brg_'+searchIDs[i]+'').val(),
          jml_besar  : $('#jml_besar_'+searchIDs[i]+'').val(),
          satuan_besar  : $('#satuan_besar_'+searchIDs[i]+'').val(),
          rasio  : $('#rasio_'+searchIDs[i]+'').val(),
          keterangan : $('#keterangan_'+searchIDs[i]+'').val(),
        };
    }

    console.log(result); 

    preventDefault();
    $.ajax({
      url: 'purchasing/permintaan/Req_selected_detail_brg/save_permintaan_from_template',
      type: "post",
      data: {id_tc_permohonan: $('#id_tc_permohonan').val(), reff_id : $('#reff_id_temp').val(), flag : $('#flag_string').val(), post : result},
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          $('#total_brg').text( jsonResponse.total_brg );
          get_detail_permintaan_brg();
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    });

  }


</script>
<div class="row">
  <div class="col-xs-12">
      <!-- PAGE CONTENT BEGINS -->
      <div class="alert alert-info">
        <strong>Pemberitahuan !</strong> 
        <i>*Simpan terlebih dahulu data anda untuk menghindari kehilangan data.</i>
      </div>
      <div class="pull-right" style="margin-bottom: -20px">
        <a href="#" onclick="save_permintaan_from_template()" class="label label-primary" style="color: black"><i class="fa fa-save bigger-120"></i> Simpan Permintaan</a>
        <a href="#" class="label label-success" onclick="hide_this_div('show_detail_selected_brg')" style="color: black"><i class="fa fa-external-link bigger-120"></i> Update Template</a>
        
        <!-- <a  href="#" class="btn btn-xs btn-primary" style="margin-left: -0px">
            <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
            Simpan Permintaan
        </a> -->

      </div>
      <h4><?php echo isset( $temp_data[0] ) ? $temp_data[0]->temp_name : 'Tidak ada data' ;?></h4>
      <!-- hidden form -->
      <input type="hidden" value="<?php echo isset( $temp_data[0] ) ? str_replace(' ','-',$temp_data[0]->temp_name) : 'Tidak ada data' ;?>" id="title_temp">
      <input type="hidden" value="<?php echo isset( $temp_data[0] ) ? $temp_data[0]->reff_id : '0' ;?>" id="reff_id_temp">

      <table id="table-template-permintaan-brg" class="table table-bordered table-hovered" style="font-size:11px">
        <tr>
          <th class="center" width="35px">
            <div class="center">
              <label class="pos-rel">
                  <input type="checkbox" class="ace" name="selected_id[]" onClick="checkAll(this);" value=""/>
                  <span class="lbl"></span>
              </label>
            </div>
          </th>
          <th class="center" width="35px">No</th>
          <th  width="120px">Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center" width="80px">Jumlah Permintaan</th>
          <th class="center" width="80px">Satuan Besar</th>
          <th class="center" width="80px">Rasio</th>
          <th class="center" width="80px">Keterangan</th>
          <th class="center" width="80px">Aksi</th>
        </tr>
        <?php 
          $no=0; 
          if( count($temp_data) > 0 ) :
          foreach($temp_data as $row_dt) : $no++
        ?>
          <tr>
            <td class="center" width="35px">
              <div class="center">
                <label class="pos-rel">
                    <input type="checkbox" class="ace checkbox_brg" name="selected_id[]" value="<?php echo $row_dt->id_tc_permohonan_temp?>"/>
                    <span class="lbl"></span>
                </label>
              </div>
            </td>
            <td class="center"><?php echo $no?></td>
            <td>
              <?php echo $row_dt->kode_brg?>
              <!-- hidden form -->
              <input type="hidden" name="kode_brg['<?php echo $row_dt->kode_brg?>']" value="<?php echo $row_dt->kode_brg?>" id="kode_brg_<?php echo $row_dt->id_tc_permohonan_temp?>">
            </td>
            <td><?php echo $row_dt->nama_brg?></td>

            <td class="center">
              <input type="text" name="jml_besar['<?php echo $row_dt->kode_brg?>']" value="<?php echo number_format($row_dt->jml_besar, 2)?>" style="text-align: right; width: 80px" id="jml_besar_<?php echo $row_dt->id_tc_permohonan_temp?>" onchange="update_row(<?php echo $row_dt->id_tc_permohonan_temp?>)">
            </td>
            
            <td class="center">
              <?php echo $row_dt->satuan_besar?>
              <!-- hidden form -->
              <input type="hidden" name="satuan_besar['<?php echo $row_dt->satuan_besar?>']" value="<?php echo $row_dt->satuan_besar?>" id="satuan_besar_<?php echo $row_dt->id_tc_permohonan_temp?>">
            </td>

            <td class="center">
              <?php echo $row_dt->rasio?>
              <!-- hidden form -->
              <input type="hidden" name="rasio['<?php echo $row_dt->rasio?>']" value="<?php echo $row_dt->rasio?>" id="rasio_<?php echo $row_dt->id_tc_permohonan_temp?>">
            </td>
            
            <td class="center">
              <input type="text" id="keterangan_<?php echo $row_dt->id_tc_permohonan_temp?>" style="width: 150px" value="<?php echo $row_dt->keterangan?>" onchange="update_row(<?php echo $row_dt->id_tc_permohonan_temp?>)">
            </td>

            <td class="center">
              <a  href="#" class="btn btn-xs btn-danger" onclick="delete_template(<?php echo $row_dt->id_tc_permohonan_temp?>)">
                  <i class="ace-icon fa fa-trash icon-on-right bigger-110"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; else: echo '<tr><td colspan="8">Tidak ada barang ditemukan</td></tr>'; endif; ?>
      </table>
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


