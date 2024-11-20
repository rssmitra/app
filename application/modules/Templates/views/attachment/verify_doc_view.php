<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Verifikasi Dokumen</title>

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/font-awesome/4.5.0/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-skins.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-rtl.css" />

	</head>
  <style>
    .div-center {
      margin: auto;
      width: 95%;
      border: 2px solid #d9d9d9;
      padding: 10px;
    }
  </style>
	<div class="row div-center" style="background: white; ">

		<div class="col-md-12" >
			<table border="0" width="100%">
				<tr>
					<td width="20%" align="center"><img src="<?php echo base_url().COMP_ICON?>" style="width: 80px"></td>
					<td width="80%">
						<?php 
							echo '<b>'.COMP_LONG.'</b><br>'; 
							echo COMP_ADDRESS.'<br>'; 
						?>
					</td>
				</tr>
			</table>
			<hr>
		</div>
		
		<div class="col-md-12">
			<div class="alert alert-success">
				<center><span class="bigger-110 bolder" style="font-size:20px !important; color: green; text-align: center !important"><i class="ace-icon glyphicon glyphicon-check bigger-200 green"></i><br><i>Verified Document</i></span>
</center>
				<br>
				Dokumen ini terverifikasi keasliannya dan dapat dipertanggungjawabkan kebenarannya<br>
				<i>"This document has been verified for its authenticity and its veracity can be confirmed"</i>
			</div>
			<br>
			<div class="col-md-6">
				<table>
				<tr>
					<td style="width: 35%;"><b>Nama Dokumen</b><br><i>Document Name</i></td>
					<td style="width: 65%;" valign="top">: <?php echo $detail['documentName']?></td>
				</tr>
				<tr>
				<td style="width: 35%;"><b>ID Dokumen</b><br><i>Document ID</i></td>
					<td style="width: 65%;" valign="top">: <?php echo $detail['ID']?></td>
				</tr>
				<tr>
				<td style="width: 35%;"><b>Dibuat Oleh</b><br><i>Created By</i></td>
					<td style="width: 65%;" valign="top">: <?php echo $detail['createdBy']?></td>
				</tr>
				<tr>
				<td style="width: 35%;"><b>Tanggal Dibuat</b><br><i>Created Date</i></td>
					<td style="width: 65%;" valign="top">: <?php echo $detail['createdDate']?></td>
				</tr>
				<tr>
				<td style="width: 35%;"><b>Status Dokumen</b><br><i>Document Status</i></td>
					<td style="width: 65%;" valign="top">: <?php echo $detail['statusDocument']?></td>
				</tr>
				<td style="width: 35%;" valign="top"><b>Keterangan</b><br><i>Noted</i></td>
					<td style="width: 65%;" valign="top">: <?php echo $detail['noted']?></td>
				</tr>
				</table>
			</div>

			<div class="col-md-6">
				<br>
				<p><b>Dokumen ini ditandatangani oleh :</b> <br><i>This document is signed by :</i></p>
				<table class="table table-hover" style="width: 100%; margin-top: 5px; margin-bottom: 10px; border-top: 0px;">
				<tr>
					<td colspan="3"><b>Penandatangan</b> / <i>Signer</i></td>
				</tr>
				<tr>
					<td class="center" style="width: 30px">
						<i class="ace-icon glyphicon glyphicon-user bigger-280"></i>
					</td>
					<td>
						<span class="bolder"><?php echo $detail['signedBy']?></span><br>
						[ <?php echo $detail['signTitle']?> ]<br><?php echo $detail['signedDate']?><br>
						<?php echo $detail['img_ttd']?>
					</td>
				</tr>
				</table>
			</div>
		</div>

		<!-- <div class="col-md-12">
			<p><br><b>Riwayat aktifitas dokumen :</b> <br><i>Document activity history :</i></p>
			<table class="table table-hover" style="width: 100%; margin-top: 5px; margin-bottom: 10px; border-top: 0px;">
				<tr>
				<td><b>Aktifitas</b> / <i>Activity</i></td>
				</tr>
				<tr>
				<td>
					<div class="timeline-container timeline-style2">
						<span class="timeline-label">
							<b>Today</b>
						</span>

						<div class="timeline-items">
							<div class="timeline-item clearfix">
								<div class="timeline-info">
									<span class="timeline-date">11:15 pm</span>

									<i class="timeline-indicator btn btn-info no-hover"></i>
								</div>

								<div class="widget-box transparent">
									<div class="widget-body">
										<div class="widget-main no-padding">
											<span class="bigger-110">
												<a href="#" class="purple bolder">Susan</a>
												reviewed a product
											</span>

											<br>
											<i class="ace-icon fa fa-hand-o-right grey bigger-125"></i>
											<a href="#">Click to read …</a>
										</div>
									</div>
								</div>
							</div>

							<div class="timeline-item clearfix">
								<div class="timeline-info">
									<span class="timeline-date">12:30 pm</span>

									<i class="timeline-indicator btn btn-info no-hover"></i>
								</div>

								<div class="widget-box transparent">
									<div class="widget-body">
										<div class="widget-main no-padding">
											Going to
											<span class="green bolder">veg cafe</span>
											for lunch
										</div>
									</div>
								</div>
							</div>

							<div class="timeline-item clearfix">
								<div class="timeline-info">
									<span class="timeline-date">11:15 pm</span>

									<i class="timeline-indicator btn btn-info no-hover"></i>
								</div>

								<div class="widget-box transparent">
									<div class="widget-body">
										<div class="widget-main no-padding">
											Designed a new logo for our website. Would appreciate feedback.
											<a href="#">
												Click to see
												<i class="ace-icon fa fa-search-plus blue bigger-110"></i>
											</a>

											<div class="space-2"></div>

											<div class="action-buttons">
												<a href="#">
													<i class="ace-icon fa fa-heart red bigger-125"></i>
												</a>

												<a href="#">
													<i class="ace-icon fa fa-facebook blue bigger-125"></i>
												</a>

												<a href="#">
													<i class="ace-icon fa fa-reply light-green bigger-130"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="timeline-item clearfix">
								<div class="timeline-info">
									<span class="timeline-date">9:00 am</span>

									<i class="timeline-indicator btn btn-info no-hover"></i>
								</div>

								<div class="widget-box transparent">
									<div class="widget-body">
										<div class="widget-main no-padding"> Took the final exam. Phew! </div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</td>
				</tr>
			</table>
		</div> -->
		
	</div>

</html>
