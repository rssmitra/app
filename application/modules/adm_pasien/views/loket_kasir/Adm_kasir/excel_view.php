<?php 

  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".'lhk_exp_date_'.date('Ymd').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);
  

?>

<html>
<head>
  <title>Data Transaksi Rawat Jalan RS Setia Mitra</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <center><h4><?php echo $title?></h4></center>
      <b>Parameter :</b> <i><?php echo print_r($_GET);?></i>

      <table class="table">
        <thead>
          <tr>
            <th>NO</th>
            <?php 
              foreach($fields as $key=>$field){
                echo '<th>'.strtoupper($field).'</th>';
            }?>
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; foreach($data as $row_data) : $no++; ?>
            <tr>
              <td align="center"><?php echo $no;?></td>
              <?php 
              foreach($fields as $row_field){
                  $field_name = $row_field;
                  echo '<td>'.(string)strtoupper($row_data[$field_name]).'</td>';
              }?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






