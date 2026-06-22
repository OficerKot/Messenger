class CommentView {
  empty = false;

  renderComment(comment) {
    return `
            <div class="comment" id="comment-${comment.comment_id}">
                <div class="comment-header">
                    <img class="comment-avatar" src="../assets/uploads/${comment.author_avatar}">
                    <div class="comment-author">
                        ${comment.author_first_name} ${comment.author_last_name}
                    </div>
                    <div class="comment-time">
                        ${comment.date}
                    </div>
                    ${
                      comment.can_edit || comment.can_delete
                        ? `
                        <div class="comment-menu-wrapper">
                            <button class="comment-menu-btn" data-comment-id="${comment.comment_id}" 
                                data-can-edit="${comment.can_edit || false}" 
                                data-can-delete="${comment.can_delete || false}">
                                ...
                            </button>
                        </div>
                    `
                        : ""
                    }
                </div>
                <div class="comment-content">
                    ${comment.comment}
                </div>
            </div>
        `;
  }

  renderAll(container, comments) {
    container.innerHTML = "";

    // Комментарии в скроллящемся контейнере
    const commentsList = document.createElement("div");
    commentsList.className = "comments-list";

    if (!comments || comments.length === 0) {
      const empty = document.createElement("div");
      empty.className = "no-comments";
      empty.textContent = "Нет комментариев";
      commentsList.appendChild(empty);
    } else {
      comments.forEach((comment) => {
        commentsList.innerHTML += this.renderComment(comment);
      });
    }

    container.appendChild(commentsList);

    this.showCommentForm(container);
    this.attachEvents();
  }

  showCommentForm(container) {
    const formHTML = `
        <form class="commentForm">
            <textarea placeholder="Какие мысли насчёт этого?"></textarea>
            <button type="submit">Отправить</button>
        </form>
    `;
    container.innerHTML += formHTML;
  }

  add(container, comment) {
    const commentsList = container.querySelector(".comments-list");
    const empty = commentsList.querySelector(".no-comments");
    if (empty) empty.remove();

    commentsList.insertAdjacentHTML("beforeend", this.renderComment(comment));
    this.attachEvents();
  }

  update(commentId, comment) {
    const commentEl = document.getElementById(`comment-${commentId}`);
    if (!commentEl) return;

    const content = commentEl.querySelector(".comment-content");
    if (content) {
      content.textContent = comment;
    }
  }

  remove(commentId) {
    document.getElementById(`comment-${commentId}`)?.remove();
  }

  showCommentEditForm(commentId, saveCallback) {
    const commentEl = document.getElementById(`comment-${commentId}`);
    const content = commentEl.querySelector(".comment-content");
    const originalText = content.textContent.trim();

    // Удалить другие формы
    document.querySelectorAll(".commentEditForm").forEach((f) => f.remove());

    // Скрыть текст
    content.style.display = "none";

    const form = document.createElement("div");
    form.className = "commentEditForm";
    form.innerHTML = `
        <textarea style="width:100%; min-height:60px;">${originalText}</textarea>
        <div style="margin-top:8px;">
            <button type="button" class="save-btn">Сохранить</button>
            <button type="button" class="cancel-btn">Отмена</button>
        </div>
    `;

    // Вставить форму после контента
    content.parentElement.insertBefore(form, content.nextSibling);

    // Отмена
    form.querySelector(".cancel-btn").onclick = () => {
      form.remove();
      content.style.display = "block";
    };

    // Сохранить
    form.querySelector(".save-btn").onclick = async () => {
      const newText = form.querySelector("textarea").value.trim();
      if (!newText) return;

      const success = await saveCallback(commentId, newText);
      if (success) {
        form.remove();
        content.style.display = "block";
      }
    };
  }

  showCommentActionsMenu(commentId, canEdit, canDelete) {
    if (!canEdit && !canDelete) return;

    const existingMenu = document.getElementById(`comment-menu-${commentId}`);
    if (existingMenu) {
      existingMenu.remove();
      return;
    }

    document.querySelectorAll(".comment-menu").forEach((m) => m.remove());

    const menu = document.createElement("div");
    menu.id = `comment-menu-${commentId}`;
    menu.classList.add("comment-menu");

    let buttonsHTML = "";
    if (canEdit) {
      buttonsHTML += `<button class="edit-comment-btn">Редактировать</button>`;
    }
    if (canDelete) {
      buttonsHTML += `<button class="delete-comment-btn">Удалить</button>`;
    }

    menu.innerHTML = `
      <div style="display:flex; flex-direction:column;">
        ${buttonsHTML}
      </div>
    `;

    menu.querySelector(".edit-comment-btn")?.addEventListener("click", (e) => {
      e.stopPropagation();
      this.emit("edit", commentId);
      menu.remove();
    });

    menu
      .querySelector(".delete-comment-btn")
      ?.addEventListener("click", (e) => {
        e.stopPropagation();
        this.emit("delete", commentId);
        menu.remove();
      });

    // Позиционирование
    const commentEl = document.getElementById(`comment-${commentId}`);
    const wrapper = commentEl?.querySelector(".comment-menu-wrapper");
    if (wrapper) wrapper.appendChild(menu);

    // Закрытие при клике вне
    setTimeout(() => {
      document.addEventListener("click", function closeMenu(e) {
        if (!menu.contains(e.target)) {
          menu.remove();
          document.removeEventListener("click", closeMenu);
        }
      });
    }, 0);
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

  // Привязка событий к кнопкам после рендера
  attachEvents() {
    document.querySelectorAll(".comment-menu-btn").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const commentId = btn.dataset.commentId;
        const canEdit = btn.dataset.canEdit === "true";
        const canDelete = btn.dataset.canDelete === "true";
        this.showCommentActionsMenu(commentId, canEdit, canDelete);
      });
    });
  }
}

