<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					Antrian Pendaftaran Pasien
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						Sistem Antrian RS Setia Mitra
					</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<div class="col-xs-12 widget-container-col ui-sortable no-padding" style="padding-right: 20px" id="widget-container-col-1">
						<?php for($i=1;$i<5;$i++) : ?>
	                      	<div class="alert alert-success" style="background-image: linear-gradient(#00cc00, #004d00);color:white;border-radius:5px;font-weight: bold">
	                      	Loket <?php echo $i?>
		                      <!-- <div class="text-no" style="font-size: 2em; width:10%;float:left;border-right:2px solid white; margin-right: 20px; vertical-align: top">
		                        <span></span>
		                      </div> -->
		                      <div class="nama-pasien-antrian" id="loket-ke-<?php echo $i?>" style="font-size: 2em">-</div> 
	                    	</div>
	                    <?php endfor; ?>
                  	</div>
                  	<hr class="doted">
                  	<br>
                  	<br>
                  	<br>
                  	<p class="center" style="font-size: 2em; font-weight: bold">JADWAL PRAKTEK DOKTER HARI INI - <?php echo date('D, d/m/Y')?></p>
                  	<div class="col-xs-12 no-padding">
                      <b><span class="label label-success">&nbsp;</span> Loket dibuka | <span class="label label-warning">&nbsp;</span> Loket ditutup </b>
                  		<table class="table" style="color: black !important; font-size: 14px">
                  			<thead>
                  				<tr>
                  					<th class="center">No</th>
                  					<th>Poli/KLinik Spesialis</th><!-- 
                  					<th>Nama Dokter</th>
                  					<th>Jam Praktek</th>
                  					<th class="hidden-480">Sisa Kuota</th> -->
                  					<th class="hidden-480">Keterangan</th>
                  				</tr>
                  			</thead>
                  			<tbody>
                  				<?php 
                  					$no=0; foreach($list_jadwal as $row) : $no++;
                  					$sisa_kuota = $row->jd_kuota - $row->kuota_terpenuhi;
						            $clr_loket = ($row->status_jadwal == 'Loket dibuka') ? 'background: linear-gradient(357deg, #13d634, #9edc9a);color: black;' : 'background: linear-gradient(357deg, #ecd212, #dcd79a);color: black;';
						            $status_jadwal = '';
						            if(!in_array($row->status_jadwal, array('Loket dibuka','Loket ditutup') )){
						                $status_jadwal = $row->status_jadwal.'<br>';
						            }
                  				?>
                  				<tr style="<?php echo $clr_loket?>;">
                  					<td class="center"><?php echo $no; ?></td>
                  					<td>
                              <b><?php echo strtoupper($row->nama_bagian)?></b><br>
                              <b><?php echo $row->nama_pegawai?></b><br>
                              Jam Praktek : <?php echo $this->tanggal->formatTime($row->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row->jd_jam_selesai)?> <br>
                              Sisa Kuota : <?php echo $sisa_kuota; ?>
                            </td>
                  					<!-- <td><?php echo $row->nama_pegawai?></td>
                  					<td><?php echo $this->tanggal->formatTime($row->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row->jd_jam_selesai)?></td>
                  					<td class="hidden-480 center"><?php echo $sisa_kuota; ?></td> -->
                  					<td class="hidden-480"><?php echo strtoupper($status_jadwal).''.strtoupper($row->jd_keterangan).'<br>'.strtoupper($row->keterangan)?></td>

                  				</tr>
                  			<?php endforeach; ?>
                  			</tbody>
                  		</table>
                  	</div>


					<!-- PAGE CONTENT ENDS -->
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->

<script>
  $(document).ready( function(){

    setInterval( function () {
    
      $.getJSON("<?php echo site_url('display_antrian/process') ?>", '', function (data) {              
        console.log(data)
        $('.nama-pasien-antrian span').remove();

        $.each(data, function (i, o) {    
           //console.log(data);
          if(o!=0){
            no =  pad(o.ant_no, 3);
            type = (o.ant_type=='bpjs')?'A':(o.ant_type=='umum')?'B':'C';

            if( o.ant_no != 0 ){
              $('<span>'+type+' '+no+'</span>').appendTo($('#loket-ke-'+i+''));
            }
                   
          }

        });

      });

    }, 2000 );

    $('#jadwal-praktek-dr').load("<?php echo base_url()?>/display_loket/main/antrian_pendaftaran_dt_tbl");
  
  });
  
  
  function pad (str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
  }
</script>