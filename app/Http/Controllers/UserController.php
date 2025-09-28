<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('user.index', compact('users'));
    }
    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

    if(!empty($data['password'])){
        $data['password'] = Hash::make($data['password']);
    }
    User::create($data);
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User moved to Trash!');
    }

    public function trash()
    {
        $trashed = User::onlyTrashed()->paginate(10);
        return view('user.trash', compact('trashed'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return back()->with('success', 'User restored successfully!');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return back()->with('success', 'User permanently deleted!');
    }
}
