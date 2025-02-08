<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>


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

  $('.format_number').number( true, 2 );

});

$(document).ready(function() {
  /*define first load*/
  $('#InputKeyDokterBagian1').val( $('#dokter_pemeriksa').val() );
  $('#pl_kode_dokter_hidden1').val( $('#dokter1').val() );
  
  var is_cito = $('#jenis_layanan').val();
  var kode_tarif_existing = $('#kode_tarif_existing').val();
  var kode_klas_existing = $('#kode_klas_val2').val();

  $('#pl_kode_tindakan_hidden').val( kode_tarif_existing );
  $('#InputKeyTindakan').val( $('#nama_tarif_existing').val() );

  getDetailTarifByKodeTarifAndKlas(kode_tarif_existing, kode_klas_existing);

  //initiate dataTables plugin
    oTableTindakan = $('#table-tindakan').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      // "scrollX": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan/get_data_tindakan?bagian=<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>&jenis=tindakan&kode=<?php echo $no_kunjungan?>&id_pesan_bedah=<?php echo $id_pesan_bedah?>",
          "type": "POST"
      },
      "columnDefs": [
            { 
                "targets": [ 0 ], //last column
                "orderable": false, //set not orderable
            },
            {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
            { "visible": true, "targets": [ 0 ] },
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
            
            $.getJSON("pelayanan/Pl_pelayanan/get_transaksi_by_id?type=html&urgensi="+is_cito+"&kode=" + kode_trans_pelayanan, '', function (data) {
                response_data = data;
                // Open this row
                row.child( format_html( response_data ) ).show();
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



    $('#InputKeyTindakan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getTindakanByBagianAutoComplete",
                data: { keyword:query, kode_klas: $('#kode_klas_val2').val(), kode_bag : $('#kode_bagian_val').val(), kode_perusahaan : $('#kode_perusahaan').val(), jenis_bedah : $('#jenis_bedah').val(), show_all : $('input[name="show_all_tarif"]:checked').val(), jenis_tarif : $('input[name="jenis_tarif"]:checked').val() },            
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
          $('.InputKeyDokterBagian').focus();
          /*get detail tarif by kode tarif and kode klas*/
          getDetailTarifByKodeTarifAndKlas(val_item, $('#kode_klas_val2').val());
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

      if( $('#tindakan_luar').val() != 1 ){
        if( $('#pl_kode_tindakan_hidden').val() == '' ){
          alert('Silahkan cari tindakan !'); return false;
        }
        url_action = "pelayanan/Pl_pelayanan/process_add_tindakan";
      }else{
        url_action = "pelayanan/Pl_pelayanan/process_add_tindakan_lain";
      }
     
      /*process add tindakan*/
      $.ajax({
          url: url_action,
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
              $('#pl_kode_dokter_hidden1').val('');
              $('#InputKeyDokterBagian1').val('');
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
              $('#pl_kode_dokter_hidden1').val('');
              $('#InputKeyDokterBagian1').val('');
              $('#detailTarifHtml').html('');
              $('#formDetailTarif').hide('fast');
            }
            
          }
      });

    });

    $('#btn_add_tindakan_luar').click(function (e) {  

      e.preventDefault();
      $('#tindakan_luar').val(1);
      $('#change_form_tindakan').load('pelayanan/Pl_pelayanan/form_tindakan_lain?flag=luar');
      $('#default_form_tindakan').hide('fast');
      $('#detailTarifHtml').html('');

    });

    $('#kode_klas_val2').change(function (e) {  

      e.preventDefault();
      $('#kode_klas_val').val($(this).val());

    });

    $('#btn_add_tindakan_lain').click(function (e) {  

      e.preventDefault();
      $('#tindakan_luar').val(1);
      $('#change_form_tindakan').load('pelayanan/Pl_pelayanan/form_tindakan_lain?flag=lain');
      $('#default_form_tindakan').hide('fast'); 
      $('#detailTarifHtml').html('');
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


    $('select[name=jenis_bedah]').change(function(e){
      backToDefault();
    });



});


$('#kode_klas_val2').change(function(e){
  getDetailTarifByKodeTarifAndKlas($('#pl_kode_tindakan_hidden').val(), $('#kode_klas_val2').val());
});

function format_html ( data ) {
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
    $('select[name=jenis_bedah]').val(data.jenis_bedah);

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
    oTableTindakan.ajax.url('pelayanan/Pl_pelayanan/get_data_tindakan?bagian=<?php echo ($sess_kode_bag)?$sess_kode_bag:0?>&jenis=tindakan&kode=<?php echo $no_kunjungan?>&id_pesan_bedah=<?php echo $id_pesan_bedah?>').load();
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
  $('#InputKeyTindakan').val('');
  $('#pl_kode_tindakan_hidden').val('');
  $('#pl_keterangan_tindakan').val('');

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
        <div class="pull-right">
          <?php if( in_array($this->session->userdata('user')->role, array('Admin Sistem')) ):?>
            <a href="#" class="btn btn-xs btn-inverse" id="btn_add_tindakan_luar">Tindakan Luar RS </a> 
            <a href="#" class="btn btn-xs btn-inverse" id="btn_add_tindakan_lain">Tindakan Lainnya </a> 
          <?php endif; ?>
          <!-- hidden form -->
          <input type="hidden" name="tindakan_luar" id="tindakan_luar">
        </div>
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

        <div class="form-group">
          <label class="control-label col-sm-2">Jenis Tarif</label>
          <div class="col-md-8">
            <div class="radio">
              <label>
                <input name="jenis_tarif" type="radio" class="ace" value="120" />
                <span class="lbl"> BPJS</span>
              </label>
              <label>
                <input name="jenis_tarif" type="radio" class="ace" value="0" checked/>
                <span class="lbl"> Umum dan Asuransi </span>
              </label>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-2">Jenis Layanan</label>
            <div class="col-md-8">
              <div class="radio">
                    <label>
                      <input name="jenis_layanan" type="radio" class="ace" value="" checked/>
                      <span class="lbl"> Operasi sama</span>
                    </label>
                    <label>
                      <input name="jenis_layanan" type="radio" class="ace" value="" />
                      <span class="lbl"> Operasi berbeda </span>
                    </label>
              </div>
            </div>
        </div>
        <br>
        <p><b> <i class="fa fa-search"></i> PENCARIAN NAMA TINDAKAN <i class="fa fa-angle-double-right bigger-120"></i></b></p>

        <div id="default_form_tindakan">

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Kelas Pasien</label>
                <div class="col-sm-3">
                <?php echo $this->master->custom_selection(array('table'=>'mt_klas', 'where'=>array(), 'id'=>'kode_klas', 'name' => 'nama_klas'),($value->kode_klas)?$value->kode_klas:'','kode_klas','kode_klas_val2','chosen-slect form-control','');?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Jenis Bedah</label>
                <div class="col-sm-4">
                <?php echo $this->master->custom_selection(array('table'=>'mt_master_tarif', 'where'=>array('is_active'=>'Y', 'tingkatan' => 3, 'kode_bagian' => $sess_kode_bag), 'id'=>'kode_tarif', 'name' => 'nama_tarif'),'','jenis_bedah','jenis_bedah','chosen-slect form-control','');?>
                </div>
                <div class="checkbox">
                  <label>
                    <input name="show_all_tarif" id="show_all_tarif" type="checkbox" class="ace" value="1">
                    <span class="lbl"><i>Tampilkan tarif dari seluruh jenis bedah</i></span>
                  </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Nama Tindakan</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="InputKeyTindakan" name="pl_nama_tindakan" placeholder="Masukan Keyword Tindakan">
                    <input type="hidden" class="form-control" id="pl_kode_tindakan_hidden" name="pl_kode_tindakan_hidden" >
                </div>
                <label class="control-label col-sm-2" for="">Jumlah</label>
                <div class="col-sm-1">
                   <input type="number" min="1" class="form-control" id="pl_jumlah" name="pl_jumlah" value="1">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">Keterangan</label>
                <div class="col-sm-10">
                   <textarea type="text" class="form-control" id="pl_keterangan_tindakan" name="pl_keterangan_tindakan" style="height: 50px"></textarea>
                </div>
            </div>

            <div class="form-group" id="formDetailTarif" style="display:none">
                <div class="col-sm-11 no-padding">
                   <div id="detailTarifHtml"></div>
                </div>
                <div class="col-sm-1" style="margin-top:6%">
                  <button type="button" class="btn btn-xs btn-danger" id="btn_hide_tindakan_luar" onclick="backToDefault()"> <i class="fa fa-angle-double-left"></i> Hide </button>
                </div>
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
        </div>

        <div id="change_form_tindakan"></div>

        <div class="col-sm-12 left no-padding">
           <a href="#" class="btn btn-xs btn-primary" id="btn_add_tindakan"> <i class="fa fa-plus"></i> Tambahkan Tindakan </a>               
        </div>


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
        <br>
        <br>
        <br>
        <br>
        <br>
    </div>

</div>


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

        <div id="detailEditTarif"></div>

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




