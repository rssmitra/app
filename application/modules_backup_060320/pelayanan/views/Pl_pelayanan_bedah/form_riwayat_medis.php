<script type="text/javascript">

$(document).ready(function() {

    oTablePesanDiagnosa = $('#table-riwayat-diagnosa').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>",
          "type": "POST"
      },

    });

    $('#pl_diagnosa').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "ws_bpjs/Ws_index/getRef?ref=refDiagnosa",
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

    $('#pl_diagnosa_awal').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "ws_bpjs/Ws_index/getRef?ref=refDiagnosa",
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
          $('#pl_diagnosa_awal').val(label_item);
        }

    });

    $('#btn_add_diagnosa').click(function (e) {   
      e.preventDefault();

      if( $('#pl_diagnosa_awal').val() == '' ){
        alert('Silahkan isi Diagnosa Awal !'); return false;
      }else{
        if( $('#pl_diagnosa').val() == '' ){
          alert('Silahkan isi Diagnosa Akhir !'); return false;
        }
      }

      /*process add diagnosa*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_ri/process_add_diagnosa",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            reset_form();


            if(response.status==200) {
             console.log(response)
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

    });

});

function reset_form(){
  $('#btn_add_diagnosa').text('Simpan');
  $('#kode_riwayat').val('');
  $('textarea#pl_diagnosa_awal').val('');
  $('textarea#pl_anamnesa').val('');
  $('textarea#pl_diagnosa').val('');
  $('textarea#pl_pengobatan').val('');
}
function reset_table(){
    oTablePesanDiagnosa.ajax.url('pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>').load();
}

function delete_diagnosa(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan/delete_diagnosa',
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
            reset_form();
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

function edit_diagnosa(kode_riwayat) {
  preventDefault();

  $.getJSON("pelayanan/Pl_pelayanan/getDiagnosaByKode/"+kode_riwayat, '' , function (response) {
    
    $('#kode_riwayat').val( response.kode_riwayat );
    $('textarea#pl_diagnosa_awal').val( response.diagnosa_awal );
    $('textarea#pl_anamnesa').val( response.anamnesa );
    $('textarea#pl_diagnosa').val( response.diagnosa_akhir );
    $('textarea#pl_pengobatan').val( response.pengobatan );
    $('#btn_add_diagnosa').text('Simpan Perubahan');

  })

}


</script>

<div class="row">
    <div class="col-sm-12">
      <br>
        <p><b><i class="fa fa-edit"></i> DIAGNOSA DAN PEMERIKSAAN PASIEN </b></p>

        <input type="hidden" name="kode_riwayat" value="" id="kode_riwayat">
        <div class="form-group" style="margin-bottom:3px">
            <label class="control-label col-sm-2" for="">Diagnosa Awal <span style="color:red">(*)</span></label>
            <div class="col-sm-4">
              <textarea name="pl_diagnosa_awal" id="pl_diagnosa_awal" class="form-control" style="height:50px !important" placeholder="Masukan keyword ICD 10" ></textarea>  
            </div>
            <label class="control-label col-sm-2" for="">Anamnesa</label>
            <div class="col-sm-4">
              <textarea name="pl_anamnesa" id="pl_anamnesa" class="form-control" style="height:50px !important"></textarea>                  
            </div>
        </div>

        <div class="form-group" style="margin-bottom:5px">
            <label class="control-label col-sm-2" for="">Diagnosa Akhir <span style="color:red">(*)</span></label>
            <div class="col-sm-4">
              <textarea name="pl_diagnosa" id="pl_diagnosa" class="form-control" style="height:50px !important" placeholder="Masukan keyword ICD 10" ></textarea> 
              <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
            </div>
            <label class="control-label col-sm-2" for="">Pengobatan</label>
            <div class="col-sm-4">
              <textarea name="pl_pengobtan" id="pl_pengobatan" class="form-control" style="height:50px !important"></textarea> 
            </div>
        </div>

        <div class="form-group" id="btn_submit_diagnosa">
            <label class="control-label col-sm-2" for=""></label>
            <div class="col-sm-7" style="margin-left:1px">
            <a href="#" class="btn btn-sm btn-primary" id="btn_add_diagnosa"><i class="fa fa-save"></i> Simpan</a> 
            </div>
        </div>

      <div style="margin-top:0px">
        <table id="table-riwayat-diagnosa" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th width="80px"></th>
              <th>Tanggal</th>
              <th>Bagian</th>
              <th>Anamnesa</th>
              <th>Diagnosa Awal</th>
              <th>Diagnosa Akhir</th>
              <th>Pengobatan</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>

</div>





