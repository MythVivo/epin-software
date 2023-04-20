importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: "AIzaSyBdFC2zS1eGdmuXdwxn7HwTNCV8ZpSB2w8",
    authDomain: "oyuneks-001.firebaseapp.com",
    databaseURL: 'https://oyuneks-001.firebaseio.com',
    projectId: "oyuneks-001",
    storageBucket: "oyuneks-001.appspot.com",
    messagingSenderId: "524101383276",
    appId: "1:524101383276:web:26668f3c052c28cb921449",
    measurementId: "G-S30GS614Y7"
});


// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);

    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "https://oyuneks.ifeelcodev.com/public/brand/brandicon.png",
        click_action: "https://oyuneks.ifeelcodev.com/panel/ilanlar-yonetim",
    };

    return self.registration.showNotification(
        title,
        options,
    );
});
