<?php

namespace App\Models;

use App\Enums\ActionsEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'model_type',
        'model_id',
        'before',
        'after',
        'action',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
        'action' => ActionsEnum::class,
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $keyType = 'string';

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
