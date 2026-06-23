<?php

class AccessHelper{
	public static function HasAccessToWall(int|null $viewerId, int|null $owner_id, Database $db){
	if( !$owner_id) return false;
	
	$wall_owner = User::getUserById($owner_id, $db);
	$authorizedUser = $viewerId? User::getUserById($viewerId, $db): null;

	$isOwner = $viewerId == $owner_id && $owner_id;
	$canView = $wall_owner->get(UserField::PRIVATE) == 0;
	
	if($authorizedUser){
		$canView = $canView || User::areFriends($authorizedUser, $wall_owner) || $isOwner;
	}

	return $canView;

	}

	// public static function CanSendMessages(int|null $senderId, int|null $receiverId, Database $db){
	// 	$msgModel = new Message($db);
	// 	$msgModel->getDialog($senderId, $receiverId);
	// 	if($msgModel != []){
	// 		return true;
	// 	}
	// }
}

?>