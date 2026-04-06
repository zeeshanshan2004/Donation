@extends('admin.layouts.master')
@section('content')
    <style>
        .chat-container {
            height: calc(100vh - 180px);
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .user-sidebar {
            width: 300px;
            border-right: 1px solid #eee;
            display: flex;
            flex-direction: column;
            background: #f8f9fa;
        }

        .user-search {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .user-list {
            flex-grow: 1;
            overflow-y: auto;
        }

        .user-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-item:hover {
            background: #f1f1f1;
        }

        .user-item.active {
            background: #e9ecef;
            border-left: 4px solid #000;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #ddd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #666;
        }

        .chat-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .messages-area {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .msg-bubble {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
            position: relative;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .msg-admin {
            align-self: flex-end;
            background: #000;
            color: #fff;
            border-bottom-right-radius: 2px;
        }

        .msg-user {
            align-self: flex-start;
            background: #fff;
            color: #333;
            border-bottom-left-radius: 2px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .msg-time {
            font-size: 0.7rem;
            margin-top: 5px;
            opacity: 0.7;
            display: block;
            text-align: right;
        }

        .chat-input-area {
            padding: 15px 20px;
            background: #fff;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .empty-chat {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="chat-container">
            <!-- Sidebar -->
            <div class="user-sidebar">
                <div class="user-search">
                    <input type="text" id="userLookup" class="form-control form-control-sm rounded-pill"
                        placeholder="Search users...">
                </div>
                <div id="user-list" class="user-list">
                    <!-- Users will be loaded here -->
                </div>
            </div>

            <!-- Main Chat -->
            <div class="chat-main">
                <div id="chat-header" class="chat-header">
                    <div>
                        <h5 class="m-0" id="active-user-name">Select a user to start chat</h5>
                        <small class="text-muted d-none" id="diag-info"></small>
                    </div>
                    <div class="text-end small">
                        Admin ID: <span class="badge bg-secondary">{{ Auth::guard('admin')->id() }}</span>
                    </div>
                </div>

                <div id="chat-box" class="messages-area">
                    <div class="empty-chat">
                        <i class="bi bi-chat-dots" style="font-size: 3rem;"></i>
                        <p>No conversation selected</p>
                    </div>
                </div>

                <div class="chat-input-area">
                    <input id="message" class="form-control rounded-pill" placeholder="Type a message..."
                        onkeypress="if(event.key === 'Enter') sendMessage()">
                    <button class="btn btn-dark rounded-circle" style="width: 45px; height: 45px;" onclick="sendMessage()">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script>
        let selectedUser = null;
        let lastMessageCount = 0;

        function loadUsers() {
            axios.get('/admin/chat/users')
                .then(res => {
                    const list = document.getElementById('user-list');
                    list.innerHTML = '';
                    res.data.forEach(u => {
                        const div = document.createElement('div');
                        div.className = `user-item ${selectedUser === u.id ? 'active' : ''}`;
                        div.onclick = () => selectUser(u.id, u.name, div);

                        const badge = u.unread_count > 0
                            ? `<span class="badge bg-danger rounded-pill">${u.unread_count}</span>`
                            : '';

                        div.innerHTML = `
                            <div class="user-avatar">${u.name.charAt(0)}</div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="fw-bold">${u.name}</div>
                                    ${badge}
                                </div>
                                <div class="small text-muted">Click to chat</div>
                            </div>
                        `;
                        list.appendChild(div);
                    });
                });
        }

        function selectUser(user_id, name, element) {
            selectedUser = user_id;
            lastMessageCount = 0;

            document.querySelectorAll('.user-item').forEach(el => el.classList.remove('active'));
            if (element) element.classList.add('active');

            // Clear badge immediately on selection
            const badge = element ? element.querySelector('.badge') : null;
            if (badge) badge.remove();

            document.getElementById('diag-info').innerText = `Chatting with User ID: ${user_id}`;
            document.getElementById('diag-info').classList.remove('d-none');
            document.getElementById('active-user-name').innerText = name;
            document.getElementById('chat-box').innerHTML = '<div class="text-center py-5"><div class="spinner-border spinner-border-sm"></div></div>';

            loadChat(user_id);
        }

        function appendMessage(sender, message, time) {
            const chatBox = document.getElementById('chat-box');
            const isAdmin = sender === 'admin';
            const bubble = document.createElement('div');
            bubble.className = `msg-bubble ${isAdmin ? 'msg-admin' : 'msg-user'}`;

            // Format time
            const date = time ? new Date(time) : new Date();
            const timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            bubble.innerHTML = `
                ${message}
                <span class="msg-time">${timeStr}</span>
            `;
            chatBox.appendChild(bubble);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function loadChat(user_id) {
            if (!user_id) return;

            axios.get(`/admin/chat/messages/${user_id}`)
                .then(res => {
                    const chatBox = document.getElementById('chat-box');
                    if (res.data.length > lastMessageCount) {
                        const isFirstLoad = lastMessageCount === 0;

                        if (isFirstLoad) chatBox.innerHTML = '';

                        // If it's a poll update, only add new ones
                        const newMessages = res.data.slice(lastMessageCount);
                        newMessages.forEach(m => {
                            appendMessage(m.sender_type, m.message, m.created_at);
                        });

                        lastMessageCount = res.data.length;
                    } else if (res.data.length === 0 && lastMessageCount === 0) {
                        chatBox.innerHTML = '<div class="text-center py-5 text-muted small">No messages in this conversation</div>';
                    }
                })
                .catch(err => {
                    console.error("Chat Error:", err);
                    document.getElementById('chat-box').innerHTML = '<div class="text-danger text-center py-5">Error loading messages</div>';
                });
        }

        function sendMessage() {
            const msgInput = document.getElementById('message');
            const msg = msgInput.value.trim();
            if (!msg || !selectedUser) return;

            axios.post('/admin/chat/send', {
                receiver_id: selectedUser,
                message: msg
            }, {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
                .then(res => {
                    appendMessage('admin', msg);
                    lastMessageCount++;
                    msgInput.value = '';
                });
        }

        // Poll for messages every 3 seconds
        setInterval(() => {
            if (selectedUser) {
                loadChat(selectedUser);
            }
        }, 3000);

        // Poll for user list (unread badges) every 10 seconds
        setInterval(loadUsers, 10000);

        // Global search
        document.getElementById('userLookup').oninput = (e) => {
            const q = e.target.value.toLowerCase();
            document.querySelectorAll('.user-item').forEach(el => {
                const name = el.innerText.toLowerCase();
                el.style.display = name.includes(q) ? 'flex' : 'none';
            });
        };

        loadUsers();
    </script>
@endsection