<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="MH test work"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="L5 Swagger OpenApi"
 * )
 *
 * @OA\Tag(
 *    name="MH test",
 *    description="MH test API",
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="sanctum",
 *   type="http",
 *   description="Enter token in format (Bearer <token>)",
 *   scheme="bearer"
 * )
 *
 * @OA\Schema(
 *  schema="Result",
 *  title="Схема результатов",
 *
 * 	@OA\Property(
 * 		property="status",
 * 		type="boolean"
 * 	),
 * 	@OA\Property(
 * 		property="message",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="error",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="Laravel",
 *      type="string"
 *  ),
 *  @OA\Property(
 * 		property="token",
 *      type="string"
 *  )
 * )
 *
 * @OA\Get(
 *     path="/",
 *     description="Laravel version",
 *     tags={"Guest URI"},
 *     security={{}},
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
 *          description="Текущая версия Ларавела"
 *     ),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
