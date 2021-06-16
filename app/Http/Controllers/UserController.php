<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $successStatus = 200;

    public function register(Request $request){
        $data = [];
        if($request->input('name') === null){
            $data['success'] = 0;
            $data['msg'] = 'the name should be filled';
        } else if ($request->input('email') === null){
            $data['success'] = 0;
            $data['msg'] = 'the email should be filled';
        } else if ($request->input('password') === null){
            $data['success'] = 0;
            $data['msg'] = 'password must be filled';
        } else if($request->input('password_confirmed') === null){
            $data['success'] = 0;
            $data['msg'] = 'Missing confirmed password';
        } else if($request->input('password_confirmed') !== $request->input('password')){
            $data['success'] = 0;
            $data['msg'] = 'Password and Confirm password does not match';
        } else {
            //check if the email exist first
            $check_exist_email = DB::table('users')->where('email', '=',$request->input('email'))->first();
            if($check_exist_email){
                $data['success'] = 0;
                $data['msg'] = 'This email has been used';
            } else {
                $user = new User;
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->password = Hash::make($request->input('password'));
                if ($user->save()) {
                    $data['success'] = 1;
                    $data['msg'] = 'You have successfully registered';
                    $data['token'] = 'Bearer '.$user->createToken('ReactAndLaravel')->accessToken;
                    $data['user_id'] = $user->id;
                } else {
                    $data['success'] = 0;
                    $data['msg'] = 'User does not saved';
                }
            }
        }
        return json_encode($data);
    }

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->input('email') , 'password' => $request->input('password')])){
            $user = Auth::user();
            $success['token'] = 'Bearer '.$user->createToken('ReactAndLaravel')->accessToken;
            return response()->json(['success' => $success , 'user_id' => $user->id ], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised' ], 401 );
        }
    }

    public function logout(Request $request){
        $data = [];
        if($request->user()->tokens()->delete()){
            $data['success'] = 1;
            $data['msg'] = 'Logged out';
        } else {
            $data['success'] = 0;
            $data['msg'] = 'Something went wrong';
        }
        return json_encode($data);
    }
}
