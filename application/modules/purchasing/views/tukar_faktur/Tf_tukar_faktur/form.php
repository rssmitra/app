<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

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
    
    $('#form_tukar_faktur').ajaxForm({
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
          $('#page-area-content').load('purchasing/tukar_faktur/Tf_tukar_faktur/view_data?flag=<?php echo $flag?>');
          // popup cetak po
          PopupCenter('purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/preview_ttf?ID='+jsonResponse.id+'&flag='+jsonResponse.flag+'','BUKTI TANDA TERIMA FAKTUR',900,650);

        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    var flag = ( $('#flag_string').val() ) ? $('#flag_string').val() : '' ;

    $('#btn_search_brg').click(function (e) {   

        if ( $('#inputKeyWord').val()=='' ) {
          alert('Silahkan Masukan Kata Kunci !'); return false;
        }

        search_selected_brg(flag, search_by, keyword);

        e.preventDefault();

    });

    $( "#inputKeyWord" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            search_selected_brg(flag, search_by, keyword);       
          }         
          return false;                
        }       
    });  

    $('input:radio[name="biaya_materai"]').change(function() {
      var value = $(this).val();
      var biaya_materai = value * 1000;
      $('#txt_materai').text(formatMoney(Math.floor(biaya_materai)));
      $('#total_biaya_materai').val(Math.floor(biaya_materai));
      hitungSubtotalTrx();
    });

})

function hitungSubtotalTrx(){

  var subtotal = parseInt(formatNumberFromCurrency($('#subtotal').text()));
  var disc = $('#diskon').val();
  var ppn = $('#ppn').val();
  var rp_disc = parseInt(subtotal) * (parseInt(disc)/100);
  var ttl_stlh_disc = subtotal - Math.floor(rp_disc);
  var materai = $('#total_biaya_materai').val();
  var rp_ppn = ttl_stlh_disc * (parseInt(ppn)/100);
  var total = ttl_stlh_disc + rp_ppn + parseInt(materai);
  console.log(total);
  $('#rp_disc').text(formatMoney(Math.floor(rp_disc)));
  $('#total_disc').val(Math.floor(rp_disc));
  $('#rp_ppn').text(formatMoney(Math.floor(rp_ppn)));
  $('#total_ppn').val(Math.floor(rp_ppn));
  $('#txt_total').text(formatMoney(Math.floor(total)));
  $('#total_harga').val(Math.floor(total));

}

</script>
<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">

          <form class="form-horizontal" method="post" id="form_tukar_faktur" action="<?php echo site_url('purchasing/tukar_faktur/Tf_tukar_faktur/process')?>" enctype="multipart/form-data" >
            <br>
            <!-- input form hidden -->
            <input name="id" id="id" value="" class="form-control" type="hidden">
            <input type="hidden" name="flag" id="flag_string" value="<?php echo $flag?>">
            <input type="hidden" name="action" id="action" value="tukar_faktur">
            <input type="hidden" name="kodesupplier" id="kodesupplier" value="<?php echo $result[0]->kodesupplier?>">
            <p>
              <b><span style="font-size: 18px"><?php echo $result[0]->namasupplier?></span></b>
              <i class="fa fa-angle-double-right"></i> <small><?php echo $result[0]->alamat?></small>
            </p>
            <div class="form-group">
              <label class="control-label col-md-2">No. Tanda Terima Faktur</label>
              <div class="col-md-2">
                <input name="no_ttf" id="no_ttf" value="<?php echo isset($format_ttf)?$format_ttf['format']:''?>" class="form-control" type="text" placeholder="Auto" readonly>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tanggal Faktur</label>
              <div class="col-md-1">
                <div class="input-group">
                  <input class="form-control date-picker" name="tgl_faktur" id="tgl_faktur" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value->tgl_faktur)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_faktur): date('Y-m-d') ?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
              <label class="control-label col-md-1" style="margin-left: 4%">Jth Tempo</label>
              <div class="col-md-1">
                <div class="input-group">
                  <input class="form-control date-picker" name="tgl_rencana_bayar" id="tgl_rencana_bayar" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value->tgl_rencana_bayar)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_rencana_bayar): date('Y-m-d') ?>"/>
                  <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                  </span>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-2">No. Seri Pajak</label>
              <div class="col-md-2">
                <input name="no_seri_pajak" id="no_seri_pajak" value="" class="form-control" type="text" >
              </div>
              <label class="control-label col-md-1">Diskon (%)</label>
              <div class="col-md-1">
                <input name="diskon" id="diskon" value="0" onchange="hitungSubtotalTrx()" class="form-control" type="text" readonly>
              </div>
              <label class="control-label col-md-1">PPN (%)</label>
              <div class="col-md-1">
                <input name="ppn" id="ppn" value="0" onchange="hitungSubtotalTrx()" class="form-control" type="text" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Biaya Materai</label>
                <div class="col-md-10">
                  <div class="radio">
                        <label>
                          <input name="biaya_materai" type="radio" class="ace" value="3" <?php echo isset($_GET['biaya_materai']) ? ($_GET['biaya_materai'] == '3') ? 'checked' : '' : ''?> />
                          <span class="lbl"> Materai 3,000,-</span>
                        </label>
                        <label>
                          <input name="biaya_materai" type="radio" class="ace" value="6" <?php echo isset($_GET['biaya_materai']) ? ($_GET['biaya_materai'] == '6') ? 'checked' : '' : ''?> />
                          <span class="lbl"> Materai 6,000,-</span>
                        </label>
                        <label>
                          <input name="biaya_materai" type="radio" class="ace" value="10" <?php echo isset($_GET['biaya_materai']) ? ($_GET['biaya_materai'] == '10') ? 'checked' : '' : ''?>/>
                          <span class="lbl"> Materai 10,000,-</span>
                        </label>
                        <label>
                          <input name="biaya_materai" type="radio" class="ace" value="0" <?php echo isset($_GET['biaya_materai']) ? ($_GET['biaya_materai'] == '0') ? 'checked' : '' : ''?> />
                          <span class="lbl"> Tanpa Materai</span>
                        </label>
                  </div>
                </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-2">Keterangan</label>
              <div class="col-md-3">
                <textarea class="form-control" style="height:50px !important" name="keterangan"></textarea>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="pull-left">
                <b>TUKAR FAKTUR</b><br>
                Data tukar faktur penerimaan barang.<br><br>
              </div>
            </div>

            <div class="col-sm-12">
              <table id="dynamic-table" base-url="purchasing/tukar_faktur/Tf_tukar_faktur" data-id="flag=<?php echo $flag?>" url-detail="purchasing/tukar_faktur/Tf_tukar_faktur/get_detail" class="table" style="width: 70%">
                <thead>
                  <tr style="background-color: #c3c3c3">  
                    <th width="30px" class="center">No</th>
                    <th width="140px">Kode Penerimaan</th>
                    <th width="120px">Nomor PO</th>
                    <th width="120px">Tgl Penerimaan</th>
                    <th width="120px">No Faktur</th>
                    <th width="120px">Petugas Gudang</th>
                    <th width="100px">Subtotal (Rp.)</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 0; 
                    foreach($result as $row_res) : 
                    $no++; 
                    $arr_total[] = $row_res->total;
                  ?>
                    <!-- hidden form -->
                    <input type="hidden" name="kode_penerimaan[]" id="kode_penerimaan" value="<?php echo $row_res->kode_penerimaan; ?>">
                    <input type="hidden" name="no_faktur[]" id="no_faktur" value="<?php echo $row_res->no_faktur; ?>">
                    <input type="hidden" name="id_penerimaan[]" id="id_penerimaan" value="<?php echo $row_res->id_penerimaan; ?>">
                    <input type="hidden" name="subtotal[]" id="total" value="<?php echo $row_res->total; ?>">
                    <tr>  
                      <td align="center"><?php echo $no?></td>
                      <td><?php echo $row_res->kode_penerimaan?></td>
                      <td><?php echo $row_res->no_po?></td>
                      <td><?php echo $this->tanggal->formatDateDmy($row_res->tgl_penerimaan)?></td>
                      <td><?php echo $row_res->no_faktur?></td>
                      <td><?php echo $row_res->petugas?></td>
                      <td align="right"><?php echo number_format($row_res->total)?></td>                    
                    </tr>
                  <?php endforeach; ?>
                  <tr>
                      <td colspan="6" align="right">Subtotal</td>
                      <td align="right"><span id="subtotal"><?php echo number_format(array_sum($arr_total)); ?></span></td>
                  </tr>
                  <tr>
                      <td colspan="6" align="right">Diskon</td>
                      <td align="right"><span id="rp_disc">0</span></td>
                  </tr>
                  <tr>
                      <td colspan="6" align="right">PPN</td>
                      <td align="right">
                        <?php $ppn = 0;?>
                        <span id="rp_ppn"><?php echo number_format(0)?></span>
                      </td>
                  </tr>
                  <tr>
                      <td colspan="6" align="right">Biaya Materai</td>
                      <td align="right"><span id="txt_materai">0</span></td>
                  </tr>
                  <tr>
                      <td colspan="6" align="right">Total</td>
                      <td align="right"><b><span id="txt_total">
                        <?php 
                          $ttl = $ppn + array_sum($arr_total);
                        echo number_format($ttl); 
                        ?></span></b></td>
                  </tr>

                  <!-- hidden -->
                  <input type="hidden" name="total_diskon" id="total_diskon" value="0">
                  <input type="hidden" name="total_ppn" id="total_ppn" value="<?php echo $ppn?>">
                  <input type="hidden" name="total_sbl_ppn" id="total_sbl_ppn" value="<?php echo array_sum($arr_total)?>">
                  <input type="hidden" name="total_harga" id="total_harga" value="<?php echo $ttl?>">
                  <input type="hidden" name="total_biaya_materai" id="total_biaya_materai" value="0">
                  <!-- <tr>
                      <td colspan="7" align="right">
                        <b>Terbilang</b> 
                        <?php 
                          $total = array_sum($arr_total);
                          $terbilang = new Kuitansi(); echo '<i>"'.ucwords($terbilang->terbilang($total)).'"</i>';
                        ?>
                      </td>
                  </tr> -->
                </tbody>
              </table>
            </div>
            <hr>
            <div class="col-sm-12" style="margin-top: 10px">
              <a onclick="getMenu('purchasing/tukar_faktur/Tf_tukar_faktur/view_data?flag=<?php echo $flag?><?php echo $qry_url?>', 'tabs_form_po')" href="#" class="btn btn-xs btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke daftar
              </a>
              <button type="submit" id="btnSave" name="submit" class="btn btn-xs btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            </div>

          </form>

        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


