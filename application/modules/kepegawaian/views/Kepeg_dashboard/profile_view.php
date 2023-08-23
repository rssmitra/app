<div class="">
	<div id="user-profile-2" class="user-profile">
		<div class="tabbable">
			<ul class="nav nav-tabs padding-18">
				<li class="active">
					<a data-toggle="tab" href="#home">
						<i class="green ace-icon fa fa-user bigger-120"></i>
						PROFIL PEGAWAI
					</a>
				</li>

				<!-- <li>
					<a data-toggle="tab" href="#feed">
						<i class="orange ace-icon fa fa-rss bigger-120"></i>
						Activity Feed
					</a>
				</li>
				-->
			</ul>

			<div class="tab-content no-border padding-24">
				<div id="home" class="tab-pane in active">
					<?php if(!isset($profile)) : ?>
					<div class="alert alert-danger">
						<strong>Pemberitahuan</strong> Anda belum melakukan updating data pengguna dan data kepegawaian.
					</div>
					<?php endif; ?>
					<br>

					<div class="row">
						<div class="col-xs-12 col-sm-3 center">
							<span class="profile-picture">
								<?php
									if(file_exists(PATH_PHOTO_PEGAWAI.$profile->pas_foto)){
										$imgsrc = base_url().'/'.PATH_PHOTO_PEGAWAI.$profile->pas_foto;
									}else{
										$imgsrc = base_url().'/'.PATH_PHOTO_PEGAWAI.'15940avatar.png';
									}
								?>
								<a href="<?php echo $imgsrc?>" target="_blank"><img class="editable img-responsive" alt="<?php echo isset($profile->nama_pegawai)?$profile->nama_pegawai:''?>" id="avatar2" src="<?php echo $imgsrc; ?>" style="max-width: 200px"></a>
							</span>

							<div class="space space-4"></div>
							<span style="font-weight: bold;font-size: 25px;"><?php echo isset($profile->kepeg_nip)?$profile->kepeg_nip:''?></span><br>
							<span class="middle" style="font-weight: bold;font-size: 15px;color: blue"><?php echo isset($profile->nama_pegawai)?$profile->nama_pegawai:''?></span><br>
							[ <?php echo isset($profile->kepeg_gol)?$profile->kepeg_gol:''?>, <?php echo isset($profile->nama_level)?$profile->nama_level:''?>, <?php echo isset($profile->nama_unit)?$profile->nama_unit:''?> ]


							<hr>

							<a href="#" class="btn btn-sm btn-block btn-success">
								<i class="ace-icon fa fa-pencil bigger-120"></i>
								<span class="bigger-110">Update Data Pegawai</span>
							</a>
						</div><!-- /.col -->

						<div class="col-xs-12 col-sm-9">
							<h4 class="blue">
								<span class="label label-success arrowed-in-right">
									<i class="ace-icon fa fa-circle smaller-80 align-middle"></i>
									online
								</span>
							</h4>

							<div class="profile-user-info">
								<div class="profile-info-row">
									<div class="profile-info-name"> NIK </div>
									<div class="profile-info-value">
										<span><?php echo isset($profile->kepeg_nik)?$profile->kepeg_nik:''?></span>
									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> Tempat/Tgl Lahir </div>
									<div class="profile-info-value">
										<span><?php echo isset($profile->tmp_lahir)?$profile->tmp_lahir.'/':''?> <?php echo isset($profile->tgl_lahir)?$this->tanggal->formatDate($profile->tgl_lahir):''?></span>
									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> Jenis Kelamin </div>
									<div class="profile-info-value">
										<span>
											<?php 
												$jk = isset($profile->jk) ? $profile->jk : '';
												$jktxt = ($jk == 1)?'Laki-laki':'Perempuan';
												echo $jktxt; 
											?>
										</span>
									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> Alamat </div>

									<div class="profile-info-value">
										<i class="fa fa-map-marker light-orange bigger-110"></i>
										<span>
											<?php echo isset($profile->alamat)?$profile->alamat:''?> 
										</span>
									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> &nbsp; </div>

									<div class="profile-info-value">
										<span>
											<?php echo isset($profile->nama_kelurahan)?$profile->nama_kelurahan:''?>
											<?php echo isset($profile->nama_kecamatan)?','.$profile->nama_kecamatan:''?>
											<?php echo isset($profile->nama_kota)?','.$profile->nama_kota:''?>
											<?php echo isset($profile->nama_provinsi)?','.$profile->nama_provinsi:''?>
											<?php echo isset($profile->kode_pos)?','.$profile->kode_pos:''?>
										</span>
									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> No.Telp / Hp </div>
									<div class="profile-info-value">
										<span><?php echo isset($profile->kepeg_no_telp)?$profile->kepeg_no_telp:''?> </span>
									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> Email </div>
									<div class="profile-info-value">
										<span><?php echo isset($profile->kepeg_email)?$profile->kepeg_email:''?> </span>
									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> Pendidikan Terakhir </div>
									<div class="profile-info-value">
										<span><?php echo isset($profile->pendidikan_terakhir)?$profile->pendidikan_terakhir:''?> </span>
									</div>
								</div>
								
							</div>

						</div><!-- /.col -->
					</div><!-- /.row -->

					<div class="space-20"></div>

					<div class="row">

						<div class="col-xs-12 col-sm-12">
							<div class="widget-box transparent">
								<div class="widget-header widget-header-small header-color-blue2">
									<h4 class="widget-title smaller">
										<i class="ace-icon fa fa-lightbulb-o bigger-120"></i>
										Rekapitulasi Tahun <?php echo date('Y')?>
									</h4>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-16">
										<div class="clearfix">
											<div class="grid3 center">
												<!-- #section:plugins/charts.easypiechart -->
												<div class="easy-pie-chart percentage" data-percent="45" data-color="#CA5952" style="height: 72px; line-height: 71px; color: rgb(202, 89, 82);">
												<span class="percent" style="font-size: 25px; cursor: pointer" onclick="getMenu('kepegawaian/Kepeg_pengajuan_cuti')"><?php echo $cuti?></span> (hari)
												</div>
												<!-- /section:plugins/charts.easypiechart -->
												<div class="space-2"></div>
												Jumlah Cuti yang sudah digunakan<br>Periode Tahun <?php echo date('Y')?>
											</div>

											<div class="grid3 center">
												<div class="center easy-pie-chart percentage" data-percent="90" data-color="#59A84B" style="height: 72px; line-height: 71px; color: rgb(89, 168, 75);">
													<span class="percent" style="font-size: 25px; cursor:pointer" onclick="getMenu('kepegawaian/Kepeg_pengajuan_lembur')"><?php echo $lembur?></span> (h:m)
												</div>
												<div class="space-2"></div>
												Total Jam Lembur<br>Periode <?php echo $this->tanggal->getBulan(date('m')).' '. date('Y')?>
											</div>

											<div class="grid3 center">
												<div class="center easy-pie-chart percentage" data-percent="80" data-color="#9585BF" style="height: 72px; line-height: 71px; color: rgb(149, 133, 191);">
													<span class="percent" style="font-size: 25px; cursor: pointer" onclick="show_modal('kepegawaian/Kepeg_upload_gaji/show_detail_row?bulan=<?php echo $gaji->kg_periode_bln; ?>&tahun=<?php echo $gaji->kg_periode_thn; ?>&nip=<?php echo $gaji->nip; ?>', 'Rincian Gaji')"><?php echo number_format($gaji->gaji_diterima)?></span> (IDR)
												</div>
												<div class="space-2"></div>
												Gaji diterima <br>Periode <?php echo $this->tanggal->getBulan(date('m')-1).' '. date('Y')?>
											</div>
										</div>

										<div class="hr hr-16"></div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div><!-- /#home -->

				<div id="feed" class="tab-pane">
					<div class="profile-feed row">
						<div class="col-sm-6">
							<div class="profile-activity clearfix">
								<div>
									<img class="pull-left" alt="Alex Doe's avatar" src="<?php echo base_url()?>assets/avatars/avatar5.png">
									<a class="user" href="#"> Alex Doe </a>
									changed his profile photo.
									<a href="#">Take a look</a>

									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										an hour ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>

							<div class="profile-activity clearfix">
								<div>
									<img class="pull-left" alt="Susan Smith's avatar" src="<?php echo base_url()?>assets/avatars/avatar1.png">
									<a class="user" href="#"> Susan Smith </a>

									is now friends with Alex Doe.
									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										2 hours ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>

							<div class="profile-activity clearfix">
								<div>
									<i class="pull-left thumbicon fa fa-check btn-success no-hover"></i>
									<a class="user" href="#"> Alex Doe </a>
									joined
									<a href="#">Country Music</a>

									group.
									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										5 hours ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>

							<div class="profile-activity clearfix">
								<div>
									<i class="pull-left thumbicon fa fa-picture-o btn-info no-hover"></i>
									<a class="user" href="#"> Alex Doe </a>
									uploaded a new photo.
									<a href="#">Take a look</a>

									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										5 hours ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>

							<div class="profile-activity clearfix">
								<div>
									<img class="pull-left" alt="David Palms's avatar" src="<?php echo base_url()?>assets/avatars/avatar4.png">
									<a class="user" href="#"> David Palms </a>

									left a comment on Alex's wall.
									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										8 hours ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>
						</div><!-- /.col -->

						<div class="col-sm-6">
							<div class="profile-activity clearfix">
								<div>
									<i class="pull-left thumbicon fa fa-pencil-square-o btn-pink no-hover"></i>
									<a class="user" href="#"> Alex Doe </a>
									published a new blog post.
									<a href="#">Read now</a>

									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										11 hours ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>

							<div class="profile-activity clearfix">
								<div>
									<img class="pull-left" alt="Alex Doe's avatar" src="<?php echo base_url()?>assets/avatars/avatar5.png">
									<a class="user" href="#"> Alex Doe </a>

									upgraded his skills.
									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										12 hours ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>

							<div class="profile-activity clearfix">
								<div>
									<i class="pull-left thumbicon fa fa-key btn-info no-hover"></i>
									<a class="user" href="#"> Alex Doe </a>

									logged in.
									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										12 hours ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>

							<div class="profile-activity clearfix">
								<div>
									<i class="pull-left thumbicon fa fa-power-off btn-inverse no-hover"></i>
									<a class="user" href="#"> Alex Doe </a>

									logged out.
									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										16 hours ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>

							<div class="profile-activity clearfix">
								<div>
									<i class="pull-left thumbicon fa fa-key btn-info no-hover"></i>
									<a class="user" href="#"> Alex Doe </a>

									logged in.
									<div class="time">
										<i class="ace-icon fa fa-clock-o bigger-110"></i>
										16 hours ago
									</div>
								</div>

								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="ace-icon fa fa-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="ace-icon fa fa-times bigger-125"></i>
									</a>
								</div>
							</div>
						</div><!-- /.col -->
					</div><!-- /.row -->

					<div class="space-12"></div>

					<div class="center">
						<button type="button" class="btn btn-sm btn-primary btn-white btn-round">
							<i class="ace-icon fa fa-rss bigger-150 middle orange2"></i>
							<span class="bigger-110">View more activities</span>

							<i class="icon-on-right ace-icon fa fa-arrow-right"></i>
						</button>
					</div>
				</div><!-- /#feed -->

			</div>
		</div>
	</div>
</div>