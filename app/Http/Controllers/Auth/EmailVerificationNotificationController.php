<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ActionsEnum;
use App\Http\Controllers\Controller;
use App\Services\HistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     *  @OA\Post(
     *     path="/api/auth/email/verification-notification",
     *     description="Выслать код подтверждения на емайл.",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             oneOf={
     *
     *                @OA\Schema(ref="#/components/schemas/Result")
     *             }
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     *
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return response()->json(
                data: ['status' => true, 'message' => 'email уже подтверждён'],
                status: JsonResponse::HTTP_OK
            );
        }

        $user->sendEmailVerificationNotification();

        HistoryService::event($user, ActionsEnum::VERIFY, null, $user->toArray());

        return response()->json(
            data: ['status' => true, 'message' => 'Код записан в файл: storage/logs/laravel.log'],
            status: JsonResponse::HTTP_OK
        );
    }
}
