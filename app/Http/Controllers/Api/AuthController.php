<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserReqisterRequest;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request){

        $passwordGrandClient = Client::where('password_client',1)->first();


        $data = [
            'grant_type' => 'password',
            'client_id' => $passwordGrandClient->id,
            'client_secret' => $passwordGrandClient->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ];

        $tokenRequest = Request::create('oauth/token','post',$data);

        $tokenResponse = app()->handle($tokenRequest);
        $contentString = $tokenResponse->content();
        $tokenContent = json_decode($contentString,true);

        if(!empty($tokenContent['access_token'])){
            return $tokenResponse;
        }

        return response()->json([
            'message' => 'Unauthenticated'
        ]);
//        return app()->handle($tokenRequest);

//        $http = new \GuzzleHttp\Client();
//
//        $response = $http->post(route('passport.token'), [
//            'form_params' => [
//                'grant_type' => 'password',
//                'client_id' => $passwordGrandClient->id,
//                'client_secret' => $passwordGrandClient->secret,
//                'username' => $request->email,
//                'password' => $request->password,
//                'scope' => '*',
//            ],
//        ]);
//
//        return response()->json((string) $response->getBody(), true);
    }

    public function register(UserReqisterRequest $request){

        $developerRole = Role::developer()->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)

        ]);
        $user->roles()->attach($developerRole->id);


        if(!$user){
            return response()->json(["success"=>false, "message" => "Registration succeeded"],500);
//            return response(json_encode(["success"=>false, "message" => "Registration failed"]));
        }


        return response()->json(["success"=>true, "message" => "Registration succeeded"],200);
//        return response(json_encode(["success"=>true, "message" => "Registration succeeded"]));
//        return response(json_encode($request->all()));

    }
}
