(function($)
{
    $.fn.popup = function(options)
    {
        var defaults = {
            width: "400px",
            height: "200px",
        };
        var options = $.extend(defaults, options);
        var $this   = $(this);
        $("body").append("<div id='background'></div>");
        $this.prepend("<div class='popuptitle'><a href='javascript:void(0)' class='close1'>&#10006;</a></div>");
        $this.addClass("popup");
        $this.width(options.width).height(options.height);
        $this.hide();
        $("#background").click(function () {
            closePopup();
        });
        $(".close1").click(function () {
            closePopup();
        });
        return this;
    };

    $.fn.openPopup = function()
    {
        var dheight = document.body.clientHeight;
        var dwidth  = document.body.clientWidth;
        var browser = navigator.userAgent;
        $("#background").width(dwidth).height(dheight);
        $("#background").fadeTo("slow",0.8);
        if (browser.indexOf('Firefox') >= 0) {
            $(this).css("top", (dheight-$(this).height())/6);
            $(this).css("left",(dwidth-$(this).width())/3);
        } else if (browser.indexOf('Chrome') >= 0) {
            $(this).css("top", (dheight-$(this).height())/4);
            $(this).css("left",(dwidth-$(this).width())/3);
        }
        $(this).fadeIn();
        return this;
    };
})(jQuery);

function closePopup()
{
    $("#background").fadeOut();
    $(".popup").hide();
    $("#return").html("");
}