<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script>
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

$(document).ready(function(){
  
    $('#form-default').ajaxForm({
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
          $('#page-area-content').load('inventory/master/Inv_master_barang?flag='+$('#flag_string').val());
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 
})

$('select[name="kode_golongan"]').change(function () {  
    /*flag string*/
    flag_string = $('#flag_string').val();
    if ( $(this).val() ) {     
      
        $.getJSON("<?php echo site_url('Templates/References/getSubGolongan') ?>/" + $(this).val() + '?flag=' +flag_string, '', function (data) {   
            $('#kode_sub_gol option').remove();         
            $('<option value="">-Pilih Sub Golongan-</option>').appendTo($('#kode_sub_gol'));  
            $.each(data, function (i, o) {   
                $('<option value="' + o.kode_sub_gol + '">' + o.nama_sub_golongan.toUpperCase() + '</option>').appendTo($('#kode_sub_gol'));  
            });   
        });   
    } else {    
        $('#kode_sub_gol option').remove();
    }    
}); 

$('select[name="kode_sub_gol"]').change(function () {  
    /*flag string*/
    flag_string = $('#flag_string').val();
    if ( $(this).val() ) {     
      
        $.getJSON("<?php echo site_url('Templates/References/getGenerik') ?>/" + $(this).val() + '?flag=' +flag_string, '', function (data) {   
            $('#kode_generik option').remove();         
            $('<option value="">-Pilih Generik-</option>').appendTo($('#kode_generik'));  
            $.each(data, function (i, o) {   
                $('<option value="' + o.kode_generik + '">' + o.nama_generik.toUpperCase() + '</option>').appendTo($('#kode_generik'));  
            });   
        });   
    } else {    
        $('#kode_generik option').remove();
    }    
}); 

$('select[name="id_profit"]').change(function () {  
    if ( $(this).val() ) {    
        $('#table_profit_detail tbody td').remove()
        $.getJSON("<?php echo site_url('Templates/References/getDetailProfit') ?>/" + $(this).val() + '?kategori=' +$('#kode_kategori').val(), '', function (data) {   
            // content here
            if( $('#kode_kategori').val() == 'D' ){
              var profit = parseFloat(data.profit_obat).toFixed(1);
            }else{
              var profit = parseFloat(data.profit_alkes).toFixed(1);
            }

            $('<tr><td>'+data.kode_profit+'</td><td>'+data.nama_pelayanan+'</td><td align="center">'+profit+' % </td></tr>').appendTo($('#table_profit_detail tbody'));
            $('#margin_percent').val(profit);
        });   
    }  
}); 

$('select[name="kode_generik"]').change(function () {  
    if ( $(this).val() ) { 
      $.getJSON("<?php echo site_url('Templates/References/getKodeBrg') ?>/" + $(this).val() + '?flag=' +$('#flag_string').val(), '', function (data) {   
          // content here
          $('#id').val(data.kode);
      });  
    }  
}); 

$('select[name="kode_sub_gol"]').change(function () {  
    if ( $(this).val() ) { 
      $.getJSON("<?php echo site_url('Templates/References/getKodeBrg') ?>/" + $(this).val() + '?flag=' +$('#flag_string').val(), '', function (data) {   
          // content here
          if( $('#old_id').val() != '' ){
            $('#id').val( $('#old_id').val() );
          }else{
            $('#id').val(data.kode);
          }
      });  
    }  
}); 


</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
          <div class="widget-body">
            <div class="widget-main no-padding">
              <form class="form-horizontal" method="post" id="form-default" action="<?php echo site_url('inventory/master/Inv_master_barang/process?flag='.$flag_string.'')?>" enctype="multipart/form-data">
                <br>
                <!-- input form hidden -->
                <input type="hidden" name="flag" id="flag_string" value="<?php echo $flag_string?>">

                <div class="form-group">
                  <label class="control-label col-md-2">Kategori</label>
                  <div class="col-md-2">
                      <?php 
                        $t_kategori = ( $flag_string == 'medis' ) ? 'mt_kategori' : 'mt_kategori_nm' ;
                        echo $this->master->custom_selection($params = array('table' => $t_kategori, 'id' => 'kode_kategori', 'name' => 'nama_kategori', 'where' => array()), isset($value->kode_kategori)?$value->kode_kategori:'' , 'kode_kategori', 'kode_kategori', 'form-control', '', '') ?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Golongan</label>
                  <div class="col-md-3">
                      <?php 
                        $table_gol = ( $flag_string == 'medis' ) ? 'mt_golongan' : 'mt_golongan_nm' ;
                        echo $this->master->custom_selection($params = array('table' => $table_gol, 'id' => 'kode_golongan', 'name' => 'nama_golongan', 'where' => array()), isset($value->kode_golongan)?$value->kode_golongan:'' , 'kode_golongan', 'kode_golongan', 'form-control', '',  ($flag=='read')?'readonly':'') ?>
                  </div>
                  <label class="control-label col-md-2">Sub Golongan</label>
                  <div class="col-md-3">
                      <?php 
                        $table_sub_gol = ( $flag_string == 'medis' ) ? 'mt_sub_golongan' : 'mt_sub_golongan_nm' ;
                        echo $this->master->get_change($params = array('table' => $table_sub_gol, 'id' => 'kode_sub_gol', 'name' => 'nama_sub_golongan', 'where' => array()),  isset($value->kode_sub_golongan)?$value->kode_sub_golongan:'' , 'kode_sub_gol', 'kode_sub_gol', 'form-control', '',  ($flag=='read')?'readonly':'') ?>
                  </div>
                </div>

                <?php if( $flag_string == 'medis' ) : ?>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">Generik</label>
                    <div class="col-md-3">
                    <?php 
                        echo $this->master->get_change($params = array('table' => 'mt_generik', 'id' => 'kode_generik', 'name' => 'nama_generik', 'where' => array()), isset($value->kode_generik)?$value->kode_generik:'' , 'kode_generik', 'kode_generik', 'form-control', '',  ($flag=='read')?'readonly':'') ?>
                    </div>
                  </div>
                <?php endif;?>

                <div class="form-group">
                  <label class="control-label col-md-2">Kode Barang</label>
                  <div class="col-md-2">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->kode_brg:0?>" placeholder="Auto" class="form-control" type="text" readonly>
                    <input name="old_id" id="old_id" value="<?php echo isset($value)?$value->kode_brg:''?>" placeholder="Auto" class="form-control" type="hidden">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2">Nama Barang</label>
                  <div class="col-md-3">
                    <input name="nama_brg" id="nama_brg" value="<?php echo isset($value)?$value->nama_brg:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2">Stok Min</label>
                  <div class="col-md-1">
                    <input name="stok_minimum" id="stok_minimum" value="<?php echo isset($value)?$value->stok_minimum:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                  <label class="control-label col-md-1">Stok Maks</label>
                  <div class="col-md-1">
                    <input name="stok_maksimum" id="stok_maksimum" value="<?php echo isset($value)?$value->stok_maksimum:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                  <label class="control-label col-md-2">Harga Pembelian Terakhir</label>
                    <div class="col-md-2">
                      <input name="harga_beli" id="harga_beli" value="<?php echo isset($value)?$value->harga_beli:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                    </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Pabrikan</label>
                  <div class="col-md-3">
                      <?php 
                        $t_pabrik = ( $flag_string == 'medis' ) ? 'mt_pabrik' : 'mt_pabrik_nm' ;
                        echo $this->master->custom_selection($params = array('table' => $t_pabrik, 'id' => 'id_pabrik', 'name' => 'nama_pabrik', 'where' => array()), isset($value->id_pabrik)?$value->id_pabrik:'' , 'id_pabrik', 'id_pabrik', 'form-control', '', '') ?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Rasio Kemasan</label>
                  <div class="col-md-1">
                    <input name="content" id="content" value="<?php echo isset($value)?$value->content:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                  </div>
                  <label class="control-label col-md-1">Satuan Bsr</label>
                  <div class="col-md-2">
                      <?php 
                        $t_satuan_besar = ( $flag_string == 'medis' ) ? 'mt_barang' : 'mt_barang_nm' ;
                        echo $this->master->custom_selection_with_same_field($params = array('table' => $t_satuan_besar, 'id' => 'satuan_besar', 'name' => 'satuan_besar', 'where' => array()), isset($value->satuan_besar)?$value->satuan_besar:'' , 'satuan_besar', 'satuan_besar', 'form-control', '',  ($flag=='read')?'readonly':'') ?>
                  </div>
                  <label class="control-label col-md-1">Satuan Kcl</label>
                  <div class="col-md-2">
                      <?php 
                        $t_satuan_kecil = ( $flag_string == 'medis' ) ? 'mt_barang' : 'mt_barang_nm' ;
                        echo $this->master->custom_selection_with_same_field($params = array('table' => $t_satuan_kecil, 'id' => 'satuan_kecil', 'name' => 'satuan_kecil', 'where' => array()), isset($value->satuan_kecil)?$value->satuan_kecil:'' , 'satuan_kecil', 'satuan_kecil', 'form-control', '',  ($flag=='read')?'readonly':'') ?>
                  </div>
                </div>

                <!-- form medis -->
                <?php if( $flag_string == 'medis' ) : ?>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">Kategori Jenis</label>
                    <div class="col-md-2">
                    <?php 
                        echo $this->master->custom_selection($params = array('table' => 'dd_jenis_barang', 'id' => 'id_dd_jenis_barang', 'name' => 'jenis_barang', 'where' => array()), isset($value->kode_jenis)?$value->kode_jenis:'' , 'kode_jenis', 'kode_jenis', 'form-control', '',  ($flag=='read')?'readonly':'') ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Layanan</label>
                    <div class="col-md-2">
                    <?php 
                        echo $this->master->custom_selection($params = array('table' => 'mt_layanan_obat', 'id' => 'kode_layanan', 'name' => 'nama_layanan', 'where' => array()), isset($value->kode_layanan)?$value->kode_layanan:'' , 'kode_layanan', 'kode_layanan', 'form-control', '',  ($flag=='read')?'readonly':'') ?>
                    </div>
                    <label class="control-label col-md-2">Jenis Obat</label>
                    <div class="col-md-2">
                    <?php 
                        echo $this->master->custom_selection($params = array('table' => 'mt_jenis_obat', 'id' => 'kode_jenis', 'name' => 'nama_jenis', 'where' => array()), isset($value->jenis_obat)?$value->jenis_obat:'' , 'jenis_obat', 'jenis_obat', 'form-control', '',  ($flag=='read')?'readonly':'') ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">Margin</label>
                    <div class="col-md-2">
                    <?php 
                        echo $this->master->custom_selection($params = array('table' => 'fr_mt_profit_margin', 'id' => 'id_profit', 'name' => 'nama_pelayanan', 'where' => array() ), isset($value->id_profit)?$value->id_profit:'' , 'id_profit', 'id_profit', 'form-control', '',  ($flag=='read')?'readonly':'') ?>
                    <input type="hidden" name="margin_percent" id="margin_percent" value="<?php echo isset($value->margin_percent)?$value->margin_percent:''?>">
                    </div>

                    <label class="control-label col-md-2">Harga Pembelian Terakhir</label>
                    <div class="col-md-2">
                      <input name="harga_beli" id="harga_beli" value="<?php echo isset($value)?$value->harga_beli:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
                    </div>
                    
                  </div>

                  <div class="form-group">
                    <label class="col-md-2">&nbsp;</label>
                    <div class="col-md-6" style="margin-left: 5px">
                      <table class="table table-bordered" id="table_profit_detail">
                        <thead>
                        <tr style="background-color: #145371; color: white">
                          <th>Kode</th>
                          <th>Profit Name</th>
                          <th>Profit (%) </th>
                        </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>-</td>
                            <td>-</td>
                            <td align="center"><?php echo isset($value->margin_percent)?$value->margin_percent:''?> %</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>


                <?php endif; ?>
                
                <div class="form-group">
                  <label class="control-label col-md-2">Rak Penyimpanan</label>
                  <div class="col-md-2">
                      <?php 
                        $flag_data = ( $flag_string == 'medis' ) ? 'rak_medis' : 'rak_non_medis' ;
                        echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => $flag_data, 'is_active' => 'Y')), isset($value->rak)?$value->rak:'' , 'rak', 'rak', 'form-control', '', '') ?>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Image</label>
                  <div class="col-md-3" style="margin-left: 5px">
                    <input name="path_image" id="path_image" value="<?php echo isset($value)?$value->path_image:''?>" type="file" class="form-control">
                  </div>
                </div>
                <?php if( isset($value) AND $value->path_image != NULL ) :?>
                  <div class="form-group" style="margin-bottom: 7px">
                    <label class="col-md-2"></label>
                    <div class="col-md-3" style="margin-left: 5px">
                        <img src="<?php echo base_url().PATH_IMG_MST_BRG.$value->path_image?>" alt="" width="200px">
                    </div>
                  </div>
                <?php endif;?>

                <div class="form-group">
                  <label class="control-label col-md-2">Spesifikasi Barang</label>
                  <div class="col-md-4">
                    <textarea name="spesifikasi" id="spesifikasi" class="form-control" style="height: 100px !important"><?php echo isset($value)?$value->spesifikasi:''?></textarea>
                  </div>
                </div>
                
                <?php if( $flag_string == 'medis' ) : ?>
                <div class="form-group">
                  <label class="control-label col-md-2">Obat Kronis?</label>
                  <div class="col-md-2">
                    <div class="radio">
                          <label>
                            <input name="is_kronis" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_kronis == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Ya</span>
                          </label>
                          <label>
                            <input name="is_kronis" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_kronis == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                            <span class="lbl">Tidak</span>
                          </label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Obat PRB?</label>
                  <div class="col-md-2">
                    <div class="radio">
                          <label>
                            <input name="is_prb" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_prb == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Ya</span>
                          </label>
                          <label>
                            <input name="is_prb" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_prb == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                            <span class="lbl">Tidak</span>
                          </label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Generik Formularium?</label>
                  <div class="col-md-10">
                    <div class="checkbox">
                          <?php 
                            $explode = isset($value->kategori_gf) ? explode(",", $value->kategori_gf) : [];
                            $gf = [];
                            foreach ($explode as $ke => $ve) {
                              $gf[$ve] = $ve;
                            }
                          ?>
                          <label>
                            <input name="kategori_gf[]" type="checkbox" class="ace" value="A" <?php echo isset($gf['A']) ? 'checked="checked"' : ''; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Generik</span>
                          </label>
                          <label>
                            <input name="kategori_gf[]" type="checkbox" class="ace" value="B" <?php echo isset($gf['B']) ? 'checked="checked"' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                            <span class="lbl"> Non Generik</span>
                          </label>
                          <label>
                            <input name="kategori_gf[]" type="checkbox" class="ace" value="C" <?php echo isset($gf['C']) ? 'checked="checked"' : ''; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Formularium</span>
                          </label>
                          <label>
                            <input name="kategori_gf[]" type="checkbox" class="ace" value="D" <?php echo isset($gf['D']) ? 'checked="checked"' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                            <span class="lbl"> Non Formularium</span>
                          </label>
                    </div>
                  </div>
                </div>

                <?php endif; ?>
                <div class="form-group">
                  <label class="control-label col-md-2">Is active?</label>
                  <div class="col-md-2">
                    <div class="radio">
                          <label>
                            <input name="is_active" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->is_active == '1') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                            <span class="lbl"> Ya</span>
                          </label>
                          <label>
                            <input name="is_active" type="radio" class="ace" value="0" <?php echo isset($value) ? ($value->is_active == '0') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                            <span class="lbl">Tidak</span>
                          </label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Last update</label>
                  <div class="col-md-8" style="padding-top:8px;font-size:11px">
                      <i class="fa fa-calendar"></i> <?php echo isset($value->updated_date)?$this->tanggal->formatDateTime($value->updated_date):isset($value)?$this->tanggal->formatDateTime($value->created_date):date('d-M-Y H:i:s')?> - 
                      by : <i class="fa fa-user"></i> <?php echo isset($value->updated_by)?$value->updated_by:isset($value->created_by)?$value->created_by:$this->session->userdata('user')->username?>
                  </div>
                </div>


                <div class="form-actions center">

                  <!--hidden field-->
                  <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->kode_brg:0?>"> -->

                  <a onclick="getMenu('inventory/master/Inv_master_barang?flag=<?php echo $flag_string;?>')" href="#" class="btn btn-sm btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                  </a>
                  <?php if($flag != 'read'):?>
                  <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                    <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                    Reset
                  </button>
                  <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                  </button>
                <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


