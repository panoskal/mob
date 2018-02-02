<?php
//meta
define ('META_ENCODING',"utf-8");
define ('META_HTML','<html lang="vn" dir="ltr">');
define ('LANG_DIR', 'ltr');	
define ('LANG_DIR_OTHER', 'rtl');	
define ('ALIGNMENT', 'left');
define ('ALIGNMENT_OTHER', 'right');

// login
define ('LOGIN_TITLE','Tra cứu điểm số');
define ('LOGIN_TEXT','Tra cứu điểm số');
define ('LOGIN_USERNAME', "Nhập số điện thoai");
define ('LOGIN_DATE', "Nhập ngày kiểm tra");
define ('LOGIN_NOT_VALID_CREDENTIALS',"không hợp lệ số điện thoại");
define ('LOGIN_NOT_VALID_CAPTCHA',"không hợp lệ mã xác thực");
define ('LOGIN_NOT_VALID_DRAWDATE',"Nhập ngày kiểm tra!");
define ('LOGIN_LABEL','gửi');
define ('LOGIN_CAPTCHA','Mã xác thực');
define ('LOGIN_THANKS','Cám ơn bạn đã cung cấp số điện thoại, bạn sẽ nhận được dải mã dự thưởng qua SMS');
define ('LOGIN_SUBTITLE','Kiểm tra dải mã dự thưởng');
define ('LOGIN_SUBLINK', '<p style="color: #ff0b2c;" class="well text-center">Để tra cứu mã dự thưởng của chương trình khuyến mại Lộc Vàng Đón Xuân - giai đoạn I, vui lòng click <a href="http://203.128.246.195:8989/" target="_blank" class="loginlink">tại đây</a> !</p>');

// errors and error codes
define ('ERROR_FOUND',"<br />Có một lỗi:");
define ('PLEASE_TRY_AGAIN',"Xin vui lòng thử lại sau.");
$error_codes = array ();
$error_codes [0][0] = 'Tổng lỗi trên WSDL ';
$error_codes [0][1] = 'No languages present on the database!!';
$error_codes [98][0] = 'Bạn chưa đăng ký tham gia chương trình';
$error_codes [102][0] = 'Không hợp lệ số điện thoại';
$error_codes [301][0] = 'Bạn chưa đăng ký tham gia chương trình';

// winners
define ('WINNERS_NO_WINNERS_FOUND', 'Không có người trúng được tìm thấy. Xin vui lòng ghé thăm sau.');
define ('WINNERS_DATE', 'Ngày tháng');
define ('WINNERS_NAME', 'Họ và tên');
define ('WINNERS_MSISDN', 'Điện thoại');
define ('WINNERS_PRIZE', 'Giải thưởng');
									   
//various	
define ('CLICK_FOR_MENU','Nhấn vào cho thực đơn');
define ('BACK','&nbsp; <a href="javascript:history.go(-1)">trang trước</a>');
define ('ALERT_WARNING','Báo động ');
define ('PRIZES_MENU_TITLE','Giải thưởng');
define ('TERMS_MENU_TITLE','Điều kiện & Thể lệ');
define ('WINNERS_MENU_TITLE','Danh sách khách hàng nhận giải');
?>