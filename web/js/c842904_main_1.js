var can_copy=true;$(document).ready(function($){$(document).on("click",".pagination",function(e){var page=$(this).attr("data-page");var address=$("#address").val();if(typeof address!="undefined"&&page!=0){get_cp_by_ajax(address,page)}e.preventDefault()});if(browser_support_copy()){$(document).on("click",".cp",function(e){$(this).focus().select();document.execCommand("copy");$(this).blur();$(".cp").each(function(){$(this).css("color","royalblue")});$(this).css("color","red");$().toastmessage("showSuccessToast","Codul postal "+$(this).data("clipboard-text")+" a fost copiat in clipboard")})}else if(browser_support_flash()){$(document).on("copy",".cp",function(e){var clipboard_text=$(this).data("clipboard-text");e.clipboardData.clearData();e.clipboardData.setData("text/plain",clipboard_text);e.preventDefault();$(".cp").each(function(){$(this).css("color","royalblue")});e.target.style.color="red";$().toastmessage("showSuccessToast","Codul postal "+clipboard_text+" a fost copiat in clipboard")})}else{can_copy=false}initialize();google.maps.event.addDomListener(window,"load",initialize);$("#cp_btn").click(function(e){var address=$("#address").val();get_cp_by_ajax(address,1);getGeocode();e.preventDefault()})});function browser_support_flash(){return typeof swfobject!=="undefined"?true:false}function browser_support_copy(){var browser=get_browser_info();return browser.name=="Firefox"&&browser.version>=41||browser.name=="Chrome"&&browser.version>=43?true:false}function get_browser_info(){var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i)||[];if(/trident/i.test(M[1])){tem=/\brv[ :]+(\d+)/g.exec(ua)||[];return{name:"IE",version:tem[1]||""}}if(M[1]==="Chrome"){tem=ua.match(/\bOPR\/(\d+)/);if(tem!=null){return{name:"Opera",version:tem[1]}}}M=M[2]?[M[1],M[2]]:[navigator.appName,navigator.appVersion,"-?"];if((tem=ua.match(/version\/(\d+)/i))!=null){M.splice(1,1,tem[1])}return{name:M[0],version:M[1]}}function get_cp_by_ajax(address,page){var page=parseInt(page);$.ajax({url:AJAX_URLS.home,type:"POST",data:{address:address,page:page},success:function(response){if(can_copy){var result="<p class='info'>!!! Click pe <b>codul postal</b> pentru copiere automata</p><ol class='rounded-list'>"}else{var result="<ol class='rounded-list'>"}var values=response;var length=values.length;var total_results;for(var i=0;i<length;i++){if(typeof values[i]["total_results"]!="undefined"){total_results=values[i]["total_results"]}else{result+="<li>"+"<input type='text' class='cp' data-clipboard-text='"+values[i]["codpostal"]+"' value='"+values[i]["codpostal"].trim()+"'</><span class='address'>"+values[i]["tip_artera"]+"&nbsp"+values[i]["denumire_artera"]+"&nbsp;"+values[i]["numar"]+", "+values[i]["localitate"]+"</span></li>"}}result+="</ol>";last_page=Math.ceil(total_results/PARAMS.cp_amount);if(page*PARAMS.cp_amount<total_results){next_page=page+1}else{next_page=0}if(page-1>0){prev_page=page-1}else{prev_page=0}$("#ajax-result").html(result).append("<div class='pagination-container'><a class='pagination' data-page='"+prev_page+"' href='#'>Pagina anterioara</a> Pagina "+page+"din "+last_page+"<a class='pagination' data-page='"+next_page+"' href='#'>Pagina urmatoare</a></div>")},error:function(){alert("Eroare de sistem.Te rugam incearca mai tarziu")}})}function initialize(){var input=document.getElementById("address");var options={componentRestrictions:{country:"ro"}};var autocomplete=new google.maps.places.Autocomplete(input,options)}function getGeocode(){geocoder=new google.maps.Geocoder;var address=document.getElementById("address").value;geocoder.geocode({address:address},function(results,status){if(status==google.maps.GeocoderStatus.OK){showMap(results[0].geometry.location.lat(),results[0].geometry.location.lng())}else{alert("Geocode was not successful for the following reason: "+status)}})}function showMap(lat,long){var mapProp={center:new google.maps.LatLng(lat,long),zoom:16,mapTypeId:google.maps.MapTypeId.ROADMAP};var map=new google.maps.Map(document.getElementById("map-canavas"),mapProp);var myCenter=new google.maps.LatLng(lat,long);var marker=new google.maps.Marker({position:myCenter});marker.setMap(map)}$.event.special.copy.options={requirePreventDefault:true,autoConvertHtmlToRtf:true,trustedDomains:ZeroClipboard.config("trustedDomains"),hoverClass:"hover",activeClass:"active"};