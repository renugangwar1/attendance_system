<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
        }
        #user-list {
            background-color: #ffffff;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            height: 500px;
            overflow-y: auto;
        }
        #user-list li {
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #user-list li:hover {
            background-color: #f1f1f1;
        }
        #chat-box {
            background-color: #ffffff;
            border-radius: 5px;
            padding: 20px;
            height: 400px;
            overflow-y: scroll;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        #chat-box p {
            color: #6c757d;
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .message.you {
            background-color: #007bff;
            color: white;
            text-align: right;
        }
        .message.other {
            background-color: #e0e0e0;
            color: black;
            text-align: left;
        }
        #chat-form {
            display: flex;
            justify-content: space-between;
        }
        #message {
            width: 80%;
            border-radius: 20px;
            padding: 10px;
            font-size: 16px;
        }
        .btn-primary {
            border-radius: 20px;
            font-size: 16px;
            padding: 10px 20px;
        }
        .user-item {
            font-weight: 500;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 8px;
            transition: background-color 0.3s ease;
        }
        .user-item:hover {
            background-color: #007bff;
            color: white;
        }
    </style>

</head>
<body>
    <div class="container mt-5">
        <h4>Chat Panel</h4>
        <div class="row">
            <!-- User List -->
            <div class="col-md-4 border-end">
                <ul id="user-list" class="list-group"></ul>
            </div>

            <!-- Chat Box -->
            <div class="col-md-8">
                <div id="chat-box" class="border p-3 mb-2 bg-light">
                    <!-- Chat content will appear here -->
                </div>
                <form id="chat-form">
                    <div class="input-group">
                        <input type="text" id="message" class="form-control" placeholder="Type your message...">
                        <button class="btn btn-primary" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
    // Fetch users from the backend
    fetch('http://127.0.0.1:8000/chat/users')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const userList = document.getElementById('user-list');
            data.forEach(function(user) {
                const li = document.createElement('li');
                li.classList.add('list-group-item', 'user-item');
                li.dataset.id = user.id;
                li.dataset.type = user.type;
                li.textContent = `${user.name} (${user.type})`;
                userList.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Error fetching users:', error);
            alert('Error fetching user data. Please try again later.');
        });

    // When a user item is clicked, display chat
    document.getElementById('user-list').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('user-item')) {
            const userId = e.target.dataset.id;
            const userName = e.target.textContent;

            document.getElementById('chat-box').innerHTML = `<p class="text-muted">Chatting with ${userName}</p>`;

            // Save the selected user ID for sending messages
            window.selectedUser = { id: userId };

            // Fetch previous chat messages for this user
            fetch(`http://127.0.0.1:8000/chat/messages/${userId}`)
                .then(response => response.json())
                .then(messages => {
                    const chatBox = document.getElementById('chat-box');
                    chatBox.innerHTML = `<p class="text-muted">Chatting with ${userName}</p>`;
                    messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add(message.user_id === window.selectedUser.id ? 'message' : 'message', message.user_id === window.selectedUser.id ? 'you' : 'other');
                        messageDiv.innerHTML = `<strong>${message.user_id === window.selectedUser.id ? 'You' : 'Other'}:</strong> ${message.message}`;
                        chatBox.appendChild(messageDiv);
                    });
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                });
        }
    });

    // Handle sending messages
    document.getElementById('chat-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const message = document.getElementById('message').value;
        if (!message.trim()) return;

        if (!window.selectedUser) {
            alert('Please select a user to chat with.');
            return;
        }

        // Display the message in the chat box
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', 'you');
        messageDiv.innerHTML = `<strong>You:</strong> ${message}`;
        document.getElementById('chat-box').appendChild(messageDiv);

        // Clear the input field
        document.getElementById('message').value = '';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (!csrfToken) {
    console.error('CSRF token not found!');
    return;
}

fetch('http://127.0.0.1:8000/chat/messages', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify({
        message: message,
        user_id: window.selectedUser.id,
        receiver_id: window.selectedUser.receiver_id,
    }),
})
.then(response => response.json())
.then(data => {
    console.log('Message sent successfully:', data);
})
.catch(error => {
    console.error('Error sending message:', error);
});
    });
});
    </script>

</body>
</html>
