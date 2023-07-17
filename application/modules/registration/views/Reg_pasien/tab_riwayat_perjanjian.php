<script type="text/javascript">

  $(document).ready(function(){


  });


</script>

<form class="form-horizontal" method="post" id="form_search" action="registration/Perjanjian_rj/find_data">

  <div class="form-group">
                  
    <label class="control-label col-sm-4">Jenis Perjanjian</label>

    <div class="col-md-8">

      <div class="radio">

          <label>

            <input name="flag" type="radio" class="ace" value="NULL" checked />

            <span class="lbl"> Rawat Jalan</span>

          </label>

          <label>

            <input name="flag" type="radio" class="ace" value="bedah" />

            <span class="lbl"> Bedah</span>

          </label>

          <label>

            <input name="flag" type="radio" class="ace" value="HD" />

            <span class="lbl"> Hemodialisa</span>

          </label>

      </div>

    </div>

  </div>

<hr class="separator">
<!-- div.dataTables_borderWrap -->
<div style="margin-top:-27px">
  <table id="riwayat-table" base-url="registration/Perjanjian_rj/get_data?no_mr=<?php echo $no_mr?>" class="table table-bordered table-hover">
    <thead>
    <tr>  
      <th width="30px" class="center"></th>
      <th></th>
      <th>Deskripsi</th>
      
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>

</form>
