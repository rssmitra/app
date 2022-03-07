<html>
  <head>
    <title><?php echo isset($result->csm_rp_no_sep)?$result->csm_rp_no_sep:'Update Dokumen Klaim'?></title>
    <meta http-equiv="refresh" content="5" />
    <script type="text/javascript">
      window.open('<?php echo isset($redirect)?$redirect:'#'?>', '_blank');
    </script>
  </head>
  <body>
    <?php
      echo '<pre>'; 
      if(isset($result)){
        print_r($result);
        '<script>window.open("'."'".$redirect."'".'","_blank)</script>';
      }else{
        echo 'Tidak ada data ditemukan';
      }
    ?>
  </body>
</html>