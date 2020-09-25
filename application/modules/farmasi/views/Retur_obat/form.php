<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

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

function checkAll(elm) {

  if($(elm).prop("checked") == true){

    $('.checkbox_resep').each(function(){
      $(this).prop("checked", true);      
    });

  }else{
    $('.checkbox_resep').each(function(){
      $(this).prop("checked", false);      
    });
  }

}

function click_edit(kode_brg){
  $("#row_kd_brg_"+kode_brg+" input[type=text], select").attr('readonly', false); 
  $('#btn_submit_'+kode_brg+'').show();
  $('#btn_edit_'+kode_brg+'').hide();
}

function saveRow(kode_brg){

  preventDefault();
  var data = {
    jumlah_retur : $("#row_kd_brg_"+kode_brg+" input[name=jumlah_retur_"+kode_brg+"]").val(),
    kd_tr_resep : $("#row_kd_brg_"+kode_brg+" input[name=kd_tr_resep_"+kode_brg+"]").val(),
    kode_brg : $("#row_kd_brg_"+kode_brg+" input[name=kode_brg_"+kode_brg+"]").val(),
    kode_trans_far : $("#kode_trans_far").val(),
  };

  $.ajax({
      url: "farmasi/Retur_obat/process",
      data: data,            
      dataType: "json",
      type: "POST",
      beforeSend: function() {        
        achtungShowLoader();          
      },  
      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});  
          $("#row_kd_brg_"+kode_brg+" input[type=text]").attr('readonly', true); 
          $('#btn_submit_'+kode_brg+'').hide();
          $('#btn_edit_'+kode_brg+'').show();
          
        }else{

          $.achtung({message: jsonResponse.message, timeout:5}); 

        }    

        achtungHideLoader();  

      }

  });

  return false;

}

$("#btn_cetak_etiket").click(function(event){
      event.preventDefault();
      var searchIDs = $("#resep_obat_etiket tbody input:checkbox:checked").map(function(){
        return $(this).val();
      }).toArray();
      if(searchIDs.length == 0){
        alert('Tidak ada item yang dipilih !'); 
        return false;
      }
      get_kode_eticket(searchIDs);
      console.log(searchIDs);
});

$("#btn_copy_resep").click(function(event){
      event.preventDefault();
      var kode = $('#kode_trans_far').val();
      getMenu('farmasi/Etiket_obat/form_copy_resep/'+kode+'');
});

function get_kode_eticket(myid){

  $.ajax({
        url: 'farmasi/Etiket_obat/get_kode_eticket',
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
          PopupCenter('farmasi/Etiket_obat/preview_etiket?'+jsonResponse.params+'', 'Etiket Obat' , 600 , 600);
          achtungHideLoader();
        }

    });

}

</script>

<div class="row">

  <div class="col-xs-12">

    <!-- breadcrumbs -->
    <div class="page-header">  
      <h1>
        <?php echo $title?>        
        <small><i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div> 
        
    <form class="form-horizontal" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      
      
      <!-- hidden form -->
      <input type="hidden" name="kode_trans_far" id="kode_trans_far" value="<?php echo isset($value)?ucwords($value->kode_trans_far):''?>">
      
      <div class="row">
        <div class="col-md-12">

        <table class="table">
          <thead>
            <tr>
              <th>Kode Transaksi</th>
              <th>No. Kuitansi</th>
              <th>Nama Pasien</th>
              <th>Dokter Pengirim</th>
              <th>Tanggal Transaksi</th>
            </tr>
          </thead>
          <tr style="background-color: #edf3f4">
            <td> <?php echo isset($value)?$value->kode_trans_far:''?> </td>
            <td> <?php echo isset($value)?$value->no_resep:''?> </td>
            <td> <?php echo isset($value)?$value->no_mr.' - ':''; echo isset($value)?$value->nama_pasien:''?> </td>
            <td> <?php echo isset($value)?$value->dokter_pengirim:''?> </td>
            <td> <?php echo isset($value)?ucwords($this->tanggal->formatDateTime($value->tgl_trans)):''?> </td>
          </tr>
        </table>
        
          <div class="form-group">
            <label class="control-label col-sm-2">Tanggal Retur</label>
            <div class="col-md-2">
              <div class="input-group">
                <input name="tgl_resep" id="tgl_resep" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                <span class="input-group-addon">
                  <i class="ace-icon fa fa-calendar"></i>
                </span>
              </div>
            </div> 
            <label class="control-label col-sm-2">Nama Petugas</label>
            <div class="col-md-2">
                <input class="form-control" type="text" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
            </div>
          </div>

          <div class="col-sm-5 no-padding">

            <div class="widget-box">
              <div class="widget-header">
                  <span class="widget-title" style="font-size: 14px; font-weight: bold; color: black">Form Retur Obat Farmasi</span>
                <div class="widget-toolbar">
                  <span class="ace-icon fa fa-refresh icon-on-right bigger-150 blue"></span>
                </div>
              </div>
              <div class="widget-body" style="padding:5px; min-height: 278px !important" >
                <table id="resep_obat_etiket" class="table table-bordered table-hover">
                  <thead>
                    <tr>  
                      <th class="center" width="30px">No</th>
                      <th>Deskripsi</th>
                      <th class="center" width="80px">Jumlah Tebus</th>
                      <!-- <th class="center" width="100px">Harga Satuan</th> -->
                      <th class="center" width="80px">Jumlah Retur</th>
                      <th width="50px"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $no = 0;
                      foreach($detail_obat as $row) { 
                        $sisa = $row->jumlah_tebus - $row->jumlah_retur;
                        // echo '<pre>';print_r($row);die;
                        $nama_brg = ($row->id_tc_far_racikan == 0)?$row->nama_brg:'Racikan';
                        if( $sisa > 0 ){
                          $no++;
                          $readonly = (empty($row->id_fr_tc_far_detail_log))?'':'readonly';
                          echo '<tr id="row_kd_brg_'.$row->kode_brg.'">';
                          echo '<td align="center">'.$no.'</td>';
                          echo '<td><b>'.$row->kode_brg.'</b><br>'.$nama_brg.'<br>@ '.number_format($row->harga_jual, 2).',-'.'</td>';
                          // jumlah
                          echo '<td align="center">';
                            echo '<input style="width:80px;height:45px;text-align:center" type="hidden" name="jumlah_'.$row->kode_brg.'" value="'.$row->jumlah_tebus.'" '.$readonly.'>';
                            echo number_format($row->jumlah_tebus).' '.$row->satuan_kecil;
                          echo '</td>';

                          // harga satuan
                          // echo '<td align="right">';
                          //   echo number_format($row->harga_jual, 2).',-';
                          // echo '</td>';

                          // retur
                          echo '<td align="center">';
                            echo '<input style="width:80px;height:45px;text-align:center" type="text" name="jumlah_retur_'.$row->kode_brg.'" value="'.$row->jumlah_retur.'" '.$readonly.'>';
                          echo '</td>';
                          
                          // aksi
                          echo '<td align="center">';
                            
                          $hidden = (empty($row->id_fr_tc_far_detail_log)) ? '' : 'style="display: none"' ;
                            echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$row->kode_brg.'" onclick="saveRow('."'".$row->kode_brg."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                            
                            echo '<a href="#" onclick="click_edit('."'".$row->kode_brg."'".')" id="btn_edit_'.$row->kode_brg.'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                          echo '</td>';

                          echo '</tr>';
                        }
                      }
                    ?>
                  </tbody>
                </table>
                <p>Keterangan :</p>

                <ol>
                      <li> Untuk obat racikan jika ingin di retur, maka harus di retur semuanya atau tidak bisa di retur sebagian.</li>
                      <li> Barang yang sudah di retur tidak dapat dikembalikan lagi</li>
                </ol>
                
              </div>
            </div>

          </div>
          
          <div class="col-sm-7">

            <div class="widget-box">
              <div class="widget-header">
                  <span class="widget-title" style="font-size: 14px; font-weight: bold; color: black">List Obat Retur</span>
                <div class="widget-toolbar">
                  <span class="ace-icon fa fa-refresh icon-on-right bigger-150 blue"></span>
                </div>
              </div>
              <div class="widget-body" style="padding:5px; min-height: 278px !important" >
                <table id="resep_obat_etiket" class="table table-bordered table-hover" >
                  <thead>
                    <tr>  
                      <th class="center" width="30px">No</th>
                      <th>Deskripsi</th>
                      <th class="center" width="80px">Jumlah<br>Tebus</th>
                      <th class="center" width="80px">Jumlah<br>Retur</th>
                      <th class="center" width="80px">Total<br>Retur</th>
                      <th width="150px">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $no = 0;
                      foreach($detail_obat as $row) { $no++;
                        $sisa = $row->jumlah_tebus - $row->jumlah_retur;
                        
                        if( $sisa <= 0 ){
                          
                          echo '<tr>';
                          echo '<td align="center">'.$no.'</td>';
                          echo '<td>'.$row->kode_brg.'<br>'.$nama_brg.' @ '.number_format($row->harga_jual, 2).',-'.'/ '.$row->satuan_kecil.'</td>';
                          // jumlah tebus
                          echo '<td align="center">';echo number_format($row->jumlah_tebus).' '.$row->satuan_kecil; '</td>';
                          // jummlah retur
                          echo '<td align="center">';echo number_format($row->jumlah_retur).' '.$row->satuan_kecil; '</td>';
                          // harga satuan
                          echo '<td align="right">';
                            $harga_retur = $row->jumlah_retur * $row->harga_jual;
                            echo number_format($harga_retur, 2).',-';
                          echo '</td>';
                          echo '<td align="left">'.$this->tanggal->formatDateTime($row->tgl_retur).'<br>'.$row->retur_by.'</td>';
                          echo '</tr>';
                        }
                        
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>


        </div>
      </div>

    </form>


  </div>

</div><!-- /.row -->

