<?php

namespace App\Http\Controllers;

use App\Chat;
use App\User;
use Illuminate\Http\Request;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $chats=Chat::all();
        return view('home',['chats'=>$chats]);
    }
    public function fcmfunction()
    {
        return view('firebase');
    }

    public function createChat( Request  $request)
    {
        $input=$request->all();
        $message=$input['message'];
        $chat=new Chat([
            'sender_id'=>auth()->user()->id,
            'sender_name'=>auth()->user()->name,
            'message'=> $message
        ]);
        $this->broadcastsMessage($message,auth()->user()->name);
         $chat->save();
         return redirect()->back();
    }

    private function broadcastsMessage( $message, $sender_name)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder('new message from'.$sender_name);
        $notificationBuilder->setBody($message)
            ->setSound('default')
            ->setClickAction('http://127.0.0.1:8000/home');
        $dataBuilder = new PayloadDataBuilder();

        $dataBuilder->addData([
            'sender_name' => $sender_name,
            'message' => $message,

        ]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $data = $dataBuilder->build();

        // You must change it to get your tokens
        $tokens = User::pluck('fcm_token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return $downstreamResponse->numberSuccess();
    }
















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
}
