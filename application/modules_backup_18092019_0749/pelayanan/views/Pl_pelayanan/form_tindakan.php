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
  /*define first load*/
  $('#InputKeyDokterBagian1').val( $('#dokter_pemeriksa').val() );
  
  if( $('#kode_bagian_val').val()=='020101'){
    $('#pl_kode_dokter_hidden1').val( $('#kode_dokter_igd').val() );
  }else if( $('#kode_bagian_val').val()=='050101'){
    $('#pl_kode_dokter_hidden1').val( 55 );
  }else{
    $('#pl_kode_dokter_hidden1').val( $('#kode_dokter_poli').val() );
  }
  
  
  //initiate dataTables plugin
    var url_tindakan = "<?php echo ($value->flag_mcu!=1)?'get_data_tindakan?bagian='. $sess_kode_bag .'&jenis=tindakan&kode='.$no_kunjungan.'':'get_data_tindakan_mcu?kode='.$no_kunjungan.'&bagian='.$sess_kode_bag.'' ?>";
    oTableTindakan = $('#table-tindakan').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": "pelayanan/Pl_pelayanan/"+url_tindakan,
          "type": "POST"
      },
      "columnDefs": [
            { 
                "targets": [ 0 ], //last column
                "orderable": false, //set not orderable
            },
            {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
            { "visible": true, "targets": [0] },
        ],

    });

    $('#table-tindakan tbody').on('click', 'td.details-control', function () {
        preventDefault();
        var tr = $(this).closest('tr');
        var row = oTableTindakan.row( tr );
        var data = oTableTindakan.row( $(this).parents('tr') ).data();
        var kode_trans_pelayanan = data[ 2 ];
                  

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/
            
            $.getJSON("pelayanan/Pl_pelayanan/get_transaksi_by_id?type=html&kode=" + kode_trans_pelayanan, '', function (data) {
                response_data = data;
                // Open this row
                row.child( format( response_data ) ).show();
                tr.addClass('shown');
            });
            
        }
    } );

    $('#table-tindakan tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            //achtungShowLoader();
            $(this).removeClass('selected');
            //achtungHideLoader();
        }
        else {
            //achtungShowLoader();
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //achtungHideLoader();
        }
    } );

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

    var url_tindakan = '<?php echo ($sess_kode_bag=='050301')?'getTindakanFisioByBagianAutoComplete':'getTindakanByBagianAutoComplete' ?>';
    $('#InputKeyTindakan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/"+url_tindakan,
                data: { keyword:query, kode_klas: $('#kode_klas_val').val(), kode_bag : $('#kode_bagian_val').val(), kode_perusahaan : $('input[name=jenis_tarif]:checked').val() },            
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
          $('#pl_kode_tindakan_hidden').val(val_item);
          $('.InputKeyDokterBagian').focus();
          /*get detail tarif by kode tarif and kode klas*/
          getDetailTarifByKodeTarifAndKlas(val_item, $('#kode_klas_val').val());
        }

    });

    var kelas = ($('#klas_titipan').val()==0)?$('#kode_klas_val').val():$('#klas_titipan').val();
    $('#InputKeyTindakan_ri').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getTindakanRIByBagianAutoComplete",
                data: { keyword:query, kode_klas: kelas, kode_perusahaan : $('input[name=jenis_tarif]:checked').val() },           
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
          $('#pl_kode_tindakan_hidden').val(val_item);
          $('.InputKeyDokterBagian').focus();
          /*get detail tarif by kode tarif and kode klas*/
          getDetailTarifByKodeTarifAndKlas(val_item, kelas);
        }

    });

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


    $('#btn_add_tindakan').click(function (e) {   
      e.preventDefault();

      if($('#tindakan_lainnya').val() == "undefined"){
        if( $('#pl_kode_tindakan_hidden').val() == '' ){
          alert('Silahkan cari tindakan !'); return false;
        }
      }
     
      /*jika ada bill_dr1 maka pilih dokter dulu, pending process and dev
      if( $('#pl_kode_dokter_hidden1').val() == '' ){
        alert('Silahkan cari dokter !'); return false;
      }*/

      /*process add tindakan*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan/process_add_tindakan",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              if(confirm('Apakah anda akan menambahkan tindakan lainnya?')){
                e.preventDefault();
                $('#InputKeyTindakan').focus(); 
                $('#pl_jumlah').val(1);
                $('#satuan_tindakan').val('#');
              }else{
                e.preventDefault();
                var scrollPos =  $("#inputKeyObat").offset().top;
                $(window).scrollTop(scrollPos);
                $('#inputKeyObat').focus(); 
              }

              /*reset all field*/
              $('#InputKeyTindakan').val('');
              $('#InputKeyTindakan_ri').val('');
              /*$('#pl_kode_dokter_hidden1').val('');*/
              /*$('#InputKeyDokterBagian1').val('');*/
              $('#detailTarifHtml').html('');
              $('#formDetailTarif').hide('fast');
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
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();

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

    $('#btn_add_sarana_fisio').click(function (e) {   
      e.preventDefault();

      /*process add konsultasi*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan/process_add_tindakan?type=sarana_fisio",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();

            if(response.status==200) {
              /*reset all field*/
              $('#InputKeyTindakan').val('');
              $('#InputKeyTindakan_ri').val('');
              /*$('#pl_kode_dokter_hidden1').val('');
              $('#InputKeyDokterBagian1').val('');*/
              $('#detailTarifHtml').html('');
              $('#formDetailTarif').hide('fast');
            }
            
          }
      });

    });

    $('#btn_add_tindakan_luar').click(function (e) {   
      e.preventDefault();

      /*process add konsultasi*/
      $.ajax({
          url: "templates/references/tindakanLainnya?tindakan_lainnya=tindakan_luar",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/

            if(response.status==200) {
              /*reset all field*/
              $('#formDetailTarif').show('fast');
              $('#detailTarifHtml').html(response.html);
            }
            
          }
      });

    });

    $('#btn_add_lain').click(function (e) {   
      e.preventDefault();

      /*process add konsultasi*/
      $.ajax({
          url: "templates/references/tindakanLainnya?tindakan_lainnya=lain_lain",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/

            if(response.status==200) {
              /*reset all field*/
              $('#formDetailTarif').show('fast');
              $('#detailTarifHtml').html(response.html);
            }
            
          }
      });

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

    $('input[name=jenis_tarif]').click(function(e){
        var field = $('input[name=jenis_tarif]:checked').val();
        if ( field == 'Jaminan Perusahaan' ) {
          $('#showFormPerusahaan').show('fast');
        }else if (field == 'Umum') {
          $('#kodePerusahaanHidden').val(0);
          $('#showFormPerusahaan').hide('fast');
        }
    });



});

function format ( data ) {
  return data.html;
}

function getDokterAutoComplete(num){

  $('#InputKeyDokterBagian'+num+'').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getDokterByBagian",
              data: { keyword:query, bag:"<?php echo $sess_kode_bag?>" },            
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
    oTableTindakan.ajax.url('pelayanan/Pl_pelayanan/get_data_tindakan?bagian=<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>&jenis=tindakan&kode=<?php echo $no_kunjungan?>').load();
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

  document.getElementById("input_file"+counterfile).innerHTML = "<div id=\"file"+counternextfile+"\" class='clonning_form'><div class='form-group'><label class='control-label col-sm-2'>&nbsp;</label><div class='col-sm-3'><input type='text' class='form-control' onclick='getDokterAutoComplete("+counterfile+")' id='InputKeyDokterBagian"+counterfile+"' name='pl_nama_dokter[]' placeholder='Masukan Keyword Nama Dokter'><input type='hidden' class='form-control' id='pl_kode_dokter_hidden"+counterfile+"' name='pl_kode_dokter_hidden[]' ></div><div class='col-md-1' style='margin-left: -2%'><input type='button' onclick='hapus_file("+counternextfile+",0)' value=' x ' class='btn btn-xs btn-danger'/></div></div></div><div id=\"input_file"+counternextfile+"\"></div>";

  counterfile++;

}

</script>

<div class="row">
    <div class="col-sm-12">
        <p><b> TINDAKAN PASIEN <i class="fa fa-angle-double-right bigger-120"></i></b></p>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Tanggal</label>
              <div class="col-md-3">
                    
                <div class="input-group">
                    
                    <input name="pl_tgl_transaksi" id="pl_tgl_transaksi" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                    <span class="input-group-addon">
                      
                      <i class="ace-icon fa fa-calendar"></i>
                    
                    </span>
                  </div>
              
              </div>
        </div>

        <?php if($type=='PM'):?>
          <div class="form-group">
            <label class="control-label col-sm-2">Jenis Layanan</label>
            <div class="col-md-8">
              <div class="radio">
                    <label>
                      <input name="jenis_layanan" type="radio" class="ace" value="<?php echo isset($cito)?($cito==1)?$cito:'':1?>" <?php echo isset($cito)?($cito==1)?'checked':'':''?>/>
                      <span class="lbl"> Cito</span>
                    </label>
                    <label>
                      <input name="jenis_layanan" type="radio" class="ace" value="<?php echo isset($cito)?($cito!=1)?$cito:'':0?>" <?php echo isset($cito)?($cito!=1)?'checked':'':'checked'?> />
                      <span class="lbl"> Biasa </span>
                    </label>
              </div>
            </div>
          </div>
          <input type="hidden" class="form-control" id="kode_penunjang" name="kode_penunjang" value="<?php echo isset($kode_penunjang)?$kode_penunjang:0?>">
        <?php endif ?>

        <div class="form-group">
          <label class="control-label col-sm-2">Jenis Tarif Tindakan</label>
          <div class="col-md-8">
            <div class="radio">
                  <label>
                    <input name="jenis_tarif" type="radio" class="ace" value="120" />
                    <span class="lbl"> BPJS</span>
                  </label>
                  <label>
                    <input name="jenis_tarif" type="radio" class="ace" value="0" checked />
                    <span class="lbl"> Non BPJS </span>
                  </label>
            </div>
          </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Nama Tindakan</label>
            <div class="col-sm-6">
              <?php if($type=='Ranap'){?>
                <input type="text" class="form-control" id="InputKeyTindakan_ri" name="pl_nama_tindakan_ri" placeholder="Masukan Keyword Tindakan">
              <?php }else{ ?>
                <input type="text" class="form-control" id="InputKeyTindakan" name="pl_nama_tindakan" placeholder="Masukan Keyword Tindakan">
              <?php } ?>
                <input type="hidden" class="form-control" id="pl_kode_tindakan_hidden" name="pl_kode_tindakan_hidden" >
            </div>
            <label class="control-label col-sm-1" for="">Keterangan</label>
            <div class="col-sm-3">
               <input type="text" class="form-control" id="pl_keterangan_tindakan" name="pl_keterangan_tindakan">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Jumlah</label>
            <div class="col-sm-1">
               <input type="number" min="1" class="form-control" id="pl_jumlah" name="pl_jumlah" value="1">
            </div>
            <label class="control-label col-sm-1" for="">Satuan</label>
            <div class="col-sm-2">
               <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'satuan_tindakan')), '' , 'satuan_tindakan', 'satuan_tindakan', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group" id="formDetailTarif" style="display:none">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-10" style="margin-left:6px">
               <div id="detailTarifHtml"></div>
               <button type="button" class="btn btn-xs btn-danger" id="btn_hide_tindakan_luar" onclick="backToDefault()"> <i class="fa fa-angle-double-left"></i> Sembunyikan </button>
            </div>
        </div>
        <div class="form-group">
            
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="">Dokter</label>
            <div class="col-sm-3">
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
        <?php if($status_pulang!=1 AND $value->flag_mcu!=1) :?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-10" style="margin-left:6px">
               <a href="#" class="btn btn-xs btn-primary" id="btn_add_tindakan"> <i class="fa fa-plus"></i> Tambahkan </a>
               <?php if($type=='Rajal') :?>
               <a href="#" class="btn btn-xs btn-success" id="btn_add_konsultasi"> <i class="fa fa-money"></i> Masukan Billing Konsultasi dan Sarana RS</a>
               <?php endif;?>
               <?php if($type=='PM' AND $sess_kode_bag=='050301') :?>
               <a href="#" class="btn btn-xs btn-success" id="btn_add_sarana_fisio"> <i class="fa fa-money"></i> Masukan Billing Sarana Fisioterapi</a>
               <?php endif;?>
               <a href="#" class="btn btn-xs btn-info" id="btn_add_tindakan_luar">Tindakan Luar</a> 
               <a href="#" class="btn btn-xs btn-warning" id="btn_add_lain">Tindakan Lain-Lain</a> 
            </div>
        </div>
        <?php endif;?>

    </div>
</div>

<div class="row">
      <div class="col-sm-12">
        <table id="table-tindakan" class="table table-bordered table-hover" >
           <thead>
            <tr>  
              <th width="40px"></th>
              <th width="80px"></th>
              <th>Kode</th>
              <th>Tanggal</th>
              <th>Nama Tindakan</th>
              <th>Jumlah</th>
              <th>Dokter</th>
              <th style="width:100px">Total Tarif</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

    </div>

</div>

<?php if(($type=='Rajal' OR ($type=='PM' AND $sess_kode_bag=='050301')) AND ($value->flag_mcu!=1)) : ?>
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
            <div class="col-sm-2">
               <input type="text" class="form-control" name="pl_jumlah_obat" id="pl_jumlah_obat" value="1">
            </div>
        </div>

        <div class="form-group" style="display:none" id="div_detail_obat">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-10" style="margin-left:6px">
              <div id="warning_stok_obat"></div>
               <div id="detailObatHtml"></div>
            </div>
        </div>

        <?php if($status_pulang!=1) :?>
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
<?php endif;?>

<div id="Modal_edit" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:100%;  margin-top: 50px; margin-bottom:50px;width:50%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">Edit Transaksi</span>

        </div>

      </div>

      <div class="modal-body">

      <form action="" id="form_edit_tindakan">

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Tanggal</label>
              <div class="col-md-3">
                <input type="hidden" class="form-control" id="kode_trans_pelayanan" name="kode_trans_pelayanan">
                    
                <div class="input-group">
                    
                    <input name="pl_tgl_transaksi_edit" id="pl_tgl_transaksi_edit" class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                      
                      <i class="ace-icon fa fa-calendar"></i>
                    
                    </span>
                  </div>
              
              </div>
        </div>

        <?php if($type!='Ranap') : ?>
          <div id="detailEditTarif"></div>
        <?php endif ?>

        </form>

      </div>

      <div class="modal-footer no-margin-top">

        <div style="text-align:center;">
            <a href="#" class="btn btn-xs btn-primary" id="btn_edit_tindakan"> <i class="fa fa-edit"></i> Submit </a>
        </div>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>




