<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = User::create($data);

            $token = $user->createToken('liberfly-' . $user->id . '-token')->plainTextToken;

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'user' => $user,
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'expiration' => 525600,
                ]
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }
    }
}
