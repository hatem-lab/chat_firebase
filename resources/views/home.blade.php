<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="baseURL" content="{{ url('/') }}">

	<style type="text/css">

		.show{
			display: block!important;
		}

	</style>
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{ asset('images/icons/favicon.ico')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/animate/animate.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/main.css')}}">
<!--===============================================================================================-->
</head>
<body>

@extends('layouts.app')

@section('content')
    <style>
        .chat{
            border: 1px solid gray;
            border-radius:3px;
            width: 50%;
            padding: 0.5em;
        }
        .chat-left{
           background-color: white;
            align-self: flex-start;
        }
        .chat-right{
            background-color: #adff2f7f;
            align-self: flex-end;
        }
        .chat-container{
            display: flex;
            flex-direction: column;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">chatting system</div>


                        <div class="card-body">

                            <div class="chat-container">
                                @if(count($chats)===0)
                                    <p>no messages yet</p>
                                @endif
                                @foreach($chats as $chat)
                                    @if($chat->sender_id ===Auth::user()->id)
                                    <p class="chat chat-right">
                                        <b>{{$chat->sender_name}}</b><br>

                                        {{$chat->message}}
                                    </p>
                                    @else
                                        <p class="chat chat-left">
                                            <b>{{$chat->sender_name}}</b><br>
                                            {{$chat->message}}
                                        </p>
                                    @endif
                                @endforeach



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">

    <form action="{{route('chat')}}" method="POST">
        @csrf
        <div class="form-group">
            <label>messages</label>
            <input type="text" class="form-control" name="message">
        </div>
        <div class="form-group">

            <button type="submit" class="btn btn-primary">send</button>
        </div>
    </form>
    </div>
@endsection



<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
	<script src="{{ asset('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('vendor/daterangepicker/moment.min.js')}}"></script>
	<script src="{{ asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('js/main.js')}}"></script>


	 <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
	 <script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-app.js"></script>

<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-analytics.js"></script>

<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.2/firebase-firestore.js"></script>

	<script type="text/javascript" src="{{ asset('js/firebase-conf.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src=" https://www.gstatic.com/firebasejs/8.6.3/firebase-messaging.js"></script>


<script>const messaging = firebase.messaging();
messaging.getToken({vapidKey: "BHqYZlOI-qp3cz7SiJdZwlFXzqS2ML8ZWQ2YsY1xYWz0NigITHVDMoYN_1opDt8udRydVF8Vgy31bGn4eXlan5g"});
    function SendTokenToServer(token)
    {
        console.log(token);
        const user_id='{{Auth::user()->id}}';



        axios.post('/fcm_chat', {
            token,user_id
        })
            .then(response =>{
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
    }
      function retrieveToken(){
          // Get registration token. Initially this makes a network call, once retrieved
          // subsequent calls to getToken will return from cache.
          messaging.getToken({ vapidKey: 'BHqYZlOI-qp3cz7SiJdZwlFXzqS2ML8ZWQ2YsY1xYWz0NigITHVDMoYN_1opDt8udRydVF8Vgy31bGn4eXlan5g' }).then((currentToken) => {
              if (currentToken) {
                  // Send the token to your server and update the UI if necessary
                  // ...
                  SendTokenToServer(currentToken);
              } else {
                  // Show permission request UI
                  console.log('No registration token available. Request permission to generate one.');
                  // ...
              }
          }).catch((err) => {
              console.log('An error occurred while retrieving token. ', err);
              // ...
          });

      }
    retrieveToken();
    messaging.onTokenRefresh(()=>{
        retrieveToken();
    });

    messaging.onMessage((payload)=>{
        console.log('Message recived');
        console.log(payload);
        location.reload();
    });


</script>



{{--	<!-- facebook provider -->--}}
{{--	  <script type="text/javascript" src="{{ asset('js/facebook.js') }}"></script>--}}


{{--	<!-- google provider -->--}}
{{--	<script type="text/javascript" src="{{ asset('js/google.js') }}"></script>--}}

</body>
</html>
