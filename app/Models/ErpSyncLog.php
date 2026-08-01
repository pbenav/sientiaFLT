<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErpSyncLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type', 'entity_id', 'action', 'request_data',
        'response_data', 'status_code', 'status', 'error_message', 'retry_count',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'status_code' => 'integer',
        'retry_count' => 'integer',
    ];
}
