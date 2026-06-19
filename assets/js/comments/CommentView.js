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

    // Форма всегда снизу
    this.showCommentForm(container);
  }

  showCommentForm(container) {
    const formHTML = `
        <form class="commentForm">
            <textarea placeholder="Что вы об этом думаете?"></textarea>
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
  }

  remove(commentId) {
    document.getElementById(`comment-${commentId}`)?.remove();
  }
}
