<?php

namespace App\Enums;

enum TransferStatusEnum: string
{
    case Init = 'init';
    case Success = 'success';
    case Failed = 'failed';
}
