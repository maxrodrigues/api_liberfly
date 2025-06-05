<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserListResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    #[OA\Post(
        path: 'api/auth/register',
        description: 'Register a new user',
        responses: [
            new OA\Response(response: 201, description: 'Successful register user'),
            new OA\Response(response: 422, description: 'Error when validating data'),
            new OA\Response(response: 400, description: 'Generic or unmapped error'),
        ]
    )]
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = User::create($data);

            $token = $user->createToken('liberfly-'.$user->id.'-token')->plainTextToken;

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'user' => new UserListResource($user),
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'expiration' => 525600,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::critical('Failed to create user', [
                self::class,
                'message' => $e->getMessage(),
            ]);

            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
