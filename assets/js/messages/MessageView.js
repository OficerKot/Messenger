class MessageView {
  empty = false;

  renderAllMessages(messages, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";

    if (!messages || messages.length === 0) {
      this.empty = true;
      container.innerHTML =
        '<div class="no-msg">Пока пусто. Начните диалог первым!</div>';
      return;
    }
    messages.forEach((message) => {
      container.innerHTML += this.renderMessage(message);
    });
    this.attachEvents();
  }

  renderMessage(message) {
    return `
        <div class="message" id="${message.message_id}"> 
            <div class="message-header">
                <div>
                    <img class="msg-avatar" src="../../assets/uploads/${message.sender_avatar}">
                </div>
                <div class="message-sender">
                    ${message.sender_first_name} ${message.sender_last_name}
                </div>
                <div class="message-time">
                    ${message.date}
                </div>
				    <div class="message-is-read">
                    ${message.is_read}
                </div>
                <div class="msg-menu-wrapper">
                    <button class="message-menu-btn" data-msg-id="${message.message_id}" data-is_author="${message.is_author === true}">
                        ...
                    </button>
                </div>
            </div>
            ${this.createMessageContent(message.message_id, message.message, message.image_path)}
        </div>
    `;
  }

  createMessageContent(id, msg, img_path) {
    return `<div class="message-content" id="content-${id}">
                <div id="content-msg-${id}">${msg}</div>
                ${img_path ? `<img src="../assets/uploads/${img_path}" class="msg-img">` : ""}
            </div>`;
  }

  showMessageActionsMenu(msgId, is_author) {
    if (!is_author) {
      return;
    }

    const existingMenu = document.getElementById(`menu-${msgId}`);
    if (existingMenu) {
      existingMenu.remove();
      return;
    }

    document.querySelectorAll(".msg-menu").forEach((menu) => menu.remove());
    const menu = document.createElement("div");
    menu.id = `menu-${msgId}`;
    menu.classList.add("msg-menu");

    let buttonsHTML = "";

    if (is_author) {
      buttonsHTML +=
        '<button class="edit-btn" data-msgId="' +
        msgId +
        '">Редактировать</button>';

      buttonsHTML +=
        '<button class="delete-btn" data-msgId="' +
        msgId +
        '">Удалить</button>';
    }

    menu.innerHTML = `
        <div style="display:flex; flex-direction:column;">
            ${buttonsHTML}
        </div>
    `;

    const deleteBtn = menu.querySelector(".delete-btn");
    if (deleteBtn) {
      deleteBtn.onclick = (e) => {
        e.stopPropagation();
        this.emit("delete", msgId);
        menu.remove();
      };
    }

    const editBtn = menu.querySelector(".edit-btn");
    if (editBtn) {
      editBtn.onclick = (e) => {
        e.stopPropagation();
        this.emit("edit", msgId);
        menu.remove();
      };
    }

    // Закрытие при клике вне меню
    setTimeout(() => {
      document.addEventListener("click", function closeMenu(e) {
        if (!menu.contains(e.target)) {
          menu.remove();
          document.removeEventListener("click", closeMenu);
        }
      });
    }, 0);

    const message = document.getElementById(msgId);
    const wrapper = message.querySelector(".msg-menu-wrapper");
    if (wrapper) {
      wrapper.appendChild(menu);
    }
  }

  showMsgEditForm(msgId, saveCallback) {
    const existingForm = document.querySelectorAll(".msgFormEdit");
    existingForm.forEach((form) => {
      form.remove();
    });

    const content = document.getElementById(`content-msg-${msgId}`);
    const message = content.textContent.trim();
    const msgImage = document
      .getElementById(`content-${msgId}`)
      ?.querySelector(".message-image");
    let removeImage = false;

    content.style.display = "none";

    const form = document.createElement("div");
    form.className = "msgFormEdit";
    form.innerHTML = `
        <textarea>${message}</textarea>

        <div style="display:flex; align-items:center; gap:10px; margin:10px 0;">
            <input type="file" accept="image/*">
            ${msgImage ? '<button type="button" class="remove-image-btn">🗑️ Удалить фото</button>' : ""}
        </div>

        <div style="margin-top:10px;">
            <button type="button" class="save-btn">Сохранить</button>
            <button type="button" class="cancel-btn">Отмена</button>
        </div>
    `;
    content.parentElement.insertBefore(form, content.nextSibling);

    const imageInput = form.querySelector('input[type="file"]');
    const removeImageBtn = form.querySelector(".remove-image-btn");

    // Кнопка удаления фото
    if (removeImageBtn) {
      removeImageBtn.onclick = () => {
        imageInput.value = "";
        removeImage = true;
        removeImageBtn.textContent = "Фото будет удалено после сохранения";
      };
    }

    // Обработчики
    form.querySelector(".cancel-btn").onclick = () => {
      form.remove();
      content.style.display = "block";
    };

    form.querySelector(".save-btn").onclick = async () => {
      const textarea = form.querySelector("textarea");
      const newMessage = textarea.value.trim();
      const imageFile = form.querySelector("input[type='file']").files[0];

      const success = await saveCallback(
        msgId,
        newMessage,
        imageFile,
        removeImage,
      );

      if (success) {
        form.remove();
        content.style.display = "block";
      }
    };
  }

  addMessage(message, containerId) {
    const container = document.getElementById(containerId);
    console.log("Контейнер:", container);
    console.log("scrollHeight:", container.scrollHeight);
    console.log("clientHeight:", container.clientHeight);
    console.log("scrollTop до:", container.scrollTop);
    container.scrollTop = container.scrollHeight;
    console.log("scrollTop после:", container.scrollTop);
    if (this.empty) {
      container.innerHTML = "";
      this.empty = false;
    }
    container.innerHTML = container.innerHTML + this.renderMessage(message);
    container.scrollTop = container.scrollHeight;
    this.attachEvents();
  }

  updateMessage(msgId, new_msg, img_path) {
    document.getElementById(`content-${msgId}`).innerHTML =
      this.createMessageContent(msgId, new_msg, img_path);
  }

  removeMessage(msgId) {
    document.getElementById(msgId).remove();
  }

  // Система событий
  on(event, callback) {
    if (!this._events) this._events = {};
    if (!this._events[event]) this._events[event] = [];
    this._events[event].push(callback);
  }

  emit(event, ...args) {
    if (this._events && this._events[event]) {
      this._events[event].forEach((cb) => cb(...args));
    }
  }

  attachEvents() {
    const container = document.getElementById("messagesContainer");

    // Убираем старый обработчик, чтобы не дублировать
    container.removeEventListener("click", this._handleMenuClick);

    // Навешиваем один обработчик на ВСЕ кнопки в контейнере
    container.addEventListener(
      "click",
      (this._handleMenuClick = (e) => {
        const btn = e.target.closest(".message-menu-btn");
        if (!btn) return;

        const msgId = btn.dataset.msgId;
        const is_author =
          btn.dataset.is_author === "true" || btn.dataset.is_author === "1";
        this.showMessageActionsMenu(msgId, is_author);
      }),
    );
  }
}
