<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{

    public function register(Request $request)
    {
    //    return $request;


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Password::defaults()],
        ]);


        // $user = User::create([
        //     'name' => $request->name,
        //     'surname' => $request->surname,
        //     'phone' => $request->phone,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);


        // event(new Registered($user));

        // $token = $user->createToken('authtoken');

        // return response()->json(
        //     [
        //         'message'=>'User Registered',
        //         'data'=> ['token' => $token->plainTextToken, 'user' => $user]
        //     ]
        // );
        $user=new User();
        $user->fill($request->only([
            'name',
            'phone',
            'profil',
            'email'
        ]));
        $user->password=Hash::make($request->password);
        $user->save();

          $token=$user->createToken($user->email.'_Token')->plainTextToken;
          return response()->json([
              'succes'=>1,
              'user'=>$user,
              'token'=>$token

          ]);


    }


    public function login(Request $request)
    {
        // return $request;
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string|min:3|max:100']);
            $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'status'=>401
        ]);
    }else{
        $token=$user->createToken($user->email.'_Token')->plainTextToken;
        return response()->json([
            'status'=>200,
            'user_name'=>$user->name,
            'token'=>$token,
            'message'=>'logged success'
        ]);
    }

    }

    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return response()->json(
            [
                'message' => 'Logged out'
            ]
        );

    }

}
