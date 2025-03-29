<?php

namespace App\Http\Controllers;

use App\Models\Password;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Password::all();
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        if (!auth('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }
        
        $validated = $request->validate([
            'user_id' => 'required',
            'title' => 'required',
            'username' => 'required',
            'password' => 'required',
            'url' => 'required',
        ]);

        $user = auth('sanctum')->user();
        if ($user->id != $validated['user_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        Password::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'username' => $validated['username'],
            'password' => $validated['password'],   
            'url' => $validated['url'],
        ]);

        return response()->json([
            'message' => 'Password stored successfully',
            'success' => true
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Password $password)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Password $password)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Password $password)
    {
        //
    }
}
