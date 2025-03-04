<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <center>
      <h4>INFORMASI DAFTAR RUANGAN RAWAT INAP<br>
        <small style="font-size:12px">
          (Data yang ditampilkan dibawah ini adalah data pasien rawat inap yang masih dalam perawatan)
        </small>
      </h4>
    </center>

    <div>
      <table class="table">
        <?php foreach($list as $key=>$row) : ?>
        <tr><td style="background: #87b87f; color: black; font-size: 24px; font-weight: bold"><?php echo strtoupper($key); ?></td></tr>
        <tr>
          <td style="padding: 15px">
            <?php foreach ($row as $k => $v) :?>
              <span style="color: black; font-size: 18px; font-weight: bold"><?php echo $k?><br></span>
              <table border="1">
                <?php foreach ($v as $k2 => $v2) :?>
                  <tr>
                    <?php foreach ($v2 as $k3 => $v3) :?>
                      <td style="min-width: 100px">
                        <?php 
                          echo '<div class="center">';
                          echo ($v3->status=='ISI')?'
                            <img src="'.base_url().'assets/images/bed/bed_green.png" width="70px">':'<img src="'.base_url().'assets/images/bed/bed_red.png" width="70px"><br>';
                          echo '</div>';
                          echo '<div><b>'.$v3->no_mr.'</b><br>'.$v3->nama_pasien.'<br>'.$v3->dokter.'</div>'?>
                      </td>
                    <?php endforeach; ?>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                  </tr>
                <?php endforeach; ?>
              </table>
              
            <?php endforeach; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  </div><!-- /.col -->
</div><!-- /.row -->



