<?php
#---------------------------------------------#
#      ********* RotorCMS *********           #
#           Author  :  Vantuz                 #
#            Email  :  visavi.net@mail.ru     #
#             Site  :  http://visavi.net      #
#              ICQ  :  36-44-66               #
#            Skype  :  vantuzilla             #
#---------------------------------------------#
require_once ('../includes/start.php');
require_once ('../includes/functions.php');
require_once ('../includes/header.php');

header('Content-type: application/json');
header('Content-Disposition: inline; filename="user.json";');

$key = (!empty($_REQUEST['key'])) ? check($_REQUEST['key']) : null;

if (!empty($key)){

	$user = DB::run()->queryFetch("SELECT * FROM `users` WHERE `users_apikey`=? LIMIT 1;", array($key));
	if (!empty($user)){

		echo json_encode(array(
			'login'     => $user['users_login'],
			'email'     => $user['users_email'],
			'nickname'  => $user['users_nickname'],
			'name'      => $user['users_name'],
			'country'   => $user['users_country'],
			'city'      => $user['users_city'],
			'site'      => $user['users_site'],
			'icq'       => $user['users_icq'],
			'jabber'    => $user['users_jabber'],
			'gender'    => $user['users_gender'],
			'birthday'  => $user['users_birthday'],
			'point'     => $user['users_point'],
			'money'     => $user['users_money'],
			'ban'       => $user['users_ban'],
			'allprivat' => user_mail($user['users_login']),
			'newprivat' => $user['users_newprivat'],
			'status'    => user_title($user['users_login']),
			'avatar'    => $config['home'].'/'.$user['users_avatar'],
			'picture'   => $user['users_picture'],
			'rating'    => $user['users_rating'],
			'lastlogin' => $user['users_timelastlogin'],
		));

	} else {echo json_encode(array('error'=>'nouser'));}
} else {echo json_encode(array('error'=>'nokey'));}

?>
