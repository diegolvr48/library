<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return response([
            'status' => true,
            'data' => Auth::user()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
   
        if (!$token = auth()->attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'message' => 'Invalid Credentials.'
            ], 401);
        }

        return response()->json([
            'success' => true
        ])->header('Authorization', $token);
    }

    public function user()
    {
        return response([
            'status' => true,
            'data' => auth()->user()
        ]);
    }

    /**
     * 
     */
    public function refresh(Request $request)
    {
        return response([
            'status' => true
        ]);
    }

    /**
     * 
     */
    public function logout()
    {
        auth()->invalidate();
        return response()->json([
            'status' => true,
            'msg' => 'Logged out Successfully.'
        ]);
    }
}
