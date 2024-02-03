<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TransferStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\ReportResource;
use App\Services\CardServices\CardRepository;

class CardToCardController extends Controller
{
    public function __construct(private readonly CardRepository $cardService)
    {
    }

    public function transfer(TransferRequest $request)
    {
        $transfer = $this->cardService->cardToCard($request->source, $request->destination, $request->amount);
        return response()->json([
            'status' => $transfer->status->value,
            'track_number' => $transfer->track_number
        ], $transfer->status == TransferStatusEnum::Success ? 200 : 421);
    }

    public function report()
    {
        return ReportResource::collection($this->cardService->MostTransactionUser());
    }
}
