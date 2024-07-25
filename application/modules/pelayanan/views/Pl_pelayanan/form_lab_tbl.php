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

});

function searchPemeriksaan() {
    var input, filter, found, table, tr, td, i, j;
    input = document.getElementById("input_keyword");
    filter = input.value.toUpperCase();
    table = document.getElementById("tbl_nama_pemeriksaan");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                found = true;
            }
        }
        if (found) {
            tr[i].style.display = "";
            found = false;
        } else {
            tr[i].style.display = "none";
        }
    }
}

</script>

<style>
  
</style>

<div class="row">
    <div class="col-sm-12">
        <p><b> FORM PERMINTAAN PEMERIKSAAN LABORATORIUM <i class="fa fa-angle-double-right bigger-120"></i></b></p>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Tanggal</label>
              <div class="col-md-3">
                    
                <div class="input-group">
                    
                    <input name="pl_tgl_transaksi" id="pl_tgl_transaksi" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                    <span class="input-group-addon">
                      
                      <i class="ace-icon fa fa-calendar"></i>
                    
                    </span>
                  </div>
              </div>
        </div>
        <br>
        <div>
          <label for="form-field-8"><b>Cari nama pemeriksaan :</b> </label>
          <input type="text" class="form-control" id="input_keyword" placeholder="Masukan keyword" onkeyup="searchPemeriksaan()">
        </div>
        <div style="overflow-y:auto; max-height: 200px; padding-top: 10px">
          <table class="table" id="tbl_nama_pemeriksaan">
            <tbody>
            <?php
              foreach ($pemeriksaan as $key => $value) {
                echo "<tr style='color: white; background: darkgreen; font-weight: bold;'><th colspan='3'>".str_replace("_"," ",$key)."</th></tr>";
                foreach ($value as $k => $v) {
                  $split = explode("|", $v);
                echo "<tr>";
                echo "<td align='center' width='50px'><label style='padding : 1px;'>
                <input name='check_pm[]' type='checkbox' value='050101' class='ace'>
                <span class='lbl'></span>
              </label></td>";
                echo "<td>".$split[1]."</td>";
                echo "</tr>";
                }
              }
            ?>
            </tbody>
          </table>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Keterangan</label>
            <div class="col-sm-8">
               <textarea type="text" class="form-control" id="pl_keterangan_tindakan" name="pl_keterangan_tindakan"></textarea>
            </div>
        </div>

    </div>
</div>





