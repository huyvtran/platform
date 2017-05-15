Documentations: 
- https://book.cakephp.org/2.0/en/development/testing.html
- https://phpunit.de/manual/3.7/en/index.html

### Installing
Đọc cakephp documentation link phía trên  

### Sử dụng
- Web browser - tương đối chậm. Chỉ sử dụng để nhìn code coverage chi tiết của 1 file nào đó.
![74979d38ce3745658e9d40fbd003cd36](https://cloud.githubusercontent.com/assets/300961/23014387/5773a75e-f461-11e6-9e64-6fe6309b4608.png)  
- Console: 
    - `Console/cake test app All` chạy tất cả các tests của app. 
	- Chạy single test giống trong cakephp documentations. VD: `Console/cake Test app Console/Command/EmailMarketingShell`
- Jenkins: http://54.251.107.39:8080/job/plf/ tự động chạy all tests cho plf sau mỗi lần push. Tự động generate code coverage toàn project vào 12h đêm.

### Lợi ích 
- Hiện tại vì chạy song song, chứ ko phải chạy trước khi deploy code , nên mình chỉ có thể phát hiện lỗi nhanh , chứ ko phải ngăn chặn.
Mình làm vậy để speed up quá trình developing, đỡ mất công đợi code deploy quá lâu.
- Phát hiện nhanh hầu hết lỗi cú pháp
- Tốn chút thời gian viết unit test ban đầu nhưng code nhanh về sau. Ví dụ:
    - Vì có sẵn seed data, không mất công làm thủ công nhập data.
	- Không dính phải lỗi cũ, tránh mất thời gian sửa lại
	...
- Người khác làm cùng mình tiếp cận code nhanh hơn. Vì có sẵn bộ unit test, sẽ giúp hiểu hơn 1 phần code. Ví dụ data seed như thế nào, trả về như thế nào , ...
- Update thêm logic code , không làm hỏng logic cũ do chưa tính toán hết.

### Một số lưu ý và chuẩn chung 
Một số thứ cần làm giống nhau, để tránh lộn xộn.
- Fixture::$fields: không cần sử dụng vì mình sẽ sử dụng Fixture::$import, vì lý do tiện lợi, không phải update code khi schema thay đổi
- Fixture::$imports: Ví dụ code
   ```php
   class ArticleFixture extends AppCakeTestFixture {

	public $import = array(
		'model' => 'Article',
		'record' => true // không sử dụng thuộc tính này, vì trên server với dữ liệu rất lớn, nếu import từ data thực là gần như ko thể
	);
	```
	không sử dụng thuộc tính record


- Database sử dụng hoàn toàn khác với database default hiện tại
- Khi sử dụng Fixture để define dữ liệu + import schema. Các bảng sẽ tự drop và re-create để làm cho schema luôn là mới nhất.
- Để giúp code dễ hơn, ko phải define quá nhiều fixtures trong files test, và tránh lỗi lặt vặt khác trong quá trình test. Tất cả các test file phải được extend `AppCakeTestCase` và `AppControllerTestCase` thay vì `CakeTestCase` và `ControllerTestCase`. 
- Trong khi extends AppCakeTestCase or AppControllerTestCase, chúng ta sẽ có thêm $_SERVER['testing'] = true; khi chạy test có thể dùng trong một số trường hợp thích hợp để phân biệt môi trường test.