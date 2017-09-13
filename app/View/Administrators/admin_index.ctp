<?php
$this->extend('/Common/blank');
if ($this->Session->read('Auth.User') && !in_array($this->Session->read('Auth.User.role'), array('User', 'Guest'))
	) {
        ?>
        <div class="col-md-12">
            <div class="box box-primary" id="box-online-by-server">
                <div class="box-header with-border">
                    <h3 class="box-title">Admin Dashboard</h3>
                </div>
                <div class="box-body">
                    <pre class='well'>
                        - Phần <strong>bài viết website</strong>. Truy cập CMS -> Articles
                        - <strong>Thông tin các games</strong> mình bản lý. Truy cập Games - Index
                        + Chú ý các cảnh báo Missing Setups phải ko còn thì games có thể chạy ko có lỗi
                    </pre>
                </div>
            </div>
        </div>
        <?php } ?>