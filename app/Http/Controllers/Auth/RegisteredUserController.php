<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ActionsEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\HistoryService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     *  @OA\Post(
     *     path="/api/register",
     *     description="Регистрация нового пользователя.",
     *     tags={"Guest URI"},
     *     security={{}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *            type="object",
     *
     *            @OA\Property(
     *                property="last_name",
     *                type="string"
     *            ),
     *            @OA\Property(
     *                property="name",
     *                type="string"
     *            ),
     *            @OA\Property(
     *                property="middle_name",
     *                type="string"
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string"
     *            ),
     *            @OA\Property(
     *                property="phone",
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
     *                "last_name": "Test",
     *                "name": "New",
     *                "middle_name": "Newest",
     *                "email": "new@local.localhost",
     *                "phone": "1(231)23123",
     *                "password": "123456789",
     *                "password_confirmation": "123456789",
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
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'last_name' => $request->last_name,
            'name' => $request->name,
            'middle_name' => $request->middle_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        HistoryService::event($user, ActionsEnum::CREATE, null, $user->toArray());

        return response()->json(
            data: ['status' => true, 'message' => 'пользователь зарегистрирован'],
            status: JsonResponse::HTTP_OK
        );
    }
}
