## Задание для MH

### Установка

Инструкция по установке. Скачать GIT-репозиторий *git clone git@github.com:older777/mh-test.git mh-test*

Создать новый *.env* файл из *.env.example*.

Выполнить следующие команды:

    cd mh-test
    composer install
    chmod 755 sail

Ресурсы всех вендров, опубликовать все: All providers and tags

    ./sail artisan vendor:publish 

![Vendor publish](/storage/app/vendor.png)

Запустить докер окружение проекту:

    ./sail build
    ./sail up

![Docker Sail](/storage/app/mh-1.gif)
    
В новом окне терминала выполнить команды:

    ./sail artisan migrate
    ./sail artisan db:seed
    ./sail artisan l5-swagger:generate

Данные пользователя для авторизации логин/пароль: admin@local.localhost/123
Данные авторизации MySQL: localhost:3306, логин/пароль: laravel/password

Перейти на страницу ***L5 Swagger UI*** [http://localhost/api/documentation/](http://localhost/api/documentation/). 

### API

Открыть вкладку "Guest URI", гостевые запросы (Санктум токен не требуется):

![Guest API](/storage/app/guest.png)

    GET /
    POST /api/login
    POST /api/reset-password
    POST /api/forgot-password
    POST /api/register

Запросы "/" получить данные версии, "/api/register" - зарегистрировать нового пользователя, "/api/forgot-password" - запрос на восстановление пароля. "/api/reset-password" - сбросить пароль пользователя с помощью кода.
Все емайл сообщения поступают в laravel.log (storage/logs/laravel.log), коды сбросов в ссылках внутри сообщений.

Выполнить запрос авторизации */api/login* с данными пользователя admin, по кнопке "Try it out". Получить токен авторизации.

![Sanctum](/storage/app/sanctum.png)

Для запросов требующих аутентификацию - ввести данные токена (по кнопке *Authorize*) в модальном окне авторизации, сохранить.

![Аутентификация](/storage/app/auth.png)

Запросы авторизованных пользователей *Authenticated URI*:

    GET /api/auth/logout
    POST /api/auth/email/verification-notification (выслать код для подтверждения емайл)
    GET /api/auth/verify-email/{id}/{hash} (подтвердить емайл)
    GET /api/auth/me (информация о текущем пользователе)
    GET /api/auth/history (список событий в таблице histories)
    GET /api/auth/history/{history} (детальная информация события)
    DELETE /api/auth/history/{history} (удалить событие в корзину)
    GET /api/auth/history/{history}/restore (восстановить событие из корзины)
    DELETE /api/auth/history/{history}/force (полное удаление события)
    GET /api/auth/users (список пользователей)
    GET /api/auth/users/{id} (получить детальную информацию пользователя)
    PUT /api/auth/users/{id} (обновить данные пользователя)
    DELETE /api/auth/users/{user} (удалить пользователя в корзину)
    GET /api/auth/users/{id}/restore (восстановить пользователя)
    DELETE /api/auth/users/{id}/force (полное удаление пользователя и его событий)
    GET /api/auth/users/all/trashed (список пользователей в корзине)
    DELETE /api/auth/users/group/remove (групповое удаление пользователей в коризину)
    POST /api/auth/users/group/restore (групповое восстановление пользователей)
    DELETE /api/auth/users/group/delete (групповое полное удаление пользователей)

Для некоторых запросов, емайл сообщения поступают в лог storage/logs/laravel.log

Пример верификации емайла:

![Данные для верификации емайл](/storage/app/verificate.png)

### Консольные команды

Выполняются по команде *./sail artisan history:__имя_команды__*

![Console](/storage/app/console.png)

### Заключение

Использован скаффолдинг авторизации Laravel Breeze (для API). Версии Laravel 10, PHP 8.3. ПХП-Сваггер с аннотациями, версия OpenAPI - 3.0.

UUIDы, фабрика фейк данных сделано. Транзакции в запросах не делал. Пагинацию, фильтры и сортировку для некоторых запросов, не делал. Юнит тесты тоже не делал. Версионирование не делал. Обработку моделей в ресурсах не делал. Форматирование кода Пинтом.

По вопросам прошу писать на [Телеграм](https://t.me/artip7)

