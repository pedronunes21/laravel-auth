<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Exceptions\AuthenticationException;
use League\OAuth2\Server\Exception\OAuthServerException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::attempt($request->only("email", "password"))) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $token = $user->createToken("app")->accessToken;

                return response([
                    "message" => "success",
                    "token" => $token,
                    "user" => $user
                ]);
            }
        } catch (\Exception $exception) {
            return response([
                "message" => $exception->getMessage()
            ], 400);
        }

        return response([
            "message" => "Invalid username/password"
        ], 401);
    }

    public function user()
    {
        return Auth::user();

    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                "first_name" => $request->input("first_name"),
                "last_name" => $request->input("last_name"),
                "email" => $request->input("email"),
                "password" => Hash::make($request->input("password")),
            ]);

            return $user;
        } catch (\Exception $exception) {
            return response([
                "message" => $exception->getMessage()
            ], 400);
        }
    }
}