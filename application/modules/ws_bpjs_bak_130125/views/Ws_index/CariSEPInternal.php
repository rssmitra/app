<script>

    $(document).ready(function(){
        
        $('#btn_search_data').click(function (e) {
            
                e.preventDefault();
                $.ajax({
                url: $('#form_search').attr('action'),
                type: "GET",
                data: $('#form_search').serialize(),
                dataType: "json",
                beforeSend: function() {
                    
                },
                success: function(response) {
                    var str = JSON.stringify(response, undefined, 4);
                    var output_highlight = syntaxHighlight(str);
                    console.log(output_highlight);
                    $('#find-result').html('<pre>'+output_highlight+'</pre>');
                }
            });
        });

    })

</script>

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
        <div class="widget-body">
            <div class="widget-main no-padding">

                <form class="form-horizontal" method="post" id="form_search" action="ws_bpjs/Ws_index/find_sep_internal"  enctype="Application/x-www-form-urlencoded" >

                    <div class="col-md-12 no-padding">
                        <p style="font-weight: bold">PENCARIAN SEP INTERNAL</p>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Masukan Nomor SEP</label>
                            <div class="col-md-3">
                                <input type="text" name="nosep" class="form-control" value="" id="nosep">
                            </div>
                            <div class="col-md-3" style="margin-left: -1%">
                                <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
                                    <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                                    Search
                                </a>
                                <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
                                    <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                        <hr class="separator">                    
                        <div id="find-result"></div>

                    </div>
                        
                    

                </form>

            </div>
        </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


