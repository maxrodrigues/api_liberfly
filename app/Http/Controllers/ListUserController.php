<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserListResource;
use App\Models\User;
use OpenApi\Attributes as OA;
use Illuminate\Http\{JsonResponse, Request};
use Symfony\Component\HttpFoundation\Response;

class ListUserController extends Controller
{
    #[OA\Get(
        path: '/api/user/{user}',
        description: 'Get a list of all users',
        tags: ['user'],
        responses: [
            new OA\Response(response: 200, description: 'success'),
            new OA\Response(response: 400, description: 'Bad request'),
        ]
    )]
    public function __invoke($user): JsonResponse
    {
        try {
            $user = User::find($user);

            if (! $user) {
                return new JsonResponse([
                    'status'  => 'error',
                    'message' => 'User not found',
                ], Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse([
                'status' => 'success',
                'data'   => new UserListResource($user),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
