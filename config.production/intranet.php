<?php
return [
    'session_exp'=>600,
    'is_production' => env('IS_PRODUCTION', false),
    'MAIL_FROM' => env('MAIL_FROM_NAME', 'AYANA Report'),
    'password_expired_day_length'=>90,
    'password_expired_notify_treshold'=>30,
    'EXCHANGE_RATE'=>explode(',', env('EXCHANGE_RATE', 'USD')),
    'PNL_SAP' =>env('PNL_SAP_URL'),
    'REFX_SAP'=>env('SAP_REFX_REVENUE_URL'),
    'FNB_BOM_COST'=>env('FNB_BOM_COST_URL'),
    'RECIPE_COST_URL'=>env('RECIPE_COST_URL'),
    'BI_EXCHANGE'=>env('BI_EXCHANGE_URL'),
    'TALENTA'=> [
        '3589'=>[
            'key'=>env('TALENTA_USER_AYANA_BALI'),
            'secret'=>env('TALENTA_PASS_AYANA_BALI')
        ],
        '1270'=>[
            'key'=>env('TALENTA_USER_PPC_PNB'),
            'secret'=>env('TALENTA_PASS_PPC_PNB')
        ],
        '3574'=>[
            'key'=>env('TALENTA_USER_PAD_HOTEL'),
            'secret'=>env('TALENTA_PASS_PAD_HOTEL')
        ],
        '3524'=>[
            'key'=>env('TALENTA_USER_PAD_PROPERTY'),
            'secret'=>env('TALENTA_PASS_PAD_PROPERTY')
        ],
        '3590'=>[
            'key'=>env('TALENTA_USER_WKK'),
            'secret'=>env('TALENTA_PASS_WKK')
        ],
        '3522'=>[
            'key'=>env('TALENTA_USER_NJP'),
            'secret'=>env('TALENTA_PASS_NJP')
        ]

    ],
    'QUINOS_POS'=> [
        'PROPERTY_TOKEN'=>env('QUINOS_TOKEN', ''),
        'PROPERTY_NAME'=>env('QUINOS_PROPERTY', '')
    ],
    'midtrans_client_key'=>array(
        'sandbox'=>array(
            'kms'=>env('MIDTRANS_CLIENT_KEY_SANDBOX',''),
        ),
        'production'=>array(
            'kms'=>env('CLIENT_KEY_KMS1_PROD',''),
        )
    ),
    'midtrans_server_key'=>array(
        'sandbox'=>array(
            'kms'=>env('MIDTRANS_SERVER_KEY_SANDBOX',''),
        ),
        'production'=>array(
            'kms'=>env('SERVER_KEY_KMS1_PROD',''),
        )
    ),
    'zapier_vcc_salt'=>'rOL1HYKZMn',
    'rfc' => array(
        'ashost' => '192.168.10.2', //prod
        'sysnr' => '00',
        'client' => '110',
        'user' => 'biznet01',
        'passwd' => 'biznet99',
        'trace' => 'SapConnection::TRACE_LEVEL_OFF',
    ),
    'rfc_dev' => array(
        'ashost' => '192.168.10.22',
        'sysnr' => '20',
        'client' => '310',
        'user' => 'SPN_MM04',
        'passwd' => 'Master2021',
        'trace' => 'SapConnection::TRACE_LEVEL_OFF',
    ),
    'rfc_QAS' => array(
        'ashost' => '192.168.10.2',
        'sysnr' => '00',
        'client' => '120',
        'user' => 'spn_is',
        'passwd' => 'biznet2017',
        'trace' => 'SapConnection::TRACE_LEVEL_OFF',
    ),
    'rfc_prod' => array(
        'ashost' => '192.168.10.2', //prod
        'sysnr' => '00',
        'client' => '100',
        'user' => 'SYS_BGJOB',
        'passwd' => 'OnlYbg2019',
        'trace' => 'SapConnection::TRACE_LEVEL_OFF',
    ),
    'BOOK4TIME'=>env('BOOK4TIME_URL'),
    'BOOK4TIME_APPOINTMENT'=>env('BOOK4TIME_URL_APPOINTMENT'),
    'BOOK4TIME_GUEST_TYPE'=>env('BOOK4TIME_URL_GUEST_TYPE'),
    'BOOK4TIME_CUSTOMER'=>env('BOOK4TIME_URL_CUSTOMER'),
    'BOOK4TIME_CREDENTIAL' => [
        'BOOK4TIME_ACCOUNT_TOKEN'=>env('BOOK4TIME_ACCOUNT_TOKEN'),
        'BOOK4TIME_API_TOKEN'=>env('BOOK4TIME_API_TOKEN'),
        'BOOK4TIME_USER_TOKEN'=>env('BOOK4TIME_USER_TOKEN')
    ],

];
