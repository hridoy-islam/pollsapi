<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use PDOException;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(){
        $user = app('db')->table('users')->get();
        return response()->json($user);
    }

    //
    public function create(Request $request){
        try {
            $this->validate($request, [
                'full_name' => 'required',
                'username' => 'required|min:6',
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        try{
            $id = app('db')->table('users')->insertGetId([
                'full_name' => trim($request->input('full_name')),
                'username' => strtolower(trim($request->input('username'))),
                'email' => strtolower(trim($request->input('email'))),
                'password' => app('hash')->make($request->input('password')),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $user = app('db')->table('users')->select('full_name', 'email', 'username')->where('id', $id)->first();

            return response()->json([
                'id' => $id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'username' => $user->username,
            ], 201);



        }catch(PDOException $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }

    }


    public function authnicate(Request $request){
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        $token = auth()->attempt($request->all());

        if($token){
            return response()->json([
                'success' => true,
                'message' => 'Successfully Logged In',
                'token' => $token,
            ]);
         }
         else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Credentials',
            ], 400);
         }



    } // Function End
}
