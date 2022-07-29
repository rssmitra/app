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
});

$(document).ready(function() {

    if ( $('#kode_bagian').val() != '070101' ) {     
      // show hide section
      $('#section_medis').show();
      $('#section_non_medis').hide();  
      /*flag string*/
      $('#flag_string').val('medis');

    } else {    
      $('#section_non_medis').show();
      $('#section_medis').hide();
      /*flag string*/
      $('#flag_string').val('non_medis');
    }  

  //initiate dataTables plugin
    oTable = $('#dt-input-so-bag').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "pageLength": 50,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#dt-input-so-bag').attr('base-url'),
          "type": "POST"
      },

      "columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        { "aTargets" : [1], "sClass":  "hidden-480"}, 
        { "aTargets" : [3], "sClass":  "hidden-480"}, 
      ],

    });

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $('#sign-out-sess-so').click(function (e) {  

      $.ajax({
          url: "inventory/so/Input_dt_so/destroy_session_input_so",
          data: { },            
          dataType: "json",
          type: "POST",
          complete: function (xhr) {
            var data=xhr.responseText;  
            var jsonResponse = JSON.parse(data);  
            if(jsonResponse.status === 200){  
              $.achtung({message: jsonResponse.message, timeout:5}); 
              getMenu('inventory/so/Input_dt_so');
            }else{          
              $.achtung({message: jsonResponse.message, timeout:5});  
            } 
            achtungHideLoader();
          }
      });

    });

});

function updateRow(kode_brg, kode_bag, agenda_so_id){
  
  preventDefault();
  var val_id = $('#row_'+kode_brg+'_'+kode_brg+'_'+agenda_so_id+'').val();
  var val_exp_id = $('#row_exp_'+kode_brg+'_'+kode_brg+'_'+agenda_so_id+'').val();
  var val_will_exp_id = $('#row_will_exp_'+kode_brg+'_'+kode_brg+'_'+agenda_so_id+'').val();
  var is_active = $('#stat_on_off_'+kode_brg+'_'+kode_brg+'_'+agenda_so_id+'').val();
  $.ajax({
      url: "inventory/so/Input_dt_so/process_input_so",
      data: {kode_bagian : kode_bag, kode_brg : kode_brg, agenda_so_id : agenda_so_id, input_stok_so :val_id, exp_stok : val_exp_id, will_exp_stok : val_will_exp_id, status_aktif: is_active, flag : 'updaterow' },
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          /*$.achtung({message: jsonResponse.message, timeout:5}); */
          /*reload table*/
          // reset_table(kode_bag);
        }else{          
          /*$.achtung({message: jsonResponse.message, timeout:5});  */
        } 
        /*achtungHideLoader();*/
      }
  });

}

function reset_table(kode_bag){
    preventDefault();
    var golongan = ( $('#kode_bagian').val() == '070101' ) ? $('#kode_golongan').val() : $('#kode_kategori').val() ;
    var rak = ( $('#kode_bagian').val() == '070101' ) ? $('#rak_nm').val() : $('#rak_m').val() ;
    oTable.ajax.url('inventory/so/Input_dt_so/get_data?bag='+kode_bag+'&gol='+golongan+'&rak='+rak+'').load();
}


function setStatusAktifBrg(kode_brg, kode_bag, agenda_so_id){
  var val_id = $('#stat_on_off_'+kode_brg+'_'+kode_brg+'_'+agenda_so_id+'').val();
  var val_exp_id = $('#row_exp_'+kode_brg+'_'+kode_brg+'_'+agenda_so_id+'').val();
  var val_will_exp_id = $('#row_will_exp_'+kode_brg+'_'+kode_brg+'_'+agenda_so_id+'').val();

  $.ajax({
      url: "inventory/so/Input_dt_so/set_status_brg",
      data: {kode_bagian : kode_bag, kode_brg : kode_brg, agenda_so_id : agenda_so_id, input_stok_so:0, exp_stok: 0, value :val_id, status_aktif: val_id, will_exp_stok : val_will_exp_id, flag : 'setstatusaktif' },
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          /*reload table*/
          reset_table(kode_bag);
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
        } 
        achtungHideLoader();
      }
  });
}

function deleteRow(kode_depo_stok){

  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: "inventory/so/Input_dt_so/delete_row",
        data: {ID : kode_depo_stok, kode_bagian : $('#kode_bagian').val() },
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          var data=xhr.responseText;  
          var jsonResponse = JSON.parse(data);  
          if(jsonResponse.status === 200){  
            $.achtung({message: jsonResponse.message, timeout:5}); 
            /*reload table*/
            reset_table($('#kode_bagian').val());
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

function find_data_reload(){
  preventDefault();
  var golongan = ( $('#kode_bagian').val() == '070101' ) ? $('#kode_golongan').val() : $('#kode_kategori').val() ;
  var rak = ( $('#kode_bagian').val() == '070101' ) ? $('#rak_nm').val() : $('#rak_m').val() ;
  oTable.ajax.url($('#dt-input-so-bag').attr('base-url')+'&gol='+golongan+'&rak='+rak+'' ).load();

}

</script>

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

    <form class="form-horizontal" method="post" id="form_input_dt_so_header" action="<?php echo site_url('inventory/so/Input_dt_so/process')?>" enctype="multipart/form-data" >
      <br>

      <p><b>AGENDA KEGIATAN STOK OPNAME</b></p>
      <div class="form-group">
        <label class="control-label col-md-2">Agenda SO</label>
        <div class="col-md-3" style="margin-top:5px; margin-left:5px">
          <?php echo isset($value->agenda_so_name)?$value->agenda_so_name:''?>
        </div>
        <label class="control-label col-md-2">Tanggal Pelaksanaan</label>
        <div class="col-md-3" style="margin-top:5px; margin-left:5px">
          <?php echo isset($value->agenda_so_date)?$this->tanggal->formatDate($value->agenda_so_date):''?>
        </div>
        <div class="col-md-2 pull-right" style="margin-top:5px; margin-left:5px">
          <a href="#" id="sign-out-sess-so" class="label label-danger"><i class="fa fa-sign-out"></i> Keluar Session Input Data</a>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Penanggung Jawab</label>
        <div class="col-md-4" style="margin-top:5px; margin-left:5px">
          <?php echo isset($value->agenda_so_spv)?$value->agenda_so_spv:''?>
        </div>
      </div>

      <hr class="separator">

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Input Data</label>
        <div class="col-md-2" style="margin-top:5px; margin-left:5px">
          <?php echo isset($this->session->userdata('session_input_so')['tanggal_input'])? $this->tanggal->formatDate($this->session->userdata('session_input_so')['tanggal_input']):''?>
        </div>
        <label class="control-label col-md-1">Waktu/Jam</label>
        <div class="col-md-2" style="margin-top:5px; margin-left:5px">
          <?php echo isset($this->session->userdata('session_input_so')['waktu_input']) ? $this->session->userdata('session_input_so')['waktu_input'] : '' ?>
        </div>
        <label class="control-label col-md-2">Petugas Input Data</label>
          <div class="col-md-3" style="margin-top:5px; margin-left:5px">
            <?php echo isset($this->session->userdata('session_input_so')['nama_pegawai']) ? $this->session->userdata('session_input_so')['nama_pegawai'] : '' ?>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Bagian Unit</label>
          <div class="col-md-4" style="margin-top:5px; margin-left:5px">
            <?php echo isset($this->session->userdata('session_input_so')['bagian']) ? $this->session->userdata('session_input_so')['nama_bagian'] : '' ?>
          </div>
      </div>

    </form>

    <form class="form-horizontal" method="post" id="form_Create_agenda_so" action="<?php echo site_url('inventory/so/Create_agenda_so/process')?>" enctype="multipart/form-data" >
      <br>

      <!-- hidden form gudang non medis-->
      <input type="hidden" name="kode_bagian" id="kode_bagian" value="<?php echo isset($this->session->userdata('session_input_so')['bagian']) ? $this->session->userdata('session_input_so')['bagian'] : '' ?>">

      <div id="section_non_medis">
        <div class="form-group">
          <label class="control-label col-md-2">Golongan</label>
          <div class="col-md-3">
              <?php 
                echo $this->master->custom_selection($params = array('table' => 'mt_golongan_nm', 'id' => 'kode_golongan', 'name' => 'nama_golongan', 'where' => array()), '' , 'kode_golongan', 'kode_golongan', 'form-control', '', '') ?>
          </div>
          <label class="control-label col-md-2">Rak/Lemari</label>
          <div class="col-md-3">
              <?php 
                echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'rak_non_medis')), '' , 'rak_nm', 'rak_nm', 'form-control', '', '') ?>
          </div>
        </div>
      </div>
      
      <div id="section_medis" style="display: none">

        <div class="form-group">
          <label class="control-label col-md-2">Kategori</label>
          <div class="col-md-2">
              <?php 
                echo $this->master->custom_selection($params = array('table' => 'mt_kategori', 'id' => 'kode_kategori', 'name' => 'nama_kategori', 'where' => array()), '' , 'kode_kategori', 'kode_kategori', 'form-control', '', '') ?>
          </div>
          <label class="control-label col-md-2">Rak/Lemari</label>
          <div class="col-md-3">
              <?php 
                echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'rak_medis', 'reff_id' => isset($this->session->userdata('session_input_so')['bagian']) ? $this->session->userdata('session_input_so')['bagian'] : '')), '' , 'rak_m', 'rak_m', 'form-control', '', '') ?>
          </div>
        </div>
        
      </div>
      
      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10" style="margin-left: 5px">
          <button type="button" name="submit" value="data" class="btn btn-xs btn-primary" onclick="find_data_reload()">
            Proses Pencarian
          </button>
        </div>
      </div>

      <hr class="separator">
      <!-- div.table-responsive -->

      <!-- div.dataTables_borderWrap -->
      <div style="margin-top:-27px">
        <table id="dt-input-so-bag" base-url="inventory/so/Input_dt_so/get_data?bag=<?php echo $this->session->userdata('session_input_so')['bagian']?>" class="table table-bordered table-hover">
           <thead>
            <tr>
              <th class="center">NO</th>
              <th>IMAGE</th>
              <!-- <th>KODE</th> -->
              <th>NAMA BARANG</th>
              <th class="center">SATUAN KECIL</th>
              <th class="center">STOK TGL<br><?php echo isset($value->agenda_so_cut_off_stock)?$this->tanggal->formatDatedmY($value->agenda_so_cut_off_stock):''?></th>
              <th class="center">STOK AKHIR<br>(Setelah/Sebelum SO)</th>
              <th class="center">STOK FISIK <br>(Input SO)</th>
              <th class="center">JML EXP</th>
              <th class="center">JML EXP -3 Bln</th>
              <th class="center">STATUS AKTIF</th>
              <th class="center">LAST UPDATE</th>
              <th class="center"></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->




