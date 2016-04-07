require.config({
    urlArgs: "bust=" + (new Date()).getTime(),
    //baseUrl:'scripts',
    paths:{
        //"jquery":["http://code.jquery.com/jquery-1.10.2.min.js","vendor/jquery/dist/jquery"],
         "jquery":"vendor/jquery/dist/jquery",
         "gmap":"https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places&language=ro",
         "zeroclipboard":"vendor/zeroclipboard/ZeroClipboard",
          "toastmessage":"vendor/ToastMessage/jquery.toastmessage"

    },
    shim:{
        "jquery":{
            exports: '$'
        },
        "zeroclipboard":{
            deps:['jquery'],
            exports: "ZeroClipboard"
        },
        "toastmessage":{
            deps:['jquery']
        }

    }
});

require(['jquery','modules/ajaxcall','modules/gmaps','modules/refreshcaptcha'],function($,ajaxcall,g){

    g.load();


    $("#cp_btn").click(function(e){
        var address=$("#address").val();
        ajaxcall.get_cp(address,1);

        e.preventDefault();

    });

    $(document).on('click', '.cp_pagination', function (e) {
        var page = $(this).attr('data-page');
        var address=$("#address").val();
        if(typeof(address)!="undefined" && page!=0){
            ajaxcall.get_cp(address,page);

        }
        e.preventDefault();
    });

    /*--------------clipboard copy------------------*/


});
