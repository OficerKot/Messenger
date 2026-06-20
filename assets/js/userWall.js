window.postView = new PostView();
window.commentView = new CommentView();
// ============ события PostView
postView.on("delete", (postId) => {
  PostApi.onDeletePost(postId);
});

postView.on("edit", (postId) => {
  postView.showPostEditForm(postId, PostApi.savePostEdit);
});

postView.on("toggleComments", async (postId) => {
  const section = document.getElementById(`comments-${postId}`);
  if (!section) return;

  if (section.style.display === "none" || !section.style.display) {
    const comments = await CommentApi.getComments(postId);
    commentView.renderAll(section, comments);
    section.style.display = "block";
  } else {
    section.style.display = "none";
  }

  // Функция обработки нажатия "Отправить"
  const handleSubmit = async (e) => {
    e.preventDefault();
    const form = e.target;
    const textarea = form.querySelector("textarea");
    const text = textarea.value.trim();
    if (!text) return;

    const result = await CommentApi.create(postId, text);

    if (result.success) {
      commentView.add(section, result.comment);
      form.reset();
    }
  };

  const form = section.querySelector(".commentForm");
  if (form) {
    form.addEventListener("submit", handleSubmit);
  }
});

// ============ события CommentView

commentView.on("delete", (commentId) => {
  CommentApi.delete(commentId);
});

commentView.on("edit", (commentId) => {
  commentView.showCommentEditForm(commentId, CommentApi.saveCommentEdit);
});

// ==================================
// Функция для получения GET параметров из URL
function getUrlParam(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

// ==================================
// Основная....программа т_т
const wall_owner_id = getUrlParam("user_id");
if (wall_owner_id) {
  PostApi.loadPosts(wall_owner_id);
}

const postForm = document.getElementById("postForm");
if (postForm) {
  postForm.addEventListener("submit", async (e) => PostApi.createPost(e));
}
