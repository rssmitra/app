<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript">
  $('#keyword_spesialis').typeahead({
      source: function (query, result) {
              $.ajax({
                  url: "templates/references/getSpesialis",
                  data: 'keyword=' + query,         
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                  }
              });
          },
          afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          var label_item=item.split(':')[1];
          console.log(val_item);
          $('#keyword_spesialis').val(label_item);
          $('#keyword_spesialis_id_hidden').val(val_item);
          
      }
  });

  function searchItem(){
    $('#search-result').load('Kiosk/jadwal_dokter_front?kode='+$('#keyword_spesialis_id_hidden').val()+'');
  }

  $( "#keyword_spesialis" )    
    .keypress(function(event) {        
      var keycode =(event.keyCode?event.keyCode:event.which);         
      if(keycode ==13){          
        event.preventDefault();          
        if($(this).valid()){            
          $('#btn-search-data').click();            
        }          
        return false;                 
      }        
  });

</script>

<style type="text/css">
    table{
      width: 100% !important;
      font-size: 12px;
    }
    .table-custom thead {
      background-color: #14506b;
      color: white;
    }

    .table-custom th, td {
      padding: 10px;
      border: 1px solid #c5d0dc;
    }
    .table-custom tbody tr:hover {background-color: #e6e6e6e0;}
    .td_custom{font-size: 20px;font-weight: bold;color: black;border: 5px solid white; cursor: pointer}
    .typeahead{min-width: 87%}
    .dropdown-menu > li > a {font-size: 18px !important}
    .dropdown-menu > li > a {
        padding-bottom: 10px;
        margin-bottom: 3px;
        margin-top: 3px;
    }
</style>

<div class="space-8"></div>

<form class="form-search">
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        
        <label style="font-size: 16px; font-weight: bold">Pencaraian Poli/Klinik Spesialis : </label>
        <div class="input-group input-group-lg">
          <span class="input-group-addon">
            <i class="ace-icon fa fa-check"></i>
          </span>

          <input type="text" class="form-control" id="keyword_spesialis" placeholder="" style="height: 55px !important; font-size: 24px !important; text-transform: uppercase;" autocomplete="off">
          <input type="hidden" class="form-control" id="keyword_spesialis_id_hidden" name="kode_bagian">
          <span class="input-group-btn">
            <button type="button" class="btn btn-lg" id="btn-search-data" onclick="searchItem()" style="height: 55px !important; background: green !important; border-color: green">
              <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
              Search
            </button>
          </span>
        </div>
        <span style="font-size: 12px;font-style: italic;">Masukan keyword, pilih poli/klinik dengan klik "enter"</span>

        <div class="hr"></div>
        
        <div id="search-result">
          <div class="center" style="padding-top: 30px !important">
            <img src="<?php echo base_url()?>assets/images/no-data-found.png" width="200px">
          </div>
        </div>
        

      </div>
      
    </div>
</form>

<div class="center" style="left: 50%; top:50%" >
  <a href="<?php echo base_url().'kiosk'?>" class="btn btn-lg" style="background : green !important; border-color: green; margin-top: 10%" > <i class="fa fa-home"></i> Kembali ke Menu Utama</a>
</div>







