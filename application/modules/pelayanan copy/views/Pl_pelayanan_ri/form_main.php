<script>
  $(document).ready(function(){

    window.filter = function(element)
    {
      var value = $(element).val().toUpperCase();
      $(".list-group > li").each(function() 
      {
        if ($(this).text().toUpperCase().search(value) > -1){
          $(this).show();
        }
        else {
          $(this).hide();
        }
      });
    }
    
    /*submit form*/
    $('#form_pelayanan').ajaxForm({      
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
          $('#table-pesan-resep').DataTable().ajax.reload(null, false);
          $('#jumlah_r').val('');
          $("#modalEditPesan").modal('hide');  
          if(jsonResponse.type_pelayanan == 'Penunjang Medis' || jsonResponse.type_pelayanan == 'Rawat Jalan')
          {
            $('#riwayat-table').DataTable().ajax.reload(null, false);
          }
          if(jsonResponse.type_pelayanan == 'pulangkan_pasien' )
          {
            $('#div_main_form').load('pelayanan/Pl_pelayanan_ri/form_main/'+$('#kode_ri').val()+'/'+$('#no_kunjungan').val()+'');
          }
        }else{           
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }        
        achtungHideLoader();        
      }      
    });     
    

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

      /*process add pesan ok*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_ri/process_add_diagnosa",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
            $('#btn_submit_diagnosa').hide('fast');
            $('#pl_diagnosa').attr('readonly', true);
            $('#pl_diagnosa_awal').attr('readonly', true);
            oTablePesanDiagnosa.ajax.url('pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>').load();
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

    });

    /*onchange form module when click tabs*/
    $('#tabs_tindakan').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/process');

    });

    $('#tabs_cppt').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/process_cppt');

    });

    $('#tabs_catatan').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processSaveCatatanPengkajian');

    });

    $('#tabs_pesan_resep').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'farmasi/Farmasi_pesan_resep/process');

    });   

    $('#tabs_penunjang_medis').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'registration/Reg_pm/process');

    });

    $('#tabs_klinik').click(function (e) {     
      
      e.preventDefault();  

      $('#form_pelayanan').attr('action', 'registration/Reg_klinik/process');

    });

})



function edit_diagnosa() {
  $('#btn_submit_diagnosa').show('fast');
  $('#btn_hide_submit_diagnosa').show('fast');
  $('#pl_diagnosa').attr('readonly', false);
  $('#pl_diagnosa_awal').attr('readonly', false);
}

function UnEditDiagnosa() {
  $('#btn_submit_diagnosa').hide('fast');
  $('#btn_hide_submit_diagnosa').hide('fast');
  $('#pl_diagnosa').attr('readonly', true);
  $('#pl_diagnosa_awal').attr('readonly', true);
}

function selesaikanKunjungan(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $("#myTab li").removeClass("active");
  //$('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan_ri/processPelayananSelesai');
  $('#tabs_form_pelayanan').show('fast');
  $('#tabs_form_pelayanan').load('pelayanan/Pl_pelayanan_ri/form_end_visit?mr='+noMr+'&id='+$('#kode_ri').val()+'&no_kunjungan='+$('#no_kunjungan').val()+''); 

}

function backToDefaultForm(){

  noMr = $('#noMrHidden').val();
  preventDefault();  
  $('#form_pelayanan').attr('action', 'pelayanan/Pl_pelayanan/processPelayananSelesai');
  $('#form_default_pelayanan').hide('fast');
  $('#form_default_pelayanan').html(''); 

}

function rollback(no_registrasi, no_kunjungan){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_ri/rollback",
      data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan },            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          $('#div_main_form').load('pelayanan/Pl_pelayanan_ri/form_main/'+$('#kode_ri').val()+'/'+$('#no_kunjungan').val()+'');
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });

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
            oTablePesanDiagnosa.ajax.url('pelayanan/Pl_pelayanan_ri/get_riwayat_diagnosa?no_kunjungan=<?php echo $no_kunjungan?>&no_registrasi=<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>').load();
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
<!-- end action form  -->
  
<!-- hidden form -->

<!-- hidden form -->  
  <input type="hidden" class="form-control" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
  <input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
  <input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>">
  <input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
  <input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
  <input type="hidden" class="form-control" name="kode_bagian_asal" value="<?php echo isset($value)?$value->bag_pas:''?>">
  <input type="hidden" class="form-control" name="kode_bagian" value="<?php echo isset($value)?$value->bag_pas:''?>" id="kode_bagian_val">
  <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>"  id="kode_klas_val">
  <input type="hidden" class="form-control" name="klas_titipan" value="<?php echo $klas_titipan ?>"  id="klas_titipan">
  <input type="hidden" class="form-control" name="kode_dokter_poli" value="<?php echo isset($value->kode_dokter)?$value->kode_dokter:''?>">
  <input type="hidden" class="form-control" name="kode_ruangan" value="<?php echo isset($value->kode_ruangan)?$value->kode_ruangan:''?>">
  <input type="hidden" name="kode_ri" id="kode_ri" value="<?php echo ($id)?$id:''?>">
  <input type="hidden" name="dr_merawat" value="<?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?>" id="dr_merawat">
  <input type="hidden" name="noKartu" id="form_cari_pasien" class="form-control search-query" placeholder="Masukan No MR atau Nama Pasien" value="<?php if(isset($no_mr)){echo $no_mr;}else if(isset($data_pesanan->no_mr)){echo $data_pesanan->no_mr; }else{ echo '';}?>" readonly>
  <input type="hidden" name="no_registrasi" class="form-control" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" readonly>
  <input type="hidden" name="no_kunjungan" id="no_kunjungan" class="form-control" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" id="no_kunjungan" readonly>
  <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:0?>">


  <table class="table table-bordered">
    <tr style="background-color:#f4ae11">
      <th width="100px">Status Pasien</th>
      <th>Kode/Tgl Masuk</th>
      <th>Dokter Merawat</th>
      <th>Ruangan/Kelas</th>
      <th>Penjamin</th>
      <th>Diagnosa Awal</th>
    </tr>

    <tr>
      <td align="center" style="vertical-align: middle"><?php echo $status_rawat; ?></td>
      <td>No. <?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?><br><?php echo isset($value->tgl_masuk)?$this->tanggal->formatDateTime($value->tgl_masuk):''?></td>
      <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
      <td><?php echo isset($value->nama_bagian)?$value->nama_bagian:'';?> (<?php echo isset($value->klas)?$value->klas:'';?>) <br><?php echo isset($ruangan)?'Kamar: '.$ruangan->no_kamar.' / Bed: '.$ruangan->no_bed:'';?></td>
      <td><?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?></td>
      <td><?php echo isset($riwayat->diagnosa_awal)?$riwayat->diagnosa_awal:'';?></td>
    </tr>

  </table>      
  
  <div class="row" style="margin-bottom:3px">
    <div class="col-md-12">
      <?php if($value->status_pulang==0) :?>
        <a href="#" class="btn btn-xs btn-primary" onclick="selesaikanKunjungan()" ><i class="fa fa-home"></i> Pulangkan Pasien</a>
      <?php else: ?>
        <!-- <a href="#" class="btn btn-xs btn-primary" onclick="getMenu('pelayanan/Pl_pelayanan_ri')"><i class="fa fa-angle-double-left"></i> Kembali ke Daftar Pasien</a> -->
        <a href="#" class="btn btn-xs btn-primary" onclick="selesaikanKunjungan()" ><i class="fa fa-stethoscope"></i> Resume Medis Pasien</a>
        <?php if($transaksi!=0):?><a href="#" class="btn btn-xs btn-primary" onclick="rollback(<?php echo isset($value)?$value->no_registrasi:'' ?>,<?php echo isset($value)?$value->no_kunjungan:''?>)"><i class="fa fa-times-circle"></i> Rollback Data</a><?php endif ?>
      <?php endif;?>
    </div>
  </div>

  <hr>

  <div class="tabbable">  

    <ul class="nav nav-tabs" id="myTab">

      <li>
        <a data-toggle="tab" id="tabs_cppt" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>" data-url="pelayanan/Pl_pelayanan_ri/cppt/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
          <i class="red ace-icon fa fa-leaf bigger-120"></i>
          CPPT
        </a>
      </li>

      <li>
        <a data-toggle="tab" id="tabs_catatan" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&no_mr=<?php echo $no_mr?>" data-url="pelayanan/Pl_pelayanan/catatan_lainnya/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
          <i class="blue ace-icon fa fa-file bigger-120"></i>
          PENGKAJIAN
        </a>
      </li>

      <li>
        <a data-toggle="tab" id="tabs_tindakan" href="#" data-id="<?php echo $no_kunjungan?>?type=Ranap&kode_bag=<?php echo isset($value)?$value->bag_pas:''?>" data-url="pelayanan/Pl_pelayanan_ri/tindakan/<?php echo $id?>" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')">
          <i class="green ace-icon fa fa-history bigger-120"></i>
          TINDAKAN
        </a>
      </li>

      <li>
        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="farmasi/Farmasi_pesan_resep/pesan_resep/<?php echo $value->no_kunjungan?>/<?php echo $kode_klas?>/<?php echo $kode_profit?>" id="tabs_pesan_resep" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id')+'?kode_bag=<?php echo $value->bag_pas?>', 'tabs_form_pelayanan')" >
          <i class="red ace-icon fa fa-list bigger-120"></i>
          RESEP
        </a>
      </li>

      <li>
        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_pm/rujuk_pm/<?php echo $value->no_registrasi?>/<?php echo $value->bag_pas?>/<?php echo $kode_klas?>/ranap" id="tabs_penunjang_medis" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
          <i class="orange ace-icon fa fa-globe bigger-120"></i>
          PENUNJANG MEDIS
        </a>
      </li>

      <li>
        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="registration/Reg_klinik/rujuk_klinik/<?php echo $value->no_registrasi?>/<?php echo $value->bag_pas?>/ranap/<?php echo $kode_klas?>" id="tabs_klinik" href="#" onclick="getMenuTabs(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
          <i class="blue ace-icon fa fa-edit bigger-120"></i>
          KLINIK
        </a>
      </li>

      <li>
        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="pelayanan/Pl_pelayanan_ri/pesan/<?php echo $id?>/<?php echo $value->no_registrasi?>" id="tabs_pesan" href="#" onclick="getMenuTabs(this.getAttribute('data-url')+'/'+this.getAttribute('data-id'), 'tabs_form_pelayanan')" >
          <i class="purple ace-icon fa fa-list bigger-120"></i>
          (OK/VK/PINDAH)
        </a>
      </li>

      <li>
        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="billing/Billing/getDetail/<?php echo $value->no_registrasi?>/RI" id="tabs_billing_pasien" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
          <i class="orange ace-icon fa fa-money bigger-120"></i>
          BILLING
        </a>
      </li>

      <li>
        <a data-toggle="tab" data-id="<?php echo $id?>" data-url="templates/References/get_riwayat_medis/<?php echo $value->no_mr?>" id="tabs_rekam_medis" href="#" onclick="getMenuTabsHtml(this.getAttribute('data-url'), 'tabs_form_pelayanan')" >
          <i class="orange ace-icon fa fa-history bigger-120"></i>
          REKAM MEDIS
        </a>
      </li>

    </ul>

    <div class="tab-content">

      <div class="row">
        <div id="tabs_form_pelayanan" style="padding: 10px !important">
          <div class="alert alert-block alert-success">
              <p class="center">
                <strong style="font-size: 16px">LEMBAR KERJA PELAYANAN PASIEN RAWAT INAP</strong> 
                <br>
                Silahkan klik pada Tab diatas untuk mengisi form yang sesuai!.
              </p>
            </div>
        </div>
      </div>
      
    </div>

  </div>

  <div id="form_default_pelayanan" style="background-color:#77dcd373"></div>