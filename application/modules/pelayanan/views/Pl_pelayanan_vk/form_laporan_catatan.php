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
  //initiate dataTables plugin
    oTableVitalSign = $('#table-vital-sign').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_igd/get_vital_sign?kode_gd=<?php echo $kode_gd?>&no_registrasi=<?php echo $no_registrasi?>",
          "type": "POST"
      },

    });


    // oTablePesanPindah = $('#table-pesan-pindah').DataTable({ 
      
    //   "processing": true, //Feature control the processing indicator.
    //   "serverSide": true, //Feature control DataTables' server-side processing mode.
    //   "ordering": false,
    //   "searching": false,
    //   "bPaginate": false,
    //   "bInfo": false,
    //   // Load data for the table's content from an Ajax source
    //   "ajax": {
    //       "url": "pelayanan/Pl_pelayanan_ri/get_pesan_pindah?kode_ri=<?php //echo $kode_ri?>&no_registrasi=<?php //echo $no_registrasi?>",
    //       "type": "POST"
    //   },

    // });


    $('input[name="tipe_laporan_catatan"]').click(function (e) {

      var value = $(this).val();
      if (value=='vital_sign') {
        $("html, body").animate({ scrollTop: "400px" });
        $('#data_vital_sign').show('fast');
        $('#data_laporan').hide('fast');
        $('#data_keracunan').hide('fast');
      }else if (value=='laporan') {
        $("html, body").animate({ scrollTop: "400px" });
        $('#data_laporan').show('fast');
        $('#data_vital_sign').hide('fast');
        $('#data_keracunan').hide('fast');
      }else if (value=='keracunan') {
        $("html, body").animate({ scrollTop: "500px" });
        
        $('#data_keracunan').show('fast');
        $('#data_vital_sign').hide('fast');
        $('#data_laporan').hide('fast');
      }

    }); 

    $('input[name="bau_bahan_keracunan"]').click(function (e) {

      var val = $(this).val();
      console.log(val)
      if (val=='Ada') {
        $('#bau_keracunan').show('fast');
      }else{
        $('#bau_keracunan').hide('fast');
      }

    }); 
   
    $('#btn_vital_sign').click(function (e) {   
      e.preventDefault();

      /*process add pesan vk*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_igd/process_add_vital_sign",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              var date = '<?php echo date('m/d/Y')?>';
              /*reset all field*/
              $('#vs_keadaan_umum_igd').attr('readonly', true);
              $('#vs_kesadaran_pasien').attr('readonly', true);
              $('#vs_tekanan_darah').attr('readonly', true);
              $('#vs_nadi').attr('readonly', true);
              $('#vs_suhu').attr('readonly', true);
              $('#vs_pernafasan').attr('readonly', true);
              $('#vs_berat_badan').attr('readonly', true);
              $('#kode_rujuk_ri').val(response.id);
              $('#btn_vital_sign_').hide('fast');

              if($('#kesadaran_pasien_keracunan').val()=='' && $('#tekanan_darah_keracunan').val()=='' && $('#nadi_keracunan').val() &&
              $('#suhu_keracunan').val() && $('#pernafasan_keracunan').val()){
                $('#kesadaran_pasien_keracunan').val($('#vs_kesadaran_pasien').val());
                $('#tekanan_darah_keracunan').val($('#vs_tekanan_darah').val());
                $('#nadi_keracunan').val($('#vs_nadi').val());
                $('#suhu_keracunan').val($('#vs_suhu').val());
                $('#pernafasan_keracunan').val($('#vs_pernafasan').val());
              }
              
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

    });

    $('#btn_laporan_dokter').click(function (e) {   
      e.preventDefault();

      $.ajax({
          url: "pelayanan/Pl_pelayanan_igd/process_add_laporan_dokter",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              var date = '<?php echo date('m/d/Y')?>';
              $.achtung({message: response.message, timeout:5});
              /*reset all field*/
              $('#pl_tgl_pesan').val(date);
              $('#id_th_laporan_dr').val(response.id);
        
            }else{
              alert(response.message); return false;
            }
            
          }
      });

    });

    $('#btn_laporan_perawat').click(function (e) {   
      e.preventDefault();

      $.ajax({
          url: "pelayanan/Pl_pelayanan_igd/process_add_laporan_perawat",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              var date = '<?php echo date('m/d/Y')?>';
              $.achtung({message: response.message, timeout:5});
              /*reset all field*/
              $('#pl_tgl_pesan').val(date);
              $('#id_th_laporan_perawat').val(response.id);
        
            }else{
              alert(response.message); return false;
            }
            
          }
      });

    });

    $('#btn_keracunan').click(function (e) {   

      if( $("#napza_bahan_keracunan").val() == '' && $("#obat_bahan_keracunan").val() == '' && $("#obat_tradisional_bahan_keracunan").val() == ''
      && $("#makanan_bahan_keracunan").val() == '' && $("#suplemen_bahan_keracunan").val() == '' && $("#kosmetik_bahan_keracunan").val() == ''
      && $("#bahan_kimia_bahan_keracunan").val() == '' && $("#pestisida_bahan_keracunan").val() == '' && $("#gigitan_ular_bahan_keracunan").val()  == ''
      && $("#binatang_bahan_keracunan").val() == '' && $("#tumbuhan_bahan_keracunan").val() == '' && $("#pencemaran_bahan_keracunan").val() == ''
      && $("#tdk_diketahui_bahan_keracunan").val() == '' ){

        alert('Silahkan isi perkiraan Jenis Bahan !');

        return $("#napza_bahan_keracunan").focus();

      }

      e.preventDefault();

      $.ajax({
          url: "pelayanan/Pl_pelayanan_igd/process_add_keracunan",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              var date = '<?php echo date('m/d/Y')?>';
              $.achtung({message: response.message, timeout:5});
              /*reset all field*/
              $('#pl_tgl_pesan').val(date);
              $('#id_cetak_racun').val(response.id);
              $("html, body").animate({ scrollTop: "0" });
              $('#cetak_keracunan').show('fast');
            }else{
              alert(response.message); return false;
            }
            
          }
      });

    });

    $('#pl_diagnosa_keracunan').typeahead({
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
          $('#pl_diagnosa_keracunan').val(label_item);
          $('#diagnosa_keracunan_hidden').val(item);
        }

    });
   
});

function getDetailTarifByKodeTarifAndKlas(kode_tarif, kode_klas){

  $.getJSON("<?php echo site_url('templates/references/getDetailTarif') ?>?kode="+kode_tarif+"&klas="+kode_klas+"&type=html", '' , function (data) {

    /*show detail tarif html*/
    $('#formDetailTarif').show('fast');
    $('#detailTarifHtml').html(data.html);

  })

}

function reset_table(){
    oTableVitalSign.ajax.url('pelayanan/Pl_pelayanan_igd/get_vital_sign?kode_gd=<?php echo $kode_gd?>&no_registrasi=<?php echo $no_registrasi?>').load();
    // oTablePesanPindah.ajax.url('pelayanan/Pl_pelayanan_ri/get_pesan_pindah?kode_ri=<?php //echo $kode_ri?>&no_registrasi=<?php //echo $no_registrasi?>').load();
}

function edit_vital_sign(myid){
  $('#btn_vital_sign_').show('fast');
  $('#vs_keadaan_umum_igd').attr('readonly', false);
  $('#vs_kesadaran_pasien').attr('readonly', false);
  $('#vs_tekanan_darah').attr('readonly', false);
  $('#vs_nadi').attr('readonly', false);
  $('#vs_suhu').attr('readonly', false);
  $('#vs_pernafasan').attr('readonly', false);
  $('#vs_berat_badan').attr('readonly', false);
  $("html, body").animate({ scrollTop: "400px" });
}


</script>

<div class="row">

  <div class="col-md-12">

    <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal</label>
          <div class="col-md-3">
                
            <div class="input-group">
                
                <input name="tgl_laporan" id="tgl_laporan" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                <span class="input-group-addon">
                  
                  <i class="ace-icon fa fa-calendar"></i>
                
                </span>
              </div>
          
          </div>
    </div>

    <div class="form-group">

        <label class="control-label col-sm-2">Laporan / Catatan</label>

        <div class="col-md-10">

          <div class="radio">

              <label>

                <input name="tipe_laporan_catatan" type="radio" class="ace" value="vital_sign" checked="checked"  />

                <span class="lbl"> Vital Sign</span>

              </label>

              <label>

                <input name="tipe_laporan_catatan" type="radio" class="ace" value="laporan"/>

                <span class="lbl"> Laporan</span>

              </label>

               <label>

                <input name="tipe_laporan_catatan" type="radio" class="ace" value="keracunan"/>

                <span class="lbl"> Keracunan</span>

              </label>

          </div>

        </div>

    </div>

    <div id="data_vital_sign">
        <br>
        <p><b><i class="fa fa-edit"></i> Vital Sign </b></p>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">*Keadaan Umum</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'keadaan_umum_igd')), isset($vital_sign)?$vital_sign->keadaan_umum:'', 'vs_keadaan_umum_igd', 'vs_keadaan_umum_igd', 'form-control', '', '', isset($vital_sign)?'readonly':'') ?>
            </div>

            <label class="control-label col-sm-2" for="">*Kesadaran Pasien</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'kesadaran_pasien')), isset($vital_sign)?$vital_sign->kesadaran_pasien:'', 'vs_kesadaran_pasien', 'vs_kesadaran_pasien', 'form-control', '', '', isset($vital_sign)?'readonly':'') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Tekanan Darah</label>
            <div class="col-sm-2">    
              <div class="input-group">

              <input type="text" class="form-control" name="vs_tekanan_darah" id="vs_tekanan_darah" value="<?php echo isset($vital_sign)?$vital_sign->tekanan_darah:''?>" <?php echo isset($vital_sign)?'readonly':''; ?> >

                <span class="input-group-addon">

                  mmHg

                </span>

              </div>
            </div>

            <label class="control-label col-sm-2" for="">Nadi</label>
            <div class="col-sm-2">    
              <div class="input-group">

              <input type="text" class="form-control" name="vs_nadi" id="vs_nadi" value="<?php echo isset($vital_sign)?$vital_sign->nadi:''?>" <?php echo isset($vital_sign)?'readonly':''; ?> >

                <span class="input-group-addon">

                  x/menit

                </span>

              </div>
            </div>

            <label class="control-label col-sm-2" for="">Suhu</label>
            <div class="col-sm-2">    
              <div class="input-group">

              <input type="text" class="form-control" name="vs_suhu" id="vs_suhu" value="<?php echo isset($vital_sign)?$vital_sign->suhu:''?>" <?php echo isset($vital_sign)?'readonly':''; ?> >

                <span class="input-group-addon">

                  &#8451;

                </span>

              </div>
            </div>
        </div>

        <div class="form-group">
           
           <label class="control-label col-sm-2" for="">Pernafasan</label>
           <div class="col-sm-2">    
             <div class="input-group">

             <input type="text" class="form-control" name="vs_pernafasan" id="vs_pernafasan" value="<?php echo isset($vital_sign)?$vital_sign->pernafasan:''?>" <?php echo isset($vital_sign)?'readonly':''; ?> >

               <span class="input-group-addon">

                 x/menit

               </span>

             </div>
           </div>

           <label class="control-label col-sm-2" for="">Berat Badan</label>
           <div class="col-sm-2">    
             <div class="input-group">

             <input type="text" class="form-control" name="vs_berat_badan" id="vs_berat_badan" value="<?php echo isset($vital_sign)?$vital_sign->berat_badan:''?>" <?php echo isset($vital_sign)?'readonly':''; ?> >

               <span class="input-group-addon">

                 kg

               </span>

             </div>
           </div>
       </div>
       <input type="hidden" class="form-control" name="kode_rujuk_ri" id="kode_rujuk_ri" value="<?php echo isset($vital_sign)?$vital_sign->kode_rujuk_ri:''?>">

        <div class="form-group" id="btn_vital_sign_" <?php echo isset($vital_sign)?'style="display:none"':''; ?>>
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
              <a href="#" class="btn btn-xs btn-primary" id="btn_vital_sign"><i class="fa fa-save"></i> Submit </a>
            </div>
        </div>  
                
          <table id="table-vital-sign" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="50px"></th>
                <th>ID</th>
                <th>Keadaan Umum</th>
                <th>Kesadaran Pasien</th>
                <th>Tekanan Darah</th>
                <th>Nadi</th>
                <th>Suhu</th>
                <th>Pernafasan</th>
                <th>Berat Badan</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        

    </div><!--end data vital sign -->

    <div id="data_laporan" style="display:none">

        <div class="col-sm-12">
            
            <br>

            <p><b><i class="fa fa-edit"></i> Laporan Dokter</b></p>

          <div class="form-group">

            <label class="control-label col-sm-2">Keadaan Umum</label>

            <div class="col-md-4">

              <textarea name="laporan_dokter_keadaan_umum" id="laporan_dokter_keadaan_umum" cols="40" style="height:100px !important;"><?php echo isset($laporan_dr)?$laporan_dr->keadaan_umum:''?></textarea>

            </div>
            
          <!-- </div>
          

          <div class="form-group"> -->

              <label class="control-label col-sm-2" for="City">Kesadaran</label>

              <div class="col-md-4">

                <textarea name="laporan_dokter_kesadaran" id="laporan_dokter_kesadaran" style="height:100px !important;" cols="40"><?php echo isset($laporan_dr)?$laporan_dr->kesadaran:''?></textarea>

              </div>

          </div>
          <input type="hidden" class="form-control" name="id_th_laporan_dr" id="id_th_laporan_dr" value="<?php echo isset($laporan_dr)?$laporan_dr->id_th_laporan_dr:''?>">

          <div class="form-group">
              <label class="control-label col-sm-2" for="">&nbsp;</label>
              <div class="col-sm-4" style="margin-left:6px">
                <a href="#" class="btn btn-xs btn-primary" id="btn_laporan_dokter"><i class="fa fa-save"></i> Submit </a>
              </div>
          </div>
          
          <br>

          <p><b><i class="fa fa-edit"></i> Laporan Perawat</b></p>

          <div class="form-group">

          <label class="control-label col-sm-2"> Laporan Perawat</label>

          <div class="col-md-8">

            <textarea name="laporan_perawat" id="laporan_perawat" cols="50" style="height:100px !important;"><?php echo isset($laporan_perawat)?$laporan_perawat->laporan_perawat:''?></textarea>

          </div>

          </div>
          <input type="hidden" class="form-control" name="id_th_laporan_perawat" id="id_th_laporan_perawat" value="<?php echo isset($laporan_perawat)?$laporan_perawat->id_th_laporan_perawat:''?>">

          <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
              <a href="#" class="btn btn-xs btn-primary" id="btn_laporan_perawat"><i class="fa fa-save"></i> Submit </a>
            </div>
          </div>
         
        </div>

    </div><!--end data laporan -->

    <div id="data_keracunan" style="display:none">

      <br><div class="center"><p><b> Catatan Medis Kasus Keracunan </b></p></div>
            
      <div class="form-group">
                
        <label class="control-label col-sm-2">*Tempat Kejadian</label>
        
        <div class="col-md-3">
          
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'tempat_kejadian')), isset($keracunan)?$keracunan->tempat_kejadian:'Rumah', 'tempat_kejadian_keracunan', 'tempat_kejadian_keracunan', 'form-control', '', '') ?>
        
        </div>
      
      </div>
      
      <div class="form-group">
                      
        <label class="control-label col-sm-2">Keluhan</label>
        
        <div class="col-md-3">
          
          <textarea name="keluhan_keracunan" id="keluhan_keracunan" cols="50" style="height:100px !important;"><?php echo isset($keracunan)?$keracunan->keluhan:'' ?></textarea>
        
        </div>
      
      
      </div>
      
      <div class="form-group">
                      
        <label class="control-label col-sm-2">RPS</label>
        
        <div class="col-md-4">
          
          <input type="text" class="form-control" name="rps_keracunan" value="<?php echo isset($keracunan)?$keracunan->rps:'' ?>">
        
        </div>
            
      </div>
      
      <div class="form-group">
      
          <label class="control-label col-sm-2" for="Province">Hamil</label>
    
          <div class="col-md-3">

            <div class="radio">

                <label>

                  <input name="hamil_keracunan" type="radio" class="ace" value="Hamil" <?php echo isset($keracunan)?($keracunan->hamil=='Hamil')?'checked="checked"':'':'' ?>/>

                  <span class="lbl"> Hamil</span>

                </label>

                <label>

                  <input name="hamil_keracunan" type="radio" class="ace" value="Tidak Hamil" <?php echo isset($keracunan)?($keracunan->hamil=='Tidak Hamil')?'checked="checked"':'':'checked="checked"' ?>/>

                  <span class="lbl"> Tidak hamil</span>

                </label>

            </div>

          </div>

          <label class="control-label col-sm-2" for="Province">Menyusui</label>
    
          <div class="col-md-4">

            <div class="radio">

                <label>

                  <input name="menyusui_keracunan" type="radio" class="ace" value="Ya" <?php echo isset($keracunan)?($keracunan->ket_pas_menyusui=='Ya')?'checked="checked"':'':'' ?>/>

                  <span class="lbl"> Ya</span>

                </label>

                <label>

                  <input name="menyusui_keracunan" type="radio" class="ace" value="Tidak" <?php echo isset($keracunan)?($keracunan->ket_pas_menyusui=='Tidak')?'checked="checked"':'':'checked="checked"' ?>/>

                  <span class="lbl"> Tidak</span>

                </label>

            </div>

          </div>

      </div>
      
        <p><b><i class="fa fa-edit"></i> Perkiraan Jenis Bahan </b></p>
                
        <table id="table-keracunan" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th class="center">Kelompok Penyebab</th>
              <th class="center">Nama Bahan</th>
              <th class="center">Jumlah Bahan</th>
            </tr>
          </thead>
          <tbody>
            <tr>  
              <td>NAPZA</td>
              <td><input type="text" class="form-control" name="napza_bahan_keracunan" id="napza_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_napza:'' ?>"></td>
              <td><input type="text" class="form-control" name="napza_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_napza:'' ?>"></td>
            </tr>
            <tr>  
              <td>Obat</td>
              <td><input type="text" class="form-control" name="obat_bahan_keracunan" id="obat_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_obat:'' ?>"></td>
              <td><input type="text" class="form-control" name="obat_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_obat:'' ?>"></td>
            </tr>
            <tr>  
              <td>Obat Tradisional</td>
              <td><input type="text" class="form-control" name="obat_tradisional_bahan_keracunan" id="obat_tradisional_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_obattradisional:'' ?>"></td>
              <td><input type="text" class="form-control" name="obat_tradisional_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_obattradisional:'' ?>"></td>
            </tr>
            <tr>  
              <td>Makanan/Minuman</td>
              <td><input type="text" class="form-control" name="makanan_bahan_keracunan" id="makanan_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_makanan:'' ?>"></td>
              <td><input type="text" class="form-control" name="makanan_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_makanan:'' ?>"></td>
            </tr>
            <tr>  
              <td>Suplemen/Vitamin</td>
              <td><input type="text" class="form-control" name="suplemen_bahan_keracunan" id="suplemen_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_suplemen:'' ?>"></td>
              <td><input type="text" class="form-control" name="suplemen_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_suplemen:'' ?>"></td>
            </tr>
            <tr>  
              <td>Kosmetik</td>
              <td><input type="text" class="form-control" name="kosmetik_bahan_keracunan" id="kosmetik_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_kosmetik:'' ?>"></td>
              <td><input type="text" class="form-control" name="kosmetik_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_kosmetik:'' ?>"></td>
            </tr>
            <tr>  
              <td>Bahan Kimia</td>
              <td><input type="text" class="form-control" name="bahan_kimia_bahan_keracunan" id="bahan_kimia_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_kimia:'' ?>"></td>
              <td><input type="text" class="form-control" name="bahan_kimia_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_kimia:'' ?>"></td>
            </tr>
            <tr>  
              <td>Pestisida</td>
              <td><input type="text" class="form-control" name="pestisida_bahan_keracunan" id="pestisida_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_pestisida:'' ?>"></td>
              <td><input type="text" class="form-control" name="pestisida_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_pestisida:'' ?>"></td>
            </tr>
            <tr>  
              <td>Gigitan Ular</td>
              <td><input type="text" class="form-control" name="gigitan_ular_bahan_keracunan" id="gigitan_ular_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_ular:'' ?>"></td>
              <td><input type="text" class="form-control" name="gigitan_ular_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_ular:'' ?>"></td>
            </tr>
            <tr>  
              <td>Binatang Selain Ular</td>
              <td><input type="text" class="form-control" name="binatang_bahan_keracunan" id="binatang_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_bukanular:'' ?>"></td>
              <td><input type="text" class="form-control" name="binatang_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_bukanular:'' ?>"></td>
            </tr>
            <tr>  
              <td>Tumbuhan Beracun</td>
              <td><input type="text" class="form-control" name="tumbuhan_bahan_keracunan" id="tumbuhan_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_tumbuhan:'' ?>"></td>
              <td><input type="text" class="form-control" name="tumbuhan_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_tumbuhan:'' ?>"></td>
            </tr>
            <tr>  
              <td>Pencemaran Lingkungan/Gas</td>
              <td><input type="text" class="form-control" name="pencemaran_bahan_keracunan" id="pencemaran_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_pencemaran:'' ?>"></td>
              <td><input type="text" class="form-control" name="pencemaran_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_pencemaran:'' ?>"></td>
            </tr>
            <tr>  
              <td>Bahan Tidak Diketahui</td>
              <td><input type="text" class="form-control" name="tdk_diketahui_bahan_keracunan" id="tdk_diketahui_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->bahan_tdkdiketahui:'' ?>"></td>
              <td><input type="text" class="form-control" name="tdk_diketahui_jml_bahan_keracunan" size="2" value="<?php echo isset($keracunan)?$keracunan->jumlah_tdkdiketahui:'' ?>"></td>
            </tr>
          </tbody>
        </table>

        <div class="form-group">
                  
          <label class="control-label col-sm-2">Tipe Pemaparan</label>
          
          <div class="col-md-3">
            
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'tipe_pemaparan')), isset($keracunan)?$keracunan->tipe_pemaparan:'', 'tipe_pemaparan_keracunan', 'tipe_pemaparan_keracunan', 'form-control', '', '') ?>
          
          </div>

          <label class="control-label col-sm-2">Tipe Kejadian</label>
          
          <div class="col-md-3">
            
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'tipe_kejadian')), isset($keracunan)?$keracunan->tipe_kejadian:'', 'tipe_kejadian_keracunan', 'tipe_kejadian_keracunan', 'form-control', '', '') ?>
          
          </div>
        
        </div>

        <p><b><i class="fa fa-edit"></i> Gambaran Klinis </b></p>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">*Kesadaran Pasien</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'kesadaran_pasien')), isset($keracunan->kesadaran)?$keracunan->kesadaran:'', 'kesadaran_pasien_keracunan', 'kesadaran_pasien_keracunan', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Tekanan Darah</label>
            <div class="col-sm-2">    
              <div class="input-group">

              <input type="text" class="form-control" name="tekanan_darah_keracunan" id="tekanan_darah_keracunan" value="<?php echo isset($keracunan->tekanan_darah)?$keracunan->tekanan_darah:''?>"  >

                <span class="input-group-addon">

                  mmHg

                </span>

              </div>
            </div>

            <label class="control-label col-sm-2" for="">Nadi</label>
            <div class="col-sm-2">    
              <div class="input-group">

              <input type="text" class="form-control" name="nadi_keracunan" id="nadi_keracunan" value="<?php echo isset($keracunan->nadi)?$keracunan->nadi:''?>"  >

                <span class="input-group-addon">

                  x/menit

                </span>

              </div>
            </div>

            <label class="control-label col-sm-2" for="">Suhu</label>
            <div class="col-sm-2">    
              <div class="input-group">

              <input type="text" class="form-control" name="suhu_keracunan" id="suhu_keracunan" value="<?php echo isset($keracunan->suhu)?$keracunan->suhu:''?>"  >

                <span class="input-group-addon">

                  &#8451;

                </span>

              </div>
            </div>
        </div>

        <div class="form-group">
           
            <label class="control-label col-sm-2" for="">Pernafasan</label>
            <div class="col-sm-2">    
              <div class="input-group">

              <input type="text" class="form-control" name="pernafasan_keracunan" id="pernafasan_keracunan" value="<?php echo isset($keracunan->pernafasan)?$keracunan->pernafasan:''?>"  >

                <span class="input-group-addon">

                  x/menit

                </span>

              </div>
            </div>

            <label class="control-label col-sm-2" for="">Urine</label>
            <div class="col-sm-2">    
              <div class="input-group">

              <input type="text" class="form-control" name="urine_keracunan" id="urine_keracunan" value="<?php echo isset($keracunan->urine)?$keracunan->urine:''?>">

                <span class="input-group-addon">

                  cc/jam

                </span>

              </div>
            </div>
        </div>

        <div class="form-group">
      
            <label class="control-label col-sm-2" for="Province">Bau Bahan</label>
      
            <div class="col-md-4">

              <div class="radio">

                  <label>

                    <input name="bau_bahan_keracunan" type="radio" class="ace" value="Ada" <?php echo isset($keracunan)?($keracunan->bau_bahan=='Ada')?'checked="checked"':'':'' ?>/>

                    <span class="lbl"> Ada</span>

                  </label>

                  <label>

                    <input name="bau_bahan_keracunan" type="radio" class="ace" value="Tidak Ada" <?php echo isset($keracunan)?($keracunan->bau_bahan=='Tidak Ada')?'checked="checked"':'':'checked="checked"' ?>/>

                    <span class="lbl"> Tidak Ada</span>

                  </label>

              </div>

            </div>

        </div>

        <div id="bau_keracunan" <?php echo isset($keracunan)?($keracunan->bau_bahan=='Ada')?'':'style="display:none"':'style="display:none"' ?>>
          <div class="form-group">
                        
            <label class="control-label col-sm-2">Sebutkan</label>
            
            <div class="col-md-4">
              
              <input type="text" class="form-control" name="nama_bau_bahan_keracunan" id="nama_bau_bahan_keracunan" value="<?php echo isset($keracunan)?$keracunan->keterangan_bau_bahan:''?>">
            
            </div>
                
          </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Pupil</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'kondisi_pupil')), isset($keracunan)?$keracunan->pupil:'', 'kondisi_pupil_keracunan', 'kondisi_pupil_keracunan', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group">
                      
          <label class="control-label col-sm-3">Pengobatan Sebelum ke IGD</label>
          
          <div class="col-md-3">
            
            <input type="text" class="form-control" name="sebelum_igd_keracunan" value="<?php echo isset($keracunan)?$keracunan->pengobatan_sbl_igd:''?>">
          
          </div>
              
        </div>

        <div class="form-group">
                      
          <label class="control-label col-sm-3">Pemeriksaan Penunjang</label>
          
          <div class="col-md-3">
            
            <input type="text" class="form-control" name="pemeriksaan_penunjang_keracunan" value="<?php echo isset($keracunan)?$keracunan->pemeriksaan_penunjang:''?>">
          
          </div>

          <label class="control-label col-sm-3">Penatalaksanaan yang diberikan</label>
          
          <div class="col-md-3">
            
            <input type="text" class="form-control" name="penatalaksanaan_keracunan" value="<?php echo isset($keracunan)?$keracunan->penatalaksanaan:''?>">
          
          </div>
              
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Diagnosa <span style="color:red">(*)</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="pl_diagnosa_keracunan" id="pl_diagnosa_keracunan" placeholder="Masukan keyword ICD 10" value="<?php echo isset($keracunan->kode_icd_x)?$keracunan->kode_icd_x:''?>">
              <input type="hidden" class="form-control" name="diagnosa_keracunan_hidden" id="diagnosa_keracunan_hidden" value="<?php echo isset($keracunan->kode_icd_x)?$keracunan->kode_icd_x:''?>">
            </div>
        </div>

        <div class="form-group">
                      
          <label class="control-label col-sm-2">Tindak Lanjut</label>
          
          <div class="col-md-4">
            
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'pasca_pulang')), isset($keracunan)?$keracunan->tindak_lanjut:'', 'tindak_lanjut_keracunan', 'tindak_lanjut_keracunan', 'form-control', '', '') ?>
          
          </div>
              
        </div>

        <input type="hidden" class="form-control" name="id_cetak_racun" id="id_cetak_racun" value="<?php echo isset($keracunan)?$keracunan->id_cetak_racun:''?>">
            
        <div class="form-group">
          <label class="control-label col-sm-2" for="">&nbsp;</label>
          <div class="col-sm-4" style="margin-left:6px">
            <a href="#" class="btn btn-xs btn-primary" id="btn_keracunan"><i class="fa fa-save"></i> Submit </a>
          </div>
        </div>

    </div><!--end data keracunan -->

  </div>
    

     
</div>







