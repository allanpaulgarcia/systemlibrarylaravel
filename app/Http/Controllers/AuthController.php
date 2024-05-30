<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = validator($request->all(), [
            "username" => "required|min:4|string|unique:users|max:32",
            "password" => "required|min:8|max:32|string|confirmed",
            'role' => 'required|in:admin,librarian,student',
            'firstname' => "required|min:4|string|unique:users|max:32",
            'lastname' => "required|min:4|string|unique:users|max:32",
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Request didn't pass the validation.",
                "errors" => $validator->errors()
            ], 400);
        }

        $user = User::create($validator->validated());

        return response()->json([
            "ok" => true,
            "message" => "Account has been created!",
            "data" => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'username' => "required ",
            'password' => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Request didn't pass validation",
                "errors" => $validator->errors()
            ], 400);
        }


        if (auth()->attempt($validator->validated())) {
            $user = auth()->user();
            $user->token = $user->createToken("api-token")->accessToken;
            return response()->json([
                "ok" => true,
                "message" => "Login Success",
                "data" => $user
            ], 200);

        }
    }


}
