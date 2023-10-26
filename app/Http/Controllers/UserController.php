<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;// For password hashing
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    public function read()
    {

        $users = User::with('role')->get();
        $paginatedUsers = User::paginate(11);
        
        return view('user.read', compact('users', 'paginatedUsers'));
    }

    public function create()
    {
        $roles = Role::all();
        $users = User::with('role')->get();
        $paginatedUsers = User::paginate(11);
        return view('user.create', compact('users', 'paginatedUsers', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'password' => 'required',
            'is_active' => 'required|boolean',
            'role_id' => 'required|exists:roles,id',
        ]);

        $data['plaintext'] = $data['password'];

        $data['password'] = Hash::make($data['password']); // Hash the password before saving
        User::create($data); // Saving the data

        return redirect(route('users.read'))->with('success', 'Successfully Save');
    }

    public function edit(User $user) 
    {
        $roles = Role::all();
        $users = User::with('role')->get();
        $paginatedUsers = User::paginate(11);
        return view('user.edit', compact('users', 'paginatedUsers', 'roles'), ['user' => $user]);
    }

    public function update(Request $request, User $user) {
        $data = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'password' => 'required',
            'is_active' => 'required|boolean',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($data);

        return redirect(route('users.read'))->with('success', 'Updated Successfully');
    }

    public function destroy(User $user) {

        $user->delete();
        return redirect(route('users.read'))->with('success', 'Successfully Deleted');

    }

    public function archives(){
        $users = User::onlyTrashed()
            ->orderBy('id', 'desc')->get();
        $paginatedUsers = User::paginate(11);

        return view('user.archives', ['paginatedUsers' => $paginatedUsers,'users' => $users]);
    }

    public function restore(User $user, Request $request)
    {
        $user->restore();

        return redirect(route('users.archives'))->with('success', 'Successfully Restored');
    }

}
