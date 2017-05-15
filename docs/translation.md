Documentation: 
-------------
- http://book.cakephp.org/2.0/en/core-libraries/internationalization-and-localization.html
- http://book.cakephp.org/2.0/en/console-and-shells/i18n-shell.html

Cần những bước sau đây:  
-----------------------
- Console/cake i18n → [E]xtract POT file from sources → thường là chấp nhận gõ enter tất cả các bươcs tiếp theo
- Framework sẽ export ra files chưa trong thư mục ”app/Locale”
- Nếu đây là ngôn ngữ chưa có trong framework sẽ copy default.pot ( có thể thêm cake.pot , thường là ko cần) trong location, ví dụ: eng/LC_MESSAGES/default.po
- Nếu ngôn ngữ này đã có file trong framework thì dùng Poedit mở rồi sử dụng chức năng import của Poedit import file app/Locale/default.po. File phải có tên tương ứng, default.pot → default.po or cake.pot → cake.po

Lưu ý:  
---------
- Framework generate ra file .pot , nhưng khi dùng Poedit phải save as file .po
- Với những validation không cần translation vì chủ yếu dùng admin, thì phải sử dụng validationDomain public $validationDomain = 'not_translate'; trong model . Tên giống nhau “not_translate” để tránh sinh ra quá nhiều files. 
- Mặc định tất cả câu cần dịch mới trong platform sẽ là tiếng Việt. Điều này tránh phải dịch 1 từ làm 2 lần , ví dụ từ "character" "nhân vật", sẽ phải dịch làm 2 lần, nếu trong code xuất hiện 2 từ này. Nhưng nếu làm như trên thì mình sẽ loại bỏ từ character trong quá trình dịch.
