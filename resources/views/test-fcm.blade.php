<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase Web Notification Test</title>
    <style>
        body { font-family: system-ui, Arial; padding: 24px; background: #f7f7f7; color:#111; }
        h1 { font-size: 20px; margin-bottom: 8px; }
        .card { background:#fff; padding:16px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); max-width:900px; }
        #token { color: green; font-weight: 600; word-break:break-all; }
        .status { margin-top:10px; font-size:0.95rem; }
        .btn { display:inline-block; padding:8px 12px; background:#1c1c1c; color:#fff; border-radius:6px; text-decoration:none; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Firebase Web Notification Test</h1>
        <p>Token: <span id="token">-</span></p>

        <div class="status" id="status">Status: <strong id="statusText">Idle</strong></div>

        <p style="margin-top:12px;">
            <a href="{{ url('/') }}" class="btn">Back</a>
        </p>
    </div>

    <script type="module">
        // ---------------------------
        // CONFIG - update if needed
        // ---------------------------
        const VAPID_KEY = 'BGKhxy_CkMqQ_Z8KbTnf-vAJi6gYfLMVIJgl-qFaeJDeSknL0ixMUwfWvlRvK9vOhnLcEbHPBzXCcMWPK1CzDbc'; // replace if needed
        const SAVE_TOKEN_URL = '/save-fcm-token'; // backend route to save token
        // ---------------------------

        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
        import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyAiBzIDvAE6OPKjOVPK35BuDNVNllNsRf8",
            authDomain: "donation-app-3ec1f.firebaseapp.com",
            projectId: "donation-app-3ec1f",
            storageBucket: "donation-app-3ec1f.firebasestorage.app",
            messagingSenderId: "1005031388611",
            appId: "1:1005031388611:web:7f5b6660ebc4fac5167976",
            measurementId: "G-QF5WBS03K1"
        };

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        const tokenEl = document.getElementById('token');
        const statusText = document.getElementById('statusText');

        function setStatus(txt, isError = false) {
            statusText.textContent = txt;
            statusText.style.color = isError ? 'crimson' : '#111';
        }

        // Register service worker then request permission and get token
        async function initFCM() {
            try {
                if (!('serviceWorker' in navigator)) {
                    setStatus('Service Worker not supported', true);
                    console.error('Service Worker not supported');
                    return;
                }

                setStatus('Registering service worker...');
                // const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                // console.log('SW registered:', registration);

                setStatus('Requesting notification permission...');
                const permission = await Notification.requestPermission();

                if (permission !== 'granted') {
                    setStatus('Notification permission denied', true);
                    console.warn('Notification permission denied.');
                    return;
                }

                setStatus('Getting FCM token...');
                // pass service worker registration to getToken
                const currentToken = await getToken(messaging, { vapidKey: VAPID_KEY, serviceWorkerRegistration: registration });

                if (currentToken) {
                    console.log('FCM Token:', currentToken);
                    tokenEl.textContent = currentToken;
                    setStatus('Token acquired — saving to server...');

                    await saveTokenToServer(currentToken);

                    setStatus('Token saved on server ✔️');
                } else {
                    console.warn('No registration token available.');
                    setStatus('No registration token available', true);
                }

                // foreground messages
                onMessage(messaging, (payload) => {
                    console.log('Message received (foreground):', payload);
                    alert(`Notification: ${payload?.notification?.title ?? 'No title'}\n${payload?.notification?.body ?? ''}`);
                });

            } catch (err) {
                console.error('FCM init error:', err);
                setStatus('Error: ' + (err?.message ?? 'unknown'), true);
            }
        }

        // send token to backend
        async function saveTokenToServer(token) {
            try {
                const res = await fetch(SAVE_TOKEN_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // blade token - file must be blade
                    },
                    body: JSON.stringify({ token })
                });
                const data = await res.json();
                console.log('Server response:', data);
                return data;
            } catch (err) {
                console.error('Error saving token:', err);
                setStatus('Failed to save token to server', true);
            }
        }

        // start
        initFCM();
    </script>
</body>
</html>
