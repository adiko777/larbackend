<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function me(){
//        return Auth::user();
        return new UserResource(Auth::user());
    }

    public function changePassword(ChangePasswordRequest $request){
        $user = User::findOrFail(Auth::id());
        if(!Hash::check($request->oldPassword, $user->password)){
            return response()->json(["message"=>"пароль не совподает"]);
        }
        $user->password = Hash::make($request->password);
        if($user->save()){
            return response()->json(["message"=>"Пароль установлен"]);
        }
        return response()->json(["message"=>"Ошибка"]);
    }

    public function changeDetails(Request $request){
        $user = User::findOrFail(Auth::id());
        $user->name = $request->name;
        if($user->save()){
            return response()->json(["message"=>true]);
        }
        return response()->json(["message"=>false]);
    }
}
