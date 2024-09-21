<?php

namespace App\Services;

use App\Enums\ActionsEnum;
use App\Models\History;
use App\Services\Service as MHService;
use Illuminate\Database\Eloquent\Model;

/**
 * @author art
 */
class HistoryService extends MHService
{
    public static $params = [];

    /**
     * (non-PHPdoc)
     *
     * @see MHService::setParams()
     */
    public static function setParams(array $params): void
    {
        self::$params = $params;
    }

    /**
     * Записать событие
     */
    public static function event(Model $model, ActionsEnum $action, ?array $before, array $after): void
    {
        if ($model->isRelation('history')) {
            $history = new History(['before' => $before, 'after' => $after, 'action' => $action]);
            $history->model()->associate($model)->save();
        }
    }
}
