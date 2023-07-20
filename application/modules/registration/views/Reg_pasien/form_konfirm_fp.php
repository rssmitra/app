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

              $('#profile_box').show();

              $('#pasien').val(obj.nama_pasien);
              $('#no_mr').val(obj.no_mr);
              $('#poli').val(obj.nama_bagian);
              $('#nama_dokter').val(obj.nama_dokter);
              $('#umur').val(obj.umur);
              $('#tgl_lahir').val(obj.tgl_lahir);


              $('#nama_pasien_txt').text(obj.nama_pasien);
              $('#no_rm_txt').text(obj.no_mr);
              $('#poli_txt').text(obj.nama_bagian);
              $('#dokter_txt').text(obj.nama_dokter);
              $('#ttl_txt').text(obj.tgl_lahir+' ('+obj.umur+')');

              $('#msg_box').html('<div class="alert alert-success" style="font-size: 12px"><b><i class="fa fa-check green"></i> Sukses..!</b><br>'+response.message+'</div><br><div class="center"><button type="button" onclick="show_modal('+"'ws_bpjs/Ws_index/view_sep/"+obj.no_sep+"?no_antrian=0'"+', '+"'SURAT ELEGIBILITAS PASIEN'"+')" class="btn btn-xs btn-primary" style="background: green !important; border-color: green"><i class="fa fa-print"></i> Surat Elegibilitas Pasien</button></div>');
            }else{
              $('#profile_box').hide();
              $('#msg_box').html('<div class="alert alert-danger" style="font-size: 12px"><b><i class="fa fa-times red"></i> Pemberitahuan..!</b><br>'+response.message+'</div>');
            }
                
          }
      });

  }

</script>


<div class="row" style="top:50%; left: 50%" id="asum_main_view">

  <p style="text-align: center; font-size: 14px; font-weight: bold; color: green">
    <img src="<?php echo base_url()?>assets/kiosk/icon-finger-scan.png" height="70" alt=""><br>
    KONFIRMASI FINGER PRINT
  </p>

  <div class="col-md-1">&nbsp;</div>
  <div class="col-md-10">
      <div id="konfirmasi_finger_print_rj">
          <div class="center">
            <label class="" style="font-size: 12px; font-weight: bold;">Nomor Kartu BPJS : </label><br>         
            <input type="text" class="form-control" id="noKartuBPJS" style="text-align: left" placeholder="" autocomplete="off" value="<?php echo isset($no_kartu_bpjs)?$no_kartu_bpjs:''?>">
            <div>
              <button type="button" class="center btn btn-sm" id="btnConfirmFp" style="background: green !important; border-color: green; width: 100% !important;margin: 0px !important;margin-top: 5px !important;">
                <span class="ace-icon fa fa-check icon-on-right bigger-110"></span>
                Konfirmasi Finger Print
              </button>
            </div>
          </div>
      </div>
      <br>
      
      <div style="width:100% !important; text-align:left; font-size:12px;color:black;padding-top:5px; font-style: italic">
        Tanggal Kunjungan :<br> <span id='ct6' style=" font-size: 12px;" ></span>
      </div>
      <div id="konfirmasi_finger_print" style="display: none">

        <div style="padding-top: 10px">
          <div id="profile_box" style="display: none; background: azure; padding: 15px">
            <span id="no_rm_txt"></span><br>
            <span id="nama_pasien_txt"></span><br>
            <span id="ttl_txt"></span><br>
            <span id="poli_txt"></span><br>
            <span id="dokter_txt"></span><br>
          </div>
          <br>
          <div id="msg_box"></div>
          
        </div>
        <!-- hidden -->
        <input type="hidden" name="poli" value="" id="poli">
        <input type="hidden" name="nama_dokter" value="" id="nama_dokter">
        <input type="hidden" name="no_mr" value="" id="no_mr">
        <input type="hidden" name="nama_pasien" value="" id="pasien">
        <input type="hidden" name="umur_pasien" value="" id="umur">
        <input type="hidden" name="tgl_lahir" value="" id="tgl_lahir">
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