    (function($) {
        $(function() {
            var scroll = $(document).scrollTop();
            var headerHeight = $('#PageTop').outerHeight();

            $(window).scroll(function() {
                var scrolled = $(document).scrollTop();
                if (scrolled > headerHeight) {
                    $('#PageTop').addClass('off-canvas');
                    /* $('#menu2').addClass('nav-logo');*/
                    /* $('#PageTopLogo>a>img').css({ "display": "none" });*/

                } else {
                    $('#PageTop').removeClass('off-canvas');
                    /* $('#menu2').removeClass('nav-logo');*/
                    /* $('#PageTopLogo>a>img').css({ "display": "inline-block" });*/

                }

                if (scrolled > scroll) {
                    $('#PageTop').removeClass('fixed');
                    /* $('#menu2').removeClass('nav-logo');*/
                    /* $('#PageTopLogo>a>img').css({ "display": "inline-block" });*/

                } else {
                    $('#PageTop').addClass('fixed');
                    /* $('#menu2').addClass('nav-logo');*/
                    /* $('#PageTopLogo>a>img').css({ "display": "none" });*/

                }
                scroll = $(document).scrollTop();
            });

        });
    })(jQuery);