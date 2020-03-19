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
      <button class="btn btn-xs btn-primary" onclick="getMenu('registration/reg_klinik')"> ~ FORM PENDAFTARAN ~ </button>
      <a href="<?php echo base_url().'display_loket/main'?>" target="_blank" class="btn btn-xs btn-inverse" > ~ DISPLAY LOKET ~ </a>
    </div>

    <hr class="separator">
    <!-- div.table-responsive -->

    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table"  base-url="registration/Reg_loket"  class="table table-striped table-bordered table-hover">
       <thead>
        <tr>  
          <th>&nbsp;</th>
          <th>Action</th>
          <th>Loket</th>
          <th>Klinik</th>
          <th>Dokter</th>
          <th>Jam Praktek</th>
          <th>Kuota</th>
          <th>Sisa</th>
          <th>Status</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<div id="modalUbahStatusLoket" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:100%;  margin-top: 50px; margin-bottom:50px;width:50%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_create_sep">FORM UBAH STATUS LOKET</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="proses-loading"><img src="<?php echo base_url().'assets/images/loading_2.gif'?>">></div>

        <div id="form_update_status_loket_modal"></div>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>

<div id="modalUbahJadwal" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:100%;  margin-top: 50px; margin-bottom:50px;width:50%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_ubah_jadwal">FORM UBAH JADWAL</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="proses-loading"><img src="<?php echo base_url().'assets/images/loading_2.gif'?>">></div>

        <div id="form_update_jadwal"></div>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>


<script src="<?php echo base_url().'assets/js/custom/als_datatable_no_style.js'?>"></script>

<script type="text/javascript">

  function showFormModalStatusLoket(jd_id)

  {  

    $('#result_text_create_sep').text('FORM UBAH STATUS LOKET');

    $('#form_update_status_loket_modal').load('registration/reg_loket/form_status_loket/'+jd_id+''); 

    $("#modalUbahStatusLoket").modal();  

  }

  function showFormModalUbahJadwal(jd_id)

  {  

    $('#result_ubah_jadwal').text('FORM UBAH JADWAL');

    $('#form_update_jadwal').load('registration/reg_loket/form_ubah_jadwal/'+jd_id+''); 

    $("#modalUbahJadwal").modal();  

  } 

  function checked_status(jd_id){

    $.ajax({
      url: 'registration/reg_loket/update_status_loket',
      type: "post",
      data: {ID:jd_id},
      dataType: "json",
      beforeSend: function() {
      },
      success: function(data) {
        reload_table();
      }
    }); 

  }

</script>



