<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends Controller
{
    /* 
    Register Api
    */
    public function register(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',

        ]);

        if($validator->fails()){

            return response()->json([
                'statuds' => false,
                'message' => 'Data Not Foune',
                'errors' => $validator->errors()
            ]);      
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

   
        return response()->json([
            'status' => true,
            'message' => 'Register Data Successfully',
            'data' => $success
        ]);

    }



    /* 
    Login Register
    */

    public function login(Request $request)

    {

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 

            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;

            return response()->json([
                'status' => true,
                'message' => 'Login Data Successfully',
                'data' => $success
            ]);
        } 

        else{ 
            return response()->json([
                'statuds' => false,
                'errors' => 'Unauthorised'
            ]);    
        } 

    }


}
