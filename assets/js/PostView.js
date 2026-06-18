//**Отрисовка постов без перезагрузки страницы */
class PostView {
  empty = false;
  renderPost(post) {
    return `
        <div class="post" id="${post.post_id}"> 
            <div class="post-header">
                <div>
                    <img class="post-avatar" src="../assets/uploads/${post.author_avatar}">
                </div>
                <div class="post-author">
                    ${post.author_first_name} ${post.author_last_name}
                </div>
                <div class="post-time">
                    ${post.date}
                </div>
                <button class="post-menu-btn" data-post-id="${post.post_id}" data-can-edit="${post.can_edit || false}" data-can-delete="${post.can_delete || false}">
                    ...
                </button>
            </div>
            ${this.createPostContent(post.post_id, post.message, post.image_path)}
        </div>
    `;
  }

  createPostContent(id, msg, img_path) {
    return `<div class="post-content" id="content-${id}">
                ${msg}
                ${img_path ? `<img src="../assets/uploads/${img_path}" class="post-image">` : ""}
            </div>`;
  }

  showPostActionsMenu(postId, canEdit, canDelete) {
    if (!canEdit && !canDelete) return;

    const existingMenu = document.getElementById(`menu-${postId}`);
    if (existingMenu) {
      existingMenu.remove();
      return;
    }

    document.querySelectorAll(".post-menu").forEach((menu) => menu.remove());
    const menu = document.createElement("div");
    menu.id = `menu-${postId}`;
    menu.classList.add("post-menu");

    let buttonsHTML = "";

    if (canEdit) {
      buttonsHTML +=
        '<button class="edit-btn" data-post-id="' +
        postId +
        '">Редактировать</button>';
    }

    if (canDelete) {
      buttonsHTML +=
        '<button class="delete-btn" data-post-id="' +
        postId +
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
        this.emit("delete", postId);
        menu.remove();
      };
    }

    const editBtn = menu.querySelector(".edit-btn");
    if (editBtn) {
      editBtn.onclick = (e) => {
        e.stopPropagation();
        this.emit("edit", postId);
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

    document.getElementById(postId).appendChild(menu);
  }

  showPostEditForm(postId, saveCallback) {
    const content = document.getElementById(`content-${postId}`);
    const message = content.textContent.trim();

    content.style.display = "none";

    const form = document.createElement("div");
    form.className = "postForm";
    form.innerHTML = `
        <textarea>${message}</textarea>
        <input type="file">
        <div style="margin-top:10px;">
            <button type="button" class="save-btn">Сохранить</button>
            <button type="button" class="cancel-btn">Отмена</button>
        </div>
    `;
    content.parentElement.insertBefore(form, content.nextSibling);

    // Обработчики
    form.querySelector(".cancel-btn").onclick = () => {
      form.remove();
      content.style.display = "block";
    };

    form.querySelector(".save-btn").onclick = async () => {
      const newMessage = form.querySelector("textarea").value;
      const imageFile = form.querySelector("input[type='file']").files[0];

      const success = await saveCallback(postId, newMessage, imageFile);

      if (success) {
        form.remove();
        content.style.display = "block";
      }
    };
  }
  renderAllPosts(posts, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";

    if (!posts || posts.length === 0) {
      this.empty = true;
      container.innerHTML =
        '<div class="no-posts">Пока пусто. Станьте первым!</div>';
      return;
    }
    posts.forEach((post) => {
      container.innerHTML += this.renderPost(post);
    });
    this.attachEvents();
  }

  addPostToWall(post, containerId) {
    const container = document.getElementById(containerId);
    if (this.empty) {
      container.innerHTML = "";
      this.empty = false;
    }
    container.innerHTML = this.renderPost(post) + container.innerHTML;

    this.attachEvents();
  }

  updatePost(postId, msg, img_path) {
    document.getElementById(`content-${postId}`).innerHTML =
      this.createPostContent(postId, msg, img_path);
  }

  removePost(postId) {
    document.getElementById(postId).remove();
  }

  // Система событий (втф)
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

  // Привязка событий к кнопкам после рендера
  attachEvents() {
    document.querySelectorAll(".post-menu-btn").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const postId = btn.dataset.postId;
        const canEdit = btn.dataset.canEdit === "true";
        const canDelete = btn.dataset.canDelete === "true";
        this.showPostActionsMenu(postId, canEdit, canDelete);
      });
    });
  }
}
