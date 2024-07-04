document.addEventListener('DOMContentLoaded', () => {
    const userList = document.getElementById('userlist');
    const chatContainer = document.getElementById('chat-container');
    const chatUsername = document.getElementById('chat-username');
    const chatbox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');
    let currentChatUserId = null;

    // Fetch users and populate the user list
    fetch('../backend/fetch_users.php').then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    }).then(users => {
        users.forEach((user) => {
            const li = document.createElement('li');
            li.textContent = user.name;
            li.onclick = () => openChat(user);
            userList.appendChild(li);
        });
    }).catch(error => console.error("Error Fetching Users: ", error));

    // Open chat function
    function openChat(user) {
        chatUsername.textContent = user.name;
        chatbox.style.display = 'display';
        currentChatUserId = user.userid;
        fetchMessages(user.userid);
    }

    // Fetch messages function
    function fetchMessages(userid) {
        fetch(`../backend/fetch_messages.php?userid=${userid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(messages => {
                console.log("Fetched Messages: ", messages);
                const chatBoxContent = document.querySelector('.chat-box-content');
                chatBoxContent.innerHTML = ''; // Clear previous messages
                messages.forEach(message => {
                    const messageElement = document.createElement('p');
                    messageElement.textContent = message.content;
                    chatBoxContent.appendChild(messageElement);
                });
            }).catch(error => console.error("Error Fetching Messages: ", error));
    }

    // Send message function
    chatForm.addEventListener('submit', e => {
        e.preventDefault();
        const message = messageInput.value.trim();
        const userid = currentChatUserId;

        if (message) {
            fetch('../backend/send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ userid: userid, message: message })
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(result => {
                if (result.success) {
                    messageInput.value = '';
                    fetchMessages(userid);
                } else {
                    console.error("Error Sending Message: ", result.error);
                }
            }).catch(error => console.error("Error Sending Message: ", error));
        }
    });
});
