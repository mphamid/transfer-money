<?php
return [
    'transfer' => [
        'success' => [
            'source' => "انتقال از کارت+کارمزد" . "\n" . ":amount" . "\n" . "مانده: :balance" . "\n" . "کارت : :card_number",
            'destination' => "واریز کارت به کارت". "\n" . ":amount" . "\n" . "مانده: :balance" . "\n" . "کارت : :card_number",
        ],
        'failed'=>[
            'source'=>'خطایی در زمان عملیات کارت به کارت رخ داد'
        ]
    ]
];
