const MessageApi = {
  async loadMessages(other_user_id) {
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
    } catch (error) {
      console.error("Ошибка загрузки:", error);
      document.getElementById("messagesContainer").innerHTML =
        '<div class="error">Не удалось загрузить диалог</div>';
    }
  },

  async sendMsg(event) {
    event.preventDefault();

    const message = document.getElementById("msgText").value;
    const imageFile = document.getElementById("msgImage").files[0];

    const formData = new FormData();
    formData.append("message", message);
    formData.append("image", imageFile);
    formData.append("other_user_id", other_user_id);

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

        //Очистка формы
        document.getElementById("msgText").value = "";
        document.getElementById("msgImage").value = "";
      } else {
        alert(newMsg.error);
      }
    } catch (error) {
      console.error("Ошибка отправки сообщения:", error);
      alert("Не удалось отправить сообщение");
    }
  },

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
        postView.removePost(postId);
      } else {
        alert("Ошибка: " + result.error);
      }
    } catch (error) {
      console.error("Ошибка удаления:", error);
      alert("Не удалось удалить сообщение");
    }
  },

  async saveEditMsg(msgId, message, imagePath, removeImage) {
    const formData = new FormData();
    formData.append("post_id", postId);
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
        messageView.update(
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
  },
};
