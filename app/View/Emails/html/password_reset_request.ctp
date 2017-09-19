Xin chào, <br/>
Bạn đã yêu cầu reset mật khẩu của tài khoản :
<strong><?php
echo $user['username'] ?></strong>. Mã PIN xác thực là: <br/>
<strong style='font-size: 20px;margin-left: 20px; color: #c53727'><?php echo $user['password_token'] ?></strong><br/>
Mã PIN này được tạo lúc 
<strong>
<?php
echo date('d-m-Y H:i:s', time());
?>
</strong>
, và hết hiệu lực vào lúc <strong><?php echo date('d-m-Y H:i:s', strtotime($user['email_token_expires'])) ?></strong>

Nếu không phải bạn đã yêu cầu reset mật khẩu, vui lòng bỏ qua email này và không làm gì cả.<br/>

Trân trọng. <br/>