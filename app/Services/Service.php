<?php

namespace App\Services;

/**
 * @author art
 */
abstract class Service
{
    public static $params = [];

    /**
     * Свой метод записи данных
     */
    abstract public static function setParams(array $params): void;

    /**
     * Получить переменные
     */
    public static function getParams(): array
    {
        return static::$params;
    }
}
