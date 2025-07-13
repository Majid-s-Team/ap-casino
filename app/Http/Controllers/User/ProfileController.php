<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        return $this->apiResponse(200, 'Profile fetched', $request->user());
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
        ]);

        $request->user()->update($request->only('first_name', 'last_name'));

        return $this->apiResponse(200, 'Profile updated', $request->user());
    }
}
