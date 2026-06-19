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
    if (!comments || comments.length === 0) {
      container.innerHTML = '<div class="no-comments">Нет комментариев</div>';
      return;
    }
    comments.forEach((comment) => {
      container.innerHTML += this.renderComment(comment);
    });
  }

  add(container, comment) {
    if (this.empty) {
      container.innerHTML = "";
      this.empty = false;
    }
    container.insertAdjacentHTML("beforeend", this.renderComment(comment));
  }

  remove(commentId) {
    document.getElementById(`comment-${commentId}`)?.remove();
  }
}
