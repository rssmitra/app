
<div class="row">
  <div class="col-md-3">
    <span><b>PERIODE</b>, <?php echo $this->tanggal->formatDateDmy($_GET['from_tgl'])?> s/d <?php echo $this->tanggal->formatDateDmy($_GET['to_tgl'])?></span>
    <table class="table">

        <tr>
          <td colspan="2" align="right">
            Total Piutang <br>
            <a href="#" onclick="show_detail('periode')"><span style="font-size: 20px; font-weight: bold">
            <span style="font-size: 18px; font-weight: bold">
              <?php 
                echo number_format($value['piutang']['periode']);
              ?>
            </span>
            </a>
          </td>
        </tr>
    </table>
  </div>

  <div class="col-md-3">
    <span><b>HARIAN</b>,  <?php echo date('d/M/Y')?></span>
    <table class="table">
        <tr>
          <td colspan="2" align="right">
            Total Piutang <br>
            <a href="#" onclick="show_detail('day')"><span style="font-size: 20px; font-weight: bold">
              <?php 
                echo number_format($value['piutang']['day']);
              ?>
            </span>
            </a>
          </td>
        </tr>
    </table>
  </div>

  <div class="col-md-3">
    <span><b>BULANAN, </b> <?php echo $this->tanggal->getBulan(date('m'))?></span>
    <table class="table">
        <tr>
          <td colspan="2" align="right">
            Total Piutang <br>
            <a href="#" onclick="show_detail('month')"><span style="font-size: 20px; font-weight: bold">
              <?php 
                echo number_format($value['piutang']['month']);
              ?>
            </span>
            </a>
          </td>
        </tr>
    </table>
  </div>

  <div class="col-md-3">
    <span><b>TAHUNAN</b>, <?php echo date('Y')?></span>
    <table class="table">
        <tr>
          <td colspan="2" align="right">
            Total Piutang <br>
            <a href="#" onclick="show_detail('year')"><span style="font-size: 20px; font-weight: bold">
              <?php 
                echo number_format($value['piutang']['year']);
              ?>
            </span>
            </a>
          </td>
        </tr>
    </table>
  </div>
</div>


<div class="clearfix" style="margin-top: 20px"></div>

<div id="show_detail_by_click"></div>

