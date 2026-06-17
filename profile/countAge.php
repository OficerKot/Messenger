<div>
	День рождения
	<?php
	$birthday_date = new DateTime($user->get(UserField::BIRTHDAY));
	echo $birthday_date->format('d.m.Y');?>
	(
	<?php
		$curDate = new DateTime();
		$birthday_date = new DateTime($user->get(UserField::BIRTHDAY));
		$age = $curDate->diff($birthday_date);
		echo $age->y;
	?>
	лет)
</div>