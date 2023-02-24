<?php

namespace App\Models;

use App\Enums\TransactionType;
use App\Enums\TransactionWhere;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
            'user_id',
            'where',
            'type',
            'dateTime',
        ];
    protected $casts = [
            'type' => TransactionType::class,
            'where' => TransactionWhere::class
        ];
}
