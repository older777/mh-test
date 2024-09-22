<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Services\HistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     *  @OA\Get(
     *     path="/api/auth/history",
     *     description="История событий.",
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
        $history = HistoryService::eventList();

        return response()->json(
            data: ['status' => true, 'data' => $history],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        return response()->json(
            data: ['status' => false, 'message' => 'Метод не реализован'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *     path="/api/auth/history/{id}",
     *     description="Данные события.",
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(
            data: ['status' => false, 'message' => 'Метод не реализован'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Get(
     *     path="/api/auth/history/{history}",
     *     description="Детали события.",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *        name="history",
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
    public function show(History $history): JsonResponse
    {
        HistoryService::showEvent($history);

        return response()->json(
            data: ['status' => true, 'data' => $history],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        return response()->json(
            data: ['status' => false, 'message' => 'Метод не реализован'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json(
            data: ['status' => false, 'message' => 'Метод не реализован'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Delete(
     *     path="/api/auth/history/{history}",
     *     description="Удалить событие.",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *        name="history",
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
     * Remove the specified resource from storage.
     */
    public function destroy(History $history): JsonResponse
    {
        HistoryService::removeEvent($history);

        return response()->json(
            data: ['status' => true, 'data' => 'Событие удалено в корзину'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Get(
     *     path="/api/auth/history/{history}/restore",
     *     description="Восстановить событие.",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *        name="history",
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
     * Remove the specified resource from storage.
     */
    public function restore(string $id): JsonResponse
    {
        HistoryService::restoreEvent($id);

        return response()->json(
            data: ['status' => true, 'data' => 'Событие восстановлено'],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     *  @OA\Delete(
     *     path="/api/auth/history/{history}/force",
     *     description="Удалить событие.",
     *     tags={"Authenticated URI"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *        name="history",
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
     * Remove the specified resource from storage.
     */
    public function delete(string $id): JsonResponse
    {
        HistoryService::deleteEvent($id);

        return response()->json(
            data: ['status' => true, 'data' => 'Событие удалено'],
            status: JsonResponse::HTTP_OK
        );
    }
}
