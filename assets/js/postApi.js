const PostApi = {
  async loadPosts(wall_owner_id) {
    try {
      const id = wall_owner_id ? `?user_id=${wall_owner_id}` : ``;
      const response = await fetch(`../../api/getPosts.php${id}`);

      if (!response.ok) {
        throw new Error(`HTTP ошибка: ${response.status}`);
      }

      const data = await response.json();

      // Проверка на закрытую страницу (если смотрим именно стену пользователя!)
      if (data.error && data.is_private) {
        document.getElementById("postsContainer").innerHTML = `
                <div class="private-message">
                    🔒 Страница закрыта. Добавьте пользователя в друзья, чтобы видеть посты.
                </div>
            `;
        return;
      }

      postView.renderAllPosts(data, "postsContainer");
    } catch (error) {
      console.error("Ошибка загрузки:", error);
      document.getElementById("postsContainer").innerHTML =
        '<div class="error">Не удалось загрузить посты</div>';
    }
  },

  async createPost(event) {
    event.preventDefault();

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
  },

  async onDeletePost(postId) {
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
  },

  async savePostEdit(postId, message, imagePath, removeImage) {
    const formData = new FormData();
    formData.append("post_id", postId);
    formData.append("message", message);

    if (removeImage) {
      formData.append("removeImage", "1");
    } else if (imagePath) {
      formData.append("image", imagePath);
    }
    try {
      const response = await fetch("../../api/updatePost.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        postView.updatePost(
          postId,
          result.post.message,
          result.post.image_path,
        );
        return true;
      } else {
        alert("Ошибка: " + result.error);
        return false;
      }
    } catch (error) {
      console.error("Ошибка:", error);
      return false;
    }
  },

  async commentPost(event, postId) {
    event.preventDefault();

    // Поле ввода внутри конкретного поста
    const postElement = document.getElementById(postId);
    const input = postElement.querySelector(".comment-input");
    const comment = input.value.trim();

    if (!comment) return;

    const formData = new FormData();
    formData.append("comment", comment);
    formData.append("post_id", postId);

    try {
      const response = await fetch("../../api/commentPost.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error(`HTTP ошибка: ${response.status}`);
      }

      const result = await response.json();

      if (result.success) {
        postView.addComment(postId, result.comment);
        input.value = "";
      } else {
        alert(result.error);
      }
    } catch (error) {
      console.error("Ошибка создания комментария:", error);
      alert("Не удалось создать комментарий");
    }
  },
};
