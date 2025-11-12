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
    var oTablePerjanjianByMR;
    var base_url = $('#dynamic-table-perjanjian-bymr').attr('base-url'); 

    oTablePerjanjianByMR = $('#dynamic-table-perjanjian-bymr').DataTable({ 
          
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
    oTablePerjanjianByMR.ajax.url('registration/Perjanjian_rj/get_data?no_mr='+no_mr+'&flag='+value).load();
    
  }); 

  function cetak_surat_kontrol(ID, jd_id) {   
    var no_mr = $('#tabs_riwayat_perjanjian_id').attr('data-id');  
    if( no_mr == '' ){
      alert('Silahkan cari pasien terlebih dahulu !'); return false;
    }else{
      url = 'registration/Reg_pasien/surat_control?id_tc_pesanan='+ID+'&jd_id='+jd_id+'';
      getMenu(url);
    }

}

function delete_perjanjian(id_tc_pesanan){  

  if(confirm('Are you sure?')){
    preventDefault();
    $.ajax({
        url: 'registration/Input_perjanjian/delete',
        type: "post",
        data: {ID:id_tc_pesanan},
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
            var no_mr = $('#no_mr_pasien_perjanjian').val();   
            oTablePerjanjianByMR.ajax.url('registration/Perjanjian_rj/get_data?no_mr='+no_mr+'&flag=RJ').load();
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

</script>

<div class="row">
<div class="col-md-12">
  
  <P><b> RIWAYAT PERJANJIAN PASIEN <i class="fa fa-angle-double-right bigger-120"></i> </b></P>
  <hr class="separator">
  <!-- div.dataTables_borderWrap -->
  <div style="margin-top:-27px">
    <table id="dynamic-table-perjanjian-bymr" base-url="registration/Perjanjian_rj/get_data?no_mr=<?php echo $no_mr?>" class="table table-bordered table-hover">
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

</div>
</div>



