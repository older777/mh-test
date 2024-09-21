<?php

namespace App\Enums;

enum ActionsEnum: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case REMOVE = 'remove';
    case DELETE = 'delete';
    case RESTORE = 'restore';
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case PASSWORD = 'password';
    case RESET = 'reset';
    case VERIFY = 'verify';
    case VERIFIED = 'verified';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
