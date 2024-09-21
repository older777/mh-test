<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ActionsEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\HistoryService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     *  @OA\Post(
     *     path="/api/reset-password",
     *     description="Сброс на новый пароль.",
     *     tags={"Guest URI"},
     *     security={{}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *            type="object",
     *
     *            @OA\Property(
     *                property="token",
     *                type="string"
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string"
     *            ),
     *            @OA\Property(
     *                property="password",
     *                type="string"
     *            ),
     *            @OA\Property(
     *                property="password_confirmation",
     *                type="string"
     *            ),
     *            example={
     *                "token": "",
     *                "email": "admin@local.localhost",
     *                "password": "123",
     *                "password_confirmation": "123"
     *            }
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
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $after = new User;
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request, &$after) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                $after = $user;

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        HistoryService::event($after, ActionsEnum::PASSWORD, null, ['password' => $request->password]);

        return response()->json(
            data: ['status' => __($status), 'message' => 'пароль обновлён'],
            status: JsonResponse::HTTP_OK
        );
    }
}
