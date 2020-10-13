<script type="text/javascript">

$(document).ready(function() {  

    oTableObat = $('#table-obat').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan/get_data_obat?bagian=<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>&jenis=obat&kode=<?php echo $no_kunjungan?>&id_pesan_bedah=<?php echo $id_pesan_bedah?>",
          "type": "POST"
      },

    });

    $('#btn_add_obat').click(function (e) {  

      e.preventDefault();
      /*process add obat*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan/process_add_obat",
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
                data: { keyword:query, bag:'<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>' },            
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
          if(bag == '030601' || bag == '030201' || bag == '030301' || bag == '030701' ){
            bag = '030201';
          }else if(bag == '030401' || bag == '030801' || bag == '031401' || bag == '031301'){
            bag = '030401';
          }else if(bag == '030101'){
            bag = '030101';
          }else if(bag == '031001'){
            bag = '031001';	
          }else if(bag == '030501' || bag == '013201'){
            bag = '030501';
          }else if(bag == '030901'){
            bag = '030901';
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
    oTableObat.ajax.url('pelayanan/Pl_pelayanan/get_data_obat?bagian=<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>&jenis=obat&kode=<?php echo $no_kunjungan?>&id_pesan_bedah=<?php echo $id_pesan_bedah?>').load();
}

function delete_transaksi(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan/delete',
        type: "post",
        data: {ID:myid},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        reset_table();
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
      <br>
        <p><b><i class="fa fa-edit"></i> OBAT YANG DIBERIKAN </b></p>

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

    </div>

    <div style="margin-top:0px">
      <table id="table-obat" class="table table-bordered table-hover">
         <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="50px"></th>
            <th width="100px">Kode</th>
            <th>Nama Obat</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th width="150px">Total Tarif</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

</div>





