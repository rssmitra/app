<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Antrian</title>

    <meta name="description" content="top menu &amp; navigation" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <script src='<?php echo base_url()?>/assets/js/jquery.js'></script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

    <!-- css default for blank page -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
    <script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>
    <!-- css default for blank page -->
    <!-- Favicon -->

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery-ui.custom.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery.gritter.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/select2.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-editable.css" />
   
  </head>

  <body class="no-skin">
    <!-- #section:basics/navbar.layout -->
   

    <!-- /section:basics/navbar.layout -->
    <div class="main-container" id="main-container">
      <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
      </script>

      <!-- /section:basics/sidebar.horizontal -->
      <div class="main-content">
        <div class="main-content-inner">
          <!-- #section:basics/content.breadcrumbs -->
          
          <div class="page-content">
            
          <a href="#" id="btn_call" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Panggil
          </a>
            
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->



    </div><!-- /.main-container -->


   <!-- MODAL -->

<div id="modalAntrian" class="modal fade" tabindex="-1">

<div class="modal-dialog" style="max-height:90%;  margin-top: 50px; margin-bottom:50px;width:50%">

  <div class="modal-content">

    <div class="modal-header no-padding">

      <div class="table-header" style="text-align:center">

        <h1 style="margin:0 !important">Nomor Antrian</h1>
          
      </div>

    </div>
    

    <div class="modal-body">

      <div class='w3-modal' style='display: block;'>
      <center>
      <div class='w3-modal-content w3-animate-zoom'>
        
        <div class='w3-container'>
          <h1 style='font-size:500%;color:black;'><b>B <span id="no_modal"></span></b></h1>
          <h2 style='color:black;'>UMUM </h2> 
        </div>
      </div>

      <a href="#" id="btn_play" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-volume-up  icon-on-right bigger-110"></i>
            Panggil Audio
          </a>
          <audio id="container" autoplay=""></audio>

        <a href="#" id="btn_clear" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-check icon-on-right bigger-110"></i>
            Selesai
          </a>

        <a href="#" id="btn_skip" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-step-forward icon-on-right bigger-110"></i>
            Lewati
          </a>

      </center>
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
      
  <script>
  
   var loket = 1;
   var type = "umum";

  $('#btn_call').click(function (e) {      

    e.preventDefault();      
      
      //console.log(loket);
      //console.log(type);

      data = [];
      data[0] = loket;
      data[1] = type;

      $.ajax({
        url:"<?php echo base_url(); ?>counter/process",
        data:{data:data},
        dataType: "json", 
        type:"POST",       
        success:function (data) {
          console.log(data)
         
          no = pad(data['no'], 3);

          // $('#klinik_modal').text(data['klinik']);
          // $('#dokter_modal').text(data['dokter']);
           $('#no_modal').text(no);

           $("#modalAntrian").modal();  

          // openWin(no,data['klinik'],data['dokter'],data['type'],data['jam_praktek']);
          // setTimeout(function () { window.location.href = "<?php echo base_url(); ?>"; }, 2000);
        }
    });
      
  });  


  $('#btn_clear').click(function (e) {      

    e.preventDefault();  
    
      data = [];
      data[0] = loket;
      data[1] = type;
      data[2] = $('#no_modal').text();

      $.ajax({
        url:"<?php echo base_url(); ?>counter/process_selesai",
        data:{data:data},
        dataType: "json", 
        type:"POST",       
        success:function (data) {
          console.log(data)
         
           $("#modalAntrian").modal('hide');  

          // openWin(no,data['klinik'],data['dokter'],data['type'],data['jam_praktek']);
          // setTimeout(function () { window.location.href = "<?php echo base_url(); ?>"; }, 2000);
        }
    });  

  });  

  $('#btn_skip').click(function (e) {      

    e.preventDefault();  
  
      data = [];
      data[0] = loket;
      data[1] = type;
      data[2] = $('#no_modal').text();

      $.ajax({
        url:"<?php echo base_url(); ?>counter/process_skip",
        data:{data:data},
        dataType: "json", 
        type:"POST",       
        success:function (data) {
          console.log(data)
        
          $("#modalAntrian").modal('hide');  

          // openWin(no,data['klinik'],data['dokter'],data['type'],data['jam_praktek']);
          // setTimeout(function () { window.location.href = "<?php echo base_url(); ?>"; }, 2000);
        }
    });  

  });  

  $('#btn_play').click(function (e) {      

    e.preventDefault();  

    num=parseInt($('#no_modal').text());
    console.log(num)

    playAudio(num,loket,type)
     
  });  
    

  function pad (str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
  }

  function playAudio(num,loket,type) { 
  var num1 = num;
    (function() {
      var numstring = num.toString();
      var res = numstring.split("");
      var numlenght = numstring.length;
      var Mp3Queue = function(container, files) {
        var index = 1;
        var nextindex;
        if(!container || !container.tagName || container.tagName !== 'AUDIO')throw 'Invalid container';
        if(!files || !files.length)throw 'Invalid files array';        
        var playNext = function() {
          // panggil nomor urut 1 sampai 9
          if (numlenght == 1) {
            if(index < files.length) {
              container.src = files[index];
              index += 1;
              if (index == 3) {
                if (num == 1){
                  index = 3;
                } else if (num == 2){
                  index = 4;
                } else if (num == 3){
                  index = 5;
                } else if (num == 4){
                  index = 6;
                } else if (num == 5){
                  index = 7;
                } else if (num == 6){
                  index = 8;
                } else if (num == 7){
                  index = 9;
                } else if (num == 8){
                  index = 10;
                } else if (num == 9){
                  index = 11;
                }
              } else if (index > 3) {
                if (num > 0){
                  index = 17;	
                }
                num = 0;
              }
            } else {
              container.removeEventListener('ended', playNext, false);
            }
          // panggil nomor urut 10 sampai 99
          } else if (numlenght == 2) {
            if(index < files.length) {
              container.src = files[index];
              index += 1;
              if (index == 3) {
                if (num == 10){
                  index = 12;
                  num = -1;
                } else if (num == 11){
                  index = 13;
                  num = -1;
                } else if (num >= 12 && num <=19){
                  if (res[1] == 2) {
                    index = 4;
                  } else if (res[1] == 3) {
                    index = 5;
                  } else if (res[1] == 4) {
                    index = 6;
                  } else if (res[1] == 5) {
                    index = 7;
                  } else if (res[1] == 6) {
                    index = 8;
                  } else if (res[1] == 7) {
                    index = 9;
                  } else if (res[1] == 8) {
                    index = 10;
                  } else if (res[1] == 9) {
                    index = 11;
                  }
                } else if (num >= 20 ){
                  if (res[1] == 0) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    } 
                  } else if (res[1] == 1) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 2) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 3) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 4) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 5) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 6) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 7) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 8) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  } else if (res[1] == 9) {
                    if (res[0] == 2){
                      index = 4;
                    } else if (res[0] == 3){
                      index = 5;
                    } else if (res[0] == 4){
                      index = 6;
                    } else if (res[0] == 5){
                      index = 7;
                    } else if (res[0] == 6){
                      index = 8;
                    } else if (res[0] == 7){
                      index = 9;
                    } else if (res[0] == 8){
                      index = 10;
                    } else if (res[0] == 9){
                      index = 11;
                    }
                  }
                }
              } else if (index > 3) {
                if (num >= 12 && num <= 19){
                  index = 14;
                  num = -1;
                } else if (num >= 20){
                  if (res[1] == 0){
                    index = 15;
                    num = -1;
                  } else {
                    index = 15;
                    num = -2;
                  }
                } else if (num == -2){
                  if (res[1] == 1){
                    index = 3;	
                  } else if (res[1] == 2){
                    index = 4;
                  } else if (res[1] == 3){
                    index = 5;
                  } else if (res[1] == 4){
                    index = 6;
                  } else if (res[1] == 5){
                    index = 7;
                  } else if (res[1] == 6){
                    index = 8;
                  } else if (res[1] == 7){
                    index = 9;
                  } else if (res[1] == 8){
                    index = 10;
                  } else if (res[1] == 9){
                    index = 11;
                  } 
                  num = -1;
                } else if (num == -1){
                  index = 17;	
                  num = 0;
                }
              }
            } else {
              container.removeEventListener('ended', playNext, false);
            }
          // panggil nomor urut 100 sampai 999
          } else if (numlenght == 3) {
            if(index < files.length) {
              container.src = files[index];
              index += 1;
              if (index == 3) {
                index = 16;
              } else if (index > 3) {
                if (num == 100){
                  num == -1
                } else if (num > 100){
                  if (res[1] == 0){
                    if (res[2] == 1){
                      index = 3;
                    } else if (res[2] == 2){
                      index = 4;
                    } else if (res[2] == 3){
                      index = 5;
                    } else if (res[2] == 4){
                      index = 6;
                    } else if (res[2] == 5){
                      index = 7;
                    } else if (res[2] == 6){
                      index = 8;
                    } else if (res[2] == 7){
                      index = 9;
                    } else if (res[2] == 8){
                      index = 10;
                    } else if (res[2] == 9){
                      index = 11;
                    } 
                    num = -1;
                  } else if (res[1] == 1){
                    if (res[2] == 0){
                      index = 12;
                      num = -1;
                    } else if (res[2] == 1){
                      index = 13;
                      num = -1;
                    } else if (res[2] >= 2){
                      if (res[2] == 2){
                        index = 4;
                      } else if (res[2] == 3){
                        index = 5;
                      } else if (res[2] == 4){
                        index = 6;
                      } else if (res[2] == 5){
                        index = 7;
                      } else if (res[2] == 6){
                        index = 8;
                      } else if (res[2] == 7){
                        index = 9;
                      } else if (res[2] == 8){
                        index = 10;
                      } else if (res[2] == 9){
                        index = 11;
                      }
                      num = -2;
                    }
                  } else if (res[1] >= 2){
                    if (res[2] == 0){
                      if (res[1] == 2){
                        index = 4;
                      } else if (res[1] == 3){
                        index = 5;
                      } else if (res[1] == 4){
                        index = 5;
                      } else if (res[1] == 5){
                        index = 5;
                      } else if (res[1] == 6){
                        index = 5;
                      } else if (res[1] == 7){
                        index = 5;
                      } else if (res[1] == 8){
                        index = 5;
                      } else if (res[1] == 9){
                        index = 5;
                      }
                      num = -3;
                    } else if (res[2] >= 1){
                      if (res[1] == 2){
                        index = 4;
                      } else if (res[1] == 3){
                        index = 5;
                      } else if (res[1] == 4){
                        index = 6;
                      } else if (res[1] == 5){
                        index = 7;
                      } else if (res[1] == 6){
                        index = 8;
                      } else if (res[1] == 7){
                        index = 9;
                      } else if (res[1] == 8){
                        index = 10;
                      } else if (res[1] == 9){
                        index = 11;
                      }
                      num = -4;
                    }
                    
                  }
                } else if (num == -1){
                  index = 17;	// langsung ke loket
                  num = 0;
                } else if (num == -2){
                  index = 14;	// untuk belasan
                  num = -1;
                } else if (num == -3){
                  index = 15;	// untuk puluhan
                  num = -1;
                } else if (num == -4){
                  index = 15;	// untuk puluhan
                  num = -5;
                } else if (num == -5){
                  if (res[2] == 1){
                    index = 3;	// untuk puluhan
                  } else if (res[2] == 2){
                    index = 4;
                  } else if (res[2] == 3){
                    index = 5;
                  } else if (res[2] == 4){
                    index = 6;
                  } else if (res[2] == 5){
                    index = 7;
                  } else if (res[2] == 6){
                    index = 8;
                  } else if (res[2] == 7){
                    index = 9;
                  } else if (res[2] == 8){
                    index = 10;
                  } else if (res[2] == 9){
                    index = 11;
                  }
                  num = -1;
                }
              }
              
            } else {
              container.removeEventListener('ended', playNext, false);
            }
          }
        };
        container.addEventListener('ended', playNext);
        container.src = files[0];
      };
      var container = document.getElementById('container');			
      if(type=='bpjs'){
        new Mp3Queue(container, [
          'assets/suara/ding.mp3',		// 0
          'assets/suara/nomor-urut.wav',	// 1
          'assets/suara/a.mp3',			// 2
          'assets/suara/satu.wav',		// 3
          'assets/suara/dua.wav',		// 4
          'assets/suara/tiga.wav',		// 5
          'assets/suara/empat.wav',		// 6
          'assets/suara/lima.wav',		// 7
          'assets/suara/enam.wav',		// 8
          'assets/suara/tujuh.wav',		// 9
          'assets/suara/delapan.wav',	// 10
          'assets/suara/sembilan.wav',	// 11
          'assets/suara/sepuluh.wav',	// 12
          'assets/suara/sebelas.wav',	// 13
          'assets/suara/belas.wav',		// 14
          'assets/suara/puluh.wav',		// 15
          'assets/suara/seratus.wav',	// 16
          'assets/suara/loket.wav',		// 17
          'assets/suara/satu.wav'		// 18
          
        ]);
      } else {
        new Mp3Queue(container, [
          'assets/suara/ding.mp3',		// 0
          'assets/suara/nomor-urut.wav',	// 1
          'assets/suara/b.mp3',			// 2
          'assets/suara/satu.wav',		// 3
          'assets/suara/dua.wav',		// 4
          'assets/suara/tiga.wav',		// 5
          'assets/suara/empat.wav',		// 6
          'assets/suara/lima.wav',		// 7
          'assets/suara/enam.wav',		// 8
          'assets/suara/tujuh.wav',		// 9
          'assets/suara/delapan.wav',	// 10
          'assets/suara/sembilan.wav',	// 11
          'assets/suara/sepuluh.wav',	// 12
          'assets/suara/sebelas.wav',	// 13
          'assets/suara/belas.wav',		// 14
          'assets/suara/puluh.wav',		// 15
          'assets/suara/seratus.wav',	// 16
          'assets/suara/loket.wav',		// 17
          'assets/suara/satu.wav'		// 18
          
        ]);
      }
    })();
  }


  </script>

    
  </body>
</html>

























