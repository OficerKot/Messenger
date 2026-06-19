window.postView = new PostView();
window.commentView = new CommentView();
// события View
postView.on("delete", (postId) => {
  onDeletePost(postId);
});

postView.on("edit", (postId) => {
  postView.showPostEditForm(postId, savePostEdit);
});

postView.on("toggleComments", async (postId) => {
  const section = document.getElementById(`comments-${postId}`);

  if (section.style.display === "none" || !section.style.display) {
    const comments = await CommentApi.getComments(postId);
    commentView.renderAll(section, comments);
    section.style.display = "block";
  } else {
    section.style.display = "none";
  }
});

// Функция для получения GET параметров из URL
function getUrlParam(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

const wall_owner_id = getUrlParam("user_id");
loadPosts(wall_owner_id);
document
  .getElementById("postForm")
  .addEventListener("submit", async (e) => createPost(e));
