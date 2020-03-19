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
          "url": "pelayanan/Pl_pelayanan/get_data_obat?bagian=<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>&jenis=obat&kode=<?php echo $no_kunjungan?>",
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
      $('#btn_add_obat').hide('fast');
      $('#warning_stok_obat').html('<span style="color:red"><b><i>Stok sudah habis !</i></b></span>');
    }else{
      $('#btn_add_obat').show('fast');
      $('#warning_stok_obat').html('');
    }
    /*show detail tarif html*/
    $('#div_detail_obat').show('fast');
    $('#detailObatHtml').html(response.html);

  })

}

function reset_table(){
    oTableObat.ajax.url('pelayanan/Pl_pelayanan/get_data_obat?bagian=<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>&jenis=obat&kode=<?php echo $no_kunjungan?>').load();
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
      <p><b><i class="fa fa-circle-o"></i> DATA ORANG TUA </b></p>
      <table class="table table-bordered pull-left" style="width:100% !important">
            <tr style="background-color:#e5f4f7">
              <th>No MR</th>
              <th>Nama Ibu</th>
              <th>Tanggal Masuk</th>
              <th>Kelas Pasien</th>
              <th>Penjamin</th>
            </tr>
            <tr>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
              <td>-</td>
            </tr>
        </table>
    </div>

    <div class="col-sm-12">
        <p><b><i class="fa fa-circle-o"></i> DATA KELAHIRAN BAYI</b></p>

        

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Nama Bayi</label>
            <div class="col-sm-3">
               <input type="text" class="form-control" id="nama_bayi" name="nama_bayi">
            </div>
            <label class="control-label col-sm-1" for="">JK</label>
            <div class="col-sm-4">
               <div class="radio">
                    <label>
                      <input name="jenis_layanan" type="radio" class="ace" value="L" />
                      <span class="lbl"> Laki-laki</span>
                    </label>
                    <label>
                      <input name="jenis_layanan" type="radio" class="ace" value="P" />
                      <span class="lbl"> Perempuan </span>
                    </label>
              </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Berat Badan (Kg)</label>
            <div class="col-sm-1">
               <input type="text" class="form-control" id="berat_badan" name="berat_badan">
            </div>
            <label class="control-label col-sm-2" for="">Panjang Badan (Cm)</label>
            <div class="col-sm-1">
               <input type="text" class="form-control" id="panjang_badan" name="panjang_badan">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Nilai APGAR</label>
            <div class="col-sm-2">
               <input type="text" class="form-control" id="apgar" name="apgar">
            </div>
            <label class="control-label col-sm-2" for="">Anus</label>
            <div class="col-sm-2">
               <input type="text" class="form-control" id="anus" name="anus">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">No Gelang</label>
            <div class="col-sm-6">
               <input type="text" class="form-control" id="no_gelang" name="no_gelang">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Tempat Lahir</label>
            <div class="col-sm-2">
               <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir">
            </div>
            <label class="control-label col-sm-1" for="">Tanggal</label>
            <div class="col-sm-2">
               <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir">
            </div>
            <label class="control-label col-sm-1" for="">Jam</label>
            <div class="col-sm-1">
               <input type="text" class="form-control" id="jam" name="jam">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Dokter/Bidan Penolong</label>
            <div class="col-sm-4">
               <input type="text" class="form-control" id="dokter" name="dokter">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Keterangan</label>
            <div class="col-sm-4">
               
               <textarea name="keterangan" class="form-control" style="height:50px !important"></textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
               <a href="#" class="btn btn-xs btn-primary" id="btn_add_obat"> <i class="fa fa-save"></i> Simpan </a>
            </div>
        </div>

    </div>

</div>





