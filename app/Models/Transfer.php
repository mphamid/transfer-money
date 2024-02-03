<?php

namespace App\Models;

use App\Enums\TransferStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transfer extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'track_number',
        'source',
        'destination',
        'amount',
        'status'
    ];
    protected $casts = [
        'status' => TransferStatusEnum::class
    ];

    public function source_card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'source', 'id');
    }

    public function source_transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transfer_id', 'id')->where('card_id', $this->source);
    }

    public function destination_card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'destination', 'id');
    }

    public function destination_transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transfer_id', 'id')->where('card_id', $this->destination);
    }

    public function fee(): HasOne
    {
        return $this->hasOne(Fee::class, 'transfer_id');
    }
}
