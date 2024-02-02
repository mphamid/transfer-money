<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use Illuminate\Http\Request;

class CardToCardController extends Controller
{
    public function transfer(TransferRequest $request)
    {
dd($request->all());
    }
    public function report()
    {

    }
}
