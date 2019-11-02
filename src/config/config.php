<?php

return [
    /** - 必填配置` - **/
    // 商户ID
    'sysid' => env('ALLINPAY_SYSID', '1910150943129056703'),
    // 接口地址
    'server_url' => env('ALLINPAY_SERVER_URL', 'https://fintech.allinpay.com/service/soa'),
    // 商户私钥
    'private_key_path' => env('ALLINPAY_PRIVATE_KEY_PATH', __DIR__ . '/allinpay_cert/123456.pfx'),
    // 商户私钥
    'tl_cert_path' => env('ALLINPAY_TL_CERT_PATH', __DIR__ . '/allinpay_cert/TLCert(prod).cer'),
    // 用户密码
    'private_password' => env('ALLINPAY_PRIVATE_PASSWORD', '111111'),
    // 别名
    'alias' => env('ALLINPAY_ALIAS', '1910150943129056703'),
    // 版本
    'version' => env('ALLINPAY_VERSION', '2.0'),
    // 回调地址
    'back_url' => env('BACK_URL', '/api/pay/notify-url/'),
    // 源账户集编号
    'source_account_set_no' => env('SOURCE_ACCOUNT_SET_NO', '2000000'),
    // 目标账户集编号
    'account_set_no' => env('ACCOUNT_SET_NO', '100001'),
    // 行业代码
    'industry_code' => env('INDUSTRY_CODE', '1910'),
    // 行业名称
    'industry_name' => env('INDUSTRY_NAME', '其他'),
    // 电子签约地址
    'sign_contract_url' => env('SIGN_CONTRACT_URL', 'https://fintech.allinpay.com/service/soa/MemberServlet.do'),
    // 电子签约成功跳转地址
    'sign_contract_jump_url' => env('SIGN_CONTRACT_URL', 'https://www.jkweixin.com'),
    // 电子签约回调地址
    'sign_contract_callback_url' => env('SIGN_CONTRACT_URL', '/api/contract-no/notify-url')

];
