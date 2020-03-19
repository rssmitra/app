$(document).ready(function () {

      $('#inputKeyPoli').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "templates/references/getPoli",
                  data: { keyword:query},            
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
            console.log(val_item);
            $('#kodePoliHidden').val(val_item);

            $.getJSON("Templates/References/getDokterBySpesialis/" + val_item, '', function (data) {
                $('#kode_dokter option').remove();
                $('<option value="">-Pilih Dokter-</option>').appendTo($('#kode_dokter'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#kode_dokter'));
                });

            });

          }
      });

      $('#form_daftar_perjanjian').ajaxForm({
          beforeSend: function() {
            achtungShowLoader();  
          },
          uploadProgress: function(event, position, total, percentComplete) {
          },
          complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);

            if(jsonResponse.status === 200){
              $.achtung({message: jsonResponse.message, timeout:5});
              $('#page-area-content').load('setting/Tmp_mst_function?_=' + (new Date()).getTime());
            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }
        }); 




  });

jQuery(function($) {


  $('#timepicker1').timepicker({
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
  
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });


});
