<script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
<style>
    input, textarea{
        border: 0px !important;
    }
</style>
<script type="text/javascript">

$(document).ready(function() {
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: <?php echo $cppt_id?>} , function (response) {    
    // show data
    var obj = response.result;
    // set value input
    var value_form = response.value_form;
    $.each(value_form, function(i, item) {
      var text = item;
      text = text.replace(/\+/g, ' ');
      $('#'+i).val(text);
    });
  }); 
});
</script>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
        <div class="widget-body">
            <table border="0" style="padding: 10px">
                <tr>
                    <td colspan="2">
                        <br>
                        <?php echo $html_content;?>
                        <br>
                    </td>
                </tr>
            </table>
        </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->




