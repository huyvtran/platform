<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <title>Vòng Quay May Mắn</title>
    <?php
        echo $this->Html->css('/uncommon/dashboard_v2/css/wheel.css');
        echo $this->fetch('css');
    ?>
    <?php
        echo $this->Html->script('/uncommon/dashboard_v2/js/jquery.min.js');
        echo $this->Html->script('/uncommon/dashboard_v2/js/Winwheel.js');
        echo $this->Html->script('/uncommon/dashboard_v2/js/TweenMax.min.js');
        echo $this->fetch('script');
    ?>

</head>
<body>
<nav  class="tab-controls">
    <div class="container">

        <ul class="list-unstyled clearfix">
            <li  class="active" ><a href="#">Quay thưởng</a></li>
            <li><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'term_wheel')) ?>">Thể lệ</a></li>
            <li><a href="<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'history_wheel')) ?>">Lịch sử quay</a></li>
        </ul>

    </div>
</nav>
<div class="container">
    <h2 class="page-title">VÒNG QUAY MAY MẮN</h2>
    <h4 id="error_wheel" style="color: red;"></h4>
    <div align="center">
        <div class="wheel_wrapper">
            <canvas id="canvas" width="298" height="298">
                <p style="{color: white}" align="center">Sorry, your browser doesn't support canvas. Please try another.</p>
            </canvas>
            <a href="javascript:void(0)" class="spin" onClick="calculatePrizeOnServer();"></a>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="note">
    <div class="container">
        <input id="result_funcoin_hide" type="hidden" />
        <input id="freespin_hide" type="hidden" />
        <input id="respin_hide" type="hidden" />
        <p>Số FunCoin của bạn là: <span id="result_funcoin"><?php echo $funcoin; ?></span></p>
        <p>Bạn có <span id="freespin"><?php echo  $free; ?></span> lượt quay miễn phí hằng ngày</p>
        <p>Bạn còn <span id="respin"><?php echo $turn_wheel; ?></span> lượt quay bằng FunCoin hôm nay (50 FunCoin/lượt quay)</p>
    </div>
</div>
<div class="prize">
    <div class="container">
        <table>
            <thead>
            <th>Tên</th>
            <th>Giải thưởng</th>
            </thead>
            <tbody>
            <?php if(count($user_prize_give)){
                foreach($user_prize_give as $prize){
            ?>

                <tr>
                    <td><?php echo $prize['PrizeUser']['Username']; ?></td>
                    <td><?php echo $prize['PrizeUser']['prize_name']; ?></td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>
    </div>
</div>
<div class="popup">
    <div class="overlay" onClick="resetWheel(); return false;"></div>
    <div class="result">
        <p><span id="result"></span></p>
        <a href="javascript:void(0)" class="button" onClick="resetWheel(); return false;">OK</a>
    </div>
</div>

<script>
    // Create new wheel object specifying the parameters at creation time.
    var theWheel = new Winwheel({
        'numSegments'  : 8,
        'outerRadius'  : 212,
        'textFontSize' : 28,
        'drawMode' : 'image',
        'segments'     :
            [
                {'fillStyle' : '#eae56f', 'text' : 'Bạn nhận được <br>30 FunCoin'},
                {'fillStyle' : '#89f26e', 'text' : 'Bạn nhận được <br>200 FunCoin'},
                {'fillStyle' : '#7de6ef', 'text' : 'Bạn nhận được <br>1000 FunCoin'},
                {'fillStyle' : '#e7706f', 'text' : 'Chúc bạn may mắn lần sau'},
                {'fillStyle' : '#eae56f', 'text' : 'Bạn nhận được <br>10 FunCoin'},
                {'fillStyle' : '#89f26e', 'text' : 'Bạn nhận được <br>Iphone 6S 128GB'},
                {'fillStyle' : '#7de6ef', 'text' : 'Bạn nhận được <br>50 FunCoin'},
                {'fillStyle' : '#e7706f', 'text' : 'Bạn nhận được <br>Sạc dự phòng'}
            ],
        'animation' :
        {
            'type'     : 'spinToStop',
            'duration' : 5,
            'spins'    : 8,
            'callbackFinished' : 'alertPrize()'
        }
    });
    var loadedImg = new Image();
    loadedImg.onload = function()
    {
        theWheel.wheelImage = loadedImg;    // Make wheelImage equal the loaded image object.
        theWheel.draw();                    // Also call draw function to render the wheel.
    };

    loadedImg.src = "http://cdn.smobgame.com/newfolder/funtap/vqmm-mobile.png";
    var wheelSpinning = false;
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = ajaxStateChange;

    // Function called on click of spin button.
    function calculatePrizeOnServer()
    {
        // Make get request to the server-side script.
        xhr.open('GET',"<?php echo $this->Html->url(array('controller'=>'users','action' => 'caculate_response_round_lucky')) ?>", true);
        xhr.send('');
    }

    // Called when state of the HTTP request changes.
    function ajaxStateChange()
    {
        if (xhr.readyState < 4)
        {
            return;
        }

        if (xhr.status !== 200)
        {
            return;
        }

        // The request has completed.
        if (xhr.readyState === 4) {
            data = JSON.parse(xhr.responseText);
            var segmentNumber = data.result;   // The segment number should be in response.
            var run = data.run;
            var enough = data.enough;
            if (run || enough){
                if (segmentNumber) {
                    var funcoin_l = data.funcoin;   // The segment number should be in response.
                    var free = data.free;   // The segment number should be in response.
                    var turn_wheel = data.turn_wheel;   // The segment number should be in response.
                    document.getElementById("result_funcoin_hide").value = funcoin_l;
                    document.getElementById("freespin_hide").value = free;
                    document.getElementById("respin_hide").value = turn_wheel;
                    // Get random angle inside specified segment of the wheel.
                    if((segmentNumber) == 1) {
                        var stopAt = (20);
                    }
                    else if((segmentNumber) == 2) {
                        var stopAt = (65);
                    }
                    else if((segmentNumber) == 3) {
                        var stopAt = (110);
                    }
                    else if((segmentNumber) == 4) {
                        var stopAt = (155);
                    }
                    else if((segmentNumber) == 5) {
                        var stopAt = (200);
                    }
                    else if((segmentNumber) == 6) {
                        var stopAt = (245);
                    }
                    else if((segmentNumber) == 7) {
                        var stopAt = (290);
                    }
                    else if((segmentNumber) == 8) {
                        var stopAt = (335);
                    }
//                    var stopAt = theWheel.getRandomForSegment(segmentNumber);

                    // Important thing is to set the stopAngle of the animation before stating the spin.
                    theWheel.animation.stopAngle = stopAt;

                    // Start the spin animation here.
                    theWheel.startAnimation();
                    wheelSpinning = true;
                }
            }else{
                document.getElementById("error_wheel").innerHTML = data.message;
            }
        }
    }

//    function startSpin()
//    {
//        // Ensure that spinning can't be clicked again while already running.
//        if (wheelSpinning == false)
//        {
//
//            theWheel.animation.spins = 8;
//            theWheel.startAnimation();
//            wheelSpinning = true;
//        }
//
//    }

    // -------------------------------------------------------
    // Function for reset button.
    // -------------------------------------------------------

    function resetWheel()
    {
        theWheel.stopAnimation(false);  // Stop the animation, false as param so does not call callback function.
        theWheel.rotationAngle = 0;     // Re-set the wheel angle to 0 degrees.
        theWheel.draw();                // Call draw to render changes to the wheel.
        wheelSpinning = false;          // Reset to false to power buttons and spin can be clicked again.
        $('.popup').hide();
    }

    // -------------------------------------------------------
    // Called when the spin animation has finished by the callback feature of the wheel because I specified callback in the parameters.
    // -------------------------------------------------------
    function alertPrize()
    {
        var winningSegment = theWheel.getIndicatedSegment();
        $('#result').html(winningSegment.text);
        $('.popup').show();
        var funcoin = document.getElementById('result_funcoin_hide').value;
        var free = document.getElementById('freespin_hide').value;
        var turn_wheel = document.getElementById('respin_hide').value;
        document.getElementById("result_funcoin").innerText = funcoin ;
        document.getElementById("freespin").innerText = free ;
        document.getElementById("respin").innerText =  turn_wheel;
        //alert("You have won " + winningSegment.text);
    }
</script>
</body>
</html>