<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class AddUserController extends Controller
{
    #[OA\Post(
        path: '/api/add-user',
        description: 'Add a new user',
        tags: ['user'],
        responses: [
            new OA\Response(response: 201, description: 'OK'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 422, description: 'Validation failed'),
        ],
    )]
    public function __invoke(AddUserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::create($data);

            return new JsonResponse([
                'status' => 'success',
                'data'   => [
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'data'   => [],
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
