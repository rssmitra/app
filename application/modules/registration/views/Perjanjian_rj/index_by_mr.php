<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script>

  jQuery(function($) {  

    $('.date-picker').datepicker({    

      autoclose: true,    

      todayHighlight: true    

    })  

    //show datepicker when clicking on the icon

    .next().on(ace.click_event, function(){    

      $(this).prev().focus();    

    });  
    var oTable;
    var base_url = $('#dynamic-table').attr('base-url'); 

    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "bPaginate": false,
      "searching": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url,
          "type": "POST"
      },
      "columnDefs": [
          { 
            "targets": [ 0 ], //last column
            "orderable": false, //set not orderable
          },
      ],

    });
    

  });

  $('input[name="flag"]').click(function (e) {
    var value = $(this).val();
    var no_mr = $('#no_mr_pasien_perjanjian').val();   
    oTable.ajax.url('registration/Perjanjian_rj/get_data?no_mr='+no_mr+'&flag='+value).load();
    
  }); 

  function cetak_surat_kontrol(ID) {   
    var no_mr = $('#tabs_riwayat_perjanjian_id').attr('data-id');  
    if( no_mr == '' ){
      alert('Silahkan cari pasien terlebih dahulu !'); return false;
    }else{
      url = 'registration/Reg_pasien/surat_control?id_tc_pesanan='+ID;
      title = 'Cetak Barcode';
      width = 850;
      height = 500;
      PopupCenter(url, title, width, height);
    }

  }

</script>

<div class="row">
  <div class="col-xs-12">
    <P><b> PERJANJIAN PASIEN <i class="fa fa-angle-double-right bigger-120"></i> </b></P>

    <form class="form-horizontal" method="post" id="form_search" action="registration/Perjanjian_rj/find_data">
      <!-- no mr -->
      <input type="hidden" name="no_mr_pasien_perjanjian" id="no_mr_pasien_perjanjian" value="<?php echo $no_mr?>">
      <div class="form-group">
                      
        <label class="control-label col-sm-2">Pelayanan</label>

        <div class="col-md-6">

          <div class="radio">

              <label>

                <input name="flag" type="radio" class="ace" value="NULL" checked />

                <span class="lbl"> Rawat Jalan</span>

              </label>

              <label>

                <input name="flag" type="radio" class="ace" value="bedah" />

                <span class="lbl"> Bedah</span>

              </label>

              <label>

                <input name="flag" type="radio" class="ace" value="HD" />

                <span class="lbl"> Hemodialisa</span>

              </label>

          </div>

        </div>

      </div>

      <div class="form-group">
                      
        <label class="control-label col-sm-2">Tanggal</label>

        <div class="col-md-3">
          <div class="input-group">
            <input name="tanggal" id="tanggal" placeholder="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd" class="form-control date-picker" type="text" value="">
            <span class="input-group-addon"><i class="ace-icon fa fa-calendar"></i></span>
          </div>
        </div>

        <div class="col-sm-4" style="margin-left:-20px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
        </div>

      </div>

      

    </form>

    
  </div><!-- /.col -->
</div><!-- /.row -->

<hr class="separator">
<!-- div.dataTables_borderWrap -->
<div style="margin-top:-27px">
  <table id="dynamic-table" base-url="registration/Perjanjian_rj/get_data?no_mr=<?php echo $no_mr?>" class="table table-bordered table-hover">
    <thead>
      <tr>  
        <th width="30px" class="center"></th>
        <th></th>
        <!-- <th>No MR</th>
        <th>Nama Pasien</th> -->
        <th>Tujuan</th>
        <th>Tgl Kontrol</th>
        
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<div id="modalDaftarPerjanjian" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">PERJANJIAN PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_daftar_perjanjian_pasien_modal"></div>

      </div>

      </div>

    </div>

  </div>

</div>




