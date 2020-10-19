<!-- css default for blank page -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
<script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>
<!-- css default for blank page -->

      <!-- #section:basics/content.breadcrumbs -->
      <?php
        $arr_color_breadcrumbs = array('#076960');
        shuffle($arr_color_breadcrumbs);
      ?>
      
      <div class="page-content-main">
        
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <!-- MODULE MENU -->
            
              <?php 
                foreach($modul as $key_row=>$rows_m) :
                  $arr_color[$key_row] = array('bg-red','bg-yellow','bg-aqua','bg-blue','bg-light-blue','bg-green','bg-navy','bg-teal','bg-olive','bg-lime','bg-orange','bg-fuchsia','bg-purple','bg-maroon','bg-black'); 
                  $arr_color_title = array('green'); 
                shuffle($arr_color[$key_row]);
                shuffle($arr_color_title);

              ?>
                <div class="row">
                <h3 class="header smaller lighter <?php echo array_shift($arr_color_title)?>">
                    <?php echo $rows_m['group_modul_name']?> 
                  </h3>
                  <?php foreach($rows_m['modul'] as $row_modul) : ?>
                    <div class="col-lg-2 col-xs-6" style="margin-top:-10px">
                      <!-- small box -->
                      <div class="small-box <?php echo array_shift($arr_color[$key_row])?>">
                        <div class="inner">
                          <h3 style="font-size:16px"><?php echo strtoupper($row_modul->name)?></h3>
                          <p style="font-size:12px"><?php echo $row_modul->title?></p>
                        </div>
                        <div class="icon" style="margin-top:-10px">
                          <i class="<?php echo $row_modul->icon?>"></i>
                        </div>
                        <?php 
                          if($row_modul->is_new_tab=='N'){
                            echo '<a href="'.base_url().'dashboard?mod='.$row_modul->modul_id.'" class="small-box-footer">Masuk ke modul  <i class="fa fa-arrow-circle-right"></i></a>';
                          }else{
                            echo '<a href="'.$row_modul->link_on_new_tab.'" target="_blank" class="small-box-footer">Masuk ke modul  <i class="fa fa-arrow-circle-right"></i></a>';
                          }
                        ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endforeach; ?>
            <!-- END MODULE MENU -->

            <!-- PAGE CONTENT ENDS -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.page-content -->

<!-- basic scripts -->
