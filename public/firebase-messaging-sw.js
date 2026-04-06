// public/firebase-messaging-sw.js

importScripts('https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging-compat.js');

// ✅ Firebase Config
firebase.initializeApp({
  apiKey: "AIzaSyAiBzIDvAE6OPKjOVPK35BuDNVNllNsRf8",
  authDomain: "donation-app-3ec1f.firebaseapp.com",
  projectId: "donation-app-3ec1f",
  storageBucket: "donation-app-3ec1f.firebasestorage.app",
  messagingSenderId: "1005031388611",
  appId: "1:1005031388611:web:7f5b6660ebc4fac5167976",
  measurementId: "G-QF5WBS03K1"
});

// ✅ Initialize Messaging
const messaging = firebase.messaging();

// ✅ Background notification listener
messaging.onBackgroundMessage(function (payload) {
  console.log('[firebase-messaging-sw.js] Received background message:', payload);

  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/icon.png' // optional — add any icon if you want
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
