<div class="pull-left"><p><b>DAFTAR PERMINTAAN BARANG </b></p>
</div>
<div class="pull-right">
  <a href="#" onclick="save_as_template()" class="btn btn-xs btn-primary" style="color: black"><i class="fa fa-save bigger-120"></i> Simpan sebagai template</a>
  <a href="#" class="btn btn-xs btn-warning" onclick="hide_this_div('show_detail_selected_brg')" style="color: black"><i class="fa fa-external-link bigger-120"></i> Sembunyikan</a>
</div>
<script>

    function checkAll(elm) {

        if($(elm).prop("checked") == true){
          $('.checkbox_brg').prop("checked", true);
        }else{
          $('.checkbox_brg').prop("checked", false);
        }

    }

    function delete_row(id, free_text = 0){
      preventDefault();
      $('#tr_id_'+id+'').hide();
      $.ajax({
        url: 'purchasing/permintaan/Req_selected_detail_brg/delete',
        type: "post",
        data: {ID: id, flag: $('#flag_string').val(), id_tc_permohonan: $('#id_tc_permohonan').val(), is_free_text: free_text},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          // response after process
          $('#total_brg').text( data.total_brg );
          preventDefault();
          get_detail_permintaan_brg();
        }
      });
        
    }

    function update_row(id, free_text = 0){
      // alert(id); 
        var post_data = {
          id : id,
          flag : $('#flag_string').val(),
          jml_besar : $('#jml_besar_'+id+'').val(),
          keterangan : $('#keterangan_'+id+'').val(),
          is_free_text : free_text,
        };
        preventDefault();
        $.ajax({
          url: 'purchasing/permintaan/Req_selected_detail_brg/update_row',
          type: "post",
          data: post_data,
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(data) {
            achtungHideLoader();
            // response after process
            preventDefault();
            get_detail_permintaan_brg();
          }
        });
        
    }

    function save_as_template(){

        var searchIDs = $("#table-permintaan-brg input:checkbox:checked").map(function(){
            if ($(this).val() != '') { return $(this).val(); }
        }).toArray();

        var result = [];
        for (var i = 0; i < searchIDs.length; i++) {
            result[i] = {
              id : searchIDs[i],
              
              kode_brg  : $('#kode_brg_'+searchIDs[i]+'').val(),
              nama_brg  : $('#nama_brg_'+searchIDs[i]+'').val(),
              satuan_besar  : $('#satuan_besar_'+searchIDs[i]+'').val(),
              rasio  : $('#rasio_'+searchIDs[i]+'').val(),
              jml_besar  : $('#jml_besar_'+searchIDs[i]+'').val(),
              keterangan : $('#keterangan_'+searchIDs[i]+'').val(),
            };
        }

        console.log(result); 

        preventDefault();
        $.ajax({
          url: 'purchasing/permintaan/Req_selected_detail_brg/save_as_template',
          type: "post",
          data: {id_tc_permohonan : $('#id_tc_permohonan').val(), flag : $('#flag_string').val(), post : result},
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);

            if(jsonResponse.status === 200){
              $.achtung({message: jsonResponse.message, timeout:5});
              load_template();
              get_detail_permintaan_brg();
            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }
        });
        
    }

</script>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <table id="table-permintaan-brg" class="table table-hovered table-bordered"  style="font-size:11px">
      <thead>
      <tr style="background-image: linear-gradient(to bottom, #c7cccb 90%, #61605f 30%)">
        <th class="center" width="35px">
          <div class="center">
            <label class="pos-rel">
                <input type="checkbox" class="ace" name="selected_id[]" onClick="checkAll(this);" value="" checked/>
                <span class="lbl"></span>
            </label>
          </div>
        </th>
        <th class="center" width="35px">No</th>
        <th  width="100px">Kode Barang</th>
        <th>Nama Barang</th>
        <th>Spesifikasi</th>
        <th>Est Harga</th>
        <th>Stok Akhir</th>
        <th class="center" width="80px">Jml Permintaan</th>
        <th class="center" width="80px">Satuan Besar</th>
        <th class="center" width="80px">Rasio</th>
        <th class="center" width="80px">Konversi</th>
        <th class="center" width="80px">Keterangan</th>
        <th class="center" width="80px">Aksi</th>
      </tr>
      </thead>
      <tbody>
        <?php 
          $no=0; 
          if( count($dt_detail_brg) > 0 ) : 
            foreach($dt_detail_brg as $row_dt) : $no++;
            $rasio = isset($row_dt['rasio'])?$row_dt['rasio']:1;
            $satuan = isset($row_dt['satuan_kecil'])?$row_dt['satuan_kecil']:$row_dt['satuan_besar'];
            $konversi = $row_dt['jml_besar'] * $rasio; 
        ?>
        <tr id="tr_<?php echo isset($row_dt['kode_brg'])?$row_dt['kode_brg']:$row_dt['id']?>" style="border-right: 1px solid #8080807a">
          <td class="center" width="35px" style="border-left: 1px solid #8080807a">
            <div class="center">
              <label class="pos-rel">
                  <input type="checkbox" class="ace checkbox_brg" name="selected_id[]" id="<?php echo isset($row_dt['kode_brg'])?$row_dt['kode_brg']:$row_dt['id']?>" value="<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>" checked/>
                  <span class="lbl"></span>
              </label>
               <!-- hidden form -->
              <input type="hidden" name="id_tc_permohonan_det['<?php echo isset($row_dt['kode_brg'])?$row_dt['kode_brg']:$row_dt['id']?>']" value="<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>" class="primary_key" id="id_tc_permohonan_det_<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>">
            </div>
          </td>
          <td class="center"><?php echo $no?></td>
          <td>
            <?php echo isset($row_dt['kode_brg'])?$row_dt['kode_brg']:'<span class="red">[free text]</span>'?>
            <!-- hidden form -->
            <input type="hidden" name="kode_brg['<?php echo isset($row_dt['kode_brg'])?$row_dt['kode_brg']:$row_dt['id']?>']" value="<?php echo isset($row_dt['kode_brg'])?$row_dt['kode_brg']:$row_dt['id']?>" id="kode_brg_<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>">
          </td>
          <td>
            <?php echo $row_dt['nama_brg']?>
            <!-- hidden form -->
            <input type="hidden" name="nama_brg['<?php echo $row_dt['nama_brg']?>']" value="<?php echo $row_dt['nama_brg']?>" id="nama_brg_<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>">
          </td>

          <td><?php echo $row_dt['spesifikasi']?></td>
          <td align="right"><?php echo isset($row_dt['estimasi_harga']) ? number_format($row_dt['estimasi_harga']) : 0?></td>
          <td align="center"><?php echo isset($row_dt['stok_gudang']) ? number_format($row_dt['stok_gudang']) : 0?> <?php echo isset($row_dt['satuan_kecil']) ? $row_dt['satuan_kecil'] : 0?></td>

          <td class="center">
            <?php if(isset($row_dt['id_tc_permohonan_det'])) : ?>
              <input type="text" name="jml_besar['<?php echo isset($row_dt['kode_brg'])?$row_dt['kode_brg']:$row_dt['id']?>']" value="<?php echo number_format($row_dt['jml_besar'], 2)?>" style="text-align: right; width: 80px" id="jml_besar_<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>" onchange="update_row(<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>)">
            <?php else: ?>
              <input type="text" name="jml_besar['<?php echo $row_dt['id']?>']" value="<?php echo number_format($row_dt['jml_besar'], 2)?>" style="text-align: right; width: 80px" id="jml_besar_<?php echo $row_dt['id']?>" onchange="update_row(<?php echo $row_dt['id']?>, 1)">
            <?php endif; ?>

          </td>
          <td class="center">
            <?php echo $row_dt['satuan_besar']?>
            <!-- hidden form -->
            <input type="hidden" name="satuan_besar['<?php echo $row_dt['satuan_besar']?>']" value="<?php echo $row_dt['satuan_besar']?>" id="satuan_besar_<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>">
          </td>
          <td class="center">
            <?php echo $rasio?>
            <!-- hidden form -->
            <input type="hidden" name="rasio['<?php echo $rasio?>']" value="<?php echo $rasio?>" id="rasio_<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>">
          </td>

          <td class="center">
            <?php echo number_format($konversi)?> <?php echo isset($row_dt['satuan_kecil']) ? $row_dt['satuan_kecil'] : $row_dt['satuan_besar']?>
          </td>
          <td class="center">
            <?php if(isset($row_dt['id_tc_permohonan_det'])) : ?>  
              <input type="text" id="keterangan_<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>" style="width: 150px" value="<?php echo $row_dt['keterangan']?>" onchange="update_row(<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>)">
            <?php else: ?>
              <input type="text" id="keterangan_<?php echo $row_dt['id']?>" style="width: 150px" value="<?php echo $row_dt['keterangan']?>" onchange="update_row(<?php echo $row_dt['id']?>, 1)">
            <?php endif; ?>


          </td>
          <td class="center">
            <?php if(isset($row_dt['id_tc_permohonan_det'])) : ?>
            <a  href="#" class="btn btn-xs btn-danger" onclick="delete_row(<?php echo isset($row_dt['id_tc_permohonan_det'])?$row_dt['id_tc_permohonan_det']:$row_dt['id']?>)">
                <i class="ace-icon fa fa-trash icon-on-right bigger-110"></i>
            </a>
            <?php else: ?>
              <a  href="#" class="btn btn-xs btn-danger" onclick="delete_row(<?php echo $row_dt['id']?>, 1)">
                <i class="ace-icon fa fa-trash icon-on-right bigger-110"></i>
            </a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; else: echo '<tr><td colspan="8">Tidak ada barang ditemukan</td></tr>'; endif; ?>
        <tr>
          <td colspan="9" style="border-top: 1px solid red"></td>
        </tr>
      </tbody>
    </table>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


