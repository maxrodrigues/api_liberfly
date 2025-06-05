<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    #[OA\Post(
        path: 'api/auth/login',
        description: 'User Login',
        responses: [
            new OA\Response(response: 200, description: 'Successful login'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 400, description: 'Bad Request'),
        ]
    )]
    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->first();

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('liberfly-'.$user->id.'-token')->plainTextToken;

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'expiration' => 525600,
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
