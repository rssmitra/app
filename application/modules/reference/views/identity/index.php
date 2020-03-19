<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">

    <!-- PAGE CONTENT BEGINS -->
   
    <div class="row">
      <div class="col-xs-12">

        
        <br>
        <div class="row">

            <div class="col-xs-12 col-sm-12 pricing-box">

                <div class="col-sm-12">
                  <?php foreach($menu as $rsm) : ?>
                  <div class="col-sm-3">
                    <center>
                      <a href="#" onclick="getMenu('<?php echo $rsm->link?>')" style="text-decoration: none">
                        <div class="well well-lg">
                          <h2><i class="<?php echo $rsm->icon?> bigger-250"></i></h2>
                          <h4 class="blue"><?php echo $rsm->name?></h4>
                          <?php echo $rsm->description?>
                      </div>
                      </a>
                    </center>
                  </div>
                <?php endforeach; ?>
                
                </div><!-- /.col -->

            </div>

            <!-- /section:pages/pricing.large -->
          </div>

      </div>
    </div>

  <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php //echo base_url().'assets/js/custom/form_wks.js'?>"></script>
