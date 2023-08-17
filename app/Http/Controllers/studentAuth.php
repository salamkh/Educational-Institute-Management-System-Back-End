<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;

class studentAuth extends Controller
{
    public function studentLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }
        $credentials = request(['phone', 'password']);

        if (!$token = auth()->guard('studentapi')->attempt($credentials)) {
            return response()->json(['error' => 'المعلومات المدخلة غير صحيحة'], 401);
        }
        $user = auth()->guard('studentapi')->user();
        $user->accessToken = $token;
        $user->expires_in = JWTFactory::getTTL() * 60;
        $user->tokenType = "bearer";
        return response([
            'user' => $user,
            'message' => 'تم تسجيل الدخول بنجاح',
        ], 200);
    }
    public function studentLogout()
    {
        auth()->guard('studentapi')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
