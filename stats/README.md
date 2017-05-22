# Thống kê dữ liệu 
------------------

Tóm tắt một số thứ dùng để thống kê: 

- Hiện thời sẽ sử dụng cronjob để tổng hợp (aggregate) dữ liệu. 
- Highchart.js để vẽ biểu đồ. 
- Pickadate.js để tạo lịch ngày tháng.

Vì khả năng dữ liệu ở nhiều bảng khác nhau khá tương đồng, và hiển thị cũng gần tương đồng. Nên khi làm trên source nên chú ý: 

- Nếu tổng hợp theo ngày thì bảng sẽ có field `day`, số liệu lưu vào field `value`
- Sử dụng 1 số class , function tự viết như `AggregateShell`, `HighchartHelper`. Một số function filter, fill dữ liệu ngày tháng trong `AppController`, `AppModel`.
- Luôn có `created`, `modified` fields nhưng chỉ là thời điểm save dữ liệu ko phải dữ liệu trên ngày đó
- Nổng hợp theo ngày thì nên sử dụng chính tên table lấy đữ liệu đó + thêm `by_day`
- Luôn đặt tên `log_` nếu những table sử dụng để log , thống kê.
- Nếu tạo thêm bảng và model.php trong stats thì sẽ copy vào app/Model để tiện đồng bộ khi sử dụng schema.php build table hiện thời.