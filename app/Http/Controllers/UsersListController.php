<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersListResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

class UsersListController extends Controller
{
    #[OA\Get (
        path: "/api/users-list",
        description: "Get a list of all users",
        tags: ["users"],
        responses: [
            new OA\Response(response: 200, description: "success"),
            new OA\Response(response: 400, description: "Bad request")
        ]
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $users = User::all()->toArray();

            return new JsonResponse([
                'status' => 'success',
                'data' => $users,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
