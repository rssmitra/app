<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="center section-title">
			<botton class="btn btn-sm btn-primary" id="btn_non_bpjs" onclick="clickBtnType('umum')" style="border-radius:10px;text-decoration:none;"><h3 style="font-size: 2rem;margin:20px">NON BPJS</h3></botton>
			<botton class="btn btn-sm btn-primary" onclick="clickBtnType('bpjs')" id="btn_bpjs" style="border-radius:10px;text-decoration:none;"><h3 style="font-size: 2rem;margin:20px">&nbsp; BPJS &nbsp; </h3></botton>
			<!-- <botton onclick="online()" href="#" class="btn btn-sm btn-primary" data-wow-delay="1.0s" style="border-radius:10px;text-decoration:none;"><h3 style="font-size: 2rem;margin:20px">ONLINE</h3></botton> -->

				<input type="hidden" name="tipe_antrian" id="tipe_antrian" value="bpjs">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="subscribe-content-area">
			
			<!-- home section -->
			<section id="home">

				<div class="row center"><br>
					<span class="center animate" style="font-size:250%; padding: 20px; color: white; padding-bottom: 20px"><b id="title_tipe_antrian">ANTRIAN PASIEN BPJS</b></span><br>
					<div id="refresh2">  
						<div class="col-xs-12" id="loket_refresh">
							<?php 
								
								$arr_color = array('yellow','lime','orange','fuchsia','lightgray','lightblue','lightgrey','cyan','aqua','khaki','lightpink','wheat');
								// $arr_color = array(''); 
								/*$arr_color = array('yellow','olive','lime','orange','fuchsia','lightgray','lightblue'); */
								shuffle($arr_color);

							?>
							<div class="row">
							<?php foreach($klinik as $row_modul) : ?>
								<div class="col-lg-3 col-xs-3" style="margin-top:0px;height:170px;">
								<!-- small box -->
									<button onclick="add_antrian_poli(<?php echo $row_modul->jd_kode_dokter ?>,'<?php echo $row_modul->nama_pegawai?>','<?php echo $row_modul->jd_kode_spesialis ?>','<?php echo $row_modul->nama_bagian?>','<?php echo $row_modul->jd_hari ?>','<?php echo $this->tanggal->formatTime($row_modul->jd_jam_mulai) ?>','<?php echo $row_modul->jd_jam_selesai ?>',<?php echo $row_modul->kuota ?>)" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
										<div class="inner" style="margin-top:-10px">
										<h3 style="font-size:18px;color:black;"><b><?php echo ucwords($row_modul->nama_bagian)?></b></h3>
										<p style="font-size:14px;color:black;">
											<?php echo $row_modul->nama_pegawai?><br>
											<?php echo $this->tanggal->formatTime($row_modul->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row_modul->jd_jam_selesai)?><br>
											<?php if($type!='online'){ ?> <b>Sisa Kuota : <?php echo $row_modul->kuota.'</b>'; }?><br>
											<?php echo isset($row_modul->jd_keterangan)?$row_modul->jd_keterangan:''?> <?php echo isset($row_modul->keterangan)?$row_modul->keterangan:''?> 
										</p>
										</div> 
											
										<input type="hidden" id="kode_dokter" val="<?php echo $row_modul->jd_kode_dokter ?>">
										<input type="hidden" id="kode_spesialis" val="<?php echo $row_modul->jd_kode_spesialis ?>">
									</button>
								</div>
							<?php endforeach; ?>

								<div class="col-lg-3 col-xs-3" style="margin-top:0px;height:180px;">
									<button onclick="add_other('Penunjang')" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
									<div class="inner" style="margin-top:-10px; text-align: center">
										<h3 style="font-size:28px;color:black;"><b>Penunjang Medis</b></h3>
										<p style="font-size:14px;color:black;">
											Pelayanan Pendaftaran Penunjang Medis (Lab, Radiologi, Fisioterapi)
										</p>
									</div>                      
									</button>
								</div>

								<div class="col-lg-3 col-xs-3" style="margin-top:0px;height:180px;">
									<button onclick="add_other('Perjanjian')" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
									<div class="inner" style="margin-top:-10px; text-align: center">
										<h3 style="font-size:28px;color:black;"><b>Perjanjian Pasien</b></h3>
										<p style="font-size:14px;color:black;">
											Penjadwalan Pasien/Reschedule Perjanjian
										</p>
									</div>                      
									</button>
								</div>

								<!-- <div class="col-lg-3 col-xs-3" style="margin-top:0px;height:180px;">
									<button onclick="add_other('Laboratorium')" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
									<div class="inner" style="margin-top:-10px; text-align: center">
										<h3 style="font-size:28px;color:black;"><b>LABORATORIUM</b></h3>
										<p style="font-size:14px;color:black;">
											Buka 24 Jam Setiap hari<br>Dengan membawa surat pengantar lab dari dokter
										</p>
									</div>                      
									</button>
								</div>

								<div class="col-lg-3 col-xs-3" style="margin-top:0px;height:180px;">
									<button onclick="add_other('Radiologi')" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
									<div class="inner" style="margin-top:-10px;text-align: center">
										<h3 style="font-size:28px;color:black;"><b>RADIOLOGI</b></h3>
										<p style="font-size:14px;color:black;">
											Pendaftaran Pasien Radiologi
										</p>
									</div>                      
									</button>
								</div> -->

								<div class="col-lg-3 col-xs-3" style="margin-top:0px;height:180px;">
									<button onclick="add_other('IGD')" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
									<div class="inner" style="margin-top:-10px; text-align: center">
										<h3 style="font-size:28px;color:black;"><b>IGD</b></h3>
										<span style="font-size:18px;color:black;">
										Instalasi Gawat Darurat
										</span>
										<p style="font-size:14px;color:black;">
											Buka 24 Jam Setiap hari
										</p>
									</div>                      
									</button>
								</div>
								
							</div>

						</div><!-- /.col -->
					</div>
				</div>
					
			</section>

		</div>
	</div>
</div>

<style type="text/css">
	.active, .btn:hover {
		background-color: #666 !important;
		color: white;
	}
</style>


<script>
	setInterval("update_antrian();",15000); 

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

	// Add active class to the current button (highlight it)
	var header = document.getElementById("myButtonType");
	var btns = header.getElementsByClassName("btn");
	for (var i = 0; i < btns.length; i++) {
		btns[i].addEventListener("click", function() {
			var current = document.getElementsByClassName("active");
			if (current.length > 0) { 
				current[0].className = current[0].className.replace(" active", "");
			}
			this.className += " active";
		});
	}

	setInterval("my_function();",3000); 
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
		$('#refresh').load(location.href + ' #time');
		$('#refresh2').load(location.href + ' #loket_refresh');
	}

	function online() {
		$("#modalAntrian").modal();  
	}

	function verifbooking() {
		
		if($('#kode_booking').val()==''){
			achtungCreate("<h3 style='text-align:center;'>Silahkan Isi form yang tersedia</h3>",false);
			return false;
		} else {
			data = [];
			data[0] = 'online';
			data[1] = $('#kode_booking').val();
			$.ajax({
				url:"<?php echo base_url(); ?>antrian/process",
				data:{data:data}, 
				dataType: "json", 
				type:"POST",       
				success:function (data) {
					console.log(data['status'])
					if(data['status']==200){
						$('#kode_booking').val('');
						$("#modalAntrian").modal('hide');  
						//window.location.href = "<?php echo base_url()?>antrian/loket?type=online";
					}else if(data['status']!=200){
						achtungCreate("<h3 style='text-align:center;'>"+data['message']+"</h3>",false);
						//$('#email').val('');
						$('#kode_booking').val('');
					}
				}
				
			
			});
		}
	}

	function add_antrian_poli(dokter,nama_dokter,spesialis,nama_spesialis,hari,jam_mulai,jam_selesai,kuota) {
		/* console.log(dokter);
		console.log(spesialis); */
		var dataString = $('#tipe_antrian').val(); 
		if((kuota>0) || (dataString=='online')){
		
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
				//console.log(data)
				
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
		}else{
		$("#modalAntrianPenuh").modal();  

		//setTimeout(function () { window.location.href = "<?php echo base_url(); ?>antrian"; }, 2000);
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





