<?php 

return [
    'serverkey' => [
    	'prod'=>[
    		'kms1'=> env('SERVER_KEY_KMS1_PROD', false),
    		'njp1'=> env('SERVER_KEY_NJP1_PROD', false)
    	], 
    	'sandbox'=>[
    		'kms1'=> env('SERVER_KEY_KMS1_SANDBOX', false),
    		'njp1'=> env('SERVER_KEY_NJP1_SANDBOX', false)
    	]
    ],
    'sap_url' => [
        'invoice_detail'=> env('PAYMENT_INVOICE_DETAIL', false),
        'invoice_list'=> env('PAYMENT_INVOICE_LIST', false),
        'va_detail'=> env('PAYMENT_BCA_VA_DETAIL', false),
        'cc_notif'=> env('PAYMENT_CC_NOTIF', false),
        'map_order_id'=> env('PAYMENT_MAPPING_ORDER_ID', false),

        'invoice_detail_qas'=> env('QAS_PAYMENT_INVOICE_DETAIL', false),
        'invoice_list_qas'=> env('QAS_PAYMENT_INVOICE_LIST', false),
        'va_detail_qas'=> env('QAS_PAYMENT_BCA_VA_DETAIL', false),
        'cc_notif_qas'=> env('QAS_PAYMENT_CC_NOTIF', false),
        'map_order_id_qas'=> env('QAS_PAYMENT_MAPPING_ORDER_ID', false),

        'invoice_detail_pre_live'=> env('PRE_PRODUCTION_PAYMENT_INVOICE_DETAIL', false),
        'invoice_list_pre_live'=> env('PRE_PRODUCTION_PAYMENT_INVOICE_LIST', false),
        'va_detail_pre_live'=> env('PRE_PRODUCTION_PAYMENT_BCA_VA_DETAIL', false),
        'cc_notif_pre_live'=> env('PRE_PRODUCTION_PAYMENT_CC_NOTIF', false),
        'map_order_id_pre_live'=> env('PRE_PRODUCTION_PAYMENT_MAPPING_ORDER_ID', false)
    ],
    'is_production'=>env('IS_PRODUCTION', false),
    'username'=>env('USERNAME_SAP_API',''),
    'password'=>env('PASSWORD_SAP_API',''),
    'username_qas'=>env('USERNAME_SAP_API_QAS',''),
    'password_qas'=>env('PASSWORD_SAP_API_QAS','')
];

?>