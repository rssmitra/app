<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript">

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

$(document).ready(function() {

   oTableObalkes = $('#table-obalkes').DataTable({ 
            
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": false,
    "bInfo": false,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": "pelayanan/Pl_pelayanan_pm/get_bpako?id=<?php echo ($id)?$id:0 ?>",
        "type": "POST"
    },
  
  });

  $('#InputKeyBrg').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getBarangAutoComplete",
              data: { keyword:query, kode_bagian : $('#kode_bagian_val').val() },            
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
        $('#pm_kode_brg_obalkes_hidden').val(val_item);

      }

  });

  $('#btn_add_obalkes').click(function (e) {   
    e.preventDefault();

    
    if( $('#pm_kode_brg_obalkes_hidden').val() == '' ){
      alert('Silahkan cari tindakan !'); return false;
    }
    
    /*process add obalkes*/
    $.ajax({
        url: "pelayanan/Pl_pelayanan_pm/process_add_obalkes",
        data: {kode_brg: $('#pm_kode_brg_obalkes_hidden').val(),jml: $('#pm_jml_obalkes').val(), kode_penunjang : $('#kode_penunjang_obalkes').val(), kode_tarif : $('#kode_tarif_obalkes').val(), kode_bagian : $('#kode_bagian_val').val()},          
        dataType: "json",
        type: "POST",
        success: function (response) {
          if(response.status==200) {
            $('#pm_jml_obalkes').val(1);
            var data = response.data
            reset_table(data.kode_penunjang,data.kode_tarif)
            /*reset all field*/
            $('#InputKeyBrg').val('');
            
          }else{
            alert('Silahkan cari pasien !'); return false;
          }
          
        }
    });

  });

  $('#btn_edit_selesai').click(function (e) {   
      e.preventDefault();

      $("#Modal_edit_obalkes").modal('hide'); 

      $("#tabs_form_pelayanan").load("pelayanan/Pl_pelayanan_pm/form_isi_hasil/<?php echo $no_kunjungan?>/"+$('#kode_bagian_val').val()+"/<?php echo $id?>?mr=<?php echo isset($no_mr)?$no_mr:0 ?>");
  });
  

  $('#inputDokterTransPelayanan').typeahead({
      source: function (query, result) {
              $.ajax({
                  url: "templates/references/getAllDokter",
                  data: { keyword:query },            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                  result($.map(response, function (item) {
                      //return item.split(':')[1];
                      return item;
                  }));
                  }
              });
          },
          afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          console.log(val_item);
          $('#dokter_trans_pelayanan').val(val_item);
          
      }
  });

  $('#inputDokterTransPelayanan2').typeahead({
      source: function (query, result) {
              $.ajax({
                  url: "templates/references/getAllDokter",
                  data: { keyword:query },            
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
          $('#dokter_trans_pelayanan2').val(val_item);
          
      }
  });

  $('#btn_edit_dokter_selesai').click(function (e) {   
      e.preventDefault();

      //alert($('#inputDokterTransPelayanan').text())
      var dr_2 = ($('#dokter_trans_pelayanan2').val()!=undefined)?$('#dokter_trans_pelayanan2').val():0;

      $.ajax({
          url: "pelayanan/Pl_pelayanan_pm/process_edit_dokter",
          data: {kode_penunjang_dr: $('#kode_penunjang_dr').val(),kode_trans_pelayanan: $('#kode_trans_pelayanan').val(), kode_dokter1 : $('#dokter_trans_pelayanan').val(), kode_dokter2 : dr_2},          
          dataType: "json",
          type: "POST",
          success: function (response) {
            if(response.status==200) {
              var dr_1 = $('#inputDokterTransPelayanan').text().split(':')[1];
              var dr_2 = ($('#inputDokterTransPelayanan2').text() != "undefined" && $('#inputDokterTransPelayanan2').text() != '')?$('#inputDokterTransPelayanan2').text().split(':')[1]:'';
              var txt = (dr_2!='')?dr_1+' | '+dr_2 :dr_1;
              $('#txt_dokter').text(txt);
              $("#form_dr").hide('fast'); 
              $.achtung({message: response.message, timeout:5});
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

      
  });

});

function reset_table(kode_penunjang,kode_tarif){
  oTableObalkes.ajax.url('pelayanan/Pl_pelayanan_pm/get_bpako?id='+kode_penunjang+'&kode_tarif='+kode_tarif).load();
}

function edit_obalkes(myid,kode_tarif){

  $('#kode_penunjang_obalkes').val(myid);
  $('#kode_tarif_obalkes').val(kode_tarif);
  reset_table(myid,kode_tarif)

  $("#Modal_edit_obalkes").modal(); 

}

function edit_dokter(myid,kode_trans_pelayanan,kode_dr1,dr1,kode_dr2,dr2){

  $('#kode_penunjang_dr').val(myid);
  $('#kode_trans_pelayanan').val(kode_trans_pelayanan);

  $('#inputDokterTransPelayanan').val('');
  $('#dokter_trans_pelayanan').val(kode_dr1);

  if(kode_dr2!=undefined && kode_dr2!=0){
    $('#inputDokterTransPelayanan2').val(dr2);
    $('#dokter_trans_pelayanan2').val(kode_dr2);

    $('#dr2').show('fast');
  }

  $("#form_dr").show('fast'); 

}

function backToDefaultForm() {
  $('#kode_penunjang_dr').val('');
  $('#kode_trans_pelayanan').val('');

  $('#inputDokterTransPelayanan').val('');
  $('#dokter_trans_pelayanan').val('');

  
  $('#inputDokterTransPelayanan2').val('');
  $('#dokter_trans_pelayanan2').val('');

  $('#dr2').hide('fast');
  

  $("#form_dr").hide('fast'); 
}

function delete_obalkes(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_pm/delete',
        type: "post",
        data: {ID:myid,kode_bagian:$('#kode_bagian_val').val()},
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
            var brg = jsonResponse.data;
            console.log(brg)
            reset_table(brg.kode_penunjang,brg.kode_tarif);
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

function prosesIsiHasilEdit() {
  
  $.ajax({
      url: "pelayanan/Pl_pelayanan_pm/process_isi_hasil",
      data: $('#form_isi_hasil').serialize(),            
      dataType: "json",
      type: "POST",
      success: function (response) {
        if(response.status==200) {
          $.achtung({message: response.message, timeout:5});
          $('.hasil_pm').attr('readonly', true);
          $('.keterangan_pm').attr('readonly', true);
          $('#cetak_isi_hasil').show('fast');
        }else{
          $.achtung({message: response.message, timeout:5});
        }
        
      }
  }); 

}

</script>

<div class="row">

  <?php if(isset($is_edit) AND $is_edit=='Y'): ?>   
  <form class="form-horizontal" method="post" id="form_isi_hasil" action="#" enctype="multipart/form-data" autocomplete="off" >      
  <input type="hidden" name="kode_penunjang" id="kode_penunjang" value="<?php echo ($id)?$id:''?>">
  <?php endif ?>

  <div class="col-md-12">

    <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal</label>
          <div class="col-md-3">
                
            <div class="input-group">
                
                <input name="pl_tgl_pm" id="pl_tgl_pm" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                <span class="input-group-addon">
                  
                  <i class="ace-icon fa fa-calendar"></i>
                
                </span>
              </div>
          
          </div>

          
    </div>

    <div>
      <table id="dynamic-table" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th>Pemeriksaan</th>
            <th class="center">Hasil</th>
            <th class="center">Keterangan</th>
            <th class="center">BPAKO</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=0; 
          $nama_tindakan='';
          $kode_tarif='';
            foreach ($list as $key_list=>$row_list) {
              if($row_list->kode_tarif!=$kode_tarif){
                if($row_list->nama_tindakan!=$nama_tindakan){
                  $dokter2=isset($row_list->dokter2)?' | '.$row_list->dokter2. ' ':'';
                  $dokter_param2=isset($row_list->dokter2)?','.$row_list->kode_dokter2.','."'$row_list->dokter2'".' ':'';
                  echo
                    '<tr>
                      <td colspan="3">
                        <b>'.$row_list->nama_tindakan.' </b><br>
                        Dokter: <p id="txt_dokter" style="display:inline">'.$row_list->dokter1.' '.$dokter2.' </p><a href="#" class="btn btn-link" onclick="edit_dokter('.$id.','.$row_list->kode_trans_pelayanan.','.$row_list->kode_dokter1.','."'$row_list->dokter1'".' '.$dokter_param2.')"><i class="fa fa-edit"></i></a>
                        <div id="form_dr" style="display:none">
                        
            
                            <div class="form-group">
                              <label class="control-label col-sm-2">Dokter 1</label>
                                <div class="col-sm-4">

                                    <input id="inputDokterTransPelayanan" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

                                    <input type="hidden" name="dokter_trans_pelayanan" id="dokter_trans_pelayanan" class="form-control">
                                </div>


                            </div>

                            <div class="form-group" id="dr2" style="display:none">
                              <label class="control-label col-sm-">Dokter 2</label>
                                <div class="col-sm-4">

                                    <input id="inputDokterTransPelayanan2" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

                                    <input type="hidden" name="dokter_trans_pelayanan2" id="dokter_trans_pelayanan2" class="form-control">
                                </div>
                                  
                            </div>

                            <input type="hidden" name="kode_penunjang_dr" id="kode_penunjang_dr">
                            <input type="hidden" name="kode_trans_pelayanan" id="kode_trans_pelayanan">

                          <div>
                              <a href="#" class="btn btn-xs btn-primary" id="btn_edit_dokter_selesai"> <i class="fa fa-edit"></i> Simpan </a>
                              <button type="button" class="btn btn-xs btn-danger" id="btn_hide_" onclick="backToDefaultForm()"> <i class="fa fa-angle-double-left"></i> Sembunyikan </button>
                          </div>
                        </div>
                      </td>
                      <td><a href="#" class="btn btn-xs btn-success" onclick="edit_obalkes('.$id.','.$row_list->kode_tarif.')"><i class="fa fa-edit"></i>&nbsp;Edit</a></td>
                    </tr>';
                    $nama_tindakan = $row_list->nama_tindakan;
                }
                
                //if($row_list->nama_tindakan==$nama_tindakan){
                  $hasil = (isset($row_list->hasil))?$row_list->hasil:$row_list->standar_rad;
                  $ket = (isset($row_list->keterangan))?$row_list->keterangan:$row_list->kesan;
                  $kode_tc_hasilpenunjang =  (isset($row_list->kode_tc_hasilpenunjang))?$row_list->kode_tc_hasilpenunjang:0;
                echo
                  '<tr>
                    <td>'.$row_list->nama_pemeriksaan.'</td>
                    <td height="150px" width="35%"><textarea name="hasil_pm['.$row_list->kode_mt_hasilpm.']" style="height:100% !important;width:100% !important" class="hasil_pm">'.$hasil.'</textarea></td>    
                    <td height="150px" width="25%"><textarea name="keterangan_pm['.$row_list->kode_mt_hasilpm.']" style="height:100% !important;width:100% !important" class="keterangan_pm">'.$ket.'</textarea></td>           
                    <td valign="top" width="20%">
                      ';
                    foreach ($bpako as $bpako) {
                      $nama_brg = (isset($bpako->nama_brg))?$bpako->nama_brg:'-';
                      $volume = (isset($bpako->volume))?$bpako->volume:0;
                      $satuan = (isset($bpako->satuan_kecil))?$bpako->satuan_kecil:'';
                      echo 
                        '<li>'.$nama_brg.' / '.$volume.' '.$satuan.'</li>';
                      
                    }
                    echo  
                      '
                    </td>
                  </tr>
                  <input type="hidden" name="kode_tc_hasilpenunjang['.$row_list->kode_mt_hasilpm.']" value="'.$kode_tc_hasilpenunjang.'" >
                  <input type="hidden" name="kode_mt_hasilpm[]" value="'.$row_list->kode_mt_hasilpm.'" >
                  <input type="hidden" name="kode_trans_pelayanan['.$row_list->kode_mt_hasilpm.']" value="'.$row_list->kode_trans_pelayanan.'" >
                  <input type="hidden" name="jumlah_hasilpm['.$row_list->kode_mt_hasilpm.']" value="" >
                  ';
                $i++;
                //}
              }

              $kode_tarif = $row_list->kode_tarif;
            }
          ?>
        </tbody>
      </table>

      <div class="form-group">
                      
        <label class="control-label col-sm-2">Catatan</label>
        
        <div class="col-md-3">
          
          <textarea name="catatan_hasil" id="catatan_hasil" cols="50" style="height:100px !important;"><?php echo isset($catatan_hasil)?$catatan_hasil:'';?></textarea>
        
        </div>
      
      
      </div>


      <?php if(isset($is_edit) AND $is_edit=='Y'): ?>   
      </form>
      <?php endif ?>


      <div class="form-group">
          <div class="col-sm-8">
              <?php if(isset($is_edit) AND $is_edit=='Y'): ?>
                <a href="#" class="btn btn-xs btn-primary" onclick="prosesIsiHasilEdit()" ><i class="fa fa-save"></i> Submit</a>
              <?php elseif((!isset($is_edit))): if( !isset($is_mcu) OR (isset($is_mcu) AND $is_mcu!=1)){?>
                <button type="submit" href="#" id="btn_submit_isihasil" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Submit</button>
              <?php } endif ?>
              <span id="cetak_isi_hasil" style="display:none">
                <a href="<?php echo base_url() ?>Templates/Export_data/export?type=pdf&flag=RAD&noreg=<?php echo isset($no_registrasi)?$no_registrasi:''?>&pm=<?php echo ($id)?$id:''?>&kode_pm=050201&no_kunjungan=<?php echo ($no_kunjungan)?$no_kunjungan:''; if(isset($is_mcu))echo '&flag_mcu=1'?>" target="blank" class="btn btn-xs btn-info" >Cetak Hasil</a>
              </span>
          </div>
      </div>

    </div>

    </div>

  </div>
    

     
</div>


<div id="Modal_edit_obalkes" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:100%;  margin-top: 50px; margin-bottom:50px;width:50%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">Edit BPAKO</span>

        </div>

      </div>

      <div class="modal-body">

      <form action="" id="form_edit_bpako">

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Nama Barang</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="InputKeyBrg" name="pm_kode_brg_obalkes" placeholder="Masukan Keyword Tindakan">
              <input type="hidden" class="form-control" id="pm_kode_brg_obalkes_hidden" name="pm_kode_brg_obalkes_hidden" >
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Jumlah</label>
            <div class="col-sm-1">
               <input type="number" min="1" class="form-control" id="pm_jml_obalkes" name="pm_jml_obalkes" value="1">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-10" style="margin-left:6px">
               <a href="#" class="btn btn-xs btn-primary" id="btn_add_obalkes"> <i class="fa fa-plus"></i> Tambahkan </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
              <table id="table-obalkes" class="table table-bordered table-hover">
                <thead>
                  <tr>  
                    <th width="80px"></th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>

        </div>
        <input type="hidden" name="kode_penunjang_obalkes" id="kode_penunjang_obalkes">
				<input type="hidden" name="kode_tarif_obalkes" id="kode_tarif_obalkes">

      </form>

      </div>

      <div class="modal-footer no-margin-top">

        <div style="text-align:center;">
            <a href="#" class="btn btn-xs btn-primary" id="btn_edit_selesai"> <i class="fa fa-edit"></i> Selesai </a>
        </div>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>


<!-- <div id="Modal_edit_dokter" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:100%;  margin-top: 50px; margin-bottom:50px;width:50%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">Edit Dokter</span>

        </div>

      </div>

      <div class="modal-body">

      <form action="" id="form_edit_dokter">
        
        <div class="form-group">
          <label class="control-label col-sm-2">Dokter 1</label>
            <div class="col-sm-4">

                <input id="inputDokterTransPelayanan" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

                <input type="hidden" name="dokter_trans_pelayanan" id="dokter_trans_pelayanan" class="form-control">
            </div>

        </div>

        <div class="form-group" id="dr2" style="display:none">
          <label class="control-label col-sm-2">Dokter 2</label>
            <div class="col-sm-4">

                <input id="inputDokterTransPelayanan2" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

                <input type="hidden" name="dokter_trans_pelayanan2" id="dokter_trans_pelayanan2" class="form-control">
            </div>
              
        </div>

        <input type="hidden" name="kode_penunjang_dr" id="kode_penunjang_dr">
        <input type="hidden" name="kode_trans_pelayanan" id="kode_trans_pelayanan">

      </form>

      </div>

      <div class="modal-footer no-margin-top">

        <div style="text-align:center;">
            <a href="#" class="btn btn-xs btn-primary" id="btn_edit_dokter_selesai"> <i class="fa fa-edit"></i> Selesai </a>
        </div>

      </div>

    </div><!-- /.modal-content -->

<!--  </div> /.modal-dialog -->

<!--</div> -->



