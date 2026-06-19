window.postView = new PostView();

// события View
postView.on("delete", (postId) => {
  onDeletePost(postId);
});

postView.on("edit", (postId) => {
  postView.showPostEditForm(postId, savePostEdit);
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
