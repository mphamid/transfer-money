<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fee extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = ['transfer_id', 'amount'];

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(Transfer::class);
    }
}
