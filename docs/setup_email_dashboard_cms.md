Setup Email + Dashboard + CMS: 
------------------------------

Warning: đọc phần này sau các phần khác.
## Game 
0. Create game trong tool admin để lấy app_key or các thông tin đầu tiên , thường bên SDK đã tạo từ trước.

1. Clone View/Themed/[.*Dashboard] từ 1 themed cũ tới 1 themed mới.  
Thay đổi nội dung code nếu cần, thường là thay đổi rất ít..

2. Clone Event/Themed/[.*Dashboard] từ 1 themed cũ tới 1 themed mới.  
Thay đổi nội dung code nếu cần, thường là thay đổi rất ít..

3. Edit gamne configs trong http://admin.smobgame.com/admin/games/edit/[game_id] ,  
một số cái như App Theme (value là tên của folder themed đã clone trên), language, website, logo ( sử dụng cho email )

## Email 
1. Nội dung Email , clone again trong View/Emails/html/games/[game_alias].
Mục đích của game_alias là phân biệt tất cả các game trên nhiều OS ios, android, pc, window phone, ... là 1 game.
	- Hiện tại có 6 loại emails cho việc auto gửi tới users , sau các action khác nhau :  
		- account_created: sau khi tạo tài khoản.
		- account_updated: sau khi upupdate từ tài khoản Guest ( chơi ngay ) tới tài khoản có email verified (ex: FB).
		- account_verification: email gửi tới user để verìy email là thực , dùng khi user đăng ký = email ( khác với email đăng kí = FB thì mặc định là verified)
		- password_reset_request: email gửi link tới user để thực hiện hành động reset password
		- password_reseted: email gửi tới user sau khi thực hiện thành công reset password 
		- auto_reply: khi user submit issue tới action /problem/[report|webreport].ctp
	- Tên file đặt cùng với đuôi languge , locale ISO_3166 3 ký tự.
2. Add nội dung cho invite email ở trên platfor.
	- Vào link editSDK của game (http://admin.smobgame.com/plf/admin/games/editofsdk/34)
	- Chọn phần Invite FB, sau đó thêm các nội dung tương ứng.

## Website 
1. Tạo website trong tool admin để lấy thông tin cơ bản

2. Vào trong tool game để bind game liên quan với website này,  
nhiều game nhiều platform có thể chung 1 website.

2. Clone View/Themed/[WebsiteName] từe 1 themed cũ tới 1 themed mới.  
Thay đổi nội dung code theo bản cut HTML.

3. Clone Event/Themed/[WebsiteName] từ 1 themed cũ tới 1 themed mới.  
Thay đổi nội dung code ở đây.

4. Ghép bản cắt giao diện từ bên frontend, và các chỉnh sửa phù hợp. Việc ghép này làm trước trên server dev (Lưu ý clearcache khi làm trên prod)

## ALL
7. Update locale translation  (nếu cần) , thêm những word nào xuất hiện universe trên code,  
or những file có thể dùng đi dùng lại cho nhiều game. chú ý không thêm những từ xuất hiện tần xuất quá ít vào translation file.


Tất cả các bước trên có thể làm riêng rẽ ko đòi hỏi làm ngay theo thứ tự.
Hiện tại emails sẽ được gửi sau thời gian là 5 phút / lần.  
Đây cũng là vấn đề cần giải quyết vì đợi 5 phút lâu cho developer + tester.
