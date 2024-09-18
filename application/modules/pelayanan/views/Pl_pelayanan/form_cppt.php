<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-timepicker.js"></script>
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

  $('#timepicker1').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    disableFocus: true,
    icons: {
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down'
    }
  }).on('focus', function() {
    $('#timepicker1').timepicker('showWidget');
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
    

});

$(document).ready(function() {
  //initiate dataTables plugin
    oTableCppt = $('#table-cppt').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>",
          "type": "POST"
      },

    });

    // proses add cppt
    $('#btn_add_cppt').click(function (e) {   
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
              $.achtung({message: jsonResponse.message, timeout:5});     
              $('#section_form_cppt').hide('fast');
              $('#section_history_cppt').show('fast');

              oTableCppt.ajax.url('pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>').load();
              // reset form
              $('#cppt_id').val('');
              $('#subjective').val('');
              $('#objective').val('');
              $('#assesment').val('');
              $('#plan').val('');
            }else{           
              $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
            }        
            achtungHideLoader();        
          } 
      });

    });

    $('#btn_search_data_cppt').click(function (e) {
        
        e.preventDefault();
        $.ajax({
        url: $('#form_search').attr('action'),
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(data) {
          achtungHideLoader();
          find_data_reload(data,base_url);
        }
      });
    });

  $('#btn_reset_data_cppt').click(function (e) {
          e.preventDefault();
          reset_table();
  });

  $('#btn_export_pdf_cppt').click(function (e) {
    var url_search = $('#form_search').attr('action');
    e.preventDefault();
    $.ajax({
      url: url_search,
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      success: function(result) {
        window.open('pelayanan/Pl_pelayanan_ri/export_pdf_cppt?kode_ri=<?php echo isset($kode_ri)?$kode_ri:''?>&'+result.data+'','_blank'); 
      }
    });
  });


});

function delete_cppt(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/delete_cppt',
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
            oTableCppt.ajax.url('pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>').load();
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

function verif_dpjp(cppt_id, value){
    
    if( $('#is_verified_' + cppt_id).is(":checked") ){
      var status = 1;
    }else{
      var status = 0;
    }

    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/verif_cppt',
        data: {ID : cppt_id, status_verif : status},            
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          if(status != 0){
            $('#verif_id_'+cppt_id+'').html('<?php echo $this->session->userdata('user')->fullname?><br><?php echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s'))?>');
          }else{
            $('#verif_id_'+cppt_id+'').html('');

          }
          return false;
        }
    });
    
}

function add_cppt(){
  preventDefault();
  $('#section_form_cppt').show('fast');
  $('#section_history_cppt').hide('fast');
}

function show_edit(myid){
  preventDefault();
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: myid} , function (response) {    
    // show data
    $('#section_form_cppt').show('fast');
    $('#section_history_cppt').hide('fast');
    $('#cppt_id').val(response.cppt_id);
    var subjective = response.cppt_subjective;
    $('#subjective').val(subjective.replace(/<br ?\/?>/g, "\n"));
    var objective = response.cppt_objective;
    $('#objective').val(objective.replace(/<br ?\/?>/g, "\n"));
    var assesment = response.cppt_assesment;
    $('#assesment').val(assesment.replace(/<br ?\/?>/g, "\n"));
    var plan = response.cppt_plan;
    $('#plan').val(plan.replace(/<br ?\/?>/g, "\n"));
  }); 
}

function find_data_reload(result, base_url){
  var data = result.data;    
  oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>&"+data).load();
  // $("html, body").animate({ scrollTop: "400px" });
}

function reset_table(){
  oTableCppt.ajax.url("pelayanan/Pl_pelayanan_ri/get_data_cppt?no_mr=<?php echo $no_mr?>").load();
  // $("html, body").animate({ scrollTop: "400px" });
}

function reload_table(){
 oTableCppt.ajax.reload(); //reload datatable ajax 
}

</script>

<div class="row">

  <div class="col-md-12" id="section_form_cppt" style="display: none">

    <div class="center"><span style="font-size: 14px"><b>FORM CPPT</b></span><br><small>(Dilengkapi setelah PPA melakukan Assesment)</small></div>
    <br>
    <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal/Jam</label>
          <div class="col-md-6">
                
            <div class="input-group">
                
                <input name="cppt_tgl" id="cppt_tgl" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="<?php echo date('Y-m-d')?>">
                <span class="input-group-addon">
                  
                  <i class="ace-icon fa fa-calendar"></i>
                
                </span>

                <input id="timepicker1" name="cppt_jam" id="cppt_jam" type="text" class="form-control">
                <span class="input-group-addon">
                  <i class="fa fa-clock-o bigger-110"></i>
                </span>
                
            </div>

          </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2">PPA</label>
        <div class="col-md-10">
          <div class="radio">
              <label>
                <input name="ppa" type="radio" class="ace" value="perawat" checked="checked"  />
                <span class="lbl"> Perawat</span>
              </label>

              <label>
                <input name="ppa" type="radio" class="ace" value="dokter"/>
                <span class="lbl"> Dokter</span>
              </label>

              <label>
                <input name="ppa" type="radio" class="ace" value="fisioterapist"/>
                <span class="lbl"> Fisioterapist</span>
              </label>

              <label>
                <input name="ppa" type="radio" class="ace" value="dietizen"/>
                <span class="lbl"> Dietizen</span>
              </label>

              <label>
                <input name="ppa" type="radio" class="ace" value="farmasi klinis"/>
                <span class="lbl"> Farmasi Klinis</span>
              </label>
          </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2">Nama PPA</label>
        <div class="col-md-3">
          <input type="text" class="form-control" name="nama_ppa" value="<?php echo $this->session->userdata('user')->fullname?>">
        </div>
    </div>
    <hr>
    <br>
    <p><b>Form SOAP <i>(Subjective, Objective, Assesment, Plan)</i></b></p>

    <!-- form default pelayanan pasien -->
    <input type="hidden" name="cppt_id" value="" id="cppt_id">
    <div>
        <label class="padding-20" style="padding-top: 10px"><b style="font-size: 18px; color: blue">S</b> (<i>Subjective</i>) <span style="color:red">(*)</span>:</label><br>
        <div class="col-sm-12 no-padding">
          <textarea name="subjective" id="subjective" class="form-control" style="height:120px !important"  placeholder="" ><?php echo isset($riwayat->subjective)?$riwayat->subjective:''?></textarea>  
        </div>
    </div>

    <div>
        <label class="padding-20" style="padding-top: 10px"><b style="font-size: 18px; color: blue">O</b> (<i>Objective</i>) <span style="color:red">(*)</span>:</label><br>
        <div class="col-sm-12 no-padding">
          <textarea name="objective" id="objective" class="form-control" style="height:120px !important"  placeholder="" ><?php echo isset($riwayat->objective)?$riwayat->objective:''?></textarea>  
        </div>
    </div>

    <div>
        <label class="padding-20" style="padding-top: 10px"><b style="font-size: 18px; color: blue">A</b> (<i>Assesment</i>) <span style="color:red">(*)</span>:</label><br>
        <div class="col-sm-12 no-padding">
          <textarea name="assesment" id="assesment" class="form-control" style="height:120px !important"  placeholder="" ><?php echo isset($riwayat->assesment)?$riwayat->assesment:''?></textarea>  
        </div>
    </div>

    <div>
        <label class="padding-20" style="padding-top: 10px"><b style="font-size: 18px; color: blue">P</b> (<i>Plan</i>) <span style="color:red">(*)</span>:</label><br>
        <div class="col-sm-12 no-padding">
          <textarea name="plan" id="plan" class="form-control" style="height:120px !important"  placeholder="" ><?php echo isset($riwayat->plan)?$riwayat->plan:''?></textarea>  
        </div>
    </div>

    <div class="col-md-12" id="btn_submit_cppt" style="margin-top: 20px" >
        <div class="col-sm-12"><a href="#" class="btn btn-sm btn-primary" id="btn_add_cppt"><i class="fa fa-save"></i> Simpan CPPT</a> 
        </div>
    </div>

    <br>
    <hr>
  </div>

  <div class="col-md-12" id="section_history_cppt">
    <!-- add form -->
    <!-- <div style="">
      <a href="#" class="btn btn-xs btn-primary" onclick="add_cppt()"><i class="fa fa-plus"></i> Input CPPT</a>
    </div> -->
    <center><span style="font-size: 14px"><b>CATATAN PERKEMBANGAN PASIEN <br>TERINTEGRASI (CPPT)</b></span></center><br>
      <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_ri/find_data" autocomplete="off">

        <div class="form-group">

            <label class="control-label col-md-2">Tanggal CPPT</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>

            <label class="control-label col-md-1" style="margin-left: 40px;padding-left: 19px">s/d</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>

            <div class="col-md-5 no-padding">
              <a href="#" id="btn_search_data_cppt" class="btn btn-xs btn-default" style="margin-left: 12%">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Search
              </a>
              <a href="#" id="btn_reset_data_cppt" class="btn btn-xs btn-warning">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset
              </a>
              <a href="#" id="btn_export_pdf_cppt" class="btn btn-xs btn-danger">
                <i class="fa fa-file-pdf-o bigger-110"></i>
                Export PDF
              </a>
            </div>

        </div>
          
        <table id="table-cppt" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th width="30px">No</th>
              <th width="150px">Tanggal/Jam/PPA</th>
              <th>SOAP/Pengkajian Pasien</th>
              <th width="120px">Verifikasi DPJP</th>
              <th width="100px">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </form>
  </div>
  
</div>







