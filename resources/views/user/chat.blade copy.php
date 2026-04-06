

<h3 class="mt-3">User Chat Test Panel</h3>
<div class="border p-3 d-flex flex-column" style="height:500px;">
    <div id="chat-box" style="flex-grow:1; overflow-y:auto;"></div>
    <div class="mt-2 d-flex">
        <input type="text" id="message" class="form-control" placeholder="Type message...">
        <button class="btn btn-success ms-2" onclick="sendMessage()">Send</button>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-messaging-compat.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// -------------------------
// JWT Token from backend (Laravel Passport / JWT Auth)
// -------------------------
const JWT_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3NjM0MjU5NDcsImV4cCI6MTc2MzQyOTU0NywibmJmIjoxNzYzNDI1OTQ3LCJqdGkiOiJJSnVscDZ1cVE3V2lXaVd2Iiwic3ViIjoiMyIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.1hBMeFSWpP6fu7GvSizR-e6hmkNL1PVY2TGrbptL8E0'; // ya session / localStorage se bhi le sakte ho

// -------------------------
// Firebase Config
// -------------------------
const firebaseConfig = {
    apiKey: "AIzaSyAiBzIDvAE6OPKjOVPK35BuDNVNllNsRf8",
    authDomain: "donation-app-3ec1f.firebaseapp.com",
    projectId: "donation-app-3ec1f",
    storageBucket: "donation-app-3ec1f.firebasestorage.app",
    messagingSenderId: "1005031388611",
    appId: "1:1005031388611:web:7f5b6660ebc4fac5167976",
};
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// -------------------------
// Request permission & get FCM token
// -------------------------
Notification.requestPermission().then(permission => {
    if (permission === 'granted') {
        messaging.getToken({ vapidKey: 'BGKhxy_CkMqQ_Z8KbTnf-vAJi6gYfLMVIJgl-qFaeJDeSknL0ixMUwfWvlRvK9vOhnLcEbHPBzXCcMWPK1CzDbc' })
        .then(token => {
            console.log('FCM Token:', token);
            // Send token to backend
            axios.post('/api/auth/save-fcm-token', { fcm_token: token }, {
                headers: {
                    'Authorization': `Bearer ${JWT_TOKEN}`,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        })
        .catch(err => console.log('Error getting FCM token:', err));
    } else {
        console.log('Notification permission not granted');
    }
});



// -------------------------
// Real-time message listener
// -------------------------
messaging.onMessage((payload) => {
    console.log('FCM Message received:', payload);
    if (payload.data && payload.data.type === 'chat') {
        appendMessage('admin', payload.data.message);
    }
});



// -------------------------
// User Chat Logic
// -------------------------
let userId = 3; // Logged-in user id
let adminId = 1;

function appendMessage(sender, message) {
    const chatBox = document.getElementById('chat-box');
    const cls = sender === 'admin' ? 'text-start' : 'text-end';
    chatBox.innerHTML += `<div class='${cls}'><b>${sender}:</b> ${message}</div>`;
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Load chat messages initially
function loadChat() {
    axios.get(`/api/auth/chat/messages/${userId}`, {
        headers: {
            'Authorization': `Bearer ${JWT_TOKEN}`
        }
    })
    .then(res => {
        document.getElementById('chat-box').innerHTML = '';
        res.data.forEach(m => appendMessage(m.sender_type, m.message));
    });
}

// Send message (user → admin)
function sendMessage() {
    let msg = document.getElementById('message').value;
    if (!msg) return;

    axios.post('/api/auth/chat/send', {
        receiver_id: adminId,
        message: msg
    }, {
        headers: {
            'Authorization': `Bearer ${JWT_TOKEN}`,
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(res => {
        document.getElementById('message').value = '';
        appendMessage('user', msg);
    });
}

// -------------------------
// Initial load
// -------------------------
loadChat();
</script>


