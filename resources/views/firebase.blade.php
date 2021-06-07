{{--  <html>
<head>
    <title>FCM</title>
    <!-- firebase integration started -->
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-messaging.js"></script>

    <!-- TODO: Add SDKs for Firebase products that you want to use
         https://firebase.google.com/docs/web/setup#available-libraries -->
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-analytics.js"></script>

    <script src="https://www.gstatic.com/firebasejs/7.20.0/firebase-messaging.js"></script>


</head>
    <body>
            Firebase Notification
    </body>

    <script>
        // Your web app's Firebase configuration
        // Your web app's Firebase configuration
       // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  var firebaseConfig = {
    apiKey: "AIzaSyDsAjDNl_R9FgrmLW-r8J-kEG7uUnWbFYU",
    authDomain: "laravelfcm-85ee0.firebaseapp.com",
    projectId: "laravelfcm-85ee0",
    storageBucket: "laravelfcm-85ee0.appspot.com",
    messagingSenderId: "776657749281",
    appId: "1:776657749281:web:42e5197e01eb12af24aa60",
    measurementId: "G-XXT2WVHS2N"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

//firebase.analytics();
const messaging = firebase.messaging();
	messaging
.requestPermission()
.then(function () {
//MsgElem.innerHTML = "Notification permission granted."
	console.log("Notification permission granted.");

     // get the token in the form of promise
	return messaging.getToken()
})
.then(function(token) {
 // print the token on the HTML page
  console.log(token);



})
.catch(function (err) {
	console.log("Unable to get permission to notify.", err);
});

messaging.onMessage(function(payload) {
    console.log(payload);
    var notify;
    notify = new Notification(payload.notification.title,{
        body: payload.notification.body,
        icon: payload.notification.icon,
        tag: "Dummy"
    });
    console.log(payload.notification);
});

    //firebase.initializeApp(config);
var database = firebase.database().ref().child("/users/");

database.on('value', function(snapshot) {
    renderUI(snapshot.val());
});

// On child added to db
database.on('child_added', function(data) {
	console.log("Comming");
    if(Notification.permission!=='default'){
        var notify;

        notify= new Notification('CodeWife - '+data.val().username,{
            'body': data.val().message,
            'icon': 'bell.png',
            'tag': data.getKey()
        });
        notify.onclick = function(){
            alert(this.tag);
        }
    }else{
        alert('Please allow the notification first');
    }
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
});



    </script>

</html>





  --}}


  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<h2>Firebase Web Push Notification Example</h2>

<p id="token"></p>
<script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-messaging.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyDsAjDNl_R9FgrmLW-r8J-kEG7uUnWbFYU",
        authDomain: "laravelfcm-85ee0.firebaseapp.com",
        projectId: "laravelfcm-85ee0",
        storageBucket: "laravelfcm-85ee0.appspot.com",
        messagingSenderId: "776657749281",
        appId: "1:776657749281:web:93a25d30fd4716dc24aa60",
        measurementId: "G-D1SBLGE7RV"
      };
    firebase.initializeApp(firebaseConfig);
    const messaging=firebase.messaging();

    function IntitalizeFireBaseMessaging() {
        messaging
            .requestPermission()
            .then(function () {
                console.log("Notification Permission");
                return messaging.getToken();
            })
            .then(function (token) {
                console.log("Token : "+token);
                document.getElementById("token").innerHTML=token;
            })
            .catch(function (reason) {
                console.log(reason);
            });
    }

    messaging.onMessage(function (payload) {
        console.log(payload);
        const notificationOption={
            body:payload.notification.body,
            icon:payload.notification.icon
        };

        if(Notification.permission==="granted"){
            var notification=new Notification(payload.notification.title,notificationOption);

            notification.onclick=function (ev) {
                ev.preventDefault();
                window.open(payload.notification.click_action,'_blank');
                notification.close();
            }
        }

    });
    messaging.onTokenRefresh(function () {
        messaging.getToken()
            .then(function (newtoken) {
                console.log("New Token : "+ newtoken);
            })
            .catch(function (reason) {
                console.log(reason);
            })
    })
    IntitalizeFireBaseMessaging();
</script>
</body>
</html>
