<script type="text/javascript">

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

        <div class="col-sm-12">

          <div class="col-xs-6">
            <h4><?php echo isset($value)?ucwords($value->kode_trans_far):''?> - <?php echo isset($value)?ucwords($value->nama_pasien):''?> (<?php echo isset($value)?ucwords($value->no_resep):''?>) </h4>
          </div>
          <div class="pull-right">
          <button type="button" onclick="getMenu('farmasi/Etiket_obat')" class="btn btn-default btn-xs">
              <span class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></span>
              Kembali ke Halaman Utama
            </button>

            <button type="button" id="btn_copy_resep" class="btn btn-purple btn-xs">
              <span class="ace-icon fa fa-copy icon-on-right bigger-110"></span>
              Copy Resep
            </button>

            <button type="button" id="btn_cetak_etiket" class="btn btn-primary btn-xs">
                  <span class="ace-icon fa fa-print icon-on-right bigger-110"></span>
                  Cetak Etiket
            </button>
          </div>

        </div>

      </div>

      <hr>

      <div class="row">
        <div class="col-md-12">

          <p><b>RESEP OBAT FARMASI</b></p>
          <table id="resep_obat_etiket" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th class="center" width="20px">
                  <label class="pos-rel">
                      <input type="checkbox" class="ace" name="checked_all" value="" onclick="checkAll(this)"/>
                      <span class="lbl"></span>
                  </label>
                </th>
                <th class="center" width="50px">No</th>
                <th width="100px">Kode</th>
                <th>Nama Obat</th>
                <th width="80px">Jumlah</th>
                <th width="80px">Retur</th>
                <th width="80px"></th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $no = 0;
                foreach($detail_obat as $row) { $no++;
                  $readonly = (empty($row->id_fr_tc_far_detail_log))?'':'readonly';
                  echo '<tr id="row_kd_brg_'.$row->kode_brg.'">';
                  echo '<td>';
                    echo '<label class="pos-rel">
                              <input type="checkbox" class="ace checkbox_resep" name="selected_id[]" value="'.$row->kd_tr_resep.'" id="checkbox_id_'.$row->kd_tr_resep.'" />
                              <span class="lbl"></span>
                          </label>';
                    // hidden form
                    echo '<input type="hidden" name="kd_tr_resep_'.$row->kode_brg.'" value="'.$row->kd_tr_resep.'" >';
                    echo '<input type="hidden" name="kode_brg_'.$row->kode_brg.'" value="'.$row->kode_brg.'" >';

                  echo '</td>';
                  echo '<td align="center">'.$no.'</td>';
                  echo '<td>'.$row->kode_brg.'</td>';
                  $nama_obat = ($row->nama_brg)?$row->nama_brg:$row->nama_racikan;
                  echo '<td>'.$nama_obat.'</td>';
                  // jumlah
                  $jumlah = ($row->jumlah_obat)?$row->jumlah_obat:$row->jumlah_tebus;
                  echo '<td align="center">';
                    echo '<input style="width:80px;height:45px;text-align:center" type="hidden" name="jumlah_'.$row->kode_brg.'" value="'.$jumlah.'" '.$readonly.'>';
                    echo $jumlah;
                  echo '</td>';
                  // retur
                  echo '<td align="center">';
                    echo '<input style="width:80px;height:45px;text-align:center" type="text" name="jumlah_retur_'.$row->kode_brg.'" value="'.$row->jumlah_obat.'" '.$readonly.'>';
                  echo '</td>';
                  // aksi
                  echo '<td align="center">';
                    
                  $hidden = (empty($row->id_fr_tc_far_detail_log)) ? '' : 'style="display: none"' ;
                    echo '<a href="#" class="btn btn-xs btn-primary" id="btn_submit_'.$row->kode_brg.'" onclick="saveRow('."'".$row->kode_brg."'".')" '.$hidden.'><i class="fa fa-check-circle"></i></a> '; 
                    
                    echo '<a href="#" onclick="click_edit('."'".$row->kode_brg."'".')" id="btn_edit_'.$row->kode_brg.'" class="btn btn-xs btn-warning"><i class="fa fa-pencil dark"></i></a>';
                  echo '</td>';

                  echo '</tr>';
                }
              ?>
            </tbody>
          </table>

        </div>
      </div>

    </form>


  </div>

</div><!-- /.row -->

