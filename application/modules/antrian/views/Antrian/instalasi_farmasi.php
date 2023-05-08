<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					Antrian Instalasi Farmasi
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						Sistem Antrian <?php echo COMP_LONG; ?>
					</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<div class="col-xs-12 widget-container-col ui-sortable no-padding" style="padding-right: 20px" id="widget-container-col-1">
            <?php for($i=1;$i<6;$i++) : ?>
              	<div class="alert alert-success" style="background-image: linear-gradient(#00cc00, #004d00);color:white;border-radius:5px;font-weight: bold">
                <div class="nama-pasien-antrian-small" id="antrian-ke-<?php echo $i;?>" style="text-align: left"></div>
            	</div> 
            <?php endfor; ?>
        	</div>

          <div class="col-xs-12 widget-container-col ui-sortable no-padding" id="widget-container-col-1">
            <?php for($i=6;$i<12;$i++) :?>
              <div class="alert alert-success" style="background-image: linear-gradient(#bbf75a, #9be820d9); color: black !important; font-weight: bold">
                <div class="nama-pasien-antrian-small" id="antrian-ke-<?php echo $i;?>" style="text-align: left"></div>
              </div>
            <?php endfor; ?>
          </div>
        	<hr class="separator">
          
        	<!-- <div class="col-xs-12 no-padding">
        		
        	</div> -->


					<!-- PAGE CONTENT ENDS -->
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->
<script>
  $(document).ready( function(){

    setInterval( function () {
    
      $.getJSON("<?php echo site_url('display_antrian/reload_antrian_farmasi') ?>", '', function (data) {              
        console.log(data)
        $('.nama-pasien-antrian span').remove();
        $('.nama-pasien-antrian-small span').remove();

        $.each(data, function (i, o) {    
           console.log(data);
           if (i < 6) {
            $('<span style="font-size: 1.5em">'+o.nama_pasien.substr(0,20)+'</span>').appendTo($('#antrian-ke-'+i+''));
           }

           if (i > 5) {
            $('<span>'+o.nama_pasien.substr(0,25)+'</span>').appendTo($('#antrian-ke-'+i+''));
           }

        });

      });

    }, 3000 );
  
  });
  
  
  setInterval("my_function();",4000); 

  function my_function(){
    $('#refresh').load(location.href + ' #time');
  }
</script>