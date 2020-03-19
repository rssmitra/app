<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<SCRIPT LANGUAGE="JavaScript">
<!--
function tutup1(id){
  if (document.kinerja_dr.frekuensi.value=='harian'){
    document.getElementById("tgl").style.display = "block";
    document.getElementById("bln").style.display = "none";
    document.getElementById("thn").style.display = "none";
    //document.frek.action="laporan_rs_bulan.php";
      }
  if (document.kinerja_dr.frekuensi.value=='bulanan'){
     document.getElementById("tgl").style.display = "none";
    document.getElementById("bln").style.display = "block";
    document.getElementById("thn").style.display = "block";
    //document.frek.action="laporan_rs_hari.php";
    }
  if (document.kinerja_dr.frekuensi.value=='tahunan'){
     document.getElementById("tgl").style.display = "none";
    document.getElementById("bln").style.display = "none";
    document.getElementById("thn").style.display = "block";
    //document.frek.action="laporan_rs_tahun.php";
    }

}

//-->
</SCRIPT>
</head>
<body>
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

      <div class="col-md-12">

        <!-- content -->
        <a href="<?php echo base_url().'laporan/Global_report'?>" class="btn btn-xs btn-primary"> Kembali ke Menu Utama</a>
        <br>
        <h4>Laporan Penerimaan Barang</h4>
        <form name="kinerja_dr" class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data" target="blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Laporan Penerimaan Barang">

           <div class="form-group">
            <label class="control-label col-md-2">Frekuensi</label>
               <div class="col-md-4">
                <select name="frekuensi" onchange="tutup1(this.value)">
                  <option value="harian">Harian</option>
                  <option value="bulanan">Bulanan</option>
                  <option value="tahunan">Tahunan</option>
                </select>
              </div>
          </div>
          <div class="form-group" id="tgl" style="display:block">
            <label class="control-label col-md-2">Tanggal</label>
              <div class="col-md-2">
                <input class="form-control date-picker" name="tgl" id="tgl" type="text" placeholder="Format : yyyy-mm-dd" value=""/>
              </div>
          </div>
         <div class="form-group" id="bln" style="display:none">
            <label class="control-label col-md-2">Bulan</label>
              <div class="col-md-2">
               <?php echo $this->master->get_bulan('','from_month','from_month','form-control','','');?>
              </div>
          </div>
          <div class="form-group"  id="thn" style="display:none">
            <label class="control-label col-md-2">Tahun</label>
              <div class="col-md-2">
                <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
              </div>
          </div>
        
          <div class="form-group">
            <label class="control-label col-md-2 ">&nbsp;</label>
            <div class="col-md-10" style="margin-left: 5px">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
                Proses Pencarian
              </button>
              <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
                Export Excel
              </button>
            </div>
          </div>

        </form>
        <!-- end content -->
        
     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






