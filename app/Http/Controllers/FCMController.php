<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FCMController extends Controller
{
    public function index(Request $req)
    {
        $input=$req->all();
        $user=User::all();
        $user_id=$req['user_id'];
        $token=$req['token'];
        $user=User::find($user_id);
        $user->fcm_token=$token;
         $user->save();
        return response()->json([
            'success'=>true,
            'messages'=>'your token is saved in database',

        ]);
    }
}
