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

  $('select[name="pb_paket_tindakan"]').change(function () {      

        if ($(this).val()) {          

            $.getJSON("<?php echo site_url('Templates/References/getRuanganByTarif') ?>/" + $(this).val(), '', function (data) {              

                $('#pb_ruangan option').remove();                

                $('<option value="">-Pilih Ruangan-</option>').appendTo($('#pb_ruangan'));                

                $.each(data, function (i, o) {                  

                    $('<option value="' + o.kode_bagian + '">' + o.nama_bagian + '</option>').appendTo($('#pb_ruangan'));                    

                });                

            });  
              

        } else {          

            $('#pb_ruangan option').remove()  
    

        }        

    });  

    $('#inputDokterMerawat').typeahead({
        source: function (query, result) {
                $.ajax({
                    url: "templates/references/getDokterByBagian",
                    data: 'keyword=' + query + '&bag=' + $('#pb_ruangan').val(),         
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
            $('#pb_dokter_ruangan').val(val_item);
            
        }
    });

    $('select[name="pb_ruangan"]').change(function () {      

        if ($(this).val()) {          

            $.getJSON("<?php echo site_url('Templates/References/getKlasByRuanganTarif') ?>/" + $(this).val() + "/" + $('#pb_paket_tindakan').val(), '', function (data) {              

                $('#pb_klas_ruangan option').remove();                

                $('<option value="">-Pilih Kelas-</option>').appendTo($('#pb_klas_ruangan'));                

                $.each(data, function (i, o) {                  

                    $('<option value="' + o.kode_klas + '">' + o.nama_klas + '</option>').appendTo($('#pb_klas_ruangan'));                    

                });                

            }); 

             $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian') ?>/" + $(this).val(), '', function (data) {              

                $('#pb_dokter_ruangan option').remove();                

                $('<option value="">-Pilih Dokter-</option>').appendTo($('#pb_dokter_ruangan'));                

                $.each(data, function (i, o) {                  

                    $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#pb_dokter_ruangan'));                    

                });                

            });      
      

        } else {          

            $('#pb_klas_ruangan option').remove()      

        }        

    }); 

      $('select[name="pb_klas_ruangan"]').change(function () {      

            if ($(this).val()) {          

                $.getJSON("<?php echo site_url('Templates/References/getDeposit') ?>/" + $("#pb_ruangan").val() + "/" + $(this).val(), '', function (data) {                       

                    $.each(data, function (i, o) {                  

                        deposit = addPeriod(o.deposit);

                        harga_ruangan = addPeriod(o.harga_r);

                        harga_ruangan_bpjs = addPeriod(o.harga_bpjs);

                        $('#pb_deposit').val(o.deposit);

                        $('#pb_deposit').text(deposit);

                        $('#pb_harga_ruangan_hidden').val(o.harga_r);

                        $('#pb_harga_ruangan_bpjs_hidden').val(o.harga_bpjs);

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

        $.getJSON("<?php echo site_url('Templates/References/getBedByKlas') ?>/" + $("#pb_ruangan").val() + "/" +  $("#pb_klas_ruangan").val(), '', function (data) {              
            
        //    achtungHideLoader();
        
            $("#result_bed tr").remove();

            
            $.each(data, function (i, o) {  

                if(o.status==null){

                    status = '-';

                    $('<tr><td>'+o.kode_ruangan+'</td><td>'+o.no_bed+'</td><td>'+status+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_bed_from_modal_bed('+"'"+o.kode_ruangan+"'"+","+"'"+o.no_bed+"'"+')"><i class="fa fa-arrow-down"></i>Pilih</a></td></tr>').appendTo($('#result_bed'));                    
                
                } else {

                    status = o.status;  

                    $('<tr><td>'+o.kode_ruangan+'</td><td>'+o.no_bed+'</td><td>'+status+'</td><td align="center"><a href="#" class="btn btn-xs btn-red"><i class="fa fa-times"></i></a></td></tr>').appendTo($('#result_bed'));                    

                }
            //    status = (o.status==null)?'-':o.status;               

            //    $('<tr><td>'+o.kode_ruangan+'</td><td>'+o.no_bed+'</td><td>'+status+'</td><td align="center"><a href="#" class="btn btn-xs btn-pink" onclick="select_bed_from_modal_bed('+"'"+o.kode_ruangan+"'"+')"><i class="fa fa-arrow-down"></i>Pilih</a></td></tr>').appendTo($('#result_bed'));                    
               
            }); 

            showModal_bed();  

        });       

    }

    function showModal_bed()

    {  

        $("#modalBed").modal();  

    }


    function select_bed_from_modal_bed(kode,bed){
                
        $("#modalBed").modal('hide');

        $('#div_load_after_selected_pasien').show('fast');

        $('#div_riwayat_pasien').show('fast');

        

        $('#pb_no_ruangan').val(kode);

        $('#pb_no_ruangan').text(kode);  

        $('#pb_no_bed_hidden').val(bed);

    }


</script>

<hr>

<p><b><i class="fa fa-edit"></i> PENDAFTARAN PAKET BEDAH </b></p>

<div class="form-group">

    <label class="control-label col-sm-3">Tanggal Masuk</label>
  
    <div class="col-md-3">
        
        <div class="input-group">
            
            <input name="pb_tgl_registrasi" id="pb_tgl_registrasi" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
            <span class="input-group-addon">
            
            <i class="ace-icon fa fa-calendar"></i>
            
            </span>
        </div>
    
    </div>

    <label class="control-label col-sm-2">Rujukan dari</label>

    <div class="col-md-3">

       <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'rujukan_dari')), '' , 'pb_rujukan_dari', 'pb_rujukan_dari', 'form-control', '', '') ?>
       
    </div>

</div>

<div class="form-group">

    <label class="control-label col-sm-3">Dokter pengirim</label>

    <div class="col-md-6">

        <?php echo $this->master->custom_selection($params = array('table' => 'mt_karyawan', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('status_dr' => 1)), '' , 'pb_dokter_pengirim', 'pb_dokter_pengirim', 'chosen-select form-control', '', '') ?>

    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3">Obstetri</label>
    <div class="col-md-4">
        <div class="radio">
            <label>
                <input name="pb_is_obstetri" type="radio" class="ace" value="1" />
                <span class="lbl"> Ya </span>
            </label>

            <label>
            <input name="pb_is_obstetri" type="radio" class="ace" value="0" checked="checked" />
                <span class="lbl">Tidak</span>
            </label>
        </div>
    </div>
</div>

<div class="form-group">

    <label class="control-label col-sm-3">*Diagnosa Masuk</label>

    <div class="col-sm-6">

        <input type="text" name="pb_diagnosa_masuk" id="pb_diagnosa_masuk" class="form-control">

    </div>

</div>

<div class="form-group">

    <label class="control-label col-sm-3" for="Province">*Paket Bedah</label>

    <div class="col-sm-4">

        <?php echo $this->master->custom_selection($params = array('table' => 'mt_master_tarif', 'id' => 'kode_tarif', 'name' => 'nama_tarif', 'where' => array('tingkatan' => 5, 'is_paket' => '1')), '' , 'pb_paket_tindakan', 'pb_paket_tindakan', 'form-control', '', '') ?>

    </div>

</div>

<div class="form-group">

    <label class="control-label col-sm-3">*Nama Ruangan</label>

    <div class="col-sm-3">
        
        <?php echo $this->master->get_change($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), '' , 'pb_ruangan', 'pb_ruangan', 'form-control', '', '') ?>

    </div>

    <label class="control-label col-sm-2">*Kelas</label>

    <div class="col-sm-3">
        
        <?php echo $this->master->get_change($params = array('table' => 'mt_ruangan', 'id' => 'kode_klas', 'name' => 'kode_bagian', 'where' => array()), '' , 'pb_klas_ruangan', 'pb_klas_ruangan', 'form-control', '', '') ?>

    </div>

</div>

<input type="hidden" name="pb_deposit" id="pb_deposit" placeholder="0" class="form-control" readonly>
<input type="hidden" name="pb_harga_ruangan_hidden" id="pb_harga_ruangan_hidden">
<input type="hidden" name="pb_harga_ruangan_bpjs_hidden" id="pb_harga_ruangan_bpjs_hidden">

<div class="form-group">

    <label class="control-label col-sm-3">No. Bed</label>            

    <div class="col-md-4">            

    <div class="input-group">

        <input type="text" name="pb_no_ruangan" id="pb_no_ruangan" class="form-control" readonly>

        <span class="input-group-btn">

        <button type="button" class="btn btn-primary btn-sm" onclick="showModalBed()">

            <span class="ace-icon fa fa-bed icon-on-right bigger-110"></span>

            Pilih Bed

        </button>

        </span>

    </div>

    <input type="hidden" name="pb_no_bed_hidden" id="pb_no_bed_hidden">

</div> 

</div>

<div class="form-group">
    <label class="control-label col-sm-3">*Dokter yang merawat</label>
    <div class="col-sm-6">

        <input id="inputDokterMerawat" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

        <input type="hidden" name="pb_dokter_ruangan" id="pb_dokter_ruangan" class="form-control">
    </div>
</div>

<p><b><i class="fa fa-user"></i> KELUARGA TERDEKAT</b></p>

<div class="form-group">
                
  <label class="control-label col-sm-3">Nama </label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="pb_nama_kel">
  
  </div>

   <label class="control-label col-sm-2">Hubungan</label>

    <div class="col-sm-3">

        <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'hubungan_keluarga')), '' , 'pb_hubungan_kel', 'pb_hubungan_kel', 'form-control', '', '') ?>

    </div>

</div>

<div class="form-group">
                
  <label class="control-label col-sm-3">Alamat</label>
  
  <div class="col-md-4">
    
    <textarea name="pb_alamat_kel" class="form-control" style="height:50px !important"></textarea>
  
  </div>

  <label class="control-label col-sm-2">No Telp</label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="pb_telp_kel">
  
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

          <span>Bed</span>

        </div>

    </div>

    <div class="modal-body no-padding">

      <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">

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

      </table>

    </div>

    <div class="modal-footer no-margin-top">

      <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

        <i class="ace-icon fa fa-times"></i>

        Close

      </button>

    </div>

  </div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->

