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
        "url": "pelayanan/Pl_pelayanan_pm/get_order_penunjang?bagian="+$('#kode_bagian_pm').val()+"&jenis=tindakan&kode="+$('#no_kunjungan_pm').val()+"&id_pm_tc_penunjang="+$('#id_pm_tc_penunjang').val()+"",
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

    $('#btn_add_tindakan_fisio').click(function (e) {   
      e.preventDefault();

      /*process add tindakan*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_pm/process_order_penunjang",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            if(response.status==200) {
              e.preventDefault();
              $('#InputKeyTindakan').focus(); 
              /*reset all field*/
              $('#no_kunjungan_pm').val(response.no_kunjungan);
              $('#InputKeyTindakan').val('');
              $('#pl_kode_tindakan_hidden').val('');
              $('#pl_keterangan_tindakan').val('');
              $('#xray_foto').val('');
              $('#kontra_indikasi').val('');
              $('#pl_diagnosa').val('');
              $('#pl_diagnosa_hidden').val('');
              reset_table();
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

    });

});

function reset_table(){
    oTableTindakan.ajax.url("pelayanan/Pl_pelayanan_pm/get_order_penunjang?bagian="+$('#kode_bagian_pm').val()+"&jenis=tindakan&kode="+$('#no_kunjungan_pm').val()+"&id_pm_tc_penunjang="+$('#id_pm_tc_penunjang').val()+"").load();
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

$('#pl_diagnosa').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getICD10",
                data: 'keyword=' + query,            
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
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#pl_diagnosa').val(label_item);
        $('#pl_diagnosa_hidden').val(val_item);
        }

    });

</script>

<div class="row">
    <div class="col-sm-12">
      <p style="text-align: center; font-size: 18px; line-height: 20px "><b> FORM PERMINTAAN<br>PEMERIKSAAN FISIOTERAPI<br><span style="font-size: 12px !important; font-weight: normal !important">Tanggal <?php echo date('D, d/m/Y')?></span></b></p>
      <hr>

        <input type="hidden" class="form-control" id="no_kunjungan_pm" name="no_kunjungan_pm" value="<?php echo $no_kunjungan?>">
        <input type="hidden" class="form-control" id="kode_bagian_pm" name="kode_bagian_pm" value="<?php echo isset($sess_kode_bag)?$sess_kode_bag:0?>">
        <input type="hidden" class="form-control" id="id_pm_tc_penunjang" name="id_pm_tc_penunjang" value="<?php echo isset($id_pm_tc_penunjang)?$id_pm_tc_penunjang:0?>">

        <div class="form-group">
        <label class="control-label col-sm-3" for="">Anamnesa</label>
            <div class="col-sm-9">
               <textarea type="text" class="form-control" id="pl_anamnesa" name="pl_anamnesa" style="height: 80px !important"><?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?></textarea>
            </div>
        </div>
        <div class="form-group" style="margin-top: 3px">
            <label class="control-label col-sm-3" for="">Diagnosa (ICD10)</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
            <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-3" for="">X ray Foto</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="xray_foto" name="xray_foto">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-3" for="">Terapi/Pemeriksaan</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="InputKeyTindakan" name="pl_nama_tindakan" placeholder="Masukan Keyword Tindakan">
                <input type="hidden" class="form-control" id="pl_kode_tindakan_hidden" name="pl_kode_tindakan_hidden" >
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-3" for="">Kontra Indikasi</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="kontra_indikasi" name="kontra_indikasi">
            </div>
        </div>

        <div class="form-group">
        <label class="control-label col-sm-3" for="">Rencana Lanjutan</label>
            <div class="col-sm-9">
               <textarea type="text" class="form-control" id="pl_keterangan_tindakan" name="pl_keterangan_tindakan" style="height: 100px !important"></textarea>
            </div>
        </div>

        <div class="col-sm-12" id="formDetailTarif" style="display:none; background-color:rgba(195, 220, 119, 0.56); margin-bottom: 3px; padding: 5px">
           <div id="detailTarifHtml"></div>
        </div>

        <div class="form-group">
            <label class="col-sm-3" for="">&nbsp;</label>
            <div class="col-sm-9" style="margin-left:6px">
               <a href="#" class="btn btn-xs btn-primary" id="btn_add_tindakan_fisio"> <i class="fa fa-plus"></i> Tambahkan </a>
            </div>
        </div>

    </div>
</div>

<div class="row">
      <div class="col-sm-12">
        <table id="table-order-tindakan" class="table table-bordered table-hover" >
           <thead>
            <tr>  
              <th width="40px">No</th>
              <th width="120px">Nama Tindakan</th>
              <th>Diagnosa</th>
              <th>Keterangan</th>
              <th width="100px">Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

    </div>

</div>





