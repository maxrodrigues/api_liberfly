<?php

namespace App\Http\Controllers;

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
            return new JsonResponse([
                'status' => 'success',
                'data' => $user,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }
    }
}
