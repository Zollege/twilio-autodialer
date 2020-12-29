<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class AuthController extends Controller
{
  public function register(Request $request)
  {
      $validated = $request->validate([
          'name'=>'required|max:55',
          'email'=>'email|required|unique:users',
          'password'=>'required|confirmed'
      ]);

      $emailChunks = explode('@', $validated['email']);
      $emailDomain = $emailChunks[1];

      if ($emailDomain != 'zollege.com') {
        return response(['message' => 'You must have a Zollge email address to register']);
      }

      $validated['password'] = bcrypt($request->password);
      $user = User::create($validated);
      $accessToken = $user->createToken('authToken')->accessToken;
      return response([ 'user' => $user, 'access_token' => $accessToken]);
  }

  public function login (Request $request) 
  {
      $validatedCreds = $request->validate([
          'email' => 'email|required',
          'password' => 'required'
      ]);

      if (!auth()->attempt($validatedCreds)) {
          return response(['message' => 'Invalid Credentials']);
      }

      $accessToken = auth()->user->createToken('authToken')->accessToken;

      return response(['user' => auth()->user(), 'access_token' => $accessToken]);
  }
}
