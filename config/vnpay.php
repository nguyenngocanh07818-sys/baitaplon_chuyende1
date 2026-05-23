<?php
return [
    'vnp_TmnCode'   => env('VNPAY_TMN_CODE', 'VEBHAG5X'),
    'vnp_HashSecret'=> env('VNPAY_HASH_SECRET', 'FAIDVGU08CM4XSYU1T0020HH0F7GVU6T'),
    'vnp_Url'       => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'vnp_Returnurl' => env('VNPAY_RETURN_URL', 'http://127.0.0.1:8000/checkout/vnpay-return'),
];
