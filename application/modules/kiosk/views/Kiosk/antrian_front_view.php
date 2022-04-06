<style>
  .widget-title{
    font-size: medium !important;
    font-weight: bold;
  }
  .widget-color-dark {
    border-color: #dfdcdc;
    border: 0px !important
  }
  .widget-body {
    /* background: linear-gradient(183deg, #1c8001, #a5f106); */
    /* background: white; */
	color: black !important;
  }
  .widget-box{
	border-radius: 15px 50px 30px !important;
	box-shadow: 3px 3px #0080006e;
  }
</style>

<div class="row" style="padding-top: 10px">
	<div class="col-md-8 col-md-offset-2">
		<div class="center section-title">

			<a href="#" class="btn btn-lg" id="btn_non_bpjs" onclick="clickBtnType('umum')" style="background : green !important; border-color: green;"><img src="<?php echo base_url()?>assets/kiosk/icon-ass-2.png" width="20px"> Non BPJS</a>
			
			<a href="" class="btn btn-lg" style="background : green !important; border-color: green; "> <i class="fa fa-home"></i> Kembali ke Menu Utama</a>

			<a href="#" class="btn btn-lg" onclick="clickBtnType('bpjs')" id="btn_bpjs" style="background : green !important; border-color: green;"><img src="<?php echo base_url()?>assets/kiosk/bpjs-2.png" width="20px"> BPJS</a>

			<input type="hidden" name="tipe_antrian" id="tipe_antrian" value="bpjs">
			<br>
			<br>
			<span id="title_tipe_antrian" style="font-size: 30px; font-weight: bold">ANTRIAN PASIEN BPJS</span>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-xs-12">
		<div id="refresh2">  
			<div class="col-xs-12" id="loket_refresh">

				<?php 
					$arr_color = array('#239B56','#28B463','#2ECC71','#52BE80');
					shuffle($arr_color);
					foreach($klinik as $row_modul) : ?>
					<div class="col-xs-12 col-sm-3 widget-container-col ui-sortable" id="widget-container-col-5" style="padding-bottom: 5px">
						<div class="widget-box ui-sortable-handle" id="widget-box-5" style="min-height: 200px; <?php echo ($row_modul->kuota > 0)?'background: white;':'background: #D5D8DC';?>">
							<div class="widget-body center" style="color: black;">
								<div class="widget-main padding-10" style="min-height: 170px !important">
									<span>
									<span style="font-size: 14px; font-weight: bold"><?php echo strtoupper($row_modul->nama_bagian)?></span><br>
									<p style="font-size:14px;">
										<span style="font-size: 16px !important"><?php echo $row_modul->nama_pegawai?></span><br>
										<?php echo $this->tanggal->formatTime($row_modul->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row_modul->jd_jam_selesai)?><br>
										<?php if($type!='online'){ ?> <b>Sisa Kuota : <?php echo $row_modul->kuota.'</b>'; }?><br>
										<?php echo isset($row_modul->jd_keterangan)?$row_modul->jd_keterangan:''?> <?php echo isset($row_modul->keterangan)?$row_modul->keterangan:''?> 
									</p> 	
									<input type="hidden" id="kode_dokter" val="<?php echo $row_modul->jd_kode_dokter ?>">
									<input type="hidden" id="kode_spesialis" val="<?php echo $row_modul->jd_kode_spesialis ?>">
									</span>
								</div>
								<div class="widget-footer" style="min-height: 50px">
									<?php if($row_modul->kuota > 0) :?>
									<a href="#" onclick="add_antrian_poli(<?php echo $row_modul->jd_kode_dokter ?>,'<?php echo $row_modul->nama_pegawai?>','<?php echo $row_modul->jd_kode_spesialis ?>','<?php echo $row_modul->nama_bagian?>','<?php echo $row_modul->jd_hari ?>','<?php echo $this->tanggal->formatTime($row_modul->jd_jam_mulai) ?>','<?php echo $row_modul->jd_jam_selesai ?>',<?php echo $row_modul->kuota ?>)" class="btn btn-lg" style="background: green !important; border-color: green">AMBIL NOMOR</a>
									
									<?php 
										else:
											echo '<span style="color: red; font-weight: bold; font-style: italic">Kuota sudah penuh</span>';
										endif;
									?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach;?>

				<div class="col-xs-12 col-sm-3 widget-container-col ui-sortable" id="widget-container-col-5">
					<div class="widget-box ui-sortable-handle" id="widget-box-5" style="background: white">
						<div class="widget-body center" style="color: black;">
							<div class="widget-main padding-6" style="min-height: 170px !important">
								<span>
								<span style="font-size: 14px; font-weight: bold">ADMISSION</span><br>
								<p style="font-size:14px;">
									Bagian Informasi dan Pendaftaran Pasien<br>
								</p>
								</span>
							</div>
							<div class="widget-footer" style="min-height: 50px">
								<a href="#" onclick="add_other('CS')" class="btn btn-lg" style="background: green !important; border-color: green">AMBIL NOMOR</a>
							</div>
						</div>
					</div>
				</div>
		
			</div>
		</div>
	</div>
</div>


<div id="modalVerifyKodeBooking" class="modal fade" tabindex="-1">

	<div class="modal-dialog" style="max-height:90%;  margin-top: 15%; margin-bottom:50px;width:50%">

	<div class="modal-content">

		<div class="modal-header no-padding">

		<div class="table-header">

			<h3 style="margin:0 !important;font-weight: bold;font-size:20px; height: 40px; padding-top: 10px">VERIFIKASI PERJANJIAN</h3>
		
		</div>

		</div>
		

		<div class="modal-body" style="text-align:left;">
			
			<!-- hidden -->
			<input type="hidden" name="modal_dataString" id="modal_dataString" value="">
			<input type="hidden" name="modal_dokter" id="modal_dokter" value="">
			<input type="hidden" name="modal_nama_dokter" id="modal_nama_dokter" value="">
			<input type="hidden" name="modal_spesialis" id="modal_spesialis" value="">
			<input type="hidden" name="modal_nama_spesialis" id="modal_nama_spesialis" value="">
			<input type="hidden" name="modal_hari" id="modal_hari" value="">
			<input type="hidden" name="modal_jam_mulai" id="modal_jam_mulai" value="">
			<input type="hidden" name="modal_jam_selesai" id="modal_jam_selesai" value="">
			
			<!-- dokter -->
			<div style="padding: 10px">
				<span style="font-weight: bold; font-size: 14px" id="nama_dokter_txt"></span><br>
				<span id="poli_txt"></span><br>
				<span id="day_txt"></span>, <span id="jam_txt"></span>
			</div>

			<!-- poli/klinik -->
			<div id="div_form_input_kode_booking">
				<div style="padding: 10px">
					<label for="form-field-mask-1" style="font-size: 12px;">
						<b>Masukan Kode Booking/No Rekam Medis/No KTP : </b>
					</label>

					<div>
						<input class="form-control" type="text" id="kode_booking" name="kode_booking" style="font-size:40px;height: 55px !important; width: 100%; text-align: center !important; text-transform: uppercase" autocomplete="off">
					</div>
				</div>

				<div style="width: 100%; margin-top: 10px; text-align: center">
					<div id="error_message"></div>
					<button class="btn" type="button" onclick="verifkodeperjanjian()" id="btnSearchPasien" style="height: 50px !important; font-size: 20px; font-weight: bold; background: green !important; border-color: green">
						<i class="ace-icon fa fa-print bigger-110"></i>
						Cetak Nomor Antrian
					</button>

					<button class="btn btn-danger" type="button" onclick="close_modal()" id="btnRePrintAntrian" style="height: 50px !important; font-size: 20px; font-weight: bold">
						<i class="ace-icon fa fa-times-circle bigger-110"></i>
						Close
					</button>
					
				</div>
			</div>

			<div id="div_form_input_kode_booking_success" style="display: none" >
				<div style="width: 100%; margin-top: 10px; text-align: center">
					<div class="alert alert-success">
						<i class="fa fa-check bigger-120"></i>
						<strong>Sukses !</strong> Silahkan ambil print out Nomor Antrian Anda !
					</div>
					<button class="btn btn-primary" type="button" onclick="rePrintAntrian()" id="btnRePrintAntrian" style="height: 50px !important; font-size: 20px; font-weight: bold">
						<i class="ace-icon fa fa-print bigger-110"></i>
						Cetak Ulang
					</button>
					<button class="btn btn-danger" type="button" onclick="close_modal()" id="btnRePrintAntrian" style="height: 50px !important; font-size: 20px; font-weight: bold">
						<i class="ace-icon fa fa-times-circle bigger-110"></i>
						Close
					</button>
				</div>
			</div>

		</div>

		<!-- <div class="modal-footer no-margin-top">

		<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

			<i class="ace-icon fa fa-times"></i>

			Close

		</button>

		</div> -->

	</div><!-- /.modal-content -->

	</div><!-- /.modal-dialog -->

</div>

<div id="modalAntrianPenuh" class="modal fade" tabindex="-1">

	<div class="modal-dialog" style="max-height:90%;  margin-top: 15%; margin-bottom:50px;width:50%">

	<div class="modal-content">

		<div class="modal-header no-padding">

		<div class="table-header">

			<h3 style="margin:0 !important;font-size18px;">PEMBERITAHUAN !</h3>
		
		</div>

		</div>
		

		<div class="modal-body" style="text-align:left;">
			<div class="alert alert-danger center">
				<strong style="font-size: 20px; font-weight: bold">Kuota Penuh !</strong><br> <span style="font-size: 16px">Silahkan cari poli/dokter lain..!</span>
			</div>
		</div>

	</div><!-- /.modal-content -->

	</div><!-- /.modal-dialog -->

</div>

<style type="text/css">
	.active, .btn:hover {
		background-color: #666 !important;
		color: white;
	}
</style>


<script>
	
	// setInterval("my_function();",3000); 

	function close_modal(){
		$('#modalVerifyKodeBooking').modal('hide');
		$('#div_form_input_kode_booking').show();
		$('#div_form_input_kode_booking_success').hide();
	}

	function update_antrian() {

		var loket = $('#select_loket').val();

		var type = $('#select_tipe').val();

		$.post("<?php echo base_url()?>antrian/Loket/reload_page", { loket:loket, tipe:type } ).done( function(data) {

			var obj = JSON.parse(data);
			console.log(obj)
			if(obj.success==1){
				$('#message_loket').hide('fast');
				$('#loket_hidden').val( obj.loket);
				$('#label_loket').text( obj.loket);

				$('#tipe_hidden').val( obj.tipe);
				$('#label_tipe').text( obj.tipe_loket.toUpperCase());
				
				format_no = pad(obj.counter, 3);

				console.log(format_no)

				$('#counter_number').text( obj.tipe+''+format_no );
				$('#counter_number_value').val( obj.counter );
				/*info antrian*/
				$('#from_num').text(obj.counter);

				if(type=='bpjs'){
				$('#to_num').text(obj.total_bpjs);
				}else{
				$('#to_num').text(obj.total_non_bpjs);
				}

				$('#total_bpjs').text(obj.total_bpjs);
				$('#sisa_antrian_bpjs').text(obj.sisa_bpjs);

				$('#total_non_bpjs').text(obj.total_non_bpjs);
				$('#sisa_antrian_non_bpjs').text(obj.sisa_non_bpjs);

			}else{
				$('#message_loket').show('fast');
				$('#message_loket').html('<span style="color:red"><i>'+obj.message+'</i></span>');          
			}

		});

	}

	function clickBtnType(flag){
		if(flag == 'bpjs'){
			$('#title_tipe_antrian').text('ANTRIAN PASIEN BPJS');
			$('#tipe_antrian').val('bpjs');
		}else{
			$('#title_tipe_antrian').text('ANTRIAN PASIEN NON BPJS ATAU UMUM');
			$('#tipe_antrian').val('umum');
		}
	}

	function my_function(){
		$('#refresh').load('kiosk/Kiosk/antrian' + ' #time');
		$('#refresh2').load('kiosk/Kiosk/antrian' + ' #loket_refresh');
	}

	function online() {
		$("#modalAntrian").modal();  
	}

	function verifkodeperjanjian() {
		
		if($('#kode_booking').val()==''){
			// achtungCreate("<span style='font-size:20px'>Masukan Kode Booking Anda !</span>", false, 'achtungFail');
			$('#error_message').html('<div class="alert alert-danger center" style="margin: 10px"><strong>Pemberitahuan! </strong><br>Masukan Kode Booking anda!</div>');
			return false;
		} else {

			var dataString = $('#modal_dataString').val();
			var dokter = $('#modal_dokter').val();
			var nama_dokter = $('#modal_nama_dokter').val();
			var spesialis = $('#modal_spesialis').val();
			var nama_spesialis = $('#modal_nama_spesialis').val();
			var hari = $('#modal_hari').val();
			var jam_mulai = $('#modal_jam_mulai').val();
			var jam_selesai = $('#modal_jam_selesai').val();

			data = [];
			data[0] = dataString;
			data[1] = dokter;
			data[2] = nama_dokter;
			data[3] = spesialis;
			data[4] = nama_spesialis;
			data[5] = hari;
			data[6] = jam_mulai;
			data[7] = jam_selesai;
			data[8] = $('#kode_booking').val();

			$.ajax({
				url:"<?php echo base_url(); ?>antrian/process_cek_kode_booking",
				data:{data:data}, 
				dataType: "json", 
				type:"POST",       
				success:function (response) {
					console.log(response['status']);

					if(response['status'] == 200){
						// close modal
						$("#div_form_input_kode_booking").hide('fast'); 
						$("#div_form_input_kode_booking_success").show('fast'); 

					}else if(response['status']!=200){
						// achtungCreate("<h3 style='text-align:center;'>"+response['message']+"</h3>",false, 'achtungFail');
						$('#kode_booking').val('');
						$('#error_message').html('<div class="alert alert-danger center" style="margin: 10px"><strong>Pemberitahuan! </strong><br>'+response['message']+'</div>');
					}
				}
				
			
			});
		}
		

	}

	function add_antrian_poli(dokter,nama_dokter,spesialis,nama_spesialis,hari,jam_mulai,jam_selesai,kuota) {
		
		var dataString = $('#tipe_antrian').val(); 

		if((kuota>0) || (dataString=='online')){
			if(dataString == 'bpjs'){
				$('#modal_dataString').val(dataString);
				$('#modal_dokter').val(dokter);
				$('#modal_nama_dokter').val(nama_dokter);
				$('#modal_spesialis').val(spesialis);
				$('#modal_nama_spesialis').val(nama_spesialis);
				$('#modal_hari').val(hari);
				$('#modal_jam_mulai').val(jam_mulai);
				$('#modal_jam_selesai').val(jam_selesai);

				$('#nama_dokter_txt').text(nama_dokter);
				$('#poli_txt').text(nama_spesialis.toUpperCase());
				$('#day_txt').text(hari);
				$('#jam_txt').text(jam_mulai);
				$('#error_message').html('');
				$("#modalVerifyKodeBooking").modal();  
			}else{
				data = [];
				data[0] = dataString;
				data[1] = dokter;
				data[2] = nama_dokter;
				data[3] = spesialis;
				data[4] = nama_spesialis;
				data[5] = hari;
				data[6] = jam_mulai;
				data[7] = jam_selesai;
				console.log(data)
				$.ajax({
					url:"<?php echo base_url(); ?>antrian/loket/process_kiosk",
					data:{data:data}, 
					dataType: "json", 
					type:"POST",       
					success:function (data) {
						//console.log(data);
						no = pad(data['no'], 3);
						$('#klinik_modal').text(data['klinik']);
						$('#dokter_modal').text(data['dokter']);
						$('#no_modal').text(no);
						//$("#modalAntrian").modal();
						//window.location.href = "<?php echo base_url(); ?>antrian";
						//   openWin(no,data['klinik'],data['dokter'],data['type'],data['jam_praktek']);
						//setTimeout(function () { window.location.href = "<?php echo base_url(); ?>antrian"; }, 2000);
					}
				});
				event.preventDefault();
			}
		}else{
			$("#modalAntrianPenuh").modal();  
		}
			
	}

	function add_other(other_name) {
		/* console.log(dokter);
		console.log(spesialis); */
		var dataString = $('#tipe_antrian').val(); 
		$.ajax({
			url:"<?php echo base_url(); ?>antrian/loket/process_other_kiosk",
			data:{type: dataString, poli: other_name}, 
			dataType: "json", 
			type:"POST",       
			success:function (data) {
				//console.log(data)
			
				no = pad(data['no'], 3);

				$('#klinik_modal').text(data['klinik']);
				$('#dokter_modal').text(data['dokter']);
				$('#no_modal').text(no);

			}
		});

		event.preventDefault();
		
	}

	function pad (str, max) {
		str = str.toString();
		return str.length < max ? pad("0" + str, max) : str;
	}

	function openWin(n,klinik,dokter,type,jam_praktek) {
		date = new Date;
		year = date.getFullYear();
		month = date.getMonth();
		months = new Array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'Desember');
		short_months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des');
		d = date.getDate();
		day = date.getDay();
		days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
		h = date.getHours();
		if(h<10)
		{
				h = "0"+h;
		}
		m = date.getMinutes();
		if(m<10)
		{
				m = "0"+m;
		}
		s = date.getSeconds();
		if(s<10)
		{
				s = "0"+s;
		}
		result = ''+days[day]+', '+d+' '+months[month]+' '+year+', '+h+':'+m+':'+s;
		current_date = ''+days[day]+', '+d+'/'+short_months[month]+'/'+year;


		myWindow = window.open("", "myWindow", "width=2,height=1");

		if(type=='bpjs'){
		var type_antrian = 'A';
		var text_title = 'BPJS';
		}else{
		var type_antrian = 'B';
		var text_title = 'UMUM';
		}

		var html = 
			'<div style="font-family: calibri" class="center">\
				<center>\
				<table align="center" border="0" width="100%">\
				<tr>\
				<td colspan="2" align="center"><span style="font-size:150% !important"><?php echo COMP_LONG; ?></span><br><small style="font-size:9px !important"><?php echo COMP_ADDRESS; ?></small><hr></td>\
				</tr>\
				<tr>\
				<td align="center" colspan="2"><span style="font-size:11px;margin-top:0">PENDAFTARAN PASIEN '+text_title+'</span><br><span style="font-size:300%;"> '+type_antrian+' '+n+' <small style="font-size:10px !important;margin-top:0"><br>Nomor Antrian</small><br><span style="font-size:20% !important;margin-top:0"><br>'+klinik.toUpperCase()+'<br>'+dokter+'<br>'+current_date+', '+jam_praktek+'</span> </td>\
				</tr>\
				</table>\
				<table align="center" width="100%">\
				<tr style="font-size:11px;">\
				<td><br><br></td>\
				</tr>\
			';

		myWindow.document.write(html);

		
		myWindow.print();
		myWindow.close();
	}

</script>






