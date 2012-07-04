$(document).ready(function(){

    
    var url = $("#url").val();
    $('#btn_delete').live("click",function() {
       var check = $("input[name='delete[]']:checked");
       if (check.size() > 0) {
           var val = "";
           $.each(check, function() {
               val += $(this).val() + "-";
           });
           $.ajax({
               url: url +'lists/delete',
               type: 'POST',
               data: {'post': val},
               success : function(response) {
                   if (response == 1) {
                      $('.alert-success').show().fadeOut(50000);
                      window.location.href= url + "lists";
//                      $('#div_list').load(url+'lists');
                   }else{
                       $('.alert-error').show().fadeOut(50000);
                   }
               }
           });

       }
    }); 

    $("#btn_search").live("click",function() {
        var type = $('#search_type').val(),query = $('#search_query').val();
        $('#div_list').load(url+'lists/search', {search_type: type, search_query: query});
    });
    
      
});
    