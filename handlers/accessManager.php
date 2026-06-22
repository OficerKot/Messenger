<?php

class AccessHelper{
	public static function HasAccessToWall(int|null $viewerId, int $owner_id, Database $db){
	$wall_owner = User::getUserById($owner_id, $db);
	$authorizedUser = $viewerId? User::getUserById($viewerId, $db): null;
	$isOwner = $viewerId == $owner_id;

	$canView = $wall_owner->get(UserField::PRIVATE) == 0;
	
	if($authorizedUser){
		$canView = $canView || User::areFriends($authorizedUser, $wall_owner) || $isOwner;
	}

	return $canView;

	}
}

?>