
<style>
.kbw-signature { width: 100%; height: 450px; border: 0px }
audio, canvas, progress, video {
    border: 0px solid white !important;
}
</style>
<script src="<?php echo base_url()?>assets/jSignature/js/jquery.signature.js"></script>
<script>
$(function() {
  var sig = $('#drawing_content').signature({thickness: 4});

	$('#clear').click(function() {
		sig.signature('clear');
  });
  
  $('#save_image').click(function() {
    $('#paramsSignature').val(sig.signature('toDataURL', 'image/png', 1));
  });

  // proses add cppt
  $('#btn_save_drawing_notes').click(function (e) {   
    e.preventDefault();
    $.ajax({
        url: $('#form_pelayanan').attr('action'),
        data: $('#form_pelayanan').serialize(),            
        dataType: "json",
        type: "POST",
        complete: function(xhr) {             
          var data=xhr.responseText;        
          var jsonResponse = JSON.parse(data);        
          if(jsonResponse.status === 200){          
            $('#btn_note').click();
            $.achtung({message: jsonResponse.message, timeout:5});  
          }else{           
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
          }        
          achtungHideLoader();        
        } 
    });

  });
  
});

function view_drawing(id){
  show_modal('pelayanan/Pl_pelayanan_ri/show_drawing/'+id+'', 'DRAWING');
}

function set_line_through(id, status){
  preventDefault();
  $.getJSON('pelayanan/Pl_pelayanan_ri/update_status_dt_monitoring', {ID: id, table: 'th_drawing_notes', deleted : status} , function(response_data) {
    $('tr#tbl_dt_'+id+'').hide();
    $('#btn_note').click();
  });
}
</script>
</head>
<body>

<div class="form-group">
    <label class="control-label col-sm-2">Dibuat oleh</label>
    <div class="col-md-10">
      <div class="radio">
          <label>
            <input name="created_by" type="radio" class="ace" value="perawat" checked="checked"  />
            <span class="lbl"> Perawat</span>
          </label>

          <label>
            <input name="created_by" type="radio" class="ace" value="dokter"/>
            <span class="lbl"> Dokter</span>
          </label>
      </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2">Nama</label>
    <div class="col-md-3">
      <input type="text" class="form-control" name="created_name" value="<?php echo $this->session->userdata('user')->fullname?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2">Jenis Catatan</label>
    <div class="col-md-10">
      <div class="radio">
          <label>
            <input name="note_type" type="radio" class="ace" value="perkembangan_pasien" checked="checked"  />
            <span class="lbl"> Perkembangan Pasien</span>
          </label>

          <label>
            <input name="note_type" type="radio" class="ace" value="catatan_dokter" checked="checked"  />
            <span class="lbl"> Catatan Dokter</span>
          </label>

          <label>
            <input name="note_type" type="radio" class="ace" value="lainnya"/>
            <span class="lbl"> Catatan Keperawatan Lainnya</span>
          </label>
      </div>
    </div>
</div>
<hr>
<span style="font-style: italic">Make your drawings or notes here</span>

<div id="drawing_content" style="min-height: 700px !important"></div>

<label>Signature code : </label><br>
<input type="text" value="" name="paramsSignature" class="form-control" id="paramsSignature" style="width: 100%; margin-bottom: 10px">

<div class="row">
<div class="col-md-6 pull-left">
	<!-- <button type="button" id="disable">Disable</button>  -->
	<button type="button" id="clear" class="btn btn-xs btn-danger"><i class="fa fa-undo"></i> Clear Image</button> 
	<!-- <button type="button" id="json">To JSON</button>
	<button type="button" id="svg">To SVG</button> -->
	<button type="button" id="save_image" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Finish Drawing</button>
</div>

<div class="col-md-6 pull-right">
  <a href="#" class="btn btn-xs btn-primary" id="btn_save_drawing_notes">Simpan Catatan</a>
</div>
</div>

<!-- hidden form -->
<input type="hidden" value="<?php echo isset($value)?$value->no_mr:''?>" name="no_mr_notes" id="no_mr_notes">

<br>
<span style="font-weight: bold">RIWAYAT DRAWING</span>
<table class="table">
  <tr style="background: #f3f3f3">
    <th width="30px" align="center">No</th>
    <th width="100px">Tanggal & Jam</th>
    <th class="center" width="100px">Dibuat Oleh</th>
    <th width="300px" class="left">Jenis Catatan</th>
    <th class="center" width="20px"></th>
  </tr>
<?php 
  if (count($note) == 0) {
    echo "<tr><td colspan='5'><div class='alert alert-warning'>Tidak ada data ditemukan</div></td></tr>";
  }else{
    $no=0;
    foreach($note as $row){
        $no++;
        $is_deleted = ($row->is_deleted == 1) ? 'style="text-decoration: line-through; color: red"' :'';
        if($row->is_deleted == 1){
          $btn = "<a href='#' onclick='set_line_through(".$row->id.", 0)'><i class='fa fa-refresh green bigger-120'></i></a>";
        }else{
          $btn = "<a href='#' onclick='set_line_through(".$row->id.", 1)'><i class='fa fa-times-circle red bigger-120'></i></a>";
        }
        echo "<tr id='tbl_dt_".$row->id."' ".$is_deleted.">";
        echo "<td align='center'>".$no."</td>";
        echo "<td>".$this->tanggal->formatDateTimeFormDmy($row->created_date)."</td>";
        echo "<td>".$row->created_by." [".$row->type_owner."] </td>";
        echo "<td><a href='#' onclick='view_drawing(".$row->id.")'>".ucwords(str_replace("_"," ",$row->jenis_catatan_draw))."</a></td>";
        echo "<td align='center'><span id='btn_action_".$row->id."'>".$btn."</span></td>";
        echo "</tr>";
    }
  }
  
?>
</table>
      

    






<!-- end form create SEP