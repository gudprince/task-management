<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Response;
use App\Traits\ApiResponse;
use Exception;

class AuthController extends Controller
{
    use ApiResponse;

    public $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $task =  $this->authService->register($request->validated());

            return $this->successResponse($task,  'User registered successfully', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $userData =  $this->authService->login($credentials);

            if (!$userData) {
                return $this->errorResponse('Invalid email/password', [], Response::HTTP_UNAUTHORIZED);
            }

            return $this->successResponse($userData,  'User logged in successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to login user', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try {
            $this->authService->logout();

            return $this->successResponse(null, 'logged out successfully');
        } catch (Exception $e) {
            return $this->errorResponse('An error occured', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
