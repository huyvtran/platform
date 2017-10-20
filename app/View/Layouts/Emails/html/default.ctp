<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <title>
        <?php
        if (!empty($title_for_layout))
            echo $title_for_layout;
        else
            echo 'MU Free';
        ?>
    </title>
</head>
<body style="margin: 0;padding: 0;background: #eeeeee">

<table style="line-height:15pt;background-color:#eeeeee;font-family:Arial, Helvetica, Sans Serif;color:#333333;font-size:10pt" border="0" cellspacing="0" cellpadding="0" width="616" align="center">
    <tbody >
    <tr >
        <td >
            <div style="margin: 10px;border: 1px solid #c1c1c1;border-radius: 6px;-moz-border-radius: 6px;background: #fff;overflow: hidden">
                <div style="padding: 20px 34px ">
                    <?php if( !empty($websiteUrl) ){ ?>
                    <div align="center">
                        <a href='http://<?php echo $websiteUrl ?>' target="_blank">
                            <?php
                            echo '<img src="https://scontent.fhan6-1.fna.fbcdn.net/v/t1.0-9/19554544_1693330710972219_7002250465989034999_n.png?oh=8265739d05a5268502b8030763149353&oe=59C4006B" style="display: block; max-width: 40%;">';
                            ?>
                        </a>
                    </div>
                    <?php } ?>

                    <?php echo $content_for_layout; ?>
                </div>
                <?php
                if($this->request->action != 'unsubscribe'){
                    $showFooter = true;
                }
                if ( !empty($showFooter) ) {
                    ?>
                    <div style="border-top: 1px solid #ebebeb;background: #f5f5f5;text-align: center;padding: 16px 0;font-size:9pt;color:#999999">
                        <div><?php echo __("Email này được gửi tới %s", '<a style="color: #999999">' . $emailAddress . '</a>') ?></div>
                        <div><?php echo __("Vui lòng KHÔNG phản hồi mail này.") ?></div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
