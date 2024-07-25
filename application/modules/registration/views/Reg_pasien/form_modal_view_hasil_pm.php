<style>
  table{
    font-size: 12px !important;
    width: 100% !important
  }
  table tr td{
    font-size: 12px !important;
  }
  span{
    font-size: 12px !important;
  }
</style>
<div class="row">

  <div class="col-sm-12 widget-container-col ui-sortable">
    <div class="widget-box transparent ui-sortable-handle">
      <div class="widget-body">

        <div class="col-md-12" style="font-size: 12px !important">
        <br>
          <?php
            if(!empty($html)){
              echo $html;
            }else{
              echo "<div class='alert alert-danger'><b>-Tidak ada data-</b><br>Belum ada tindakan dan hasil penunjang yang diinput.</div>";
            }
          ?>
        </div>

      </div>

      </div>
    </div>
  </div>

  
  
</div>



<!-- end form create SEP