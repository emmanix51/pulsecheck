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
use App\Models\College;
use App\Models\Program;

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
                'year_level' => 'nullable|string',
                'college_id' => 'nullable|exists:colleges,id',
                'program_id' => 'nullable|exists:programs,id',
            ]);

            // Prepare category based on college_id and program_id if available
            if (empty($data['category'])) {
                // If no category provided, generate category based on college and program names
                if ($data['college_id'] && $data['program_id']) {
                    $college = College::find($data['college_id']);
                    $program = Program::find($data['program_id']);

                    // Log the college and program info for debugging
                    Log::info("College: " . ($college ? $college->college_name : 'Not Found'));
                    Log::info("Program: " . ($program ? $program->program_name : 'Not Found'));

                    // Debugging: Check if college and program are found
                    if (!$college) {
                        Log::error("College not found for ID: " . $data['college_id']);
                    }
                    if (!$program) {
                        Log::error("Program not found for ID: " . $data['program_id']);
                    }

                    if ($college && $program) {
                        // Generate category by concatenating names
                        $category = $college->college_name . ' - ' . $program->program_name;
                        Log::info("Generated category: " . $category); // Debugging the generated category
                    } else {
                        // If either college or program not found, set category to null
                        $category = null;
                    }
                } elseif ($data['college_id']) {
                    $college = College::find($data['college_id']);
                    Log::info("College: " . ($college ? $college->college_name : 'Not Found'));
                    if (!$college) {
                        Log::error("College not found for ID: " . $data['college_id']);
                    }

                    if ($college) {
                        // Generate category by concatenating names
                        $category = $college->college_name;
                        Log::info("Generated category: " . $category); // Debugging the generated category
                    } else {
                        // If either college or program not found, set category to null
                        $category = null;
                    }
                } else {
                    $category = null;
                }
            } else {
                // If category was provided, use the provided category
                $category = $data['category'];
            }


            /** @var \App\Models\User $user */
            $user = User::create([
                'idnum' => $data['idnum'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'respondent_type' => $data['respondent_type'],
                'category' => $category,
                'year_level' => $data['year_level'],
                'college_id' => $data['college_id'],
                'program_id' => $data['program_id'],
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
        // Validate the email
        $request->validate([
            'email' => 'required|email',
        ]);

        // Send the password reset link
        $status = PasswordFacade::sendResetLink($request->only('email'));

        if ($status == PasswordFacade::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link has been sent to your email.',
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to send reset link.',
            ], 400);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'token' => 'required',
        ]);

        $status = PasswordFacade::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status == PasswordFacade::PASSWORD_RESET) {
            // Respond with JSON for frontend handling
            return response()->json([
                'message' => 'Password reset successfully. Please log in with your new password.',
            ]);
        }

        return response()->json([
            'message' => __($status),
        ], 400);
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }
}
