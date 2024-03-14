<html>
<head>
  <title>Laporan</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  <script>
	$("body").on('click','.toggle-password',function(){
		$(this).toggleClass("fa-eye fa-eye-slash");

		var input = $("#password").attr("type");

		if (input.attr("type") === "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});
   </script>

</head>
<body>
  <div class="row">
    <div class="col-xs-12">

      <div class="col-md-12">

        <!-- content -->
        <h4>Masukan Akun Anda</h4>
        <?php if(isset($_GET['login']) AND $_GET['login'] == false) :?>
          <span>Username dan password anda salah!</span>
        <?php endif;?>
        <form class="form-horizontal" method="post" id="form_login" action="<?php echo base_url()?>lapi/auth">
          <div class="form-group">
            <label class="control-label col-md-1">Username</label>
            <div class="col-md-2">
            <input class="form-control" name="username" id="username" type="text" value=""/>
            </div>
            <label class="control-label col-md-1">Password</label>
            <div class="col-md-2">
            <input class="form-control" name="password" id="password" type="password" value=""/>
            <span toggle="#password-field" class="fa fa-fw fa-eye toggle-password"></span>
            </div>
            <div class="col-md-1">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-primary">
                <i class="fa fa-key"></i> Login
              </button>
            </div>
          </div>
		  
        </form>
        <!-- end content -->
        
     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






