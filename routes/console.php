<?php

use App\Models\History;
use App\Services\HistoryService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('history:list', function () {
    $history = HistoryService::eventList();
    dd($history);
})->purpose('Cписок событий');

Artisan::command('history:remove {id?}', function ($id = null) {
    if (! $id) {
        return;
    }
    $history = History::find($id);
    if ($history) {
        HistoryService::removeEvent($history);
    } else {
        $this->error('Событие не найдено');

        return;
    }
    $this->info('Событие удалено в корзину');
})->purpose('Удаление события в корзину. Использование history:remove id_события');

Artisan::command('history:restore {id?}', function ($id = null) {
    if (! $id) {
        return;
    }
    try {
        HistoryService::restoreEvent($id);
    } catch (Exception $e) {
        $this->error($e->getMessage());

        return;
    }
    $this->info('Событие восстановлено');
})->purpose('Восстановление события');

Artisan::command('history:delete {id?}', function ($id = null) {
    if (! $id) {
        return;
    }
    try {
        HistoryService::deleteEvent($id);
    } catch (Exception $e) {
        $this->error($e->getMessage());

        return;
    }
    $this->info('Событие удалено');
})->purpose('Полное удаление события');
