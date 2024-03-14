<?php 

  if( $_POST['submit']=='excel' ) {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$_POST['submit'].'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }

?>


<html>
<head>
  <title>Laporan</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
</head>

<body class="no-skin" style="background-color:white">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background-color: #00b8a8">
			
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

				</div>

			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<!-- /section:basics/sidebar.horizontal -->
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
                				Laporan Penjualan Bulan <?php echo $this->tanggal->getBulan($_POST['month'])?> Tahun <?php echo $_POST['year']?>
							</h1>
						</div><!-- /.page-header -->

						<div class="row" id="content_to_export_excel">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								Total data <?php echo number_format(count($result['data']))?>
								<br>
								<table class="table table-bordered">
									<thead>
										<tr>
										<th>NO</th>
										<?php 
											foreach($result['fields'] as $field){
											echo '<th>'.strtoupper($field->name).'</th>';
										}?>
										<th>TOTAL</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$no = 0; 
											foreach($result['data'] as $row_data) : 
											$no++; 
											$total = $row_data->jumlah_item * $row_data->rata_jual;
											$warning = ($total > 10000000) ? 'red' : 'black';
											$arr_total[] = $total;
										?>
										<tr>
											<td align="center"><?php echo $no;?></td>
											<?php 
											foreach($result['fields'] as $row_field){
												$field_name = $row_field->name;
												echo '<td>'.strtoupper($row_data->$field_name).'</td>';
											}?>
											<td align="right"><?php echo $total; ?></td>
										</tr>
										<?php endforeach; ?>
										<tr>
											<td colspan="10" align="right"><b>TOTAL PENJUALAN KESELURUHAN</b></td>
											<td align="right"><span style="color: <?php echo $warning?>"><?php echo array_sum($arr_total)?></span></td>
										</tr>
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
							<span class="blue bolder"><?php echo APPS_NAME_SORT?></span>
							- <?php echo COMP_LONG; ?> &copy; 2018 - <?php echo date('Y')?>
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






