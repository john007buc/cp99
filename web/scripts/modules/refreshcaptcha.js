define(['jquery'],function($){
$("#refresh_captcha").click(function(){
    var random=Math.random();
    $("#captcha-img").attr("src",AJAX_URLS.captcha_url+"/"+random)
})

});
