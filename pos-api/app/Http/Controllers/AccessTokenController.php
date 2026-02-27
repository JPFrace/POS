<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccessTokenController extends Controller
{
    public function generateToken(Request $request)
    {
        $user = User::where('email', $request->email)->first(); // Access token generation logic here
        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 404);
        }
        $isPasswordValid = Hash::check($request->password, $user->password); // Check if the password matches the hashed password
        if (!$isPasswordValid) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        return response()->json(['token' => $user->createToken('fms', ['*'])->plainTextToken]);
    }
}
