<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserListResource;
use App\Http\Resources\UsersListResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ListUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($user)
    {
        try {
            $user = User::find($user);

            if (!$user) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'User not found',
                ], Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse([
                'status' => 'success',
                'data' => new UserListResource($user),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
