<div class="m-main fixCen">
    <div class="box-404"  style="text-align: center;padding: 40px 0">
        <h3 class="rs" style="font-size: 80px">500</h3>
        <p class="rs txt-404" style="color: #fff;font-size: 20px;    text-shadow: 0 0 1px #212121, 1px 0 0 #212121, 0 1px 0 #212121;">Không tìm thấy trang bạn yêu cầu</p>
        <p class="rs txt-404" style="color: #fff;font-size: 20px;    text-shadow: 0 0 1px #212121, 1px 0 0 #212121, 0 1px 0 #212121;">Vui lòng quay lại <a href="<?php echo $this->Html->url('/home') ?>" target="_self">Trang chủ</a></p>
    </div>
</div>
<script>
    (function($) {
        $.fn.rainbow = function(options) {
            this.each(function() {

                options.originalText = $(this).html();
                options.iterations = 0;
                if (!options.pauseLength) {
                    options.pauseLength = options.animateInterval;
                }
                $(this).data('options',options);

                if (options.pad) {

                    for (x = 0; x < options.originalText.length; x++) {
                        options.colors.unshift(options.colors[options.colors.length-1]);
                    }
                }

                $.fn.rainbow.render(this);

            });
        }

        $.fn.pauseRainbow = function() {
            this.each(function() {
                var options = $(this).data('options');
                if (options) {
                    options.animate = false;
                    $(this).data('options',options);
                }
            });
        }


        $.fn.resumeRainbow = function() {
            this.each(function() {
                var options = $(this).data('options');
                if (options) {
                    options.animate = true;
                    $(this).data('options',options);
                    $.fn.rainbow.render(this);
                }
            });
        }


        $.fn.rainbow.render = function(obj) {

            var options = $(obj).data('options');
            var chars = options.originalText.split('');

            options.iterations++;

            var newstr = '';
            var counter = 0;
            for (var x in chars) {

                if (chars[x]!=' ') {
                    newstr = newstr + '<span style="color: ' + options.colors[counter] + ';">' + chars[x] + '</span>';
                    counter++;
                } else {
                    newstr = newstr + ' ';

                }


                if (counter >= options.colors.length) {
                    counter = 0;
                }
            }
            $(obj).html(newstr);

            var pause = (options.iterations % options.colors.length == 0);



            if (options.animate) {

                (
                    function(obj,interval) {
                        var options = $(obj).data('options');
                        var i = setTimeout(function() {
                            $.fn.rainbow.shift(obj);
                        },interval);
                        options.interval = i;
                        $(obj).data('options',options);
                    }
                )(obj,pause?options.pauseLength:options.animateInterval);
            }


        }


        $.fn.rainbow.shift = function(obj) {

            var options = $(obj).data('options');
            var color = options.colors.pop();
            options.colors.unshift(color);
            $.fn.rainbow.render(obj);

        }

    })(jQuery);
    $('.box-404 h3').rainbow({
        colors: [
            '#FF0000',
            '#f26522',
            '#fff200',
            '#00a651',
            '#28abe2',
            '#2e3192',
            '#6868ff'
        ],
        animate: true,
        animateInterval: 100,
        pad: false,
        pauseLength: 100
    });
    $('.box-404 a').rainbow({
        colors: [
            '#FF0000',
            '#f26522',
            '#fff200',
            '#00a651',
            '#28abe2',
            '#2e3192',
            '#6868ff'
        ],
        animate: true,
        animateInterval: 100,
        pad: false,
        pauseLength: 100
    });
</script>