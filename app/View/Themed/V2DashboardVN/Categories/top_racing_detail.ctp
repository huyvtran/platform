<div class="box-evt">
    <div class="box-evtI">
        <div class="cf box-ev1">
            <a href="#" class="btn-ev1 sev btn-ev active"></a>
            <a href="#" class="btn-ev2 sev btn-ev"></a>
        </div>
        <div class="info-events">
            <div class="cf box-ev2">
                <a href="#" class="btn-e1 sev btn-e active"></a>
                <a href="#" class="btn-e2 sev btn-e"></a>
            </div>
            <div class="box-eslt">
                <span class="ev-txt">Choose tournament:</span>
                <select>
                    <option>1</option>
                    <option>1</option>
                    <option>1</option>
                </select>
            </div>
            <div class="box-evtime">
                <p class="rs cf">
                    <span class="text-l">* Start:</span>
                    <span class="text-r">03-02-2017 10:00:00</span>
                </p>
                <p class="rs cf">
                    <span class="text-l">* End:</span>
                    <span class="text-r">03-02-2017 10:00:00</span>
                </p>
            </div>
            <div class="box-evtime">
                <p class="rs cf">
                    <span class="text-l">* Reward time starts:</span>
                    <span class="text-r">03-02-2017 10:00:00</span>
                </p>
                <p class="rs cf">
                    <span class="text-l">* Reward time ends:</span>
                    <span class="text-r">03-02-2017 10:00:00</span>
                </p>
            </div>
            <a href="#" class="btn-evrw sev"></a>
        </div>
    </div>
</div>
<div class="box-ebxh">
    <div class="rules"></div>
    <table class="tblBxh">
        <tbody>
            <tr>
                <th class="th1">Top</th>
                <th class="th2">Character</th>
                <th class="th3">Level</th>
                <th class="th4">Exp</th>
                <th class="th5">Rewards</th>
            </tr>
            <tr>
                <td><strong class="number">1</strong></td>
                <td>khanhdinh91@g...</td>
                <td>80</td>
                <td>179984</td>
                <td>179984</td>
            </tr>
            <tr>
                <td><strong class="number">1</strong></td>
                <td>khanhdinh91@g...</td>
                <td>80</td>
                <td>179984</td>
                <td>179984</td>
            </tr>
            <tr>
                <td><strong class="number">1</strong></td>
                <td>khanhdinh91@g...</td>
                <td>80</td>
                <td>179984</td>
                <td>179984</td>
            </tr>
            <tr>
                <td><strong class="number">1</strong></td>
                <td>khanhdinh91@g...</td>
                <td>80</td>
                <td>179984</td>
                <td>179984</td>
            </tr>
            <tr>
                <td><strong class="number">1</strong></td>
                <td>khanhdinh91@g...</td>
                <td>80</td>
                <td>179984</td>
                <td>179984</td>
            </tr>
        </tbody>
    </table>
</div>
<script>
jQuery(document).ready(function($){
    $('.box-evt .btn-ev1').on('click', function(event){
        event.preventDefault();
        $('.box-evt .box-ebxh .rules').show(200);
        $('.box-evt .box-ebxh .tblBxh').hide(200);
    });
});
</script>