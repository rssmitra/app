<script type="text/javascript">

// normalize bag code for ICU / VK / kamar bedah / instalasi farmasi
var sess_bag = $('#sess_kode_bagian_stok').val();
if (typeof sess_bag === 'string' && sess_bag.startsWith('03')) {
  var map = {
    '031001': '031001', // ICU
    '030501': '030501', // VK
    '013201': '030501', // treat 013201 as VK
    '030901': '030901'  // kamar bedah
  };
  sess_bag = map[sess_bag] || '030001'; // default to instalasi farmasi
}

$(document).ready(function() {  

    

    oTableObat = $('#table-obat-bhp').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "inventory/stok/Riwayat_pemakaian_bhp/get_data_bhp_unit?kode_bagian=<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>&jenis=obat&kode=<?php echo $no_kunjungan?>&reff_id=<?php echo $reff_id?>&type=<?php echo $type ?>",
          "type": "POST"
      },

    });

    $('#btn_add_obat').click(function (e) {  

      e.preventDefault();
      /*process add obat*/
      $.ajax({
          url: "inventory/stok/Pemakaian_bhp/process_pemakaian_bhp_unit",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              if(confirm('Apakah anda akan menambahkan obat lainnya?')){
                e.preventDefault();
                $('#inputKeyObat').focus(); 
              }else{
                e.preventDefault();
                var scrollPos =  $("#inputKeyObat").offset().top;
                $(window).scrollTop(scrollPos);
                $('#inputKeyObat').focus(); 
              }

              /*reset all field*/
              $('#inputKeyObat').val('');
              $('#pl_kode_tindakan_hidden').val('');
              $('#div_detail_obat').hide('fast');
              $('#detailObatHtml').html('');
            }else{
              alert('Error'); return false;
            }
            
          }
      });

    });

    $('#inputKeyObat').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getObatByBagianAutoComplete",
                data: { keyword: query, bag: sess_bag },            
                dataType: "json",
                type: "POST",
                success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                }
            });
        },
        afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          var label_item=item.split(':')[1];
          $('#inputKeyObat').val(label_item);
          console.log(val_item);
          $('#pl_kode_brg_hidden').val(val_item);
          $('#pl_jumlah_obat').focus();
          var bag = '<?php echo ($sess_kode_bag)?$sess_kode_bag:0 ?>';

          // normalize bag code for ICU / VK / kamar bedah / instalasi farmasi
          if (typeof bag === 'string' && bag.startsWith('03')) {
            var map = {
              '031001': '031001', // ICU
              '030501': '030501', // VK
              '013201': '030501', // treat 013201 as VK
              '030901': '030901'  // kamar bedah
            };
            bag = map[bag] || '030001'; // default to instalasi farmasi
          }

          console.log(bag)

          getDetailObatByKodeBrg(val_item, bag);
        }

    });

    $( "#pl_jumlah_obat" ).keypress(function(event) { 
      var keycode =(event.keyCode?event.keyCode:event.which);  
      if(keycode ==13){  
        event.preventDefault(); 
        if($(this).valid()){  
          $('#btn_add_obat').focus();  
        }   
        return false;                 
      }
      
    });

});


function getDetailObatByKodeBrg(kode_brg,kode_bag){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($value)?$value->kode_kelompok:0?>&bag="+kode_bag+"&type=html&type_layan=<?php echo $type ?>", '' , function (response) {
    if(response.sisa_stok <= 0){
      // seharusnya jika stok habis button di hide sementara stok blom bener jadi di show dulu
      $('#btn_add_obat').show('fast');
      $('#warning_stok_obat').html('<div class="alert alert-danger"><b>Perhatian !</b> <br>Stok barang tersebut sudah habis, mohon perhatikan satuan kecil barang sebelum diproses </div>');
    }else{
      $('#btn_add_obat').show('fast');
      $('#warning_stok_obat').html('');
    }
    /*show detail tarif html*/
    $('#satuan_text').text('('+response.satuan_kecil+')');
    $('#div_detail_obat').show('fast');
    $('#detailObatHtml').html(response.html);

  })

}

function reset_table(){
    oTableObat.ajax.url('inventory/stok/Riwayat_pemakaian_bhp/get_data_bhp_unit?kode_bagian=<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>&jenis=obat&kode=<?php echo $no_kunjungan?>&reff_id=<?php echo $reff_id?>').load();
}

function rollback_stok_bhp(id_kartu){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'inventory/stok/Riwayat_pemakaian_bhp/rollback_stok_bhp',
        type: "post",
        data: {ID:id_kartu, kode_bagian : sess_bag},
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


</script>

<div class="row">
    <div class="col-sm-12">
      <input type="hidden" name="sess_kode_bagian_stok" id="sess_kode_bagian_stok" value="<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>">
      <h3 class="header smaller lighter blue padding-10">
        BHP (Barang Habis Pakai) <small style="font-size: 11px !important; font-style: italic">Untuk mutasi Obat/Alkes yang digunakan dalam satuan paket tindakan atau operasi.  </small>
      </h3>

      <div class="form-group">
          <label class="control-label col-sm-2" for="">*Tanggal</label>
          <div class="col-md-3">
            <div class="input-group">
                <input name="tgl_trx" id="tgl_trx"  class="form-control date-picker" data-date-format="yyyy-mm-dd" type="text" value="<?php echo date('Y-m-d')?>">
                <span class="input-group-addon">
                  <i class="ace-icon fa fa-calendar"></i>
                </span>
              </div>
          </div>
          <label class="control-label col-sm-1" for="">Jam</label>
          <div class="col-sm-2">
            <input type="text" class="form-control" name="jam_trx" id="jam_trx" value="<?php echo date('H:i:s')?>">
          </div>
      </div>
      <div class="form-group">
          <label class="control-label col-sm-2" for="">Nama Obat</label>
          <div class="col-sm-6">
              <input type="text" class="form-control" id="inputKeyObat" name="pl_nama_obat" placeholder="Masukan Keyword Obat ">
              <input type="hidden" class="form-control" id="pl_kode_brg_hidden" name="pl_kode_brg_hidden">
          </div>
      </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Jumlah</label>
            <div class="col-sm-1">
               <input type="text" class="form-control" name="pl_jumlah_obat" id="pl_jumlah_obat" value="1"> 
            </div>
            <div class="col-sm-4" style="padding-top: 5px">
              <span id="satuan_text">(Satuan Kecil)</span>
            </div>
        </div>
        <div style="display:none;" id="div_detail_obat">
          <div class="form-group">
              <label class="control-label col-sm-2" for="">&nbsp;</label>
              <div class="col-sm-10" style="margin-left:6px">
                <div id="warning_stok_obat"></div>
                <div id="detailObatHtml"></div>
              </div>
          </div>
        </div>

        <?php if(empty($value->status_keluar)) :?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
               <a href="#" class="btn btn-xs btn-primary" id="btn_add_obat"> <i class="fa fa-plus"></i> Tambahkan </a>
            </div>
        </div>
        <?php endif;?>

        <div>
          <table id="table-obat-bhp" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="30px" class="center">No</th>
                <th width="150px">Tanggal/Jam</th>
                <th>Nama Obat</th>
                <th width="100px">Jumlah</th>
                <th width="100px">Harga Satuan</th>
                <th width="100px">Total Tarif</th>
                <th width="80px"></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

    </div>

    

</div>





