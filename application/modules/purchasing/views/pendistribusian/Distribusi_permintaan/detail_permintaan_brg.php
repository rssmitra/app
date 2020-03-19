<p><b>DAFTAR PERMINTAAN BARANG <a href="#" onclick="hide_this_div('div_daftar_permintaan_brg')"><i class="fa fa-external-link bigger-120"></i></a> </b></p>
<!-- hidden -->
<input type="hidden" value="<?php echo $id_tc_permintaan_inst?>" name="id_tc_permintaan_inst" id="id_tc_permintaan_inst">

<table class="table" style="width: 100% !important">
<thead>
    <tr style="background-color: #e4e7e8;color: #0a0a0a;">
    <td class="center">No</td>
    <td>Kode</td>
    <td>Nama Barang</td>
    <td class="center">Stok Akhir</td>
    <td class="center">Jumlah Permintaan<br>(Satuan Kecil)</td>
    <td class="center">Rasio</td>
    <td class="center">Konversi<br>(Satuan Besar)</td>
    <td class="center">Hapus</td>
    </tr>
</thead>
<tbody>
    <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
        <tr id="tr_id_<?php echo $row_dt->id_tc_permintaan_inst_det?>">
        <td class="center"><?php echo $no?></td>
        <td><?php echo $row_dt->kode_brg?></td>
        <td><?php echo $row_dt->nama_brg?></td>
        <td class="center"><?php echo $row_dt->jumlah_stok_sebelumnya.'&nbsp;'.$row_dt->satuan_kecil?></td>
        <td class="center"><?php echo $row_dt->jumlah_permintaan.' '.$row_dt->satuan_kecil?></td>
        <td class="center"><?php echo $row_dt->rasio?></td>
        <td class="center"><?php $konversi = $row_dt->jumlah_permintaan * $row_dt->rasio; echo $konversi.' '.$row_dt->satuan_kecil; ?></td>
        <td class="center"><a href="#" onclick="delete_row(<?php echo $row_dt->id_tc_permintaan_inst_det?>)" ><i class="fa fa-times-circle-o bigger-120 red"></i></td>
        </tr>
    <?php endforeach;?>
</tbody>
</table>

<script type="text/javascript">
    function delete_row(id){
        preventDefault();
        $('#tr_id_'+id+'').hide();
        $.ajax({
          url: 'purchasing/pendistribusian/Distribusi_permintaan/delete_row_brg',
          type: "post",
          data: {ID: id, flag: $('#flag_string').val(), id_tc_permintaan_inst: $('#id_tc_permintaan_inst').val() },
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
</script>