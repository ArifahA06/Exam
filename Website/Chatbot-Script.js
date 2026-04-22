const chatBody = document.querySelector('.chat-body');
const messageInput = document.querySelector('.message-input');
const sendMessageButton = document.querySelector('#send-message');

const API_KEY = '';
const API_URL = 'https://api.openai.com/v1/chat/completions?key=${API_KEY}';

const userData = {
    message: null,
};

const createMessageElement = (content, ...classes) => {
    const div = document.createElement('div');
    div.classList = (`message`, ...classes);
    div.innerHTML = content;
    return div;
};

const generateBotResponse = async (incomingMessageDiv) => {
    const messageElement = incomingMessageDiv.querySelector('.message-text');
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            contents: [{
                parts: [{text: userData.message}]
            }]
        })
    };

    try {
        const response = await fetch(API_URL, requestOptions);
        const data = await response.json();
        if(!response.ok) throw new Error(data.error.message);

        const apiResponseText = data.candidates[0].content.parts[0].text.replace(/\n/g, '<br>').trim();
        messageElement.innerText = apiResponseText;
    } catch (error) {
        console.log(error);
    } finally {
        incomingMessageDiv.classList.remove('thinking');
    }
};

const handleOutgoingMessage = (userMessage) => {
    e.preventDefault();
    userData = messageInput.value.trim();
    messageInput.value = '';

    const messageContent = <div class="message-text"></div>;

    const outgoingMessageDiv = createMessageElement(messageContent, "user-message");
    outgoingMessageDiv.querySelector('.message-text').textContent = userData.message;
    chatBody.appendChild(outgoingMessageDiv);

    setTimeout(() => {
       const messageContent = `<svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path></svg>
                    <div class="message-text">
                        <div class="thinking-indicator">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                    </div>`;
        const incomingMessageDiv = createMessageElement(messageContent, "bot-message");
        incomingMessageDiv.querySelector('.message-text').textContent = userData.message;
        chatBody.appendChild(incomingMessageDiv);
        generateBotResponse(incomingMessageDiv);
    }, 600);
};

messageInput.addEventListener('keydown',(e) => {
    const userMessage = e.target.value.trim();
    if(e.key === 'Enter' && userMessage){
        handleOutgoingMessage(e);
    }
});

sendMessageButton.addEventListener('click', (e) => handleOutgoingMessage(e))