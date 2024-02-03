<?php

namespace App\Base;

use Exception;

class ServiceException extends Exception
{
    public function getResponseMessage(): string
    {
        return !empty($this->message) ? $this->message : (isset(__('exceptions')[get_called_class()]) ? __('exceptions')[get_called_class()] : '');
    }
}
