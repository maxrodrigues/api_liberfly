<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddUserController extends Controller
{
    #[OA\Post(
        path: "/api/add-user",
        description: "Add a new user",
        tags: ["user"],
        responses: [
            new OA\Response(response: 201, description: "OK",),
            new OA\Response(response: 400, description: "Bad request",)
        ],
    )]
    public function __invoke(AddUserRequest $request)
    {
        try {
            $data = $request->validated();
            User::create($data);

            return new JsonResponse([
                'status' => 'success',
                'data' => [],
            ], Response::HTTP_CREATED);
        }
        catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
