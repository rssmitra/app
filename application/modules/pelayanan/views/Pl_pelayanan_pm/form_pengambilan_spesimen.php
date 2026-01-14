<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
<script type="text/javascript">

jQuery(function($) {  

  $('.date-picker').datepicker({    
    autoclose: true,    
    todayHighlight: true    
  })  
  .next().on(ace.click_event, function(){    
    $(this).prev().focus();    
  });  

  $('#jam_sample').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#jam_sample').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

});

function reset_table(){
    oTable.ajax.url('pelayanan/Pl_pelayanan_lab/get_hasil_pm?kode_penunjang=<?php echo $id?>&mktime=<?php echo $mktime?>').load();
}


function delete_transaksi(myid,type){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/delete',
        type: "post",
        data: {ID:myid,type:type},
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


function hapus_file(a, b)
{

  if(b != 0){
    $.getJSON("<?php echo base_url('posting/delete_file') ?>/" + b, '', function(data) {
        document.getElementById("file"+a).innerHTML = "";
        greatComplate(data);
    });
  }else{
    y = a ;
    document.getElementById("file"+a).innerHTML = "";
  }

}

counterfile = <?php $j=1;echo $j.";";?>

function tambah_file()
{

  counternextfile = counterfile + 1;

  counterIdfile = counterfile + 1;

  document.getElementById("input_file"+counterfile).innerHTML = "<div id=\"file"+counternextfile+"\"><div class='form-group'><label class='col-md-2'>&nbsp;</label><div class='col-md-2'><input type='text' name='pf_file_name[]' id='pf_file_name' class='form-control'></div><label class='control-label col-md-1'>Pilih File</label><div class='col-md-3'><input type='file' id='pf_file' name='pf_file[]' class='upload_file form-control' /></div><div class='col-md-1' style='margin-left:-2.5%'><input type='button' onclick='hapus_file("+counternextfile+",0)' value='x' class='btn btn-sm btn-danger'/></div></div></div><div id=\"input_file"+counternextfile+"\"></div>";

  counterfile++;

}

function submitPengambilanSampel()
{
  $.ajax({
    url: "pelayanan/Pl_pelayanan_pm/submit_pengambilan_sampel",
    type: "POST",
    data: $('#form_pengambilan_sampel').serialize(),
    dataType: "json",
    beforeSend: function() {
      achtungShowLoader();
    },
    success: function(response) {
      if(response.status === 200) {
        $.achtung({message: response.message, timeout: 5});
        reset_table();
      } else {
        $.achtung({message: response.message, timeout: 5, className: 'achtungFail'});
      }
    },
    error: function(xhr) {
      $.achtung({message: 'Error occurred', timeout: 5, className: 'achtungFail'});
    },
    complete: function() {
      achtungHideLoader();
    }
  });
}

</script>

<div class="row">
  <form class="form-horizontal" method="post" id="form_pengambilan_sampel" action="#" enctype="multipart/form-data" autocomplete="off" > 

    <input type="hidden" name="kode_penunjang" id="kode_penunjang" value="<?php echo ($id)?$id:''?>">

    <div class="col-md-12">
        <p style="font-weight: bold">FORM PENGAMBILAN SPESIMEN/SAMPEL LABORATORIUM</p>
        <div class="form-group">
            <label class="control-label col-sm-2" for="">*Tgl Pengambilan Sample</label>
            <div class="col-md-2">
              <div class="input-group">
                  <input name="pl_tgl_pm" id="pl_tgl_pm" placeholder="<?php echo date('Y-m-d')?>" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" />
                  <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                  </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="">*Waktu/Jam</label>
            <div class="col-md-2">
              <div class="input-group">
                  <input id="jam_sample" name="jam_sample"  type="text" class="form-control">
                  <span class="input-group-addon">
                    <i class="fa fa-clock-o bigger-110"></i>
                  </span>
              </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="">*Nama Petugas Lab</label>
            <div class="col-md-2">
              <input name="nama_petugas" id="nama_petugas" class="form-control " type="text" value="<?php echo $this->session->userdata('user')->fullname?>" readonly/>
            </div>
        </div>

        <br>
        <p style="font-weight: bold">JENIS PEMERIKSAAN</p>
        <table id="dynamic-table" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th>Pemeriksaan</th>
              <th width="200px">Nilai Standar</th>
              <th width="150px">Satuan</th>
              <th width="150px">Spesimen</th>
              <th width="150px">Tipe</th>
              <th width="150px">Metode</th>
              <th class="center">Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=0; 
            $nama_tindakan='';
            $detail_item_1 = '';
            $detail_item_2 = '';
              // echo '<pre>'; print_r($list);die;
              foreach ($list as $key=>$row_list) {
                
                $nilai_std = ($pasien->jen_kelamin=='L') ? $row_list->standar_hasil_pria : $row_list->standar_hasil_wanita ;

                $hasil = (isset($row_list->hasil))?stripslashes($row_list->hasil):'';
                $ket = (isset($row_list->keterangan_pm))?$row_list->keterangan_pm:'';
                $kode_tc_hasilpenunjang =  (isset($row_list->kode_tc_hasilpenunjang))?$row_list->kode_tc_hasilpenunjang:0;

                if($row_list->nama_tindakan!=$nama_tindakan){
                  echo
                    '<tr>
                      <td colspan="6"><b>'.$row_list->nama_tindakan.'</b></td>
                    </tr>';
                    $nama_tindakan = $row_list->nama_tindakan;
                }
                
                if(($row_list->detail_item_1!=' ') OR ($row_list->detail_item_1=='' OR $row_list->detail_item_1==' ')){

                  $detail_item_1 = (!empty(trim($row_list->detail_item_1)))?' <i class="fa fa-arrow-right"></i> '.$row_list->detail_item_1:'';
                  $detail_item_2 = (!empty(trim($row_list->detail_item_2)))?' <i class="fa fa-arrow-right"></i> '.$row_list->detail_item_2:'';
                  echo
                    '<tr>
                      <td style="padding-left: 30px">- '.$row_list->nama_pemeriksaan.' '.$detail_item_1.' '.$detail_item_2.'</td>
                      <td align="center">'. $nilai_std.' '.$row_list->satuan.'</td> 
                      <td align="center">'. $this->master->custom_selection($params = array('table' => 'view_mst_loinc_uom', 'id' => 'id', 'name' => 'label', 'where' => array() ), $row_list->loinc_uom , 'loinc_uom['.trim($row_list->kode_mt_hasilpm).']', 'loinc_uom', 'form-control', '', '').'</td> 
                      <td align="center">'. $this->master->custom_selection($params = array('table' => 'view_mst_loinc_speciment', 'id' => 'id', 'name' => 'label', 'where' => array() ), $row_list->loinc_speciment , 'loinc_speciment['.trim($row_list->kode_mt_hasilpm).']', 'loinc_speciment', 'form-control', '', '').'</td> 
                      <td align="center">'. $this->master->custom_selection($params = array('table' => 'view_mst_loinc_type', 'id' => 'id', 'name' => 'label', 'where' => array() ), $row_list->loinc_type , 'loinc_type['.trim($row_list->kode_mt_hasilpm).']', 'loinc_type', 'form-control', '', '') .'</td> 
                      <td align="center">'. $this->master->custom_selection($params = array('table' => 'view_mst_loinc_metode', 'id' => 'id', 'name' => 'label', 'where' => array() ), $row_list->loinc_metode , 'loinc_metode['.trim($row_list->kode_mt_hasilpm).']', 'loinc_metode', 'form-control', '', '').'</td> 
                      <td width="100px">
                        <input type="text" name="keterangan_pm['.trim($row_list->kode_mt_hasilpm).']" class="keterangan_pm" value="'.$ket.'">
                        <input type="hidden" name="nama_tindakan['.trim($row_list->kode_mt_hasilpm).']" value="'.$row_list->nama_tindakan.'" >
                        <input type="hidden" name="kode_tc_hasilpenunjang['.trim($row_list->kode_mt_hasilpm).']" value="'.$kode_tc_hasilpenunjang.'" >
                        <input type="hidden" name="kode_mt_hasilpm[]" value="'.trim($row_list->kode_mt_hasilpm).'" >
                        <input type="hidden" name="kode_trans_pelayanan['.trim($row_list->kode_mt_hasilpm).']" value="'.$row_list->kode_trans_pelayanan.'" > 
                      </td>           
                    </tr>
                    
                    ';
                    $detail_item_1 = $row_list->detail_item_1;
                    $detail_item_2 = $row_list->detail_item_2;
                  $i++;
                }  
              }
            ?>
          </tbody>
        </table>
        <p style="font-style: italic;">
          <b>Note : </b> Lengkapi data pada kolom Spesimen, Tipe, Metode dan Keterangan sesuai dengan pemeriksaan yang dilakukan, karena data tersebut akan dikirim ke dalam platform <b>SATU SEHAT KEMENKES RI</b>.
        </p>
        <br>
        
        <!-- <p style="font-weight: bold">CATATAN PEMERIKSAAN</p>
        <div class="col-md-12 no-padding">
          <textarea class="form-control" name="catatan_hasil" id="catatan_hasil" cols="50" style="height:150px !important;"><?php echo isset($catatan_hasil)?strip_tags($catatan_hasil):'';?></textarea>
        </div> -->

        </form>

        <div class="form-group">
            <div class="col-sm-12 center" style="padding-top: 10px">
                <button type="button" href="#" id="btn_submit_pengambilan_sampel" onclick="submitPengambilanSampel()" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Submit Pengambilan Sampel</button>
            </div>
        </div>

    </div>

  </form>
  
  <br>
  <br>
</div>






