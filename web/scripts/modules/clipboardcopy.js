define(['jquery','zeroclipboard','toastmessage'],function ($,ZeroClipboard){

    return {

        can_copy:function()
        {
           return (this.browser_support_flash() || this.browser_support_html5_copy())?Boolean(true):Boolean(false);

        },
        get_browser_info:function(){
            var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
            if(/trident/i.test(M[1])){
                tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
                return {name:'IE',version:(tem[1]||'')};
            }
            if(M[1]==='Chrome'){
                tem=ua.match(/\bOPR\/(\d+)/);
                if(tem!=null)   {return {name:'Opera', version:tem[1]};}
            }
            M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
            if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
            return {
                name: M[0],
                version: M[1]
            };
        },

        browser_support_html5_copy: function()
        {
            var browser=this.get_browser_info();

            return ((browser.name=="Firefox" && browser.version >=41)||(browser.name=="Chrome" && browser.version >=43))?true:false;

        },
        browser_support_flash:function()
        {
            return !ZeroClipboard.isFlashUnusable()
        },

        load: function()
        {


            if(this.browser_support_html5_copy()){

                var self=this;

                $(document).on('click', '.cp', function (e) {
                    $(this).focus().select();
                    document.execCommand('copy');
                    $(this).blur();
                    $('.cp').each(function(){
                        $(this).css("color", "royalblue")
                    });
                    $(this).css("color","red");

                    self.showMessage($(this).data('clipboard-text'));
            });

            }else if(this.browser_support_flash()){

                ZeroClipboard.config({
                    forceEnhancedClipboard: true
                });

                var self=this;
                var client = new ZeroClipboard( $('.cp') );


                client.on( "ready", function( readyEvent ) {

                    client.on( "aftercopy", function( event ) {
                       // clipboard_text=event.data["text/plain"];
                        // `this` === `client`
                        // `event.target` === the element that was clicked
                        $('.cp').each(function(){
                            $(this).css("color", "royalblue")
                        });
                        event.target.style.color = "red";

                       self.showMessage(event.data["text/plain"]);

                    } );
                } );

                client.on( 'error', function(event) {
                    // console.log( 'ZeroClipboard error of type "' + event.name + '": ' + event.message );
                    ZeroClipboard.destroy();
                } );
            }


        },
        showMessage:function(clipboard_text)
        {
            $().toastmessage('showSuccessToast', "Codul postal "+clipboard_text+" a fost copiat in clipboard" );
        }
    };

});