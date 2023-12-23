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

    var url_tindakan = 'getTindakanByKunjungan';
    $('#InputKeyTindakan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/"+url_tindakan,
                data: { keyword:query, no_registrasi : $('#no_registrasi').val(), no_kunjungan : $('#no_kunjungan').val() },            
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
          var kode_klas= item.split(':')[2];
          console.log(val_item);
          $('#kode_klas').val(kode_klas);
          $('#pl_kode_tindakan_hidden').val(val_item);
          $('.InputKeyDokterBagian').focus();
          /*get detail tarif by kode tarif and kode klas*/
          getDetailTarifByKodeTarifAndKlas(val_item, kode_klas);
        }

    });

    $('#btn_add_tindakan').click(function (e) {   
      e.preventDefault();

      /*jika ada bill_dr1 maka pilih dokter dulu, pending process and dev
      if( $('#pl_kode_dokter_hidden1').val() == '' ){
        alert('Silahkan cari dokter !'); return false;
      }*/

      /*process add tindakan*/
      $.ajax({
          url: "billing/Billing/process_add_tindakan",
          data: $('#form_billing_kasir').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            if(response.status==200) {
              load_billing_data();
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

    });

    $('#btn_add_konsultasi').click(function (e) {   
      e.preventDefault();

      /*process add konsultasi*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan/process_add_tindakan?type=konsultasi",
          data: $('#form_billing_kasir').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {

            if(response.status==200) {
              /*reset all field*/
              $('#InputKeyTindakan').val('');
              $('#InputKeyTindakan_ri').val('');
              /*$('#pl_kode_dokter_hidden1').val('');*/
              /*$('#InputKeyDokterBagian1').val('');*/
              $('#detailTarifHtml').html('');
              $('#formDetailTarif').hide('fast');
            }
            
          }
      });

    });

    $('#btn_edit_tindakan').click(function (e) {  

      e.preventDefault();
      /*process add obat*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan/process_edit_tindakan",
          data: $('#form_edit_tindakan').serialize(),                
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              $("#Modal_edit").modal('hide');  
            }else{
              alert('Error'); return false;
            }
            
          }
      });

    });


});

function format ( data ) {
  return data.html;
}


function getDokterAutoComplete(num){

  $('#InputKeyDokterBagian'+num+'').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query, bag:"0" },            
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
        next = num + 1;
        $('#pl_kode_dokter_hidden'+num+'').val(val_item);
        $('#btn_add_tindakan').focus();
      }

  });

}

function getDetailTarifByKodeTarifAndKlas(kode_tarif, kode_klas){

  $.getJSON("<?php echo site_url('templates/references/getDetailTarif') ?>?kode="+kode_tarif+"&klas="+kode_klas+"&type=html", '' , function (data) {

    /*show detail tarif html*/
    $('#formDetailTarif').show('fast');
    $('#detailTarifHtml').html(data.html);

  })

}



function edit_transaksi(myid){

  preventDefault();
  
  $.ajax({
    url: 'pelayanan/Pl_pelayanan/get_transaksi_by_id',
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
        var datetime = jsonResponse.tgl;
        var date = datetime.split(' ')[0];
        $("#kode_trans_pelayanan").val(myid);
        $("#pl_tgl_transaksi_edit").val(date);
        $('#detailEditTarif').html(jsonResponse.html);
        
        $("#Modal_edit").modal();
      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }

  });
  
}

function backToDefault(){

  $('#formDetailTarif').hide('fast');
  $('#detailTarifHtml').html('');

}

counterfile = <?php $j=2;echo $j.";";?>

function hapus_file(a, b)

{

  if(b != 0){
    /*$.getJSON("<?php echo base_url('posting/delete_file') ?>/" + b, '', function(data) {
        document.getElementById("file"+a).innerHTML = "";
        greatComplate(data);
    });*/
  }else{
    y = a ;
    document.getElementById("file"+a).innerHTML = "";
  }

}

function tambah_file()

{

  counternextfile = counterfile + 1;

  counterIdfile = counterfile + 1;

  document.getElementById("input_file"+counterfile).innerHTML = "<div id=\"file"+counternextfile+"\" class='clonning_form'><div class='form-group'><label class='control-label col-sm-2'>&nbsp;</label><div class='col-sm-4'><input type='text' class='form-control' onclick='getDokterAutoComplete("+counterfile+")' id='InputKeyDokterBagian"+counterfile+"' name='pl_nama_dokter[]' placeholder='Masukan Keyword Nama Dokter'><input type='hidden' class='form-control' id='pl_kode_dokter_hidden"+counterfile+"' name='pl_kode_dokter_hidden[]' ></div><div class='col-md-1' style='margin-left: -2%'><input type='button' onclick='hapus_file("+counternextfile+",0)' value=' x ' class='btn btn-xs btn-danger'/></div></div></div><div id=\"input_file"+counternextfile+"\"></div>";

  counterfile++;

}

</script>

<input type="hidden" name="kode_klas" value="" id="kode_klas">

<div id="accordion" class="accordion-style1 panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOneForm" aria-expanded="true">
                    <i class="bigger-110 ace-icon fa fa-angle-down" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                    &nbsp;TAMBAH BILLING TINDAKAN
                </a>
            </h4>
        </div>

        <div class="panel-collapse collapse" id="collapseOneForm" aria-expanded="true" style="">
            <div class="panel-body">
                <br>
                <div class="col-sm-12">
                    <p><b> TAMBAHKAN BILLING TINDAKAN <i class="fa fa-angle-double-right bigger-120"></i></b></p>

                    <div class="form-group">
                      <label class="control-label col-sm-2" for="">Tanggal</label>
                      <div class="col-md-2">
                        <div class="input-group">
                            <input name="pl_tgl_transaksi" id="pl_tgl_transaksi" placeholder="<?php echo date('Y-m-d')?>" class="form-control date-picker" data-date-format="yyyy-mm-dd" type="text" value="<?php echo date('Y-m-d')?>">
                            <span class="input-group-addon">
                              <i class="ace-icon fa fa-calendar"></i>
                            </span>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="">Unit/Bagian</label>
                        <div class="col-sm-4">
                            <?php echo $this->master->custom_selection_with_data($arr_data=array('data' => $kunjungan, 'value' => 'no_kunjungan', 'label' => 'nama_bagian'), '','no_kunjungan','no_kunjungan','form-control','','',''); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="">Nama Tindakan</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="InputKeyTindakan" name="pl_nama_tindakan" placeholder="Masukan Keyword Tindakan">
                            <input type="hidden" class="form-control" id="pl_kode_tindakan_hidden" name="pl_kode_tindakan_hidden" >
                        </div>
                    </div>

                    <div class="col-sm-12" id="formDetailTarif" style="display:none; margin-bottom: 3px; padding: 5px">
                      <div id="detailTarifHtml"></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="">Dokter Pemeriksa</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="InputKeyDokterBagian1" onclick="getDokterAutoComplete(1)" name="pl_nama_dokter[]" placeholder="Masukan Keyword Nama Dokter">
                          <input type="hidden" class="form-control" id="pl_kode_dokter_hidden1" name="pl_kode_dokter_hidden[]" >
                        </div>
                        <div class ="col-md-1" style="margin-left: -2%">
                          <input onClick="tambah_file()" value="+" type="button" class="btn btn-xs btn-info" />
                        </div>
                    </div>

                    
                    <div id="clone_form_dokter">
                      <div id="input_file<?php echo $j;?>"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="">&nbsp;</label>
                        <div class="col-sm-10" style="margin-left:6px">
                          <a href="#" class="btn btn-xs btn-primary" id="btn_add_tindakan"> <i class="fa fa-plus"></i> Tambahkan </a>
                          <!-- <a href="#" class="btn btn-xs btn-info" id="btn_add_tindakan_luar">Tindakan Luar</a> 
                          <a href="#" class="btn btn-xs btn-warning" id="btn_add_lain">Tindakan Lain-Lain</a>  -->
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </div>

</div>



<hr>




