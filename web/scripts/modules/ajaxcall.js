define(['jquery','modules/clipboardcopy'],function($,copy ){

   return {
      'get_cp': function(address,page){

              var page=parseInt(page);
              $.ajax({
                  url: AJAX_URLS.home,
                  type: 'POST',
                  data: {address: address,page:page},
                  success: function (response){

                    if(copy.can_copy()){
                          var result="<p class='info'>!!! Click pe <b>codul postal</b> pentru copiere automata</p><ol class='rounded-list'>";
                      }else{
                          var result="<ol class='rounded-list'>";
                      }
                     // var result="<ol class='rounded-list'>";


                      //var values=JSON.parse(response);
                      var values=response;
                      var length = values.length;
                      var total_results;
                      for(var i=0; i<length;i++)
                      {
                          if(typeof(values[i]['total_results'])!='undefined'){
                              total_results=values[i]['total_results'];
                          }else{
                              result+='<li>'+"<input type='text' class='cp' data-clipboard-text='"+values[i]['codpostal']+"' value='"+values[i]['codpostal'].trim()+"'</><span class='address'>"+values[i]['tip_artera']+"&nbsp"+values[i]['denumire_artera']+"&nbsp;"+values[i]['numar']+ " "+values[i]['localitate']+"-"+values[i]['judet']+ "</span></li>";

                          }

                      }
                      result+='</ol>';
                     
                      //show pagination if results are greather than PARAMS.cp_per_page
                      if(total_results>PARAMS.cp_per_page){

                          last_page=Math.ceil(total_results/PARAMS.cp_per_page);

                          if(page*PARAMS.cp_per_page<total_results){

                              next_page=page+1;
                          } else{
                              next_page=0;
                          }
                          if(page-1>0){
                              prev_page=page-1;
                          }else{
                              prev_page=0;
                          }

                          $("#ajax-result").html(result).append("<div class='pagination-container'><a class='cp_pagination' data-page='"+prev_page+"' href='#'>Pagina anterioara</a> Pagina "+page+" din "+last_page+"<a class='cp_pagination' data-page='"+next_page+"' href='#'>Pagina urmatoare</a></div>");

                      }else{
                          $("#ajax-result").html(result);
                      }


                  },
                  complete:function(data)
                  {
                      //alert(copy.can_copy());
                      copy.load();
                  },
                  error:function()
                  {
                      alert("Eroare de sistem.Te rugam incearca mai tarziu")
                  }

              });
          }
      }


});