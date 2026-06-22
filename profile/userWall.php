<?php 
include "../includes/init.php";
$wall_owner_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$wall_owner = User::getUserById($_GET['user_id'], $db);
	$editableFields = $wall_owner? $wall_owner->getEditableFields(): [];
	$profile_img = $wall_owner? $wall_owner->get(UserField::AVATAR): 'baseimage.jpg';
	$first_name = $wall_owner? $wall_owner->get(UserField::FIRST_NAME): 'Пользователь';
	$last_name = $wall_owner? $wall_owner->get(UserField::LAST_NAME): 'Несуществующий';	
	$login = $wall_owner? $wall_owner->get(UserField::LOGIN): 'does_not_exist';
	

if ($wall_owner_id == 0) {
    header('Location: ../friends/friends.php');
    exit;
}

$wall_owner = User::getUserById($wall_owner_id, $db);

if (!$wall_owner) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Пользователь не найден</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body>
        <?php include '../includes/header.php'; ?>
        <div class="columns-container">
            <?php include '../includes/menuLeft.php'; ?>
            <div class="center-panel">
                <div class="pageNotFound">Пользователь не найден</div>
            </div>
            <?php include '../includes/menuRight.php'; ?>
        </div>
    </body>
    </html>
    <?php
    exit;
}


$profile_img = $wall_owner->get(UserField::AVATAR);

// Проверяем статус дружбы
$friendship_status = 'none';
$is_owner = false;
$sent_request = false;
$incoming_request = false;

// ===== ПРОВЕРКА НА АДМИНА =====
$is_admin = false;
$complaints_count = 0;

if (isset($_SESSION['id'])) {
    $current_user_id = $_SESSION['id'];
    $is_owner = ($current_user_id == $wall_owner_id);
    
    // Проверяем, является ли текущий пользователь админом
    $current_user = User::getUserById($current_user_id, $db);
    if ($current_user && $current_user->get('role') == '1') {
        $is_admin = true;
        
        // Получаем количество жалоб на этого пользователя
        $complaints_sql = "SELECT COUNT(*) as count FROM complains WHERE target_user_id = ?";
        $complaints_result = $db->fetchOne($complaints_sql, [$wall_owner_id]);
        $complaints_count = $complaints_result ? $complaints_result['count'] : 0;
    }
    
    if (!$is_owner && !$is_admin) {
        // Обычный пользователь - проверяем статус дружбы
        if ($current_user) {
            $friendship_status = $current_user->getFriendshipStatus($wall_owner_id);
            $sent_request = $current_user->hasSentRequestTo($wall_owner_id);
            $incoming_request = $current_user->hasIncomingRequestFrom($wall_owner_id);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../assets/css/style.css">
	<link rel="stylesheet" href="../assets/css/user_wall.css">
	<title><?php echo $first_name ?> <?php echo $last_name ?>
	</title>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="columns-container">

        <?php include '../includes/menuLeft.php'; ?>

        <div class="center-panel">
            <div class="center-panel1">
                <!-- Верхняя часть страницы-->
                <div class="userInfoContainer">
                    <div style="display: flex; flex-direction: column; flex: 1;">
                        <div class="userName">
                            <?php echo $first_name ?>
							<?php echo $last_name ?>
                        </div>

                        <div class="userLogin"> @<?php echo $login ?></div>

						<?php if($wall_owner != null){?>
                        <div class="otherInfo">
                            День рождения <?php echo $wall_owner->getFormattedBirthday(); ?>
                            (<?php echo $wall_owner->getAge(); ?> лет)
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <img src="../assets/uploads/<?php echo $profile_img; ?>" style="height: 200px; border-radius: 12px;">
                        
                        <!-- ===== КНОПКИ ДЕЙСТВИЙ ===== -->
                        <?php if (isset($_SESSION['id']) && !$is_owner): ?>
                            
                            <!-- АДМИН: показываем жалобы и кнопку удаления -->
                            <?php if ($is_admin): ?>
                                <?php if ($complaints_count > 0): ?>
                                    <div class="complaints-info" style="text-align: center; margin-bottom: 4px;">
                                        <span style="color: #e74c3c; font-size: 14px;">
                                            ⚠ Жалоб на пользователя: <strong><?php echo $complaints_count; ?></strong>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="complaints-info" style="text-align: center; margin-bottom: 4px;">
                                        <span style="color: #818c99; font-size: 14px;">
                                            ✅ Жалоб нет
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <button class="admin-delete-btn" data-user-id="<?php echo $wall_owner_id; ?>">
                                    🗑 Удалить пользователя
                                </button>
                            
                            <!-- ОБЫЧНЫЙ ПОЛЬЗОВАТЕЛЬ: кнопки дружбы и жалобы -->
                            <?php else: ?>
                                
                                <?php if ($friendship_status == 'accepted'): ?>
                                    <button class="friend-btn friends" disabled>
                                        Вы друзья ✓
                                    </button>
                                
                                <?php elseif ($sent_request): ?>
                                    <button class="friend-btn pending" disabled>
                                        Заявка отправлена
                                    </button>
                                
                                <?php elseif ($incoming_request): ?>
                                    <button class="friend-btn accept-request" data-user-id="<?php echo $wall_owner_id; ?>">
                                        Принять заявку в друзья
                                    </button>
                                
                                <?php else: ?>
                                    <button class="friend-btn send-request" data-user-id="<?php echo $wall_owner_id; ?>">
                                        Добавить в друзья
                                    </button>
                                <?php endif; ?>
                                
                                <!-- Кнопка жалобы -->
                                <button class="complaint-btn" data-user-id="<?php echo $wall_owner_id; ?>">
                                    ⚠ Пожаловаться
                                </button>
                                
                            <?php endif; ?>
                            
                        <?php elseif ($is_owner): ?>
                            <button class="friend-btn" style="background-color: #e7e8ec; color: #818c99; cursor: default;" disabled>
                                Это ваш профиль
                            </button>
                        <?php endif; ?>
                    </div>
                    
                </div>
                <?php if($wall_owner != null && isset($_SESSION['id'])){?>
                <!-- Форма для создания поста -->
                <div>
                    <form id="postForm" class="postForm">
                        <textarea id="postMessage" placeholder="Что у вас нового?"></textarea>
                        <input id="postImage" type="file">
                        <button>Поделиться</button>
                    </form>
                </div>
                <?php } 
				if ($wall_owner != null){?>

                <!-- Посты -->
                <div style="width:100%; display: flex; justify-content: center; align-items: center;">
                    <h3>Все посты</h3>
                </div>
                <div class="postsContainer" id="postsContainer">
                    
                </div>
                <?php 
                }
                } else {?>
				<div class="pageNotFound">
					Как вы тут оказались?
				</div>
				<?php } ?>
            </div>
        </div>
        <?php include '../includes/menuRight.php'; ?>
    </div>

    <!-- ===== МОДАЛЬНОЕ ОКНО ЖАЛОБЫ ===== -->
    <div class="complaint-modal" id="complaintModal" style="display: none;">
        <div class="complaint-modal-overlay" id="complaintModalOverlay"></div>
        <div class="complaint-modal-content">
            <div class="complaint-modal-header">
                <h3>Пожаловаться на пользователя</h3>
                <button class="complaint-modal-close" id="closeComplaintModal">&times;</button>
            </div>
            
            <div class="complaint-modal-body">
                <p>Выберите причину жалобы:</p>
                
                <div class="complaint-types">
                    <?php
                    $types_sql = "SELECT complain_type_id, complain FROM complain_types";
                    $types = $db->fetchAll($types_sql);
                    foreach ($types as $type):
                    ?>
                        <label class="complaint-type-option">
                            <input type="radio" name="complaint_type" value="<?php echo $type['complain_type_id']; ?>" 
                                <?php echo ($type['complain_type_id'] == 4) ? 'checked' : ''; ?>>
                            <?php echo htmlspecialchars($type['complain']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                
                <div class="complaint-message-field">
                    <label for="complaintMessage">Дополнительный комментарий (необязательно):</label>
                    <textarea id="complaintMessage" placeholder="Опишите подробнее причину жалобы..."></textarea>
                </div>
            </div>
            
            <div class="complaint-modal-footer">
                <button class="complaint-btn-cancel" id="cancelComplaintBtn">Отмена</button>
                <button class="complaint-btn-send" id="sendComplaintBtn">Отправить жалобу</button>
            </div>
        </div>
    </div>

    <!-- ===== ПОДКЛЮЧЕНИЕ СКРИПТОВ ===== -->
    <script src="../assets/js/friends.js" defer></script>
    <script src="../assets/js/complaints.js" defer></script>
    <script src="../assets/js/admin.js" defer></script>
    <script src="../assets/js/comments/CommentView.js"></script>
	<script src="../assets/js/comments/commentApi.js"></script>
    <script src="../assets/js/postView.js"></script>
	<script src="../assets/js/postApi.js"></script>
	<script src="../assets/js/userWall.js"></script>

</body>

</html>