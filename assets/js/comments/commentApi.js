const CommentApi = {
  async getComments(postId) {
    const response = await fetch(
      `/api/comments/getComments.php?post_id=${postId}`,
    );
    return response.json();
  },

  async create(postId, comment) {
    const formData = new FormData();
    formData.append("post_id", postId);
    formData.append("comment", comment);

    const response = await fetch("/api/comments/createComment.php", {
      method: "POST",
      body: formData,
    });

    return response.json();
  },

  async saveCommentEdit(commentId, comment) {
    const formData = new FormData();
    formData.append("comment_id", commentId);
    formData.append("comment", comment);

    const response = await fetch("/api/comments/updateComment.php", {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    if (result.success) {
      commentView.update(commentId, comment);
      return result;
    }
  },

  async delete(commentId) {
    const response = await fetch("/api/comments/deleteComment.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `comment_id=${commentId}`,
    });
    const result = await response.json();
    if (result.success) {
      commentView.remove(commentId);
    }
  },
};
