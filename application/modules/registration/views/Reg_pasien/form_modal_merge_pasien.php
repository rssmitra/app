<input type="hidden" value="<?php echo isset($value)?$value->no_mr:''?>" name="noMrHiddenPasien" id="noMrHiddenPasien">
    
<center>
  <h3>NO MR LAMA</h3>
    <input type="text" style="width:200px;text-align:center;height:40px !important;font-size:20px" name="no_mr_lama" id="no_mr_lama" class="form-control" value="<?php echo isset($value)?$value->no_mr:''?>" >
    <span style="font-size: 11px">No MR Lama yaitu No MR yang akan dihapus dan akan digantikan dengan No MR baru</span>
  <br><br>

  <h3>NO MR BARU</h3>
    <input type="text" style="width:200px;text-align:center;height:40px !important;font-size:20px" name="no_mr_baru" id="no_mr_baru" class="form-control" value="" >
    <span style="font-size: 11px">Semua data pasien berikut data transaksi akan diubah dengan No MR Baru</span>
    <br>
    <i class="fa fa-angle-double-down bigger-300"></i><br>
    
    <button type="submit" name="submit" class="btn btn-xs btn-primary">

      <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>

      Merge Data

    </button>

</center>








<!-- end form create SEP