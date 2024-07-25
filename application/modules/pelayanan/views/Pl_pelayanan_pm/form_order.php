<script type="text/javascript">

$(document).ready(function() {
  
  //initiate dataTables plugin
    var url_tindakan = "<?php echo 'get_order_penunjang?bagian='. $sess_kode_bag .'&jenis=tindakan&kode='.$no_kunjungan.'' ?>";
    oTableTindakan = $('#table-order-tindakan').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": "pelayanan/Pl_pelayanan_pm/get_order_penunjang?bagian="+$('#kode_bagian_pm').val()+"&jenis=tindakan&kode="+$('#no_kunjungan_pm').val()+"",
          "type": "POST"
      },

    });

    var url_tindakan = '<?php echo ($sess_kode_bag=='050301')?'getTindakanFisioByBagianAutoComplete':'getTindakanByBagianAutoComplete' ?>';

    $('#InputKeyTindakan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/"+url_tindakan,
                data: { keyword:query, kode_klas: $('#kode_klas_val').val(), kode_bag : $('#kode_bagian_pm').val(), kode_perusahaan : $('input[name=jenis_tarif]:checked').val() },            
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
          console.log(val_item);
          $('#InputKeyTindakan').val(label_item);
          $('#pl_kode_tindakan_hidden').val(val_item);
        }

    });

    $('#btn_add_tindakan').click(function (e) {   
      e.preventDefault();

      /*process add tindakan*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_pm/process_order_penunjang",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
                e.preventDefault();
                $('#InputKeyTindakan').focus(); 
              /*reset all field*/
              $('#no_kunjungan_pm').val(response.no_kunjungan);
              $('#InputKeyTindakan').val('');
              $('#pl_kode_tindakan_hidden').val('');
              $('#pl_keterangan_tindakan').val('');
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

    });

});

function reset_table(){
    oTableTindakan.ajax.url("pelayanan/Pl_pelayanan_pm/get_order_penunjang?bagian="+$('#kode_bagian_pm').val()+"&jenis=tindakan&kode="+$('#no_kunjungan_pm').val()+"").load();
}

function delete_transaksi(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_pm/delete_order',
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
        <p> <b> ORDER PEMERIKSAAN <?php echo ($sess_kode_bag == '050301') ? 'FISIOTERAPI' : 'RADIOLOGI'; ?> <i class="fa fa-angle-double-right bigger-120"></i></b></p>

        <input type="hidden" class="form-control" id="no_kunjungan_pm" name="no_kunjungan_pm" value="<?php echo $no_kunjungan?>">
        <input type="hidden" class="form-control" id="kode_bagian_pm" name="kode_bagian_pm" value="<?php echo isset($sess_kode_bag)?$sess_kode_bag:0?>">

        <div class="col-sm-12" id="formDetailTarif" style="display:none; background-color:rgba(195, 220, 119, 0.56); margin-bottom: 3px; padding: 5px">
           <div id="detailTarifHtml"></div>
        </div>
    </div>
</div>

<div class="row">
      <div class="col-sm-12">
        <table id="table-order-tindakan" class="table table-bordered table-hover" >
           <thead>
            <tr>  
              <th width="40px">No</th>
              <th width="100px">Kode</th>
              <th>Nama Tindakan</th>
              <th>Keterangan</th>
              <th width="150px">Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

    </div>

</div>





