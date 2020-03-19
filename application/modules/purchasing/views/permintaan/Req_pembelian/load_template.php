<?php
  foreach($template as $row_temp){
    $temp_name = str_replace(' ','-',$row_temp->temp_name);
    echo '<a href="#" onclick="loadDataFromTemplate('."'".$temp_name."'".')" ><span class="badge badge-secondary" style="color: black !important">'.$row_temp->temp_name.'</span></a>';
  }
?>
