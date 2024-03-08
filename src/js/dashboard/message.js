const messageForm = document.getElementById("messageForm");
const messageBox = document.getElementById("messageBox");
var _messages = [];
var sound;

if (messageForm) {
  const inputBox = document.getElementById("messageInput");
  const friend = document.getElementById("friendid");
  sound = document.getElementById("sound");
  messageForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(messageForm);
    sendRequest("POST", "api/postMessage", formData)
      .then((res) => {
        inputBox.value = "";
      })
      .catch((err) => {
        console.log(err);
      });
  });
  messageBox.scrollTo({ top: messageBox.scrollHeight, behavior: "smooth" });

  //set loop for messages
  setInterval(() => {
    fetchMessages(friend.value).then((res) => {
      updateMessage(res);
    });
  }, 1000);
}
function updateMessage(messages) {
  if (JSON.stringify(messages) !== JSON.stringify(_messages)) {
    //play sound!
    if (_messages.length != 0) {
      sound.play();
      sound.volume = 0.2;
    }
    //update messages
    _messages = messages;
    messageBox.innerHTML = "";

    //display new messages
    messages.forEach((message) => {
      const newMessage = document.createElement("div");
      newMessage.classList.add("msg");
      if (userid == message.sender) {
        newMessage.classList.add("me");
      } else {
        newMessage.classList.add("other");
      }
      newMessage.innerHTML = message.message;
      messageBox.appendChild(newMessage);
    });
    //scroll to bottom
    messageBox.scrollTo({
      top: messageBox.scrollHeight,
      behavior: "smooth",
    });
  }
}

function fetchMessages(user) {
  return new Promise((resolve, reject) => {
    const formdata = new FormData();
    formdata.append("friendid", user);
    sendRequest("POST", "api/getMessage", formdata)
      .then((res) => {
        resolve(JSON.parse(res));
      })
      .catch((err) => {
        reject(err);
      });
  });
}
