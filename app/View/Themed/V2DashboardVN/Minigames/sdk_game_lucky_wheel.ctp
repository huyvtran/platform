<?php
// xử lý cho link ko lưu vào history cho việc back

$MobileDetect = new Mobile_Detect();

$url_rules = $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkRule', 'slug' => $minigame['Minigame']['slug'], '?'=>array('clearHistoryBackstack'=>true) ));
$url_game = $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkGame', 'slug' => $minigame['Minigame']['slug'], '?'=>array('clearHistoryBackstack'=>true) ));
$url_history = $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkHistory', 'slug' => $minigame['Minigame']['slug'], '?'=>array('clearHistoryBackstack'=>true) ));

if($MobileDetect->isiOS()){
  $url_rules   = "javascript:MobAppSDKexecute('mobLoadURL', {'url' : '" . $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkRule', 'slug' => $minigame['Minigame']['slug'] )) . "'})";
  $url_game   = "javascript:MobAppSDKexecute('mobLoadURL', {'url' : '" . $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkGame', 'slug' => $minigame['Minigame']['slug'] )) . "'})";
  $url_history   = "javascript:MobAppSDKexecute('mobLoadURL', {'url' : '" . $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkHistory', 'slug' => $minigame['Minigame']['slug'] )) . "'})";
}

// check xem còn trong khoảng thời gian diễn ra minigame ko
$current_time = new DateTime();
$minigame_online = ( ($minigame['Minigame']['start_time'] <= $current_time->format('H:i:s') || empty($minigame['Minigame']['start_time']) )
  && ($minigame['Minigame']['end_time'] >= $current_time->format('H:i:s') || empty($minigame['Minigame']['end_time']) ) )
  ? 1 : 0;

// lấy appkey cho refresh view
$app_key = $this->request->header('mobgame_appkey');
?>

<?php
        echo $this->Html->css('/uncommon/dashboard_v2/css/wheel.css');
        echo $this->fetch('css');
?>
<?php
    echo $this->Html->script('/uncommon/dashboard_v2/js/Winwheel.js');
    echo $this->Html->script('/uncommon/dashboard_v2/js/TweenMax.min.js');
    echo $this->fetch('script');
?>

<nav  class="tab-controls">
    <div class="container">

        <ul class="list-unstyled clearfix">
            <li><a href="<?php echo $url_rules; ?>"><?php echo _('Thể lệ'); ?></a></li>
            <li class="active"><a href="<?php echo $url_game; ?>"><?php echo _('Quay thưởng'); ?></a></li>
            <li><a href="<?php echo $url_history; ?>"><?php echo _('BXH'); ?></a></li>
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
            <a href="javascript:void(0)" class="spin" onClick="get_result_wheel();"></a>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="note">
    <div class="container">
        <input id="result_funcoin_hide" type="hidden" />
        <input id="freespin_hide" type="hidden" />
        <p>Số FunCoin của bạn là: <span id="result_funcoin"><?php echo $funcoin; ?></span></p>
        <p>Tổng lượt quay kỳ này của bạn là: <span id="total_join_in_circle"><?php echo $total_join_in_circle; ?></span> lượt</p>
        <p>Phí mỗi vòng quay: <?php echo $minigame['Minigame']['funcoin_join']; ?> FunCoin</p>
        <p>Bạn có <span id="free_spin"><?php echo $free_turn; ?></span> lượt quay miễn phí hằng ngày</p>
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
            <?php if( !empty($winners) ){
                foreach($winners as $item){
            ?>

                <tr>
                    <td><?php echo $item['User']['username']; ?></td>
                    <td><?php echo $item['MinigameRound']['prize']; ?></td>
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
    var arr_result_position = {
        // 20 Funcoin
        4: {min:235, max: 259}, // 6 - 1FC
        5: {min:55, max: 79}, // 2 - 5FC
        6: {min:325, max: 350}, // 8 - 10FC
        7: {min:10, max: 34}, // 1 - 20FC
        8: {min:280, max: 304}, // 7 - 50FC
        9: {min:100, max: 124}, // 3 - 1000FC
        10: {min:190, max: 214}, // 5 - 500FC
        11: {min:145, max: 169}, // 4 - 100FC
        // 100 Funcoin
        12: {min:325, max: 350}, // 8 - 10FC
        13: {min:235, max: 259}, // 6 - 20FC
        14: {min:280, max: 304}, // 7 - 50FC
        15: {min:55, max: 79}, // 2 - 200FC
        16: {min:100, max: 124}, // 3 - 1000FC
        17: {min:145, max: 169}, // 4 - 5000FC
        18: {min:190, max: 214}, // 5 - 500FC
        19: {min:10, max: 34}, // 1 - 100FC
    };

    var arr_result_alert = [];
    <?php
    // vòng quay 100
    if( $minigame['Minigame']['funcoin_join'] == 100 ) {
        ?>
        arr_result_alert = [
            {'fillStyle' : '#eae56f', 'text' : 'Bạn nhận được <br>100 FunCoin', 'funcoin_in' : 100, 'funcoin_out' : 100},
            {'fillStyle' : '#89f26e', 'text' : 'Bạn nhận được <br>200 FunCoin', 'funcoin_in' : 100, 'funcoin_out' : 200},
            {'fillStyle' : '#7de6ef', 'text' : 'Bạn nhận được <br>1000 FunCoin', 'funcoin_in' : 100, 'funcoin_out' : 1000},
            {'fillStyle' : '#e7706f', 'text' : 'Bạn nhận được <br>5000 FunCoin', 'funcoin_in' : 100, 'funcoin_out' : 5000},
            {'fillStyle' : '#eae56f', 'text' : 'Bạn nhận được <br>500 FunCoin', 'funcoin_in' : 100, 'funcoin_out' : 500},
            {'fillStyle' : '#89f26e', 'text' : 'Bạn nhận được <br>20 FunCoin', 'funcoin_in' : 100, 'funcoin_out' : 20},
            {'fillStyle' : '#7de6ef', 'text' : 'Bạn nhận được <br>50 FunCoin', 'funcoin_in' : 100, 'funcoin_out' : 50},
            {'fillStyle' : '#e7706f', 'text' : 'Bạn nhận được <br>10 FunCoin', 'funcoin_in' : 100, 'funcoin_out' : 10}
        ];
        <?php
    } else { // vòng quay 20
        ?>
        arr_result_alert = [
            {'fillStyle' : '#eae56f', 'text' : 'Bạn nhận được <br>20 FunCoin', 'funcoin_in' : 20, 'funcoin_out' : 20},
            {'fillStyle' : '#89f26e', 'text' : 'Bạn nhận được <br>5 FunCoin', 'funcoin_in' : 20, 'funcoin_out' : 5},
            {'fillStyle' : '#7de6ef', 'text' : 'Bạn nhận được <br>1000 FunCoin', 'funcoin_in' : 20, 'funcoin_out' : 1000},
            {'fillStyle' : '#e7706f', 'text' : 'Bạn nhận được <br>100 FunCoin', 'funcoin_in' : 20, 'funcoin_out' : 100},
            {'fillStyle' : '#eae56f', 'text' : 'Bạn nhận được <br>500 FunCoin', 'funcoin_in' : 20, 'funcoin_out' : 500},
            {'fillStyle' : '#89f26e', 'text' : 'Bạn nhận được <br>1 FunCoin', 'funcoin_in' : 20, 'funcoin_out' : 1},
            {'fillStyle' : '#7de6ef', 'text' : 'Bạn nhận được <br>50 FunCoin', 'funcoin_in' : 20, 'funcoin_out' : 50},
            {'fillStyle' : '#e7706f', 'text' : 'Bạn nhận được <br>10 FunCoin', 'funcoin_in' : 20, 'funcoin_out' : 10}
        ];
        <?php
    }
    ?>

    // Create new wheel object specifying the parameters at creation time.
    var theWheel = new Winwheel({
        'numSegments'  : 8,
        'outerRadius'  : 212,
        'textFontSize' : 28,
        'drawMode' : 'image',
        'segments'     : arr_result_alert,
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

    <?php
    // vòng quay 100
    if( $minigame['Minigame']['funcoin_join'] == 100 ) {
        ?>
        loadedImg.src = "http://cdn.smobgame.com/newfolder/dashboard/vqmm3-mobile.png";
        <?php
    } else { // vòng quay 20
        ?>
        loadedImg.src = "http://cdn.smobgame.com/newfolder/dashboard/vqmm2-mobile.png";
        <?php
    }
    ?>

    var wheelSpinning = false;
    var result_funcoin = <?php echo $funcoin; ?>;
    var total_join_in_circle = <?php echo $total_join_in_circle; ?>;

    // Function called on click of spin button.
    function get_result_wheel()
    {
        $('.spin').addClass('disable');
        $.ajax({
          url: '<?php echo $this->Html->url(array('controller'=>'minigames','action' => 'sdkJoinGame')); ?>',
          async: true,
          type: 'get',
          data: {
            minigame_id: <?php echo $minigame['Minigame']['id']; ?>,
            slug: '<?php echo $minigame['Minigame']['slug']; ?>'
          },
          dataType: 'json',
          success:function(returnedData) {
            if(returnedData.code == 1) {
                var index = returnedData.data.result_code;
                result_funcoin = returnedData.data.funcoin_remain;

                // Ensure that spinning can't be clicked again while already running.
                if (wheelSpinning == false)
                {

                   theWheel.animation.stopAngle = getRandomInt(arr_result_position[ index ].min, arr_result_position[ index ].max);
                   theWheel.startAnimation();
                   wheelSpinning = true;
                }

                //$('#error_wheel').text(returnedData.message);
            } else {
                $('#error_wheel').text(returnedData.message);
            }
          }
        });
    }

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
        $('.spin').removeClass('disable');
    }

    // -------------------------------------------------------
    // Called when the spin animation has finished by the callback feature of the wheel because I specified callback in the parameters.
    // -------------------------------------------------------
    function alertPrize()
    {
        var winningSegment = theWheel.getIndicatedSegment();
        $('#result').html(winningSegment.text);
        $('.popup').show();
        //$('#result_funcoin').text( parseInt($('#result_funcoin').text()) - winningSegment.funcoin_in + winningSegment.funcoin_out );
        $('#result_funcoin').text( result_funcoin );

        total_join_in_circle = $('#total_join_in_circle').text();
        total_join_in_circle++;
        $('#total_join_in_circle').text( total_join_in_circle );

        $('#free_spin').text('0');
    }

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
</script>