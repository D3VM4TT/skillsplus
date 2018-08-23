$(document).ready(function(){
    (function( $ ){

        // Easing equation based on
        // EaseInOutExpo by Robert Penner (c) 2001
        // robertpenner.com/easing_terms_of_use.html

        $.fn.extend( jQuery.easing, {
            eioe: function( ø, t, b, c, d ) {
                if(t==0) return b;
                if(t==d) return b+c;
                if( (t /= d/2) < 1 ) return c/2 * Math.pow( 2, 10 * (t - 1) ) + b;
                return c/2 * ( -Math.pow( 2, -10 * --t ) + 2 ) + b;
            }
        });

        // Toggle attribute value
        // Anders Grimsrud, 2013

        $.fn.toggleAttr = function(a, v1, v2) {
            return this.each(function() {
                var $t = $(this),
                    v  = $t.attr(a) === v1 ? v2 : v1;
                $t.attr(a, v)
            });
        };

        // Toggle login/password reset form
        $('#login-combined .toggle').click(function(){
            if($('#formLogin').hasClass("hide")) {
                $('#formLogin').removeClass("hide");
            }
            else {
                $('#formLogin').addClass("hide");
            }
            $('#formPassword').slideToggle({
                easing: 'eioe',
                duration: 850
            });
            return false;
        });

    })(jQuery);

});

$(document).ready(function(){
    $('.menu-tab').click(function(){
        $('.menu-hide').toggleClass('show');
        $('.menu-tab').toggleClass('active');
    });
    $('a').click(function(){
        $('.menu-hide').removeClass('show');
        $('.menu-tab').removeClass('active');
    });
});

$(function(){
    var onClass = "on";
    var showClass = "show";

    $("input").bind("checkval",function(){
        var label = $(this).prev("label");
        if(this.value !== ""){
            label.addClass(showClass);
        } else {
            label.removeClass(showClass);
        }
    }).on("keyup",function(){
        $(this).trigger("checkval");
    }).on("focus",function(){
        $(this).prev("label").addClass(onClass);
    }).on("blur",function(){
        $(this).prev("label").removeClass(onClass);
    }).trigger("checkval");

    $(".confirm-password-showhide .trigger-password, .password-showhide .trigger-password").click(function() {
        var c = $(this).parent().attr("class").replace("-showhide", "");
        var obj = $("#" + (c.indexOf("confirm") > -1 ? "confirmPassword" : "password"));
        obj.attr("type", obj.attr("type") == "text" ? "password" : "text");
        $(this).text($(this).text() == "Hide" ? "Show" : "Hide");
    });

    $('#showResetPasswordPanel').click(function(event) {
        if ($('.container-reset-password').hasClass('dismiss-reset-password')) {
            $('.container-reset-password').removeClass('dismiss-reset-password').addClass('selected-reset-password').show();
        }
        event.preventDefault();
    });

    $('#closeResetPasswordPanel').click(function(event) {
        if ($('.container-reset-password').hasClass('selected-reset-password')) {
            $('.container-reset-password').removeClass('selected-reset-password').addClass('dismiss-reset-password');
        }
        event.preventDefault();
    });

});