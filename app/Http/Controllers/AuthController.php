<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //

    // public function __construct()
    // {
    //     $this->middleware("auth:api",['except'=>['login','register']]);
    // }
    use HttpResponses;

    // User registration
    public function register(Request $request)
    {

        try {
            $validated = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if ($validated->fails()) {
                return $this->invalidRequest($validated->errors());
            }


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = Auth::login($user);

            return $this->success(compact('user', 'token'), 'user register successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->error($th->getMessage());
        }
    }



    // User login
    public function login(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validated->fails()) {
            return $this->invalidRequest($validated->errors());
        }
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->unauthorized('Invalid credentials');
            }
            $user = auth()->user();

            $data = [
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                    'expires_in' => JWTAuth::factory()->getTTL() * 60

                ]
            ];
            return $this->success($data, 'login successful');
        } catch (JWTException $e) {
            return $this->error('Could not create token');
        }
    }

    // Get authenticated user
    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return $this->badRequest('User not found');
            }
        } catch (JWTException $e) {
            return $this->badRequest('Invalid token');
        }

        return $this->success(compact('user'));
    }


    // User logout

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->success('Successfully logged out');
        } catch (\Exception $e) {
            return $this->error($e->getMessage() ?? 'Internal server error');
        }
    }
}
