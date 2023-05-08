<script>

  function print_bukti_pendaftaran() {
		/* console.log(dokter);
		console.log(spesialis); */
		var dataString = 'BPJS'; 
		$.ajax({
			url:"<?php echo base_url(); ?>antrian/loket/process_other_kiosk_reg_online",
			data:{
        type: dataString, 
        poli: $('#poli').val(), 
        dokter: $('#nama_dokter').val(), 
        nama_pasien: $('#pasien').val(), 
        umur_pasien: $('#umur').val(), 
        tgl_lahir: $('#tgl_lahir').val(), 
        no_mr: $('#no_mr').val()
      }, 
			dataType: "json", 
			type:"POST",       
			success:function (data) {
				console.log(data)
			
				no = pad(data['no'], 3);

				$('#klinik_modal').text(data['klinik']);
				$('#dokter_modal').text(data['dokter']);
				$('#no_modal').text(no);

			}
		});

		event.preventDefault();
		
	}

  $('#btnConfirmFp').click(function (e) {
      e.preventDefault();
      findFingerPrint();
  });

  function findFingerPrint(){
      var noKartuBPJS = $('#noKartuBPJS').val();
      

      $.ajax({
          url: 'Templates/References/findFingerPrint',
          type: "post",
          data: {kode:noKartuBPJS},
          dataType: "json",
          beforeSend: function() {
            
          },
          success: function(response) {
            $('#message_fp').text(response.message)
            $('#konfirmasi_finger_print').show();
            if(response.status == 200){
              var obj = response.data;
              $('#pasien').val(obj.nama_pasien);
              $('#no_mr').val(obj.no_mr);
              $('#poli').val(obj.nama_bagian);
              $('#nama_dokter').val(obj.nama_dokter);
              $('#umur').val(obj.umur);
              $('#tgl_lahir').val(obj.tgl_lahir);

              $('#msg_box').html('<div class="alert alert-success" style="font-size: 16px"><b><i class="fa fa-check green"></i> Sukses..!</b><br>'+response.message+'</div><br><button type="button" onclick="print_bukti_pendaftaran('+"'REGISTRASI_ONLINE'"+')" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 20px;min-width: 320px; background: green !important; border-color: green"><i class="fa fa-print bigger-150"></i> Cetak Bukti Pendaftaran</button>');
            }else{
              $('#msg_box').html('<div class="alert alert-danger" style="font-size: 16px"><b><i class="fa fa-times red"></i> Pemberitahuan..!</b><br>'+response.message+'</div><br><button type="button" onclick="getMenu('+"'Kiosk/antrian_front'"+')" class="btn btn-xs btn-primary" style="height: 45px !important;font-size: 20px;min-width: 320px; background: green !important; border-color: green"><i class="fa fa-print bigger-150"></i> Ambil Nomor Antrian</button>');
            }
                
          }
      });

  }

</script>

<style>
  .widget-title{
    font-size: medium !important;
    font-weight: bold;
  }
  .widget-color-dark {
    border-color: #dfdcdc;
    border: 0px !important
  }
  .profile-info-value{
    text-align: left;
  }
  
</style>

<div class="row" style="top:50%; left: 50%" id="asum_main_view">

  <p style="text-align: center; font-size: 2em; font-weight: bold; color: green">
    <img src="<?php echo base_url()?>assets/kiosk/icon-finger-scan.png" height="100" alt=""><br><br>
    KONFIRMASI FINGER PRINT
  </p>

  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-10">
      <div id="konfirmasi_finger_print_rj">
        <div style="padding-top: 30px;">
          <div>
            <label class="" style="font-size: 20px; font-weight: bold;">Masukan Nomor Kartu BPJS</label><br>         
              <div class="input-group input-group-lg">
              <span class="input-group-addon">
                  <i class="ace-icon fa fa-check"></i>
              </span>
              <input type="text" class="form-control" id="noKartuBPJS" style="height: 55px !important;font-size: 30px !important; text-align: left" placeholder="" autocomplete="off">
              <span class="input-group-btn">
                  <button type="button" class="btn btn-lg" id="btnConfirmFp" style="height: 55px !important; background: green !important; border-color: green">
                  <span class="ace-icon fa fa-check icon-on-right bigger-110"></span>
                  Konfirmasi Finger Print
                  </button>
              </span><br>
            </div>
          </div>
        </div>
      </div>
      <br>
      
      <div style="width:100% !important; text-align:left; font-size:16px;color:black;padding-top:5px; font-style: italic">
        Tanggal Kunjungan : <span id='ct6' style=" font-size: 16px;" ></span>
      </div>
      <div id="konfirmasi_finger_print" style="display: none">

        <div style="padding-top: 10px">
          <div id="msg_box"></div>
          
        </div>
        <!-- hidden -->
        <input type="hiddenxx" name="poli" value="" id="poli">
        <input type="hiddenxx" name="nama_dokter" value="" id="nama_dokter">
        <input type="hiddenxx" name="no_mr" value="" id="no_mr">
        <input type="hiddenxx" name="nama_pasien" value="" id="pasien">
        <input type="hiddenxx" name="umur_pasien" value="" id="umur">
        <input type="hiddenxx" name="tgl_lahir" value="" id="tgl_lahir">
      </div>
      <div class="center" style="left: 50%; top:80%; margin-top: 50px" >
        <a href="<?php echo base_url().'kiosk'?>" class="btn btn-lg" style="background : green !important; border-color: green"> <i class="fa fa-home"></i> Kembali Ke Beranda</a>
      </div>

  </div>
  <div class="col-md-1">&nbsp;</div>

</div>

<script>
  function display_ct6() {
    var x = new Date()
    var ampm = x.getHours( ) >= 12 ? ' PM' : ' AM';
    hours = x.getHours( ) % 12;
    hours = hours ? hours : 12;
    var x1=x.getMonth() + 1+ "/" + x.getDate() + "/" + x.getFullYear(); 
    x1 = x1 + " - " +  hours + ":" +  x.getMinutes() + ":" +  x.getSeconds() + ":" + ampm;
    document.getElementById('ct6').innerHTML = x1;
    display_c6();
  }
  function display_c6(){
    var refresh=1000; // Refresh rate in milli seconds
    mytime=setTimeout('display_ct6()',refresh)
  }
  display_c6()
</script>

