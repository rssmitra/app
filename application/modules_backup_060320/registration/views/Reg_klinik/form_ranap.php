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

    $('input[name="ri_status_pasien"]').click(function (e) {
      
      var value = $(this).val();

      if (value==1) {

        $('#ri_klas_titipan_form').show('fast');
        $('#ri_klas_titipan_ruangan').val('');

      }else{

        $('#ri_klas_titipan_form').hide('fast');

      }

    }); 


    // $('#ri_diagnosa_masuk').typeahead({
    //       source: function (query, result) {
    //           $.ajax({
    //               url: "ws_bpjs/Ws_index/getRef?ref=refDiagnosa",
    //               data: 'keyword=' + query,            
    //               dataType: "json",
    //               type: "POST",
    //               success: function (response) {
    //                 result($.map(response, function (item) {
    //                       return item;
    //                   }));
                    
    //               }
    //           });
    //       },
    //       afterSelect: function (item) {
    //         // do what is needed with item
    //         var label_item=item.split(':')[1];
    //         var val_item=item.split(':')[0];
    //         console.log(val_item);
    //         $('#ri_diagnosa_masuk').val(label_item);
    //         //$('#inputKeyDiagnosa').val(label_item);
    //       }

    //   });
    
      $('#ri_diagnosa_masuk').typeahead({
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
            $('#ri_diagnosa_masuk').val(label_item);
            //$('#inputKeyDiagnosa').val(label_item);
          }

      });

    $('#inputDokterPengirim').typeahead({
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
            $('#ri_dokter_pengirim').val(val_item);
            
        }
    });

  $('select[name="ri_ruangan"]').change(function () {      

        $('#ri_no_bed_hidden').val('');
        $('#ri_no_ruangan').val('');

        if ($(this).val()) {          

            $.getJSON("<?php echo site_url('Templates/References/getDeposit') ?>/" + $(this).val() + "/" +$("#ri_klas_ruangan").val(), '', function (data) {                       

                $.each(data, function (i, o) {                  

                    console.log(o.deposit)
                    deposit = (o.deposit!==null)?addPeriod(o.deposit):0;

                    harga_ruangan = addPeriod(o.harga_r);

                    harga_ruangan_bpjs = addPeriod(o.harga_bpjs);

                    $('#ri_deposit').val(o.deposit);

                    $('#ri_deposit_show').val(deposit);

                    $('#ri_harga_ruangan_hidden').val(o.harga_r);

                    $('#ri_harga_ruangan_bpjs_hidden').val(o.harga_bpjs);

                });                

            }); 


            $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian') ?>/" + $(this).val(), '', function (data) {              

                $('#ri_dokter_ruangan option').remove();                

                $('<option value="">-Pilih Dokter-</option>').appendTo($('#ri_dokter_ruangan'));                

                $.each(data, function (i, o) {                  

                    $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#ri_dokter_ruangan'));                    

                });                

            });            

        } else {          

            $('#ri_klas_ruangan option').remove()  

             $('#ri_dokter_ruangan option').remove()          

        }        

    });  

    $('#inputDokterMerawat').typeahead({
        source: function (query, result) {
                $.ajax({
                    url: "templates/references/getDokterByBagian",
                    data: 'keyword=' + query + '&bag=' + $('#ri_ruangan').val(),         
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
            $('#ri_dokter_ruangan').val(val_item);
            
        }
    });

     $('select[name="ri_klas_ruangan"]').change(function () {      


        /*hide value*/
        $('#ri_no_bed_hidden').val('');
        $('#ri_no_ruangan').val('');
        $('#ri_deposit').val(0);
        $('#ri_harga_ruangan_hidden').val('');
        $('#ri_harga_ruangan_bpjs_hidden').val('');

        if ($(this).val()) {          

            /*get ruangan by klas*/
            $.getJSON("<?php echo site_url('Templates/References/getRuanganByKlas') ?>/" + $(this).val(), '', function (data) {              

                $('#ri_ruangan option').remove();                

                $('<option value="">-Pilih Ruangan-</option>').appendTo($('#ri_ruangan'));                

                $.each(data, function (i, o) {                  

                    $('<option value="' + o.kode_bagian + '">' + o.nama_bagian + '</option>').appendTo($('#ri_ruangan'));                    

                });                

            }); 

        }    

    });  

    function addPeriod(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }

    function showModalBed() {

        $.getJSON("<?php echo site_url('Templates/References/getBedByKlasWithView') ?>/" + $("#ri_ruangan").val() + "/" +  $("#ri_klas_ruangan").val(), '', function (data) {              
            
        //    achtungHideLoader();
            
            $('#result_bed').html(data.html);

            showModal_bed();  

        });       

    }

    function showModal_bed()

    {  

        $("#modalBed").modal();  

    }


    function select_bed_from_modal_bed(kode,bed,kamar){
        
        preventDefault();

        $("#modalBed").modal('hide');

        $('#div_load_after_selected_pasien').show('fast');

        $('#div_riwayat_pasien').show('fast');

        $('#ri_no_ruangan').val(kode);

        $('#ri_no_ruangan').text(kode);  

        $('#ri_no_bed_hidden').val(bed);

        $('#ri_no_ruangan').focus();

        kelas = $('#ri_klas_ruangan option:selected').text();
        ruangan = $('#ri_ruangan option:selected').text();

        $('#text_title_selected_ruangan_klas').text(ruangan+' '+kelas);

        /*Table Value*/
        $('#td_kode_ruangan').text(kode);  
        $('#td_nama_ruangan').text(ruangan);  
        $('#td_kelas').text(kelas);  
        $('#td_bed').text(bed);  
        $('#td_kamar').text(kamar);  

    }


</script>

<hr>

<p><b><i class="fa fa-edit"></i> PENDAFTARAN RAWAT INAP </b></p>

<div class="form-group">

    <label class="control-label col-sm-3">Tanggal Masuk</label>
  
    <div class="col-md-3">
        
        <div class="input-group">
            
            <input name="ri_tgl_registrasi" id="ri_tgl_registrasi" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
            <span class="input-group-addon">
            
            <i class="ace-icon fa fa-calendar"></i>
            
            </span>
        </div>
    
    </div>

</div>

<div class="form-group">
    <label class="control-label col-sm-3">Rujukan dari</label>
    <div class="col-md-5">
    <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'rujukan_dari')), '' , 'ri_rujukan_dari', 'ri_rujukan_dari', 'form-control', '', '') ?>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3">Dokter pengirim</label>
    <div class="col-sm-6">
      <input id="inputDokterPengirim" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
        <input type="hidden" name="ri_dokter_pengirim" id="ri_dokter_pengirim" class="form-control">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3">Obstetri</label>
    <div class="col-md-6">
        <div class="radio">
            <label>
                <input name="is_obstetri" type="radio" class="ace" value="1" />
                <span class="lbl"> Ya </span>
            </label>
            <label>
            <input name="is_obstetri" type="radio" class="ace" value="0" checked="checked" />
                <span class="lbl">Tidak</span>
            </label>
        </div>
    </div>
</div>

<div class="form-group">

    <label class="control-label col-sm-3">*Diagnosa Masuk</label>

    <div class="col-sm-6">
      <!-- <input id="inputKeyDiagnosa" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" /> -->

        <input type="text" name="ri_diagnosa_masuk" id="ri_diagnosa_masuk" class="form-control" placeholder="Masukan keyword minimal 3 karakter">

    </div>

</div>

<p><b><i class="fa fa-edit"></i> PEMILIHAN RUANGAN DAN KELAS PASIEN </b></p>

<div class="form-group">
    <label class="control-label col-sm-3">Status pasien</label>
    <div class="col-md-4">
        <div class="radio">
            <label>
                <input name="ri_status_pasien" type="radio" class="ace" value="1" />
                <span class="lbl"> Titipan </span>
            </label>
            <label>
                <input name="ri_status_pasien" type="radio" class="ace" value="0" checked="checked" />
                <span class="lbl"> Bukan Titipan </span>
            </label>
        </div>
    </div>
</div>

<div class="form-group" style="display:none" id="ri_klas_titipan_form">

    <label class="control-label col-sm-3">*Hak Kelas Pasien</label>

    <div class="col-sm-3">
        
        <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array()), '' , 'ri_klas_titipan_ruangan', 'ri_klas_titipan_ruangan', 'form-control', '', '') ?>

    </div>

    <div class="col-sm-8">
        
        <small>&nbsp;<i>Jika pasien titipan, maka pilih Hak Kelas Pasien Yang Seharusnya pada field diatas, dan Hak Kelas Pasien inilah yang akan di charge untuk billing </i></small>

    </div>

</div>

<div class="form-group">
    <label class="control-label col-sm-3">*Kelas Ruangan</label>
    <div class="col-sm-3">
        <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array()), '' , 'ri_klas_ruangan', 'ri_klas_ruangan', 'form-control', '', '') ?>
    </div>
</div>

<div  class="form-group">
    <label class="control-label col-sm-3">*Ruangan</label>
    <div class="col-sm-4">
        <?php echo $this->master->get_change($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => '0300', 'pelayanan' => 1)), '' , 'ri_ruangan', 'ri_ruangan', 'form-control', '', '') ?>
    </div>
</div>

<div  class="form-group">

    <label class="control-label col-sm-3">Uang Muka</label>

    <div class="col-sm-3">
    
        <input type="text" name="ri_deposit_show" id="ri_deposit_show" placeholder="0" class="form-control" readonly>
        <input type="hidden" name="ri_deposit" id="ri_deposit">
        <input type="hidden" name="ri_harga_ruangan_hidden" id="ri_harga_ruangan_hidden">
        <input type="hidden" name="ri_harga_ruangan_bpjs_hidden" id="ri_harga_ruangan_bpjs_hidden">
    
    </div>

    <label class="control-label col-sm-1">Kode</label>            

     <div class="col-md-4">            

       <div class="input-group">

         <input type="text" name="ri_no_ruangan" id="ri_no_ruangan" class="form-control" readonly>

         <span class="input-group-btn">

           <button type="button" class="btn btn-primary btn-sm" onclick="showModalBed()">

             <span class="ace-icon fa fa-bed icon-on-right bigger-110"></span>

             Pilih Bed

           </button>

         </span>

        </div>

        <input type="hidden" name="ri_no_bed_hidden" id="ri_no_bed_hidden">
        
    </div> 

</div>

<div class="form-group">
    <div class="col-sm-12">
       <table class="table">
            <thead>
           <tr>
               <td>Kode</td>
               <td>Ruangan</td>
               <td>Kelas</td>
               <td>Kamar</td>
               <td>Bed</td>
           </tr>
           </thead>

           <tr>
               <td id="td_kode_ruangan">-</td>
               <td id="td_nama_ruangan">-</td>
               <td id="td_kelas">-</td>
               <td id="td_kamar">-</td>
               <td id="td_bed">-</td>
           </tr>
       </table>
    </div>
</div>


<div class="form-group">
    <label class="control-label col-sm-3">*Dokter yang merawat</label>
    <div class="col-sm-6">

        <input id="inputDokterMerawat" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

        <input type="hidden" name="ri_dokter_ruangan" id="ri_dokter_ruangan" class="form-control">
    </div>
</div>
<hr>
<p><b><i class="fa fa-user"></i> KELUARGA TERDEKAT</b></p>

<div class="form-group">                
  <label class="control-label col-sm-3">Nama </label>  
  <div class="col-md-5">    
    <input type="text" class="form-control" name="ri_nama_kel">  
  </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3">Hubungan Keluarga</label>
    <div class="col-sm-4">
        <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'hubungan_keluarga')), '' , 'ri_hubungan_kel', 'ri_hubungan_kel', 'form-control', '', '') ?>
    </div>
</div>

<div class="form-group">                
    <label class="control-label col-sm-3">Alamat</label>  
    <div class="col-md-5">    
        <textarea name="ri_alamat_kel" class="form-control" style="height:50px !important"></textarea>  
    </div>
</div>

<div class="form-group">                
    <label class="control-label col-sm-3">No Telp</label>  
    <div class="col-md-5">    
        <input type="text" class="form-control" name="ri_telp_kel">  
    </div>
</div>


<!-- MODAL BED -->

<div id="modalBed" class="modal fade" tabindex="-1">

<div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:80%">

  <div class="modal-content">
  
    <div class="modal-header">

        <div class="table-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

        </button>

          <span id="text_title_selected_ruangan_klas">RUANGAN KELAS</span>

        </div>

    </div>

    <div class="modal-body no-padding">

        <div id="result_bed"></div>

      <!-- <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">

        <thead>

          <tr>

            <th>Ruangan</th>

            <th>Bed</th>

            <th>Status</th>

            <th></th>

          </tr>

        </thead>

        <tbody id="result_bed">


        </tbody>

      </table> -->

    </div>

    <div class="modal-footer no-margin-top">

      <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

        <i class="ace-icon fa fa-times"></i>

        Close

      </button>

    </div>

  </div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->


