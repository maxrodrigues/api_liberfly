<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->first();

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => "Invalid credentials"
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('liberfly-' . $user->id . 'token')->plainTextToken;

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'token' => $token,
                    'expiration' => 525600,
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }
    }
}
