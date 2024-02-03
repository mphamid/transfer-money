<?php

use App\Services\CardServices\Exceptions\AmountExceededLimitationException;
use App\Services\CardServices\Exceptions\DestinationCardNotFoundException;
use App\Services\CardServices\Exceptions\InsufficientBalanceException;
use App\Services\CardServices\Exceptions\SourceAndDestinationIsSameException;
use App\Services\CardServices\Exceptions\SourceCardNotFoundException;

return [
    InsufficientBalanceException::class => 'موچودی حساب کافی نیست',
    SourceCardNotFoundException::class => 'کارت مبدا در سیستم موجود نیست',
    DestinationCardNotFoundException::class => 'کارت مقصد در سیستم موجود نیست',
    AmountExceededLimitationException::class => 'مبلغ از حد مجاز تراکنش را رعایت نکرده',
    SourceAndDestinationIsSameException::class => 'شماره کارت مبدا و مقصد نمیتواند یکی باشد',
];
