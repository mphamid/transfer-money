<?php
return [
    'fee' => env('CARD_TO_CARD_FEE_AMOUNT', 500),
    'minimum_amount' => env('CARD_TO_CARD_MINIMUM_AMOUNT', 1000),
    'maximum_amount' => env('CARD_TO_CARD_MAXIMUM_AMOUNT', 50000000),
];
