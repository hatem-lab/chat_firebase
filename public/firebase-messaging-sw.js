// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.6.3/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.6.3/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object

var firebaseConfig = {
    apiKey: "AIzaSyAY49-fQHTGXMtrJd8CX4IkHTjPWGoVIE0",
    authDomain: "laravel-auth-10fa7.firebaseapp.com",
    projectId: "laravel-auth-10fa7",
    storageBucket: "laravel-auth-10fa7.appspot.com",
    messagingSenderId: "1045131648612",
    appId: "1:1045131648612:web:7c051f79258ef21f918971",
    measurementId: "G-26BZEBT9Z3"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);


// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const{title,body}=payload.notification;

    const notificationOptions = {
        body,

    };

    self.registration.showNotification(notificationTitle,
        notificationOptions);
});
