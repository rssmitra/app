<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <div class="clearfix" style="margin-bottom:-5px">
      <button type="button" id="btnPengajuanSep" class="btn btn-sm btn-primary" data-toggle="button">Proses Pengajuan SEP</button>
      <button type="button" id="btnApprovalSep" class="btn btn-sm btn-success" data-toggle="button">Approval Pengajuan SEP</button>
    </div>
    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="ws_bpjs/Ws_index/get_data_history_sep?flag=" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="30px" class="center"></th>
            <th>No SEP</th>
            <th width="120px">Nama</th>
            <th width="100px">Tanggal SEP</th>
            <th width="120px">Tanggal Pulang</th>
            <th>Poli</th>
            <th>Diagnosa</th>
            <th>Status</th>
            <th width="100px">Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<div id="modalShowForm" class="modal fade" tabindex="-1">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width80%">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
          </button>
          <span id="result_text">Pulangkan Pasien Dengan No SEP ""</span>
        </div>
      </div>
      <form class="form-horizontal" method="post" id="formUpdateTglPulang" action="<?php echo base_url().'ws_bpjs/ws_index/updateTglPulang'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">
        <div class="modal-body">
              <br>

              <div class="form-group">
                <label class="control-label col-md-4">Tanggal Pulang</label>
                <div class="col-md-6">
                  <div class="input-group">
                      <input name="tglPulang" id="tglPulang" value="" placeholder="dd/MM/YYYY" class="form-control date-picker" type="text">
                      <input type="hidden" id="noSep" name="noSep" value="">
                      <span class="input-group-addon">
                        <i class="ace-icon fa fa-calendar"></i>
                      </span>
                    </div>
                </div>
              </div>

            
        </div>
        <div class="modal-footer no-margin-top">
          <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
            <i class="ace-icon fa fa-times"></i>
            Batalkan
          </button>
          <button type="submit" class="btn btn-sm btn-primary pull-left">
            <i class="ace-icon fa fa-save"></i>
            Proses Pulangkan
          </button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable_custom_url.js'?>"></script>
<script type="text/javascript">

  jQuery(function($) {

    $('.date-picker').datepicker({
      autoclose: true,
      todayHighlight: true,
      dateFormat: 'yyyy-MM-dd'
    })
    //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){
      $(this).prev().focus();
    });
  });

  $(document).ready(function(){
  
    $('#formUpdateTglPulang').ajaxForm({
      beforeSend: function() {
        achtungShowFadeIn();
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status == 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          
          reload_table()
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        $("#modalShowForm").modal('hide');
        achtungHideLoader();
      }
    }); 

    $("#btnPengajuanSep").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          count =  searchIDs.length;
          if(count == 0){
            $.achtung({message: 'Silahkan pilih salah satu data', timeout:5});
          }else{
            prosesPengajuanSep(searchIDs);
          }
          
          console.log(searchIDs);
    });

    $("#btnApprovalSep").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          count =  searchIDs.length;
          if(count == 0){
            $.achtung({message: 'Silahkan pilih salah satu data', timeout:5});
          }else{
            prosesApprovalSep(searchIDs);
          }
          
          console.log(searchIDs);
    });

    function prosesPengajuanSep(arraySep){
        $.ajax({
            url: 'ws_bpjs/ws_index/prosesPengajuanSep',
            type: "post",
            data: {arrNoSep:arraySep},
            dataType: "json",
            beforeSend: function() {
              achtungShowFadeIn();  
            },
            success: function(jsonResponse) {
              reload_table();
              $.achtung({message: jsonResponse.message, timeout:5});
              achtungHideLoader();
            }
        });
    }

    function prosesApprovalSep(arraySep){
        $.ajax({
            url: 'ws_bpjs/ws_index/prosesApprovalSep',
            type: "post",
            data: {arrNoSep:arraySep},
            dataType: "json",
            beforeSend: function() {
              achtungShowFadeIn();  
            },
            success: function(jsonResponse) {
              reload_table();
              $.achtung({message: jsonResponse.message, timeout:5});
              achtungHideLoader();
            }
        });
    }


})

function delete_sep(sep, jp, tgl){
    $.ajax({
        url: 'ws_bpjs/ws_index/delete_sep',
        type: "post",
        data: {ID:sep, jnsPelayanan:jp, tglSep:tgl},
        dataType: "json",
        beforeSend: function() {
          achtungShowFadeIn();  
        },
        success: function(jsonResponse) {
          reload_table();
          $.achtung({message: jsonResponse.message, timeout:5});
          achtungHideLoader();
        }
    });
}

function showModal(noSep){  

    $("#result_text").text('Pulangkan Pasien Dengan No SEP "'+noSep+'"');  
    $("#noSep").val(noSep);  
    $("#modalShowForm").modal();  

}

</script>


