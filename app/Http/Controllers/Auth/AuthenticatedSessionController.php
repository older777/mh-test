<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ActionsEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\HistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     description="Аутентификация",
     *     tags={"Guest URI"},
     *     security={{}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *            type="object",
     *
     *            @OA\Property(
     *                    property="email",
     *                     type="string"
     *            ),
     *            @OA\Property(
     *              property="password",
     *                 type="string"
     *            ),
     *            example={"email": "admin@local.localhost", "password": "123"}
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
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = $request->user();
        $token = $user->createToken($user->name);
        HistoryService::event($user, ActionsEnum::LOGIN, null, $user->toArray());

        return response()->json(
            data: ['status' => true, 'token' => $token->plainTextToken],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     * @OA\Get(
     *     path="/api/auth/logout",
     *     description="Логаут",
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
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();
        HistoryService::event($user, ActionsEnum::LOGOUT, null, $user->toArray());
        $user->tokens()->delete();

        return response()->json(
            data: ['status' => true],
            status: JsonResponse::HTTP_OK
        );
    }
}
