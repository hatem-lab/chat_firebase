<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function senderNotification(){
        $token = "fuMUT4O9d2u1d-tQR0Qghp:APA91bH47JQpPzf86pHDJBAXKYpJ3ofRy-RmwAHEF3E1EviVw5E7OhlVmHdbuq3abgxpVS1QkjryKD8snchHgexP3U9yfQzw0Tp5dTLyl4fg_YpvKc8XR4dgPXmCrr0uMYNSpskrPYEW";
        $from = "AAAAtNRoxSE:APA91bGDMwYDWyB1JMEvXTNl_HI8gQMnSju2mLhBa28ZmJ7NG4eNxMaoBaJ_L6YiS5OP4ZDvxm0enlGv-mJjGRpQ7UwMPkrZHq5sEyq5VGjVAYiGwfUwtE9rOqW9NrVfQHCO2bH0KZCH";
        $msg = array
        (
            'body'  => "Test Test",
            'title' => "Hi, From Diyaa",
            'receiver' => 'erw',
            'icon'  => "https://image.flaticon.com/icons/png/512/270/270014.png",/*Default Icon*/
            'sound' => 'mySound'/*Default sound*/
        );

        $fields = array
        (
            'to'        => $token,
            'notification'  => $msg
        );

        $headers = array
        (
            'Authorization: key=' . $from,
            'Content-Type: application/json'
        );
        //#Send Response To FireBase Server
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        dd($result);
        curl_close( $ch );
    }


    // public function logout(){
	// 	Auth::logout();
    // 	return redirect('/');
    // }

    public function facebookLogin(Request $request){

return "true";

    	$checkUser = User::where('social_id',$request->uid)->first();

    	if($checkUser){
    		$checkUser->social_id = $request->uid;
    		$checkUser->full_name = $request->displayName;
    		$checkUser->image = $request->photoURL;
    		$checkUser->email = $request->email;
    		$checkUser->mobile_number = $request->phoneNumber;
    		$checkUser->save();
    		Auth::loginUsingId($checkUser->id, true);
    		return response()->json([
    			"status" => "success"
    		]);

    	}else{
    		$user = new User;
    		$user->social_id = $request->uid;
    		$user->full_name = $request->displayName;
    		$user->image = $request->photoURL;
    		$user->email = $request->email;
    		$user->mobile_number = $request->phoneNumber;
    		$user->user_type = "facebook";
    		$user->save();
    		Auth::loginUsingId($user->id, true);
    		return response()->json([
    			"status" => "success"
    		]);
    	}




    }

}
