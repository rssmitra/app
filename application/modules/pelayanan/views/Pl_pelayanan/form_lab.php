<script type="text/javascript">

$(document).ready(function(){

  oTable = $('#dynamic-table-order-lab').DataTable({ 
            
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": true,
    // "bInfo": false,
    "pageLength": 10,
    "bLengthChange": false,
    "bInfo": false,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": "pelayanan/Pl_pelayanan_pm/get_order_penunjang_lab_view_w_action?kode_bagian="+$("#kode_bagian_pm").val()+"&no_mr="+$("#sess_no_mr").val(),
        "type": "POST"
    },

  });
  

  $('#btn_add_order').click(function (e) {   
    e.preventDefault();
    getMenuTabs('pelayanan/Pl_pelayanan/form_lab_detail/'+$('#id_pl_tc_poli').val()+'/'+$('#no_kunjungan').val()+'?kode_bag='+$('#kode_bagian_pm').val()+'', 'section_data_order');
  });

  
});

function edit_order_lab(id_pm_tc_penunjang){
  preventDefault();
  getMenuTabs('pelayanan/Pl_pelayanan/form_lab_detail/'+$('#id_pl_tc_poli').val()+'/'+$('#no_kunjungan').val()+'?kode_bag='+$('#kode_bagian_pm').val()+'&id_pm_tc_penunjang='+id_pm_tc_penunjang+'', 'section_data_order');
}

function delete_order_lab(id_pm_tc_penunjang){

  preventDefault();  
  achtungShowLoader();
  if(confirm('Are you sure?')){
    $.ajax({
        url: "pelayanan/Pl_pelayanan_pm/delete_order_by_bundle",
        data: { ID: id_pm_tc_penunjang},            
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          var data=xhr.responseText;  
          var jsonResponse = JSON.parse(data);  
          if(jsonResponse.status === 200){  
            $.achtung({message: jsonResponse.message, timeout:5}); 
            oTable.ajax.reload();
          }else{          
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
          } 
          achtungHideLoader();
        }
    });
  }else{
    return false;
  }

}

</script>

<div class="row">
    <div class="col-sm-12">
       <!-- input hidden -->
      <input type="hidden" class="form-control" id="kode_bagian_pm" name="kode_bagian_pm" value="<?php echo $sess_kode_bag?>">
      <input type="hidden" class="form-control" id="sess_no_mr" name="sess_no_mr" value="<?php echo $no_mr?>">

      <div id="section_data_order">
        <p><b> RIWAYAT PERMINTAAN PEMERIKSAAN LABORATORIUM <i class="fa fa-angle-double-right bigger-120"></i></b></p>
        <a href="#" class="btn btn-xs btn-primary" id="btn_add_order"> <i class="fa fa-plus"></i> Order Laboratorium </a>
        <table id="dynamic-table-order-lab" base-url="pelayanan/Pl_pelayanan_pm" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th>No</th>
              <th>Tanggal Order</th>
              <th>Nama Pasien</th>
              <th>Pemeriksaan</th>
              <th>Dr Pengirim</th>
              <th>Status</th>
              <th width="80px">Aksi</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </div>
</div>





