<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $user = User::create($input);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'User berhasil didaftarkan',
            'access_token' => $token,
            'data' => $user->name
        ], 200);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'data' => $validator->errors()
            ], 400);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => 401,
                'message' => 'Email atau password salah',
                'data' => null
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'Login berhasil',
            'access_token' => $token,
            'data' => $user->name
        ], 200);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logout berhasil',
            'data' => null
        ], 200);
    }
}
