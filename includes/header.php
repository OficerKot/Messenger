<script>
const IS_LOGGED_IN = <?php echo isset($_SESSION['id']) ? 'true' : 'false'; ?>;
</script>

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
		require_once __DIR__ . '/../classes/Database.php';
		
		if (isset($_SESSION['id'])):
		?>
		<!-- ВЫХОД -->
		<a href="../auth/logout.php" style="text-decoration: none; color: #2c3e50; font-size: 14px;">Выход</a>

		<!-- УВЕДОМЛЕНИЯ -->
		<?php include '../includes/notificationBell.php'; ?>

		<!-- АВАТАРКА -->
		<div class="avatar">
			<?php 
				$db = new Database();
				$user = User::getUserById($_SESSION['id'], $db);
				$image_name = $user->get(UserField::AVATAR);
				?>
			<img src="../assets/uploads/<?php echo ($image_name);?>"
				style="width: 32px; height: 32px; border-radius: 100%;">
		</div>
		<?php else:?>
		<div>Вы вошли как гость</div>
		<?php endif;?>
	</div>
</div>
<script src="../assets/js/notifications.js" defer></script>