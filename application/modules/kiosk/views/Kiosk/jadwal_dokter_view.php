<script type="text/javascript">
  $(document).ready(function() {

  //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "paging": false,
      "searching": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "Kiosk/get_data_jadwal_dokter?kode=<?php echo isset($_GET['kode'])?$_GET['kode']:''?>",
          "type": "POST"
      },

    });

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );


  });


  function setAppointment(jd_id){
    $('#globalModalView').toggle('close');
    getMenu('Kiosk/form_perjanjian/'+jd_id+'');
  }

</script>
<style type="text/css">
    table{
      width: 100% !important;
      font-size: 12px;
    }
    .table-custom thead {
      background-color: #eeeeee;
      color: black !important;
      font-size: 14px;
    }

    .table-custom th, td {
      padding: 5px;
      border: 1px solid #c5d0dc;
    }
    .table-custom tbody tr:hover {background-color: #e6e6e6e0;}
    .accordion-style1.panel-group .panel-heading .accordion-toggle {font-size: 18px !important}
</style>

<div>
  <p style="text-align: center; font-size: 2.5em; font-weight: bold"><?php echo strtoupper($nama_bagian)?></p>

  <hr class="separator">

</div>

<div id="faq-list-3" class="panel-group accordion-style1 accordion-style2">
    <?php foreach ($result as $key => $value) : ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <a href="#faq-<?php echo $key?>" data-parent="#faq-list-3" data-toggle="collapse" class="accordion-toggle collapsed">
          <i class="ace-icon fa fa-plus smaller-80" data-icon-hide="ace-icon fa fa-minus" data-icon-show="ace-icon fa fa-plus"></i>&nbsp;
          <?php echo strtoupper($value['nama_dr'])?>
        </a>
      </div>

      <div class="panel-collapse collapse" id="faq-<?php echo $key?>">
        <div class="panel-body">
          <div style="padding: 10px" class="center">
            <p style="font-size: 18px; font-weight: bold">JADWAL PRAKTEK DOKTER <?php echo strtoupper($value['nama_dr'])?></p>
            <table style="font-size: 18px" class="table">
              <tr>
                <?php foreach ($value as $k => $v) : if($k != 'nama_dr') :?>
                  <td><?php echo strtoupper($k);?></td>
                <?php endif; endforeach; ?>
              </tr>
              <tr>
                <?php foreach ($value as $k => $v) : if($k != 'nama_dr') : ?>
                  <td style="font-size: 20px"><?php echo $value[$k]['time']?><br><a href="#" class="btn btn-lg" style="background: green !important; border-color: green; margin-top: 10px" onclick="getMenu('kiosk/Kiosk/form_perjanjian/<?php echo $value[$k]['jd_id']?>')">Pilih</a></td>
                <?php endif; endforeach; ?>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

  </div>






