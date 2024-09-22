<?php

namespace App\Services;

use App\Enums\ActionsEnum;
use App\Models\History;
use App\Services\Service as MHService;
use Exception;
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

    /**
     * Список событий
     */
    public static function eventList(): array
    {
        $history = History::select('id', 'action', 'created_at', 'model_type', 'model_id')
            ->with('model:id,last_name,name,middle_name,created_at')->get();

        return $history->toArray();
    }

    /**
     * Детали события
     *
     * @return array
     */
    public static function showEvent(History &$history): void
    {
        $history->load('model');
    }

    /**
     * Удалить событие в корзину
     */
    public static function removeEvent(History $history): void
    {
        $history->delete();
    }

    /**
     * Восстановить событие
     */
    public static function restoreEvent(string $id): void
    {
        $history = History::onlyTrashed()->where('id', $id)->first();
        if (! $history) {
            throw new Exception('Ошибка! События нет в корзине');
        }
        $history->restore();
    }

    /**
     * Удалить событие
     */
    public static function deleteEvent(string $id): void
    {
        $history = History::withTrashed()->where('id', $id)->first();
        if ($history) {
            $history->forceDelete();
        }
    }
}
