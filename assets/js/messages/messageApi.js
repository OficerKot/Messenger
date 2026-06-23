class MessageApi {
  constructor() {
    this.lastId = 0;
    this.isPolling = false;
    this.otherUserId = null;
    this.pollingInterval = null;
  }

  async loadMessages(other_user_id) {
    this.otherUserId = other_user_id;

    try {
      const id = other_user_id ? `?user_id=${other_user_id}` : ``;
      const response = await fetch(`../../api/messages/getMessages.php${id}`);

      if (!response.ok) {
        throw new Error(`HTTP ошибка: ${response.status}`);
      }

      const data = await response.json();

      if (data.error && data.is_private) {
        document.getElementById("messagesContainer").innerHTML = `
          <div class="private-message">
            🔒 Страница закрыта. Добавьте пользователя в друзья, чтобы общаться.
          </div>
        `;
        return;
      }

      messageView.renderAllMessages(data, "messagesContainer");

      if (data.length > 0) {
        this.lastId = data[data.length - 1].message_id;
      }

      this.startPolling(other_user_id);
    } catch (error) {
      console.error("Ошибка загрузки:", error);
      document.getElementById("messagesContainer").innerHTML =
        '<div class="error">Не удалось загрузить диалог</div>';
    }
  }

  async checkNewMessages() {
    if (!this.otherUserId) {
      console.warn("therUserId не установлен");
      return;
    }

    try {
      const formData = new FormData();
      formData.append("other_user_id", this.otherUserId);
      formData.append("last_id", this.lastId);

      const response = await fetch("/api/messages/checkMessages.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (!data.success) {
        return;
      }

      if (data.messages && data.messages.length > 0) {
        data.messages.forEach((msg) => {
          messageView.addMessage(msg, "messagesContainer");
        });

        this.lastId = data.new_last_id || this.lastId;
      }
    } catch (error) {
      console.error("Polling error:", error);
    }
  }

  startPolling(other_user_id) {
    if (other_user_id) {
      this.otherUserId = other_user_id;
    }

    if (this.isPolling) {
      return;
    }

    console.log("▶️ Started polling for user:", this.otherUserId);
    this.isPolling = true;

    // Первая проверка сразу
    this.checkNewMessages();

    this.pollingInterval = setInterval(() => {
      this.checkNewMessages();
    }, 3000);
  }

  stopPolling() {
    console.log("⏹️ Stopped polling");
    this.isPolling = false;
    if (this.pollingInterval) {
      clearInterval(this.pollingInterval);
      this.pollingInterval = null;
    }
  }

  async sendMsg(event) {
    event.preventDefault();

    const message = document.getElementById("msgText").value;
    const imageFile = document.getElementById("msgImage").files[0];

    if (message.length > 5000) {
      alert("Сообщение слишком длинное (максимум 5000 символов)");
      return;
    }

    const formData = new FormData();
    formData.append("message", message);
    formData.append("image", imageFile);
    formData.append("other_user_id", this.otherUserId);

    try {
      const response = await fetch(`../../api/messages/sendMessage.php`, {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error(`HTTP ошибка: ${response.status}`);
      }

      const newMsg = await response.json();

      if (newMsg.success) {
        messageView.addMessage(newMsg.message, "messagesContainer");

        this.lastId = newMsg.message.message_id;

        // Очистка формы
        document.getElementById("msgText").value = "";
        document.getElementById("msgImage").value = "";
      } else {
        alert(newMsg.error);
      }
    } catch (error) {
      console.error("Ошибка отправки сообщения:", error);
      alert("Не удалось отправить сообщение");
    }
  }

  async deleteMsg(msgId) {
    try {
      const response = await fetch("../../api/messages/deleteMessage.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `msg_id=${msgId}`,
      });

      const result = await response.json();

      if (result.success) {
        messageView.removeMessage(msgId);
      } else {
        alert("Ошибка: " + result.error);
      }
    } catch (error) {
      console.error("Ошибка удаления:", error);
      alert("Не удалось удалить сообщение");
    }
  }

  async saveEditMsg(msgId, message, imagePath, removeImage) {
    const formData = new FormData();
    formData.append("msg_id", msgId);
    formData.append("message", message);

    if (removeImage) {
      formData.append("removeImage", "1");
    } else if (imagePath) {
      formData.append("image", imagePath);
    }
    try {
      const response = await fetch("../../api/messages/editMessage.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        messageView.updateMessage(
          msgId,
          result.message.message,
          result.message.image_path,
        );
        return true;
      } else {
        alert("Ошибка: " + result.error);
        return false;
      }
    } catch (error) {
      console.error("Ошибка:", error);
      return false;
    }
  }
}
