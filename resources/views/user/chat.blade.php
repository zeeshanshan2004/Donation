<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0">User Chat Test Interface</h5>
                </div>
                <div class="card-body bg-light">
                    <!-- Config Section -->
                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <label class="small fw-bold">Access Token (Bearer)</label>
                            <input type="text" id="tokenInput" class="form-control form-control-sm"
                                placeholder="Paste JWT token here...">
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold">My User ID</label>
                            <input type="number" id="userIdInput" class="form-control form-control-sm" value="31">
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold">Target Admin ID</label>
                            <input type="number" id="receiverIdInput" class="form-control form-control-sm" value="1">
                        </div>
                        <div class="col-12 mt-2">
                            <button class="btn btn-primary btn-sm w-100" onclick="startChat()">Initialize Chat</button>
                        </div>
                    </div>

                    <hr>

                    <!-- Chat UI -->
                    <div id="chat-wrapper" class="chat-wrapper d-none"
                        style="max-width: 100%; margin: 0; height: 450px; background: #fff; border: 1px solid #ddd; border-radius: 8px; display: flex; flex-direction: column;">
                        <div id="chat-box" class="chat-body p-3 flex-grow-1"
                            style="overflow-y: auto; background: #f8f9fa; display: flex; flex-direction: column; gap: 10px;">
                            <div class="text-center text-muted py-5">Enter token to start</div>
                        </div>
                        <div class="chat-footer p-3 border-top d-flex gap-2 bg-white">
                            <input type="text" id="message" class="form-control rounded-pill"
                                placeholder="Type message..." onkeypress="if(event.key === 'Enter') sendMessage()">
                            <button class="btn btn-success rounded-circle" style="width: 40px; height: 40px;"
                                onclick="sendMessage()">
                                <i class="bi bi-send-fill" style="color: white"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bubble {
        max-width: 80%;
        padding: 8px 12px;
        border-radius: 15px;
        font-size: 0.9rem;
        position: relative;
    }

    .bubble-admin {
        align-self: flex-start;
        background: #e9ecef;
        color: #333;
        border-bottom-left-radius: 2px;
    }

    .bubble-user {
        align-self: flex-end;
        background: #198754;
        color: #fff;
        border-bottom-right-radius: 2px;
    }

    .time-stamp {
        font-size: 0.65rem;
        margin-top: 3px;
        opacity: 0.7;
        display: block;
        text-align: right;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<script>
    let lastMessageCount = 0;
    let pollingInterval = null;

    function startChat() {
        const token = document.getElementById('tokenInput').value.trim();
        if (!token) {
            alert("Please paste an Access Token first!");
            return;
        }

        document.getElementById('chat-wrapper').classList.remove('d-none');
        document.getElementById('chat-box').innerHTML = '<div class="text-center py-5">Connecting...</div>';

        lastMessageCount = 0;
        if (pollingInterval) clearInterval(pollingInterval);

        loadChat();
        pollingInterval = setInterval(loadChat, 3000);
    }

    function appendMessage(sender, message, time) {
        const chatBox = document.getElementById('chat-box');
        if (!chatBox) return;

        const isAdmin = sender === 'admin';
        const bubble = document.createElement('div');
        bubble.className = `bubble ${isAdmin ? 'bubble-admin' : 'bubble-user'}`;

        const date = time ? new Date(time) : new Date();
        const timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        bubble.innerHTML = `
        ${message}
        <span class="time-stamp">${timeStr}</span>
    `;
        chatBox.appendChild(bubble);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function loadChat() {
        const token = document.getElementById('tokenInput').value;
        const userId = document.getElementById('userIdInput').value;

        axios.get(`/api/auth/chat/messages/${userId}`, {
            headers: { 'Authorization': `Bearer ${token}` }
        })
            .then(res => {
                const data = res.data.messages || res.data;
                if (data.length > lastMessageCount) {
                    const chatBox = document.getElementById('chat-box');
                    if (lastMessageCount === 0) chatBox.innerHTML = '';

                    const newMessages = data.slice(lastMessageCount);
                    newMessages.forEach(m => appendMessage(m.sender_type, m.message, m.created_at));
                    lastMessageCount = data.length;
                } else if (data.length === 0 && lastMessageCount === 0) {
                    document.getElementById('chat-box').innerHTML = '<div class="text-center py-5 text-muted small">No messages yet. Send one!</div>';
                }
            })
            .catch(err => {
                console.error("Auth Error:", err);
                if (err.response && err.response.status === 401) {
                    clearInterval(pollingInterval);
                    document.getElementById('chat-box').innerHTML = '<div class="text-danger text-center py-5">Invalid or Expired Token!</div>';
                }
            });
    }

    function sendMessage() {
        const token = document.getElementById('tokenInput').value;
        const receiverId = document.getElementById('receiverIdInput').value;
        const msgInput = document.getElementById('message');
        const msg = msgInput.value.trim();
        if (!msg) return;

        axios.post('/api/auth/chat/send', {
            receiver_id: receiverId,
            message: msg
        }, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(res => {
            msgInput.value = '';
            appendMessage('user', msg);
            lastMessageCount++;
        });
    }
</script>