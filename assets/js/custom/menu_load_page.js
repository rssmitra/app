var loading = "<center><img src='"+window.location.origin+"/sirs/app/assets/images/loading.gif' style='width:150px;padding-top:200px;cursor:auto;z-index:15;'/></center>";

$('#message_notification').click(function() {
  $('#message_notification span').remove();
});

$('.delete_attachment').click(function(e){  // ... or however you attach to that link
    e.preventDefault();
    var row = $(this).closest('tr');
    var myid = $(this).attr('data-id');
    //alert(myid);
    // hide this row first
    delete_attachment(myid);
    row.hide();

});

function getMenu(link)
{

    $('#page-area-content').html(loading);
    $('#page-area-content').load(link);

    // $.ajax({
    //     url : link,
    //     type: "POST",
    //     beforeSend: function() {
    //         //achtungShowLoader();
    //         $('#page-area-content').html(loading);
    //       },
    //     error: function(xhr)
    //     { 
    //        $('#page-area-content').html(xhr.responseText);
    //     },
    //     complete: function(xhr) {     
    //         $('#page-area-content').load(link);
    //         //achtungHideLoader();
    //     }
    // });

}

function getMenuTabs(link, tabs_id)
{
    /*$('#content_tab_custom').html(loading);*/
    preventDefault();
    $('#'+tabs_id).html('Loading...');
    $('#'+tabs_id).load(link);
    
    // $.ajax({
    //     url : link,
    //     type: "POST",
    //     beforeSend: function() {
    //         //achtungShowLoader();
    //         $('#'+tabs_id+'').html('Loading...');
    //       },
    //     error: function(xhr)
    //     { 
    //        $('#'+tabs_id).html(xhr.responseText);
    //     },
    //     complete: function(xhr) {    
    //         $('#'+tabs_id).load(link);
    //         //$('#'+tabs_id).html(xhr.html);
    //         //achtungHideLoader();
    //         //$("html, body").animate({ scrollTop: "300px" });
    //     }
    // });

}

function getMenuTabsHtml(link, tabs_id)
{
    /*$('#content_tab_custom').html(loading);*/
    preventDefault();
    $('#'+tabs_id).html('Loading...');
    
    $.ajax({
        url : link,
        type: "POST",
        beforeSend: function() {
            //achtungShowLoader();
          },
        error: function(xhr)
        { 
           $('#'+tabs_id).html(xhr.responseText);
        },
        success: function(xhr) { 
          obj = JSON.parse(xhr);
          //alert(obj.html);
          $('#'+tabs_id).html(obj.html);
        }
    });

}

function delete_attachment_csm(myid){
    if(confirm('Are you sure?')){
      $('#tr_id_'+myid+'').hide();
      preventDefault();
      $.ajax({
          url: 'Templates/Attachment/delete_attachment_csm',
          type: "post",
          data: {ID:myid},
          dataType: "json",
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
              $('#attc_table_id tbody tr #'+myid+'').hide();
              // reload_table();
            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }

        });

    }else{
      return false;
    }

}

function delete_attachment_fr(myid){
  if(confirm('Are you sure?')){
    $('#tr_id_'+myid+'').hide();
    
    $.ajax({
        url: 'Templates/Attachment/delete_attachment_fr',
        type: "post",
        data: {ID:myid},
        dataType: "json",
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
            $('#attc_table_id tbody tr #'+myid+'').hide();
            reload_table();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }

}

function delete_attachment(myid){
  if(confirm('Are you sure?')){
    $('#tr_id_'+myid+'').hide();
    
    $.ajax({
        url: 'Templates/Attachment/delete_attachment',
        type: "post",
        data: {ID:myid},
        dataType: "json",
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
            $('#attc_table_id tbody tr #'+myid+'').hide();
            reload_table();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }

}

