<?php

namespace App\Http\Controllers;

// use Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Validation\Rules\Password;

use App\Mail\ForgotPassword;


class AuthController extends Controller
{
    public function register(Request $request)
    {

        try {
            $data = $request->validate([
                'idnum' => 'required|integer|unique:users,idnum',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)->numbers()
                ],
                'role' => 'required|string|in:admin,surveymaker,respondent',
                'respondent_type' => 'nullable|string|in:student,faculty,staff,stakeholder',
                'category' => 'nullable|string',
            ]);

            /** @var \App\Models\User $user */
            $user = User::create([
                'idnum' => $data['idnum'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'respondent_type' => $data['respondent_type'],
                'category' => $data['category'],
            ]);
            info($user);
            $token = $user->createToken('main')->plainTextToken;
            info($token);

            return response([
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Registration failed.'], 500);
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'error' => 'The provided credentials are not correct'
            ], 422);
        }

        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
    public function logout()
    {
        /** @var User $user */
        $user = Auth::user();
        // Revoke the token that was used to authenticate the current request
        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return response([
            'success' => true
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        // Send the reset link
        PasswordFacade::sendResetLink($request->only('email'));
    
        return response()->json([
            'message' => 'Password reset link has been sent to your email.',
        ]);
    }
}
