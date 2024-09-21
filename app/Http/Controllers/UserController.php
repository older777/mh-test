<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     *  @OA\Get(
     *     path="/api/auth/users",
     *     description="Список пользователей.",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             type="object"
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     *
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = UserService::getUsers();

        return response()->json(
            data: ['status' => true, 'data' => $users],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        return response()->json(
            data: ['status' => false, 'message' => 'метод не реализован'],
            status: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(
            data: ['status' => false, 'message' => 'метод не реализован'],
            status: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     *  @OA\Get(
     *     path="/api/auth/users/{id}",
     *     description="Данные пользователя.",
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
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             type="object"
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     *
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = UserService::getUsers($id);

        return response()->json(
            data: ['status' => true, 'data' => $user],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        return response()->json(
            data: ['status' => false, 'message' => 'метод не реализован'],
            status: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * *  @OA\Put(
     *     path="/api/auth/users/{id}",
     *     description="Редактирование пользователя",
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
     *                "last_name": "",
     *                "name": "",
     *                "middle_name": "",
     *                "email": "",
     *                "phone": ""
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        UserService::userUpdate($request, $id);

        return response()->json(
            data: ['status' => true, 'message' => 'данные обновлены'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Delete(
     *     path="/api/auth/users/{user}",
     *     description="Удалить пользователя в корзину",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *        name="user",
     *        in="path",
     *        required=true,
     *
     *        @OA\Schema(
     *          type="string"
     *        )
     *     ),
     *
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             type="object"
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     *
     * Soft remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        UserService::userRemove($user);

        return response()->json(
            data: ['status' => true, 'message' => 'Пользователь удалён в корзину'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Get(
     *     path="/api/auth/users/{id}/restore",
     *     description="Удалить пользователя в корзину",
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
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             type="object"
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     *
     * Restore the specified resource from storage.
     */
    public function restore($id): JsonResponse
    {
        UserService::userRestore($id);

        return response()->json(
            data: ['status' => true, 'message' => 'Пользователь восстановлен'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Delete(
     *     path="/api/auth/users/{id}/force",
     *     description="Полностью удалить пользователя",
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
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             type="object"
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     * Delete the specified resource from storage.
     */
    public function delete($id): JsonResponse
    {
        UserService::userForceDelete($id);

        return response()->json(
            data: ['status' => true, 'message' => 'Пользователь полностью удалён'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Get(
     *     path="/api/auth/users/all/trashed",
     *     description="Пользователи в корзине",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             type="object"
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     * Delete the specified resource from storage.
     */
    public function trashedUsers(): JsonResponse
    {
        $users = UserService::trashedUsers();

        return response()->json(
            data: ['status' => true, 'data' => $users],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Delete(
     *     path="/api/auth/users/group/remove",
     *     description="Груповое удаление пользователей в корзину",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *            type="object",
     *
     *            @OA\Property(
     *                property="ids",
     *                type="array",
     *
     *                @OA\Items(
     *                  type="string"
     *                )
     *            ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             type="object"
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     *
     * Delete the specified ids from storage.
     */
    public function usersGroupRemove(Request $request): JsonResponse
    {
        UserService::userGroupRemove($request->ids);

        return response()->json(
            data: ['status' => true, 'message' => 'Пользователи удалены в корзину'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Post(
     *     path="/api/auth/users/group/restore",
     *     description="Груповое восстановление пользователей из корзины",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *            type="object",
     *
     *            @OA\Property(
     *                property="ids",
     *                type="array",
     *
     *                @OA\Items(
     *                  type="string"
     *                )
     *            ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             type="object"
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     * Груповое восстановление пользователей из корзины
     */
    public function usersGroupRestore(Request $request): JsonResponse
    {
        UserService::userGroupRestore($request->ids);

        return response()->json(
            data: ['status' => true, 'message' => 'Пользователи восстановлены'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Delete(
     *     path="/api/auth/users/group/delete",
     *     description="Полное удаление пользователей",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *            type="object",
     *
     *            @OA\Property(
     *                property="ids",
     *                type="array",
     *
     *                @OA\Items(
     *                  type="string"
     *                )
     *            ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *
     *          @OA\JsonContent(
     *             type="object"
     *          ),
     *          response="200",
     *          description="ok"
     *     ),
     * )
     *
     * Полное удаление пользователей.
     */
    public function usersGroupDelete(Request $request): JsonResponse
    {
        UserService::userGroupDelete($request->ids);

        return response()->json(
            data: ['status' => true, 'message' => 'Пользователи полностью удалены'],
            status: JsonResponse::HTTP_OK
        );
    }
}
