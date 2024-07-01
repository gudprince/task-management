<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register($data)
    {   
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return [
            'user' => new UserResource($user),
            'token' => $token
        ];
    }

    public function login($credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->accessToken;

            return [
                'user' => new UserResource($user),
                'token' => $token
            ];
        } else {
            return false;
        }
    }

    public function logout()
    {
        Auth()->user()->token()->revoke();
    }
}
