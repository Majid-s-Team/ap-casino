<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'dob' => 'required|date', 
            'contact' => 'required|string|unique:users,contact',
            'password' => 'required|string|confirmed|min:6',
            'email' => 'nullable|email|unique:users,email',

        ]);

        if ($validator->fails())
            return $this->apiResponse(422, 'Validation failed', $validator->errors());

        $otp = rand(100000, 999999);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'contact' => $request->contact,
            'username' => $request->username,
            'dob' => $request->dob,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        return $this->apiResponse(200, 'OTP sent', ['otp' => $otp]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'otp' => 'required|digits:6',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'contact';

        $user = User::where($field, $request->login)
            ->where('otp', $request->otp)
            ->first();

        if (!$user || Carbon::now()->gt($user->otp_expires_at)) {
            return $this->apiResponse(401, 'Invalid or expired OTP');
        }

        $user->update([
            'otp' => null,
            'otp_expires_at' => null
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->apiResponse(200, 'OTP verified successfully', [
            'token' => $token,
            'user' => $user,
        ]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'contact';

        $credentials = [
            $loginField => $request->login,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return $this->apiResponse(401, 'Invalid credentials');
        }

        $user = User::where($loginField, $request->login)->first();

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->apiResponse(200, 'Login successful', [
            'token' => $token,
            'user' => $user,
        ]);
    }


    public function sendOtp(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // email or contact
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'contact';

        $user = User::where($field, $request->login)->first();

        if (!$user)
            return $this->apiResponse(404, 'User not found');

        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        return $this->apiResponse(200, 'OTP sent', [
            'otp' => $otp,
            'user' => $user->only('id', 'first_name', 'last_name', $field)
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // email or contact
            'otp' => 'required|digits:6',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'contact';

        $user = User::where($field, $request->login)
            ->where('otp', $request->otp)
            ->first();

        if (!$user || Carbon::now()->gt($user->otp_expires_at)) {
            return $this->apiResponse(401, 'Invalid or expired OTP');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null
        ]);

        return $this->apiResponse(200, 'Password reset successfully');
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->apiResponse(200, 'Logged out successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return $this->apiResponse(401, 'Incorrect current password');
        }

        $request->user()->update(['password' => Hash::make($request->new_password)]);
        return $this->apiResponse(200, 'Password changed successfully');
    }
}
