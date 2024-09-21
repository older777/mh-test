<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ActionsEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\HistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     *  @OA\Post(
     *     path="/api/forgot-password",
     *     description="Сбросить пароль.",
     *     tags={"Guest URI"},
     *     security={{}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *            type="object",
     *
     *            @OA\Property(
     *                property="email",
     *                type="string"
     *            ),
     *            example={"email": "admin@local.localhost"}
     *         )
     *     ),
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
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        $user = User::where('email', $request->email)->first();
        HistoryService::event($user, ActionsEnum::RESET, null, $user->toArray());

        return response()->json(
            data: ['status' => true, 'message' => 'Код записан в файл: storage/logs/laravel.log'],
            status: JsonResponse::HTTP_OK
        );
    }
}
