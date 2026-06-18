<link rel="stylesheet" href="assets/css/header.css">

<div class="header-fullwidth">
	<h1>Название</h1>

	<!-- ПОИСКОВАЯ СТРОКА -->
	<div class="search-container">
		<div class="search-box">
			<svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<circle cx="10.5" cy="10.5" r="6.5" />
				<line x1="21" y1="21" x2="15" y2="15" />
			</svg>
			<input type="text" class="search-input" placeholder="Поиск">
		</div>
	</div>

	<!-- ПРАВАЯ ЧАСТЬ ХЕДЕРА -->
	<div class="header-right">
		<?php
		
		session_start();
		/** @var mysqli $conn */
		if (isset($_SESSION['id'])):?>
		<a href="../auth/logout.php" style="text-decoration: none;">Выход</a>

		<div class="avatar">
			<?php 
			$user = new User($_SESSION['id'], $conn);
			$image_name = $user->get(UserField::AVATAR);?>
			<img src="../assets/uploads/<?php echo ($image_name);?>"
				style="width: 32px; height: 32px; border-radius: 100%;">
		</div>
		<?php else:?>
		<div>Вы вошли как гость</div>
		<?php endif;?>
	</div>
</div>