
<html>
<head>
  <title>Format Stok Opname</title>
  <!-- bootstrap & fontawesome -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />

  <!-- page specific plugin styles -->
  <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" /> -->
  <!-- text fonts -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
  <!-- css date-time -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
  <!-- end css date-time -->
  <!-- ace styles -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />

   <!--[if !IE]> -->
  <script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url()?>/assets/js/jquery.js'>"+"<"+"/script>");
  </script>

  <script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
  </script>
  <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

  <script src="<?php echo base_url()?>assets/js/bootstrap-multiselect.js"></script>

  <!-- page specific plugin scripts -->


  <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.js"></script>
  <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
  <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
  <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>

  <script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
  <script>
    $(document).ready(function(){
      $('#InputKeyNamaKaryawan').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "getNamaKaryawan",
                    data: { keyword:query },            
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                      result($.map(response, function (item) {
                          return item;
                      }));
                    }
                });
            },
            afterSelect: function (item) {
              // do what is needed with item
              var val_item=item.split(':')[0];
              console.log(val_item);
              $('#kode_petugas').val(val_item);
            }
      });
    })
    
  </script>

</head>
<body style="background-color: white">
  <div class="row">
    <div class="col-xs-12">

      <center><h4>INPUT DATA STOK OPNAME <?php echo strtoupper($result['data'][0]->nama_bagian)?></h4></center>

      <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data" target="blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Daftar Barang Yang Akan di Stok Opname">
          
          <div class="form-group">
            <label class="control-label col-md-2">Tanggal Stok Opname</label>
              <div class="col-md-4" style="padding-top:4px; margin-left:8px;">
                <?php echo $this->tanggal->formatDateTime(date('Y-m-d H:i:s'))?>
              </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2">Petugas Input Data</label>
              <div class="col-md-4">
                <input id="InputKeyNamaKaryawan" class="form-control" name="petugas_input" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input type="hidden" name="kode_petugas" value="" id="kode_petugas">
              </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2 ">&nbsp;</label>
            <div class="col-md-10" style="margin-left: 5px">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
                Simpan Data
              </button>
            </div>
          </div>

      </form>

      <div style="width:200px">
        Tanggal Stok Opname :<br>
        Nama Petugas :
      </div>
      
      <br>

      <table class="table" border="1" style="font-size:12px">
        <thead>
          <tr>
            <th class="center">NO</th>
            <th>KODE</th>
            <th>NAMA BARANG</th>
            <th class="center">STOK AKHIR</th>
            <th class="center">SATUAN<br>KECIL/BESAR</th>
            <th>GOLONGAN</th>
            <th class="center">JUMLAH SO</th>
            <th class="center">PARAF</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $no = 0; 
            foreach($result['data'] as $row_data) : $no++; 
            $satuan = ($row_data->satuan_kecil==$row_data->satuan_besar) ? $row_data->satuan_kecil : $row_data->satuan_kecil.'/ '.$row_data->satuan_besar;
          ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <td><?php echo $row_data->kode_brg;?></td>
              <td><?php echo $row_data->nama_brg;?></td>
              <td align="center"><?php echo $row_data->jml_sat_kcl;?></td>
              <td align="center"><?php echo $satuan;?></td>
              <td><?php echo $row_data->nama_golongan;?></td>
              <td></td>
              <td></td>
              
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>
