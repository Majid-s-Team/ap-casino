<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        return $this->apiResponse(200, 'All users fetched', User::latest()->get());
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->apiResponse(200, 'User found', $user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'contact' => 'required|unique:users,contact',
            'password' => 'required|confirmed',
            'role' => 'in:user,admin'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
        ]);

        return $this->apiResponse(201, 'User created successfully', $user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only('first_name', 'last_name', 'contact', 'role'));

        return $this->apiResponse(200, 'User updated successfully', $user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return $this->apiResponse(200, 'User deleted successfully');
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:user,admin']);
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return $this->apiResponse(200, 'Role updated successfully', $user);
    }

    public function search(Request $request)
    {
        $keyword = $request->query('q');
        $users = User::where('first_name', 'like', "%$keyword%")
                     ->orWhere('last_name', 'like', "%$keyword%")
                     ->orWhere('contact', 'like', "%$keyword%")
                     ->get();

        return $this->apiResponse(200, 'Search results', $users);
    }

    public function stats()
    {
        $data = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'users' => User::where('role', 'user')->count(),
        ];

        return $this->apiResponse(200, 'User statistics', $data);
    }
}
