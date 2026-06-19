//**Отрисовка постов без перезагрузки страницы */
class PostView {
  empty = false;
  renderPost(post) {
    return `
		<div class="post"> 

			<div >
				<img class="post-avatar" src="../assets/uploads/${post.author_avatar}">
			</div>

			<div class="post-author">
			${post.author_first_name} ${post.author_last_name}
			</div>

			<div class="post-time">
			${post.date}
			</div>

			<div class="post-content">
			${post.message}

			${post.image_path ? `<img src="../assets/uploads/${post.image_path}" class="post-image">` : ""}
			</div>
		</div>
	`;
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
  }

  addPostToWall(post, containerId) {
    const container = document.getElementById(containerId);
    if (this.empty) {
      container.innerHTML = "";
      this.empty = false;
    }
    container.innerHTML = this.renderPost(post) + container.innerHTML;
  }

  updatePost(postId, newData) {
    // Обновляет содержимое поста
  }

  removePost(postId) {
    // Удаляет DOM элемент поста
  }
}
