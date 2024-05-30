<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * CREATE a user data from request
     * POST: /api/users
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            "username" => "required|min:4|string|unique:users|max:32",
            "password" => "required|min:8|max:32|string|confirmed",
            'role' => 'required|in:admin,librarian,student',
            'firstname' => "required|min:4|string|unique:users|max:32",
            'lastname' => "required|min:4|string|unique:users|max:32",
            'email' => 'required|email|unique:users',
        ]);

        if ($request->user()->role !== "admin") {
            unset($validator->rules()['role']);
        }

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

    /**
     * RETRIEVE all users
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json([
            "ok" => true,
            "message" => "User info has been retrieved",
            "data" => User::all()
        ], 200);
    }

    /**
     * Retrieve specific user using ID
     * GET: /api/users/{user}
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        return response()->json([
            "ok" => true,
            "message" => "User has been retrieved.",
            "data" => $user
        ], 200);
    }

    /**
     * Update specific user using inputs from request and id from URI
     * PATCH: /api/users/{user}
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = validator($request->all(), [
            'username' => 'required|unique:users,username,' . $user->id,
            'role' => 'required|in:admin,librarian,student',
            'firstname' => "required|min:4|string|unique:users|max:32",
            'lastname' => "required|min:4|string|unique:users|max:32",
            'email' => 'required|min:4|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Request didn't pass the validation",
                "errors" => $validator->errors()
            ], 400);
        }

        $user->update($validator->validated());

        return response()->json([
            "ok" => true,
            "message" => "User has been updated!",
            "data" => $user
        ], 200);
    }

    /**
     * DELETE specific user using ID
     * GET: /api/users/{user}
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();
        return response()->json([
            "ok" => true,
            "message" => "User has been deleted.",
            "data" => $user
        ], 200);
    }
}
