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

async function loadPosts(wall_owner_id) {
  try {
    const response = await fetch(
      `../../api/getPosts.php?user_id=${wall_owner_id}`,
    );
    if (!response.ok) {
      throw new Error(`HTTP ошибка: ${response.status}`);
    }

    const posts = await response.json();
    postView.renderAllPosts(posts, "postsContainer");
  } catch (error) {
    console.error("Ошибка загрузки:", error);
    document.getElementById("postsContainer").innerHTML =
      '<div class="error">Не удалось загрузить посты</div>';
  }
}

async function onDeletePost(postId) {
  try {
    const response = await fetch("../../api/deletePost.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `post_id=${postId}`,
    });

    const result = await response.json();

    if (result.success) {
      postView.removePost(postId);
    } else {
      alert("Ошибка: " + result.error);
    }
  } catch (error) {
    console.error("Ошибка удаления:", error);
    alert("Не удалось удалить пост");
  }
}

async function savePostEdit(postId, message, imagePath) {
  const formData = new FormData();
  formData.append("post_id", postId);
  formData.append("message", message);
  if (imagePath) {
    formData.append("image", imagePath);
  }

  try {
    const response = await fetch("../../api/updatePost.php", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.success) {
      postView.updatePost(postId, result.post.message, result.post.image_path);
      return true;
    } else {
      alert("Ошибка: " + result.error);
      return false;
    }
  } catch (error) {
    console.error("Ошибка:", error);
    return false;
  }
}

const wall_owner_id = getUrlParam("user_id");
loadPosts(wall_owner_id);

document.getElementById("postForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const message = document.getElementById("postMessage").value;
  const imageFile = document.getElementById("postImage").files[0];

  const formData = new FormData();
  formData.append("message", message);
  formData.append("image", imageFile);
  formData.append("wall_owner_id", wall_owner_id);

  try {
    const response = await fetch(`../../api/createPost.php`, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP ошибка: ${response.status}`);
    }

    const newPost = await response.json();

    if (newPost.success) {
      postView.addPostToWall(newPost.post, "postsContainer");

      document.getElementById("postMessage").value = "";
      document.getElementById("postImage").value = "";
    } else {
      alert(newPost.error);
    }
  } catch (error) {
    console.error("Ошибка создания поста:", error);
    alert("Не удалось создать пост");
  }
});
