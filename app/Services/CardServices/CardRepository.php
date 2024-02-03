<?php

namespace App\Services\CardServices;

use App\Models\Transfer;

interface CardRepository
{
    /**
     * @param string $sourceCard
     * @param string $destinationCard
     * @param int $amount
     * @return Transfer
     */
    public function cardToCard(string $sourceCard, string $destinationCard, int $amount): Transfer;

    /**
     * @param int $userNumber
     * @param int $transactionNumber
     * @return mixed
     */
    public function MostTransactionUser(int $userNumber = 3, int $transactionNumber = 10);
}
