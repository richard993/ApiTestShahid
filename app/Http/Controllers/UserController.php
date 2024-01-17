<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'     => 'required',
                'email'    => 'required|unique:users,email',
                'password' => 'required|min:6|max:8'
            ]);

            // Your logic for handling the validated data
        } catch (ValidationException $e) {
            // Validation failed. Return JSON response with custom error messages.

            $errors = $e->validator->errors();

            $emailError = $errors->first('email');
            $passwordError = $errors->first('password');

            return response()->json(['message' => 'Validation failed', 'errors' => [
                'email'    => $emailError ? 'The email field is required.' : null,
                'password' => $passwordError ? 'The password field is required.' : null,
            ]], 422);
        }




        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already exists',
                'status' => 'failed'
            ], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken($request->email)->plainTextToken;
        return response([
            'token' => $token,
            'message' => 'Registration Success',
            'status' => 'success'
        ], 201);
    }

    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email'    => 'required',
                'password' => 'required|min:6|max:8'
            ]);

            // Your logic for handling the validated data
        } catch (ValidationException $e) {
            // Validation failed. Return JSON response with custom error messages.

            $errors = $e->validator->errors();

            $emailError = $errors->first('email');
            $passwordError = $errors->first('password');

            return response()->json(['message' => 'Validation failed', 'errors' => [
                'email'    => $emailError ? 'The email field is required.' : null,
                'password' => $passwordError ? 'The password field is required.' : null,
            ]], 422);
        }
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken($request->email)->plainTextToken;
        if ($user && Hash::check($request->password, $user->password)) {
            return response([
                'token' => $token,
                'message' => 'Login Success',
                'status' => 'success'
            ], 200);
        }
        return response([
            'message' => 'The Provided Credentials are incorrect',
            'status' => 'failed'
        ], 401);
    }

    public function logout()
    {
        dd('ff');
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Success',
            'status' => 'success'
        ], 200);
    }

    public function logged_user()
    {
        $loggeduser = auth()->user();
        return response([
            'user' => $loggeduser,
            'message' => 'Logged User Data',
            'status' => 'success'
        ], 200);
    }
}
