<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            ], 401);
        }

        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $user = User::create($input);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil mendaftarkan user',
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
                'message' => 'Login gagal',
                'data' => $validator->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email dan password tidak sesuai',
                'data' => null
            ], 401);
        }

        $auth = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'token' => $auth->createToken('auth_token')->plainTextToken,
            'data' => $auth->name
        ], 200);
    }
}
