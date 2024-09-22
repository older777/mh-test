<?php

namespace App\Services;

use App\Enums\ActionsEnum;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

/**
 * @author art
 */
class UserService extends Service
{
    public static $params = [];

    /**
     * (non-PHPdoc)
     *
     * @see \App\Services\Service::setParams()
     */
    public static function setParams(array $params): void
    {
        self::$params = $params;
    }

    /**
     * Данные пользователя(ей)
     */
    public static function getUsers(?string $id = null): mixed
    {
        if ($id) {
            $user = Cache::get('user_'.$id);
            if ($user) {
                return $user;
            } else {
                $user = User::withCount('history')->find($id);
                Cache::set('user_'.$id, $user, 15 * 60);

                return $user;
            }
        }
        $users = Cache::get('users');
        if ($users) {
            return $users;
        } else {
            $users = User::withCount('history')->get();
            Cache::set('users', $users, 15 * 60);

            return $users;
        }
    }

    /**
     * Обновление данных
     */
    public static function userUpdate(Request $request, string $id): void
    {
        $request->validate([
            'last_name' => ['string', 'max:255'],
            'name' => ['string', 'max:255'],
            'middle_name' => ['string', 'max:255'],
            'email' => ['string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['string', 'max:255'],
            'password' => ['confirmed', Rules\Password::defaults()],
        ]);

        $user = User::find($id);

        $before = clone $user;

        if (! $user) {
            throw ValidationException::withMessages([
                'id' => ['Ошибка идентификатора пользователя'],
            ]);
        }

        if ($request->email) {
            if (($email = User::where('email', $request->email)->first()) && $email && $email->id != $id) {
                throw ValidationException::withMessages([
                    'email' => ['Данный емайл адрес занят'],
                ]);
            }
        }

        $user->update($request->all());

        HistoryService::event($user, ActionsEnum::UPDATE, $before->toArray(), $user->toArray());
    }

    /**
     * Удаление пользователя в корзину
     */
    public static function userRemove(User $user): void
    {
        $user->tokens()->delete();
        $user->history()->delete();
        $user->delete();
        HistoryService::event($user, ActionsEnum::REMOVE, $user->toArray(), ['status' => 'removed']);
    }

    /**
     * Восстановление пользователя из корзины
     */
    public static function userRestore(string $id): void
    {
        $user = User::withTrashed()->where('id', $id)->first();
        if ($user && ! $user->trashed()) {
            throw new Exception('Ошибка! Пользователь не в корзине');
        }
        $before = clone $user;
        $user->restore();
        $user->history()->restore();
        HistoryService::event($user, ActionsEnum::RESTORE, $before->toArray(), $user->toArray());
    }

    /**
     * Полное удаление пользователя
     */
    public static function userForceDelete(string $id): void
    {
        $user = User::withTrashed()->where('id', $id)->first();
        if (! $user) {
            throw new Exception('Ошибка! Пользователь не в корзине');
        }
        $user->tokens()->delete();
        $user->history()->forceDelete();
        $user->forceDelete();
        HistoryService::event($user, ActionsEnum::DELETE, $user->toArray(), ['status' => 'deleted']);
    }

    /**
     * Список удаленных пользователей
     */
    public static function trashedUsers(): mixed
    {
        return User::onlyTrashed()->get();
    }

    /**
     * Удаление пользователей в корзину
     */
    public static function userGroupRemove(array $ids): void
    {
        $users = User::whereIn('id', $ids)->get();
        foreach ($users as $user) {
            $user->tokens()->delete();
            $user->history()->delete();
            $user->delete();
            HistoryService::event($user, ActionsEnum::REMOVE, $user->toArray(), ['status' => 'removed']);
        }

    }

    /**
     * Восстановление пользователей из корзины
     */
    public static function userGroupRestore(array $ids): void
    {
        $users = User::onlyTrashed()->whereIn('id', $ids)->get();
        foreach ($users as $user) {
            $before = clone $user;
            $user->restore();
            $user->history()->restore();
            HistoryService::event($user, ActionsEnum::RESTORE, $before->toArray(), $user->toArray());
        }

    }

    /**
     * Полное удаление пользователей
     */
    public static function userGroupDelete(array $ids): void
    {
        $users = User::withTrashed()->whereIn('id', $ids)->get();
        foreach ($users as $user) {
            $user->tokens()->delete();
            $user->history()->forceDelete();
            $user->forceDelete();
            HistoryService::event($user, ActionsEnum::DELETE, $user->toArray(), ['status' => 'deleted']);
        }
    }
}
