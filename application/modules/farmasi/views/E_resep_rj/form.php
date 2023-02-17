<script>

  $(document).ready(function(){

      // load form entry default
      getMenuTabs('farmasi/E_resep_rj/frm_telaah_resep/<?php echo $value->kode_pesan_resep?>?mr=<?php echo $value->no_mr?>&tipe_layanan=RJ', 'frm_telaah_resep');
      // get data antrian pasien
      getDataAntrianPasien();

      window.filter = function(element)
      {
        var value = $(element).val().toUpperCase();

        $(".itemdiv").each(function() 
        {
          if ($(this).text().toUpperCase().search(value) > -1){
            $(this).show();
          }
          else {
            $(this).hide();
          }
        });
      }

  })


  function getDataAntrianPasien(){

    // getTotalBilling();
    $.getJSON("farmasi/E_resep_rj/getAntrianResep?flag=RJ", '', function (data) {   
      $.each(data, function (i, o) {   

          html_cancel = '';
          html_cancel += '<table class="table itemdiv commentdiv">';
          html_cancel += '<tr>';
          html_cancel += '<td>';
          html_cancel += '<div onclick="getMenuTabs('+"'farmasi/E_resep_rj/frm_telaah_resep/"+o.kode_pesan_resep+"?mr="+o.no_mr+"&tipe_layanan=RJ'"+', '+"'frm_telaah_resep'"+');" style="cursor: pointer">';
          html_cancel += '<span class="pull-left"><a href="#">'+o.no_mr+'</a></span><span style="font-size:9px; font-style: italic" class="pull-right">'+o.tgl_pesan+'</span><br>';
          html_cancel += '<span style="font-size: 14px">'+o.nama_pasien+'</span><br>';
          html_cancel += '<span style="font-size:9px" class="pull-left">'+o.nama_perusahaan+'</span>';
          html_cancel += '</div>';
          html_cancel += '</td>';
          html_cancel += '</tr>';
          html_cancel += '</table>';
          
          $(html_cancel).appendTo($('#list_antrian_existing'));
          
          
      });   

    });

  }


</script>


<div class="row">

<!-- breadcrumbs -->
<div class="page-header">  
    <h1>
      <?php echo $title?>      
      <small><i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
    </h1>
  </div>  
  
  <div class="col-md-2 no-padding">
    <div id="antrian_tabs" class="tab-pane fade in active">
        <div class="center">
          <span class="pull-left" style="padding-top: 10px"><b>Cari pasien :</b></span> <br>
          <input type="text" id="seacrh_ul_li" value="" placeholder="Masukan keyword..." class="form-control" onkeyup="filter(this);">
        </div>
        <div style="margin-top: 20px; font-weight: bold">List antrian resep pasien : </div>
        <div class="comments ace-scroll"  style="position: relative;height: 650px;overflow: scroll;">
          <div id="list_antrian_existing"></div>
        </div>

      </div>

  </div>
  <div class="col-xs-10">
    <div id="frm_telaah_resep"></div>
  </div>

</div><!-- /.row -->

