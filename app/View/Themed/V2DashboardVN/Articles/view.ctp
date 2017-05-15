<?php echo $this->element('count_time'); ?>
<div id="sb-site" class="m-container">
    <div class="entry-content">
        <div class="mob-BoxNews">
            <?php
                $hotnews = '';
                if ($article['Article']['is_new']) {
                    $hotnews = "<span class='snews'>new</span>";
                }
                if ($article['Article']['is_hot']) {
                    $hotnews = "<span class='shot'>hot</span>";
                }

            ?>


            <?php if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'events'){ ?>
                <?php
                    if(strtotime($article['Article']['event_end']) < strtotime(date('Y-m-d H:i:s'))){
                        $hotnews = '';
                    }
                    echo "<h1 class='focus rs' itemprop='name'>".$hotnews.$article['Article']['title']."</h1>";
                    $events_time = $article['Article']['event_end'];
                    if(!empty($article['Article']['event_start']) && strtotime($article['Article']['event_start']) > strtotime(date('Y-m-d H:i:s')))
                        $events_time = $article['Article']['event_start'];

                    if(empty($article['Article']['event_end']) || $article['Article']['event_end'] == "")
                        $events_time = date('Y-m-d H:i:s');

                    if(strtotime($events_time) < strtotime(date('Y-m-d H:i:s')) || strtotime($article['Article']['event_start']) >= strtotime(date('Y-m-d H:i:s'))){
                        $time_start = $this->Time->format("M d \a\\t h:i A", $article['Article']['event_start']);
                        $time_end = $this->Time->format("M d \a\\t h:i A", $article['Article']['event_end']);
                        if($currentGame['language_default'] == 'vie'){
                            if($this->Time->isThisYear($article['Article']['event_start'])) {
                                $time_start = $this->Time->format("d \\t\h\á\\n\g m \l\ú\c h:i A", $article['Article']['event_start']) . ' ' . '(GMT+7)';
                                $time_end = $this->Time->format("d \\t\h\á\\n\g m \l\ú\c h:i A", $article['Article']['event_end']) . ' ' . '(GMT+7)';
                            }else{
                                $time_start =  $this->Time->format("d \\t\h\á\\n\g m \l\ú\c\, Y h:i A",$article['Article']['event_start']) . ' ' . '(GMT+7)';
                                $time_end =  $this->Time->format("d \\t\h\á\\n\g m \l\ú\c\, Y h:i A",$article['Article']['event_end']) . ' ' . '(GMT+7)';
                            }
                        }else{
                            if($this->Time->isThisYear($article['Article']['event_start'], 'Asia/Hong_Kong')) {
                                $time_start = $this->Time->format("M d \a\\t h:i A", $article['Article']['event_start'], null, 'Asia/Hong_Kong') . ' (UTC+8)';
                                $time_end = $this->Time->format("M d \a\\t h:i A", $article['Article']['event_end'], null, 'Asia/Hong_Kong') . ' (UTC+8)';
                            }else {
                                $time_start =  $this->Time->format("M d, Y \a\\t h:i A",$article['Article']['event_start'], null, 'Asia/Hong_Kong') . ' (UTC+8)';
                                $time_end =  $this->Time->format("M d, Y \a\\t h:i A",$article['Article']['event_end'], null, 'Asia/Hong_Kong') . ' (UTC+8)';
                            }

                            $time_zone = "Asia/Hong_Kong";
                            if(!empty($currentGame['data']['time_zone'])) $time_zone = $currentGame['data']['time_zone'];
                            if($this->Time->isThisYear($article['Article']['event_start'], $time_zone)) {
                                $time_start = $this->Nav->timeShort($article['Article']['event_start'], "M d \a\\t h:i A", $time_zone);
                                $time_end = $this->Nav->timeShort($article['Article']['event_end'], "M d \a\\t h:i A", $time_zone);
                            }else {
                                $time_start = $this->Nav->timeShort($article['Article']['event_start'], "M d, Y \a\\t h:i A", $time_zone);
                                $time_end =  $this->Nav->timeShort($article['Article']['event_end'], "M d, Y \a\\t h:i A", $time_zone);
                            }
                        }

                        $time_published = $time_start . ' - ' . $time_end;
                        $class_date = "date";

                        echo "<span class='mob-timeDetail'><span class='" . $class_date . "'>" . $time_published . "</span></span>";
                    }else{
                ?>
                        <span class="mob-timeDetail mob-timeBG" id="countdown"> </span>
                        <script type="text/javascript">
                            CountDownTimer("<?php echo $events_time; ?>", 'countdown', "<?php echo __('thời gian còn lại'); ?>", "<?php echo $currentGame['language_default']; ?>");
                        </script>
                    <?php } ?>
            <?php }else{
                $class_date = "date";
                $time_published =  $this->Time->format("M d \a\\t h:i A",$article['Article']['published_date']);
                if($currentGame['language_default'] == 'vie'){
                    if($this->Time->isThisYear($article['Article']['published_date']))
                        $time_published =  $this->Time->format("d \\t\h\á\\n\g m \l\ú\c h:i A",$article['Article']['published_date']) . ' ' . '(GMT+7)';
                    else $time_published =  $this->Time->format("d \\t\h\á\\n\g m \l\ú\c\, Y h:i A",$article['Article']['published_date']) . ' ' . '(GMT+7)';

                    if ($this->Time->isToday($article['Article']['published_date'])) {
                        $time_published = __('Hôm nay') . ' ' . $this->Time->format("\l\ú\c h:i A",$article['Article']['published_date']) . ' ' . '(GMT+7)';
                        $class_date = $class_date. " datesk";
                    }elseif ($this->Time->wasYesterday($article['Article']['published_date'])) {
                        $time_published = __('Hôm qua') . ' ' . $this->Time->format("\l\ú\c h:i A",$article['Article']['published_date']) . ' ' . '(GMT+7)';
                    }
                }else{
                    $time_zone = "Asia/Hong_Kong";
                    if(!empty($currentGame['data']['time_zone'])) $time_zone = $currentGame['data']['time_zone'];
                    if($this->Time->isThisYear($article['Article']['published_date'], $time_zone))
                        $time_published = $this->Nav->timeShort($article['Article']['published_date'], "M d \a\\t h:i A", $time_zone);
                    else $time_published = $this->Nav->timeShort($article['Article']['published_date'], "M d, Y \a\\t h:i A", $time_zone);

                    if ($this->Time->isToday($article['Article']['published_date'], $time_zone)) {
                        $time_published = __('Hôm nay') . " " . $this->Nav->timeShort($article['Article']['published_date'], "\a\\t h:i A", $time_zone);
                        $class_date = $class_date. " datesk";
                    }elseif ($this->Time->wasYesterday($article['Article']['published_date'], $time_zone)) {
                        $time_published = __('Hôm qua') . " " . $this->Nav->timeShort($article['Article']['published_date'], "\a\\t h:i A", $time_zone);
                    }
                }
            ?>
                <h1 class="focus rs" itemprop="name"><?php echo $hotnews.$article['Article']['title']; ?></h1>
                <span class="mob-timeDetail"><span class="<?php echo $class_date; ?>"><?php echo $time_published; ?></span></span>
            <?php } ?>

        </div>
        <div class="mob-listEvent ">
            <article class="box-detail" itemscope="" itemtype="http://schema.org/Article">
                <div class="dataBody" itemprop="articleBody">
                    <?php echo $article['Article']['parsed_body']; ?>
                </div>
            </article>
        </div>
    </div>
</div>
<script type='text/javascript'>
    $(function() {
        $('body').removeClass("info");
    });
</script>