<?php
//CẤU HÌNH TÀI KHOẢN (Configure account)
define('EMAIL_BUSINESS','dev.baokim@bk.vn');    //Email Bảo kim
/*
define('MERCHANT_ID','1234');                // Mã website tích hợp
define('SECURE_PASS','43284c9d2ed45ff1');   // Mật khẩu

// Cấu hình tài khoản tích hợp
define('API_USER','merchant');  //API USER
define('API_PWD','1234');       //API PASSWORD
*/

define('PRIVATE_KEY_BAOKIM','-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDAd1CQHP+lTYcD
7FzNEehcV+JLhy9oQdT70WDpaF6V4cCd22qHWVl+ScrqFXQDj1672DIHfFRe0IRq
KbUkMqIeObfLcblV7pv8rcTGF2r05f5a6UQ0D7+dSvOu1lY0mkqSDBS+YozLdM86
vqGQekAmFf7QwOJCvaxyy5HBjDO9OYsqNqNwmc74qXpGpHPkaSuZXBbScFmbuTso
PaSagnIuUwDonybRhr/kx/zB+JNS0WfA/1skppNsDYnmV61bAcNi/1EidnZ+dIqR
UtMubVXhIuVtUFdstGhidQgz9fYoREg3PzHOwMDKvWQY23+oRPvJskt72Jle1ELh
lSSklz0DAgMBAAECggEAYF9eR8s9d1hKsvw3u7er0hFvjsIyStIsA6vkCvvn4MUZ
3fFwdjWXSlVI9c2oOeAgX47x/i2TUaa3pzEbMvTnmJruHUDkvNKt6OhaD5bKR7ai
loMXU7pPCEPvoSxSHiTkEh1ahbpzJa1n1tJ9tAqC9I8u+PicUy8GmrS0V2YMwkKd
u6/7MiffQ+ZuwHu6eqJ0/bUob0U34UXxLooRWrZU1888SLO0VL7AjzsXmzXxVg4U
RYGIxY+sCqPwiu8Pbna57DB1V+RkYPnqb0dNaPXdB+pK/0o4ilZLJoWZazJuiYSB
mTEHbd+Y9Le4Ch2wgi6NBdJL81vKbKeh8OqkjA21gQKBgQD+gCrQvUbxKuwiYNw7
JGRzYvLLEAhRQwntQskJoZtaUHf3knN51GH1fVgyVvnvAFVfQqM4d3SJ8Qxj+1XL
Uqr7p4of2FrToy4QvQcr+o7bKsKGQc6fmsZnrWx5zPIKyibsfSWmwtFlvtjdW2eT
vRd32R5t7pSaov6Zh1wXQNJe3wKBgQDBmZaQ57kUoaJ8qimeTTXmhLeiGSwwfKYs
aQzCIKjQHHtlA+qLWNw7AT7PHKDuXridH3QdIRWw55h2zu01DihIZWC1560TxjHK
w450xkEJxSws6y9nBxxkVgqtua04aVMRWpgaWGShaOBOzsH5ZmEyJoEBFKHAAncP
osSuGhv6XQKBgHuw9x40oAIehl2/DcqAyYF7Gd2vkRtNpcl2qBbSQJsIeQYOW6le
m62gmfj9ZMPQOa3uyT1scWuJiCgfw3bqWsesiekoUhyCNqVT+eRaBoxmn9x4p1/S
7ZL+KraX8RtlXL2bpW0DSppUwznybsjTIlDRaYSsa/UsOumLbCqxCo2pAoGAYsEL
ssLhAAsrLVhHfn54HixY15Drn5gV09WKMoQiWHYO44pJ2+bqABc0iMVbufwLQ+rF
eg5kWCbq+L+49DVSiwtbd67D9LBGud1jD2IJzwgK7qbROUbBTtUtAU0TdREU1GdX
6yhnvIYY1VpWS/fTYJePepOhpqYbQZiPpxeeaekCgYBq53J66Jwy++boIb3+105U
CqBJZYIhmdvjxIDBLtitl2knI87YJ92aIbJlPE+Lk9sHkgkPR0xTDJDjqQkM6RVt
roMw3Hjjd8ZyZmvBT+LqcLWMjBbMnPbhSEFf1R+8SLnpg7A8rYxsNw4zaJ8DojuW
/FQMP+t069IBaLsNjfaiwQ==
-----END PRIVATE KEY-----');

define('BAOKIM_API_SELLER_INFO','/payment/rest/payment_pro_api/get_seller_info');
define('BAOKIM_API_PAY_BY_CARD','/payment/rest/payment_pro_api/pay_by_card');
define('BAOKIM_API_PAYMENT','/payment/order/version11');

define('BAOKIM_URL','https://www.baokim.vn');
//define('BAOKIM_URL','http://baokim.dev');
//define('BAOKIM_URL','http://kiemthu.baokim.vn');

//Phương thức thanh toán bằng thẻ nội địa
define('PAYMENT_METHOD_TYPE_LOCAL_CARD', 1);
//Phương thức thanh toán bằng thẻ tín dụng quốc tế
define('PAYMENT_METHOD_TYPE_CREDIT_CARD', 2);
//Dịch vụ chuyển khoản online của các ngân hàng
define('PAYMENT_METHOD_TYPE_INTERNET_BANKING', 3);
//Dịch vụ chuyển khoản ATM
define('PAYMENT_METHOD_TYPE_ATM_TRANSFER', 4);
//Dịch vụ chuyển khoản truyền thống giữa các ngân hàng
define('PAYMENT_METHOD_TYPE_BANK_TRANSFER', 5);

?>