<?php 

  if($_POST['submit']=='excel') {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$flag.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

?>

<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/blue.css"/>
</head>
<body>
  <!-- #section:basics/navbar.layout -->
    <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background-color: #00b8a8">
      <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
      </script>

      <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
          <!-- #section:basics/navbar.layout.brand -->
          <a href="#" class="navbar-brand">
            <small>
              <i class="fa fa-leaf"></i>
              <?php echo strtoupper(COMP_LONG); ?>
            </small>
          </a>

          <!-- /section:basics/navbar.layout.brand -->

          <!-- #section:basics/navbar.toggle -->
          <button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
            <span class="sr-only">Toggle user menu</span>

            <img src="<?php echo base_url()?>assets/avatars/user.jpg" alt="Jason's Photo" />
          </button>

          <button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
          </button>

          <!-- /section:basics/navbar.toggle -->
        </div>

      </div><!-- /.navbar-container -->
    </div>

    <!-- /section:basics/navbar.layout -->
    <div class="main-container" id="main-container">
      <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
      </script>


      <!-- /section:basics/sidebar.horizontal -->
      <div class="main-content">
        <div class="main-content-inner">
          <div class="page-content">
            <!-- /section:settings.box -->
            <div class="page-header">
              <h1>
               <center><h4><?php echo $title?> <br><?php echo $jenis ?></h4></center>
               </h1>
            </div><!-- /.page-header -->
  <div class="row">
    <div class="col-xs-12">
        <b>Parameter :</b> <i><?php echo print_r($_POST);?></i>?>
      <table class="greyGridTable">
        <thead>
          <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">Kode Penerimaan</th>
            <th rowspan="2">Tgl Penerimaan</th>
            <th rowspan="2">Kode Barang</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">Supplier</th>
            <th rowspan="2">No PO</th>
            <th rowspan="2">No Faktur</th>
     
           <th colspan="2">Jumlah Besar</th>
            <th rowspan="2">Satuan Besar</th>
            <th rowspan="2">Rasio</th>
            <th rowspan="2">Harga Satuan</th>
            <th rowspan="2">Harga Satuan Netto</th>
            <th rowspan="2">HPA</th>
            <th rowspan="2">Jumlah Harga Satuan</th>
            <th rowspan="2">Jumlah Harga Satuan Netto</th>
          </tr>
          <tr>
          <th>Pesan</th>
          <th>Diterima</th>
        </tr>
        </thead>
        <tbody>
          <?php $no = 0; 
          foreach($result['data'] as $row_data){
            $t_po = ($_POST['jenis']=='Non Medis')?'tc_po_nm':'tc_po';
            $t_po_d = ($_POST['jenis']=='Non Medis')?'tc_po_nm_det':'tc_po_det';

            $po=$this->db->query('select b.jumlah_besar, CAST(b.harga_satuan as INT) as harga_satuan, CAST(b.harga_satuan_netto as INT) as harga_satuan_netto, CAST(b.jumlah_harga as INT) as jumlah_harga,
             CAST(b.jumlah_harga_netto as INT) as jumlah_harga_netto, b.discount  from '.$t_po.' a JOIN '.$t_po_d.' b ON b.id_tc_po=a.id_tc_po  WHERE a.no_po='."'".$row_data->no_po."'".'
              AND b.kode_brg='."'".$row_data->kode_brg."'".'');
            $poo= $po->row_array();
           
            $kode_penerimaan = $row_data->kode_penerimaan;
            $tgl_penerimaan = $row_data->tgl_penerimaan;
            $no_faktur = $row_data->no_faktur;
            $kode_brg = $row_data->kode_brg;
            $kode_penerimaan = $row_data->kode_penerimaan;
            $jumlah_pesan = $row_data->jumlah_pesan;
            $jumlah_kirim = $row_data->jumlah_kirim;
            $content = $row_data->content;
            $hpa = $poo['harga_satuan_netto']/$content;
            $no++;             
            ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
               echo '<td>'.$kode_penerimaan.'</td>';
                  echo '<td>'.$tgl_penerimaan.'</td>';
                  echo '<td>'.$kode_brg.'</td>';
                  echo '<td>'.$row_data->nama_brg.'</td>';
                  echo '<td>'.$row_data->namasupplier.'</td>';
                  echo '<td>'.$row_data->no_po.'</td>';
                  echo '<td>'.$no_faktur.'</td>';
                  echo '<td>'.$jumlah_pesan.'</td>';
                  echo '<td>'.$jumlah_kirim.'</td>';
                  echo '<td>'.$row_data->satuan_besar.'</td>';
                  echo '<td>'.$content.'</td>';
                  echo '<td>'.$poo['harga_satuan'].'</td>';
                  echo '<td>'.$poo['harga_satuan_netto'].'</td>';
                  echo '<td>'.$hpa.'</td>';
                  echo '<td>'.$poo['jumlah_harga'].'</td>';
                  echo '<td>'.$poo['jumlah_harga_netto'].'</td>';
              ?>
            </tr>
          <?php 
        // endforeach; 
      }?>
        </tbody>
      </table>
  <!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->

      <div class="footer">
        <div class="footer-inner">
          <!-- #section:basics/footer -->
          <div class="footer-content">
            <span class="bigger-120">
              <span class="blue bolder"><?php echo APPS_NAME_SORT; ?></span>
              - <?php echo COMP_LONG; ?> &copy; 2019
            </span>
          </div>

          <!-- /section:basics/footer -->
        </div>
      </div>

      <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
      </a>
    </div>
    
</body>
</html>




