<!-- css default for blank page -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" class="ace-main-stylesheet" id="main-ace-style" />
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
                  $arr_color_title = array('blue'); 
                shuffle($arr_color[$key_row]);
                shuffle($arr_color_title);

              ?>
                <div class="row">
                <h3 class="header smaller lighter <?php echo array_shift($arr_color_title)?>" style="font-weight: bold">
                    <?php echo $rows_m['group_modul_name']?> 
                  </h3>
                  <?php foreach($rows_m['modul'] as $row_modul) : ?>

                    <?php 
                      if($row_modul->is_new_tab=='N'){
                        $href = 'href="'.base_url().'dashboard?mod='.$row_modul->modul_id.'"';
                      }else{
                        $href = 'href="'.$row_modul->link_on_new_tab.'" target="_blank"';
                      }
                    ?>

                    <div class="col-sm-2 widget-container-col ui-sortable" id="widget-container-col-11">
                      <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
                        <div class="widget-body" style="background: #137cc1; border-top-right-radius: 25px; border-bottom-right-radius: 25px; border-bottom-left-radius: 25px">
                          <div class="widget-main" data-size="125" style="position: relative;">
                            <div  style="cursor:pointer">
                              <a <?php echo $href?> style="text-decoration: none" class="small-box-footer">
                                <div class="content">
                                  <div class="center">
                                    <span style="color: white; font-weight: bold"><i class="<?php echo $row_modul->icon?> bigger-300"></i><br><?php echo strtoupper($row_modul->name)?></span>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                        </div>
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
