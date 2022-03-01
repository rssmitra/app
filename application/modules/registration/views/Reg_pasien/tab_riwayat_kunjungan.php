<script type="text/javascript">

  $(document).ready(function(){

    var no_mr = '<?php echo $no_mr?>';
    var kode_bagian = '<?php echo (isset($kode_bagian))?$kode_bagian:''?>';
    var no_reg = '<?php echo (isset($no_reg))?$no_reg:''?>';
    var tujuan = '<?php echo (isset($tujuan))?$tujuan:''?>';

    var url = (no_reg!='')?'registration/Reg_pasien/get_riwayat_pasien?mr='+no_mr+'&tujuan='+tujuan+'&no_reg='+no_reg+'':'registration/Reg_pasien/get_riwayat_pasien?mr='+no_mr+'&kode_bagian='+kode_bagian+'';

    table_riwayat = $('#riwayat-table').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bInfo": false,
      "scrollY": "500px",
      //"scrollX": "500px",
      "lengthChange": false,
      "bPaginate": false,
      // Load data for the table's content from an Ajax source
      
      "ajax": {
          
          "url": url,
          
          "type": "POST"
      
      },
    
    });

  });

  function delete_registrasi(no_reg, no_kunjungan){
    preventDefault();
    if(confirm('Are you sure?')){
      $.ajax({
          url: 'registration/Reg_pasien/delete_registrasi',
          type: "post",
          data: {ID:no_reg, KunjunganID:no_kunjungan},
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
              reload_table();
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

  function ubah_penjamin_pasien(no_reg, no_kunjungan){
    preventDefault();
    
    $('#form_edit_penjamin_modal').load('registration/reg_pasien/form_modal_edit_penjamin/'+no_reg+'/'+no_kunjungan+''); 
    $('#result_text_edit_pasien').text('UBAH PENJAMIN PASIEN');
    $('#modalEditPenjamin').modal('show');
    
  }

  function view_hasil_pm(no_reg, no_kunjungan){
    preventDefault();
    
    $('#form_edit_penjamin_modal').load('registration/reg_pasien/form_modal_view_hasil_pm/'+no_reg+'/'+no_kunjungan+''); 
    $('#modalEditPenjamin').modal('show');
    
  }

  function reload_table(){
   table_riwayat.ajax.reload(); //reload datatable ajax 
  }


</script>

<!-- <b> RIWAYAT KUNJUNGAN PASIEN <i class="fa fa-angle-double-right bigger-120"></i> </b> -->

<table id="riwayat-table" class="table">

   <thead>

    <tr>  
      
      <th>No</th>

      <th>&nbsp;</th>

      <th>Deskripsi</th>

    </tr>

  </thead>

  <tbody id="table_riwayat_pasien">

  </tbody>

</table>

<div id="modalEditPenjamin" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="min-height:500px; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:75%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_edit_pasien">UBAH PENJAMIN PASIEN</span>

        </div>

      </div>

      <div class="modal-body">         
        
        <div id="form_edit_penjamin_modal"></div>

      </div>

      <!-- <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div> -->

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>