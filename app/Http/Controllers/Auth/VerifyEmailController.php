<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ActionsEnum;
use App\Http\Controllers\Controller;
use App\Services\HistoryService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/auth/verify-email/{id}/{hash}",
     *     description="Подтвердить емайл.",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *
     *        @OA\Schema(
     *          type="string"
     *        )
     *     ),
     *
     *     @OA\Parameter(
     *        name="hash",
     *        in="path",
     *        required=true,
     *
     *        @OA\Schema(
     *          type="string"
     *        )
     *     ),
     *
     *     @OA\Parameter(
     *        name="expires",
     *        in="query",
     *        required=true,
     *
     *        @OA\Schema(
     *          type="integer"
     *        )
     *     ),
     *
     *     @OA\Parameter(
     *        name="signature",
     *        in="query",
     *        required=true,
     *
     *        @OA\Schema(
     *          type="string"
     *        )
     *     ),
     *
     *     @OA\Response(
     *
     *         @OA\JsonContent(
     *            oneOf={
     *
     *               @OA\Schema(ref="#/components/schemas/Result")
     *            }
     *         ),
     *         response="200",
     *         description="ok"
     *     ),
     * )
     *
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return response()->json(
                data: ['status' => true, 'message' => 'email уже подтверждён'],
                status: JsonResponse::HTTP_OK
            );
        }

        $before = clone $user;

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        HistoryService::event($user, ActionsEnum::VERIFIED, $before->toArray(), $user->toArray());

        return response()->json(
            data: ['status' => true, 'message' => 'email подтверждён'],
            status: JsonResponse::HTTP_OK
        );
    }
}
