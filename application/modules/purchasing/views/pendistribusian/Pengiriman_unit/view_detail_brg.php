<script type="text/javascript">

  function checkAll(elm) {

    if($(elm).prop("checked") == true){

      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').each(function(){

        var kode_brg = $(this).val();
        // jumlah dikirim
        $('#input_jml_kirim_'+kode_brg+'').val( $('#stok_akhir_'+kode_brg+'').text() );
        // max input
        $('#input_jml_kirim_'+kode_brg+'').attr( 'max', $('#stok_akhir_'+kode_brg+'').text() );
        // total
        var total = parseInt($('#input_jml_kirim_'+kode_brg+'').val()) * parseInt($('#harga_beli_'+kode_brg+'').val());
        $('#total_harga_'+kode_brg+'').text( formatMoney(total) );
        $('#total_harga_val_'+kode_brg+'').val( total );
        // checked
        $(this).prop("checked", true);    
        $('#input_jml_kirim_'+kode_brg+'').attr( 'disabled', false );    

      });

    }else{

      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').prop("checked", false);
      $('.checkbox_brg_<?php echo $flag?>_<?php echo $id?>').each(function(){
        var kode_brg = $(this).val();
        $('#input_jml_kirim_'+kode_brg+'').val( '' );
        $('#input_jml_kirim_'+kode_brg+'').attr( 'max', false );
        $('#total_harga_'+kode_brg+'').text(formatMoney(0));
        $('#total_harga_val_'+kode_brg+'').val( 0 );
        $(this).prop("checked", false);
        $('#input_jml_kirim_'+kode_brg+'').attr( 'disabled', true );    
      });

    }

    sum_total = sumClass('total_harga_val');
    $('#total_all').text( formatMoney(sum_total) );
  }

  function checkOne(elm) {

    var kode_brg = $(elm).val();

    if($(elm).prop("checked") == true){

        // jumlah dikirim
        $('#input_jml_kirim_'+kode_brg+'').val( $('#stok_akhir_'+kode_brg+'').text() );
        // max input
        $('#input_jml_kirim_'+kode_brg+'').attr( 'max', $('#stok_akhir_'+kode_brg+'').text() );
        // total
        var total = parseInt($('#input_jml_kirim_'+kode_brg+'').val()) * parseInt($('#harga_beli_'+kode_brg+'').val());
        $('#total_harga_'+kode_brg+'').text( formatMoney(total) );
        $('#total_harga_val_'+kode_brg+'').val( total );
        // checked
        $(elm).prop("checked", true);
        $('#input_jml_kirim_'+kode_brg+'').attr( 'disabled', false );    

    }else{

        $(elm).prop("checked", false);
        $('#input_jml_kirim_'+kode_brg+'').attr( 'disabled', true );    
        var kode_brg = $(elm).val();
        $('#input_jml_kirim_'+kode_brg+'').val( '' );
        $('#input_jml_kirim_'+kode_brg+'').attr( 'max', false );
        $('#total_harga_'+kode_brg+'').text( formatMoney(0) );
        $('#total_harga_val_'+kode_brg+'').val( 0 );

    }

    sum_total = sumClass('total_harga_val');
    $('#total_all').text( formatMoney(sum_total) );

  }

  function updateTotal(kode_brg){
    // total
    var total = parseInt($('#input_jml_kirim_'+kode_brg+'').val()) * parseInt($('#harga_beli_'+kode_brg+'').val());
    $('#total_harga_'+kode_brg+'').text( formatMoney(total) );
    $('#total_harga_val_'+kode_brg+'').val( total );
    sum_total = sumClass('total_harga_val');
    $('#total_all').text( formatMoney(sum_total) );
  }

</script>

<div class="row">
  <div class="col-xs-12">

    <table class="table table-bordered table-hover" style="font-size:12px;" width="100%">
      <tr style="background-color: #428bca; color: white; ">
        <th class="center" width="35px"><input type="checkbox" onClick="checkAll(this);" style="cursor:pointer" ></th>
        <th class="center" width="40px">No</th>
        <th width="100px">Image</th>
        <th width="200px">Deskripsi Barang</th>
        <th class="center" width="100px">Stok Minimum</th>
        <th class="center" width="100px">Stok Akhir</th>
        <th class="center" width="100px">Satuan Kecil</th>
        <th class="center" width="100px">Harga Satuan</th>
        <th class="center" width="120px">Jumlah Dikirim</th>
        <th class="center" width="100px">Total</th>
      </tr>
      <?php 
        $no=0; 
        foreach($value as $row_dt) :
          $no++;
          $link_image = ( $row_dt->path_image != NULL ) ? PATH_IMG_MST_BRG.$row_dt->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
      ?>
        <tr style="border: 1px solid #ccc9c9; border-collapse: collapse">
          <td class="center">
            <?php if( $row_dt->jml_sat_kcl != 0 ) : ?>
            <input type="checkbox" class="checkbox_brg_<?php echo $flag?>_<?php echo $id?>" id="checkbox_brg_<?php echo $flag?>_<?php echo $row_dt->kode_brg?>_<?php echo $row_dt->kode_brg?>" class="form-control" value="<?php echo $row_dt->kode_brg?>" onClick="checkOne(this);" style="cursor:pointer" name="kode_brg[]">
            <?php else: echo '<i class="fa fa-times-circle bigger-120 red"></i>' ; endif; ?>
          </td>
          <td class="center"><?php echo $no?></td>
          <td><a href="<?php echo base_url().$link_image?>" target="_blank"><img src="<?php echo base_url().$link_image?>" width="100px"></a></td>
          <td>
            <?php 
              echo '<b>'.$row_dt->kode_brg.'</b><br>'.$row_dt->nama_brg.'<br>Spesifikasi :<br><span>'.$row_dt->spesifikasi.'</span>';
            ?>
            
          </td>
          <td class="center"><?php echo $row_dt->stok_minimum?></td>
          <td class="center" id="stok_akhir_<?php echo $row_dt->kode_brg?>"><?php echo $row_dt->jml_sat_kcl?></td>           
          <td class="center">
            <?php echo $row_dt->satuan_kecil?>
            <input type="hidden" value="<?php echo $row_dt->satuan_kecil?>" name="satuan[<?php echo $row_dt->kode_brg?>]" >
          </td>           
          <td align="right">
            <?php echo number_format($row_dt->harga_beli)?>
            <input type="hidden" value="<?php echo $row_dt->harga_beli?>" name="harga_beli[<?php echo $row_dt->kode_brg?>]" id="harga_beli_<?php echo $row_dt->kode_brg?>">
          </td>    
          <td class="center">
            <?php if($row_dt->jml_sat_kcl != 0 ) :?>
            <input type="number" max="3" name="total_dikirim[<?php echo $row_dt->kode_brg?>]" style="width:100px; text-align: center; height: 35px !important; font-size: 18px" value="" id="input_jml_kirim_<?php echo $row_dt->kode_brg?>" onchange="updateTotal('<?php echo $row_dt->kode_brg?>')" disabled>
            <?php else: echo '<span class="red"><b>-Stok Habis-</b></span>'; endif; ?>
          </td>
          <td align="right">
            <input type="hidden" class="total_harga_val" id="total_harga_val_<?php echo $row_dt->kode_brg?>" name="total_harga_val[<?php echo $row_dt->kode_brg?>]">
            <span id="total_harga_<?php echo $row_dt->kode_brg?>"></span>
          </td>
        </tr>
      <?php endforeach;?>

    </table>

    <div class="pull-right">
          <h2>Total Rp. <span id="total_all">0</span>,-</h2>
    </div>

  </div><!-- /.col -->
</div><!-- /.row -->


