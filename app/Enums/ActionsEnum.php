<?php

namespace App\Enums;

enum ActionsEnum: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case REMOVE = 'remove';
    case DELETE = 'delete';
    case RESTORE = 'restore';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
