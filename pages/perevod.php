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
include_once ('../themes/header.php');

if (isset($_GET['act'])) {
	$act = check($_GET['act']);
} else {
	$act = 'index';
}
if (isset($_GET['uz'])) {
	$uz = check($_GET['uz']);
} elseif (isset($_POST['uz'])) {
	$uz = check($_POST['uz']);
} else {
	$uz = "";
}

show_title('Перевод денег');

if (is_user()) {

	switch ($act):
	############################################################################################
	##                                    Главная страница                                    ##
	############################################################################################
		case 'index':

			echo 'В наличии: '.moneys($udata['users_money']).'<br /><br />';

			if ($udata['users_point'] >= $config['sendmoneypoint']) {
				if (empty($uz)) {
					echo '<div class="form">';
					echo '<form action="perevod.php?act=send&amp;uid='.$_SESSION['token'].'" method="post">';
					echo 'Логин юзера:<br />';
					echo '<input type="text" name="uz" maxlength="20" /><br />';
					echo 'Кол-во денег:<br />';
					echo '<input type="text" name="money" /><br />';
					echo 'Примечание:<br />';
					echo '<textarea cols="25" rows="5" name="msg"></textarea><br />';
					echo '<input type="submit" value="Перевести" /></form></div><br />';
				} else {
					echo '<div class="form">';
					echo 'Перевод для <b>'.$uz.'</b>:<br /><br />';
					echo '<form action="perevod.php?act=send&amp;uz='.$uz.'&amp;uid='.$_SESSION['token'].'" method="post">';
					echo 'Кол-во денег:<br />';
					echo '<input type="text" name="money" /><br />';
					echo 'Примечание:<br />';
					echo '<textarea cols="25" rows="5" name="msg"></textarea><br />';
					echo '<input type="submit" value="Перевести" /></form></div><br />';
				}
			} else {
				show_error('Ошибка! Для перевода денег вам необходимо набрать '.points($config['sendmoneypoint']).'!');
			}
		break;

		############################################################################################
		##                                       Перевод                                          ##
		############################################################################################
		case 'send':

			$money = abs(intval($_POST['money']));
			$msg = check($_POST['msg']);
			$uid = check($_GET['uid']);

			if ($uid == $_SESSION['token']) {
				if ($money > 0) {
					if ($udata['users_point'] >= $config['sendmoneypoint']) {
						if ($money <= $udata['users_money']) {
							if ($uz != $log) {
								if ($msg <= 1000) {
									$queryuser = DB::run() -> querySingle("SELECT `users_id` FROM `users` WHERE `users_login`=? LIMIT 1;", array($uz));
									if (!empty($queryuser)) {
										//blacklist
										//$ignorstr = DB::run() -> querySingle("SELECT `ignore_id` FROM `ignore` WHERE `ignore_user`=? AND `ignore_name`=? LIMIT 1;", array($uz, $log));
										if (empty($ignorstr)) {
											DB::run() -> query("UPDATE `users` SET `users_money`=`users_money`-? WHERE `users_login`=?;", array($money, $log));
											DB::run() -> query("UPDATE `users` SET `users_money`=`users_money`+?, `users_newprivat`=`users_newprivat`+1 WHERE `users_login`=?;", array($money, $uz));

											$comment = (!empty($msg)) ? $msg : 'Не указано';
											// ------------------------Уведомление по привату------------------------//
											$textpriv = '<img src="/images/img/money.gif" alt="money" /> Пользователь [b]'.nickname($log).'[/b] перечислил вам '.moneys($money).'<br />Примечание: '.$comment;

											DB::run() -> query("INSERT INTO `inbox` (`inbox_user`, `inbox_author`, `inbox_text`, `inbox_time`) VALUES (?, ?, ?, ?);", array($uz, $log, $textpriv, SITETIME));
											// ------------------------ Запись логов ------------------------//
											DB::run() -> query("INSERT INTO `transfers` (`trans_user`, `trans_login`, `trans_text`, `trans_summ`, `trans_time`) VALUES (?, ?, ?, ?, ?);", array($log, $uz, $comment, $money, SITETIME));

											DB::run() -> query("DELETE FROM `transfers` WHERE `trans_time` < (SELECT MIN(`trans_time`) FROM (SELECT `trans_time` FROM `transfers` ORDER BY `trans_time` DESC LIMIT 1000) AS del);");

											$_SESSION['note'] = 'Перевод успешно завершен! Пользователь уведомлен о переводе';
											redirect("perevod.php");

										} else {
											show_error('Ошибка! Вы внесены в игнор-лист получателя!');
										}
									} else {
										show_error('Ошибка! Данного адресата не существует!');
									}
								} else {
									show_error('Ошибка! Текст комментария не должен быть длиннее 1000 символов!');
								}
							} else {
								show_error('Ошибка! Запещено переводить деньги самому себе!');
							}
						} else {
							show_error('Ошибка! Недостаточно средств для перевода такого количества денег!');
						}
					} else {
						show_error('Ошибка! Для перевода денег вам необходимо набрать '.points($config['sendmoneypoint']).'!');
					}
				} else {
					show_error('Ошибка! Перевод невозможен указана неверная сумма!');
				}
			} else {
				show_error('Ошибка! Неверный идентификатор сессии, повторите действие!');
			}

			echo '<img src="/images/img/back.gif" alt="image" /> <a href="perevod.php">Вернуться</a><br />';
		break;

	default:
		redirect("perevod.php");
	endswitch;

} else {
	show_login('Вы не авторизованы, чтобы совершать операции, необходимо');
}

include_once ('../themes/footer.php');
?>
