<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authUser = $request->user();
        // Non-admins only see themselves
        if (!$authUser || $authUser->role !== User::ROLE_ADMIN) {
            return response()->json([$authUser]);
        }
        // Admin can see all users (consider pagination later)
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(UserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        return response()->json($user, 201);

    }
    public function authenticated_user(Request $request)
    {
        $user = $request->user();
        return $user;
    }
    /**
     * Display the specified resource.
     */
        public function show(string $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();

        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json($user);
    }
}
