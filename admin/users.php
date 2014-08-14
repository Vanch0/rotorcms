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

$config['usersearch'] = 30;

if (isset($_GET['act'])) {
	$act = check($_GET['act']);
} else {
	$act = 'index';
}

if (isset($_POST['uz'])) {
	$uz = check($_POST['uz']);
} elseif (isset($_GET['uz'])) {
	$uz = check($_GET['uz']);
} else {
	$uz = '';
}

if (isset($_GET['start'])) {
	$start = abs(intval($_GET['start']));
} else {
	$start = 0;
}

if (is_admin(array(101, 102))) {
	show_title('Управление пользователями');

	switch ($act):
	############################################################################################
	##                                    Главная страница                                    ##
	############################################################################################
		case 'index':

			echo '<div class="form">';
			echo 'Логин или ник пользователя:<br />';
			echo '<form action="users.php?act=edit" method="post">';
			echo '<input type="text" name="uz" maxlength="20" />';
			echo '<input value="Редактировать" type="submit" /></form></div><br />';

			echo '<a href="users.php?act=sort&amp;q=1">0-9</a> / <a href="users.php?act=sort&amp;q=a">A</a> / <a href="users.php?act=sort&amp;q=b">B</a> / <a href="users.php?act=sort&amp;q=c">C</a> / <a href="users.php?act=sort&amp;q=d">D</a> / <a href="users.php?act=sort&amp;q=e">E</a> / <a href="users.php?act=sort&amp;q=f">F</a> / <a href="users.php?act=sort&amp;q=g">G</a> / <a href="users.php?act=sort&amp;q=h">H</a> / <a href="users.php?act=sort&amp;q=i">I</a> / <a href="users.php?act=sort&amp;q=j">J</a> / <a href="users.php?act=sort&amp;q=k">K</a> / <a href="users.php?act=sort&amp;q=l">L</a> / <a href="users.php?act=sort&amp;q=m">M</a> / <a href="users.php?act=sort&amp;q=n">N</a> / <a href="users.php?act=sort&amp;q=o">O</a> / <a href="users.php?act=sort&amp;q=p">P</a> / <a href="users.php?act=sort&amp;q=q">Q</a> / <a href="users.php?act=sort&amp;q=r">R</a> / <a href="users.php?act=sort&amp;q=s">S</a> / <a href="users.php?act=sort&amp;q=t">T</a> / <a href="users.php?act=sort&amp;q=u">U</a> / <a href="users.php?act=sort&amp;q=v">V</a> / <a href="users.php?act=sort&amp;q=w">W</a> / <a href="users.php?act=sort&amp;q=x">X</a> / <a href="users.php?act=sort&amp;q=y">Y</a> / <a href="users.php?act=sort&amp;q=z">Z</a><br />';

			echo 'Введите логин пользователя который необходимо отредактировать<br /><br />';

			echo '<b>Cписок последних зарегистрированных</b><br />';

			$total = DB::run() -> querySingle("SELECT count(*) FROM `users`;");
			if ($total > 0) {

				if ($start >= $total) {
					$start = 0;
				}

				$queryusers = DB::run() -> query("SELECT * FROM `users` ORDER BY `users_joined` DESC LIMIT ".$start.", ".$config['userlist'].";");

				while ($data = $queryusers -> fetch()) {
					if (empty($data['users_email'])) {
						$data['users_email'] = 'Не указан';
					}

					echo '<hr /><div>'.user_gender($data['users_login']).' <b><a href="users.php?act=edit&amp;uz='.$data['users_login'].'">'.$data['users_login'].'</a></b> (E-mail: '.$data['users_email'].')<br />';

					echo 'Зарегистрирован: '.date_fixed($data['users_joined']).'</div>';
				}

				page_strnavigation('users.php?', $config['userlist'], $start, $total);


			} else {
				show_error('Пользователей еще нет!');
			}
			echo '<br /><br />';
		break;

		############################################################################################
		##                                  Сортировка профилей                                   ##
		############################################################################################
		case 'sort':
			if (isset($_POST['q'])) {
				$q = check(strtolower($_POST['q']));
			} else {
				$q = check(strtolower($_GET['q']));
			}

			if (!empty($q)) {
				if ($q == 1) {
					$search = "RLIKE '^[-0-9]'";
				} else {
					$search = "LIKE '$q%'";
				}

				$total = DB::run() -> querySingle("SELECT count(*) FROM `users` WHERE LOWER(`users_login`) ".$search.";");

				if ($total > 0) {
					if ($start >= $total) {
						$start = 0;
					}

					$queryuser = DB::run() -> query("SELECT `users_login`, `users_nickname`, `users_point` FROM `users` WHERE LOWER(`users_login`) ".$search." ORDER BY `users_point` DESC LIMIT ".$start.", ".$config['usersearch'].";");

					while ($data = $queryuser -> fetch()) {

						echo user_gender($data['users_login']).' <b><a href="users.php?act=edit&amp;uz='.$data['users_login'].'">'.$data['users_login'].'</a></b> ';

						if (!empty($data['users_nickname'])) {
							echo '(Ник: '.$data['users_nickname'].') ';
						}

						echo user_online($data['users_login']).' ('.points($data['users_point']).')<br />';
					}

					page_strnavigation('users.php?act=sort&amp;q='.$q.'&amp;', $config['usersearch'], $start, $total);

					echo 'Найдено совпадений: '.$total.'<br /><br />';
				} else {
					show_error('Совпадений не найдено!');
				}
			} else {
				show_error('Ошибка! Не выбраны критерии поиска пользователей!');
			}

			echo '<img src="/images/img/back.gif" alt="image" /> <a href="users.php">Вернуться</a><br />';
		break;

		############################################################################################
		##                                    Просмотр профиля                                    ##
		############################################################################################
		case 'edit':

			$user = DB::run() -> queryFetch("SELECT * FROM `users` WHERE LOWER(`users_login`)=? OR LOWER(`users_nickname`)=? LIMIT 1;", array(strtolower($uz), utf_lower($uz)));

			if (!empty($user)) {
				$uz = $user['users_login'];

				echo user_gender($user['users_login']).' <b>Профиль '.profile($user['users_login']).'</b> '.user_visit($user['users_login']).'<br /><br />';

				if ($log == $config['nickname'] || $log == $user['users_login'] || ($user['users_level'] < 101 || $user['users_level'] > 105)) {
					if ($user['users_login'] == $log) {
						echo '<b><span style="color:#ff0000">Внимание! Вы редактируете cобственный аккаунт!</span></b><br /><br />';
					}

					echo '<div class="form">';
					echo '<form method="post" action="users.php?act=upgrade&amp;uz='.$user['users_login'].'&amp;uid='.$_SESSION['token'].'">';

					if ($log == $config['nickname']) {
						$arr_access = array(101, 102, 103, 105, 107);

						echo 'Уровень доступа:<br />';
						echo '<select name="level">';
						foreach ($arr_access as $value) {
							$selected = ($user['users_level'] == $value) ? ' selected="selected"' : '';
							echo '<option value="'.$value.'"'.$selected.'>'.user_status($value).'</option>';
						}
						echo '</select><br />';
					}

					echo 'Новый пароль: (Oставьте пустым если не надо менять)<br />';
					echo '<input type="text" name="pass" maxlength="20" /><br />';
					echo 'Страна:<br />';
					echo '<input type="text" name="country" maxlength="30" value="'.$user['users_country'].'" /><br />';
					echo 'Откуда:<br />';
					echo '<input type="text" name="city" maxlength="50" value="'.$user['users_city'].'" /><br />';
					echo 'E-mail:<br />';
					echo '<input type="text" name="email" maxlength="50" value="'.$user['users_email'].'" /><br />';
					echo 'Сайт:<br />';
					echo '<input type="text" name="site" maxlength="50" value="'.$user['users_site'].'" /><br />';
					echo 'Зарегистрирован:<br />';
					echo '<input type="text" name="joined" maxlength="10" value="'.date_fixed($user['users_joined'], "d.m.Y").'" /><br />';
					echo 'Дата рождения:<br />';
					echo '<input type="text" name="birthday" maxlength="10" value="'.$user['users_birthday'].'" /><br />';
					echo 'ICQ:<br />';
					echo '<input type="text" name="icq" maxlength="10" value="'.$user['users_icq'].'" /><br />';
					echo 'Jabber:<br />';
					echo '<input type="text" name="jabber" maxlength="50" value="'.$user['users_jabber'].'" /><br />';
					echo 'Имя пользователя:<br />';
					echo '<input type="text" name="name" maxlength="20" value="'.$user['users_name'].'" /><br />';
					echo 'Ник пользователя:<br />';
					echo '<input type="text" name="nickname" maxlength="20" value="'.$user['users_nickname'].'" /><br />';
					echo 'Актив:<br />';
					echo '<input type="text" name="point" value="'.$user['users_point'].'" /><br />';
					echo 'Деньги:<br />';
					echo '<input type="text" name="money" value="'.$user['users_money'].'" /><br />';
					echo 'Особый статус:<br />';
					echo '<input type="text" name="status" maxlength="25" value="'.$user['users_status'].'" /><br />';
					echo 'Аватар:<br />';
					echo '<input type="text" name="avatar" value="'.$user['users_avatar'].'" /><br />';
					echo 'Авторитет (плюсы):<br />';
					echo '<input type="text" name="posrating" value="'.$user['users_posrating'].'" /><br />';
					echo 'Авторитет (минусы):<br />';
					echo '<input type="text" name="negrating" value="'.$user['users_negrating'].'" /><br />';
					echo 'Скин:<br />';
					echo '<input type="text" name="themes" value="'.$user['users_themes'].'" /><br />';

					echo 'Пол:<br />';
					echo '<select name="gender">';
					$selected = ($user['users_gender'] == 1) ? ' selected="selected"' : '';
					echo '<option value="1"'.$selected.'>Мужской</option>';
					$selected = ($user['users_gender'] == 2) ? ' selected="selected"' : '';
					echo '<option value="2"'.$selected.'>Женский</option>';
					echo '</select><br />';

					echo 'О себе:<br />';
					$user['users_info'] = yes_br($user['users_info']);
					echo '<textarea cols="25" rows="5" name="info">'.$user['users_info'].'</textarea><br />';

					echo '<input value="Изменить" type="submit" /></form></div><br />';

					echo '<div class="b"><b>Дополнительная информация</b></div>';
					if ($user['users_confirmreg'] == 1) {
						echo '<span style="color:#ff0000"><b>Аккаунт не активирован</b></span><br />';
					}

					$visit = DB::run() -> queryFetch("SELECT `visit_ip`, `visit_nowtime` FROM `visit` WHERE `visit_user`=? LIMIT 1;", array($uz));
					if (!empty($visit)) {
						echo '<b>Последний визит:</b> '.date_fixed($visit['visit_nowtime'], 'j F Y / H:i').'<br />';
						echo '<b>Последний IP:</b> '.$visit['visit_ip'].'<br />';
					}

					if ($user['users_ban'] == 1 && $user['users_timeban'] > SITETIME) {
						echo '<span style="color:#ff0000"><b>Пользователь забанен</b></span><br />';
					}
					if (!empty($user['users_timelastban']) && !empty($user['users_reasonban'])) {
						echo '<div class="form">';
						echo 'Последний бан: '.date_fixed($user['users_timelastban'], 'j F Y / H:i').'<br />';
						echo 'Последняя причина: '.bb_code($user['users_reasonban']).'<br />';
						echo 'Забанил: '.profile($user['users_loginsendban']).'</div>';
					}
					echo 'Строгих банов: <b>'.$user['users_totalban'].'</b><br /><br />';

					if ($user['users_level'] < 101 || $user['users_level'] > 105) {
						echo '<img src="/images/img/error.gif" alt="image" /> <b><a href="users.php?act=poddel&amp;uz='.$uz.'">Удалить профиль</a></b><br />';
					}
				} else {
					show_error('Ошибка! У вас недостаточно прав для редактирования этого профиля!');
				}
			} else {
				show_error('Ошибка! Пользователя с данным логином не существует!');
			}

			echo '<img src="/images/img/back.gif" alt="image" /> <a href="users.php">Вернуться</a><br />';
		break;

		############################################################################################
		##                                   Изменение профиля                                    ##
		############################################################################################
		case 'upgrade':

			$uid = check($_GET['uid']);

			if (!empty($_POST['level'])) {
				$level = intval($_POST['level']);
			}

			$pass = check($_POST['pass']);
			$email = check($_POST['email']);
			$joined = check($_POST['joined']);
			$name = check($_POST['name']);
			$nickname = check($_POST['nickname']);
			$country = check($_POST['country']);
			$city = check($_POST['city']);
			$info = check($_POST['info']);
			$site = check($_POST['site']);
			$icq = intval($_POST['icq']);
			$jabber = check($_POST['jabber']);
			$gender = intval($_POST['gender']);
			$birthday = check($_POST['birthday']);
			$themes = check($_POST['themes']);
			$point = intval($_POST['point']);
			$money = intval($_POST['money']);
			$status = check($_POST['status']);
			$avatar = check($_POST['avatar']);
			$posrating = intval($_POST['posrating']);
			$negrating = intval($_POST['negrating']);

			if ($uid == $_SESSION['token']) {
				$user = DB::run() -> queryFetch("SELECT * FROM `users` WHERE `users_login`=? LIMIT 1;", array($uz));

				if (!empty($user)) {
					if ($log == $config['nickname'] || $log == $user['users_login'] || ($user['users_level'] < 101 || $user['users_level'] > 105)) {
						if (empty($pass) || preg_match('|^[a-z0-9\-]+$|i', $pass)) {
							if (preg_match('#^([a-z0-9_\-\.])+\@([a-z0-9_\-\.])+(\.([a-z0-9])+)+$#', $email) || empty($email)) {
								if (preg_match('#^([a-z0-9_\-\.])+\@([a-z0-9_\-\.])+(\.([a-z0-9])+)+$#', $jabber) || empty($jabber)) {
									if (preg_match('#^http://([а-яa-z0-9_\-\.])+(\.([а-яa-z0-9\/])+)+$#u', $site) || empty($site)) {
										if (preg_match('#^[0-9]{2}+\.[0-9]{2}+\.[0-9]{4}$#', $joined)) {
											if (preg_match('#^[0-9]{2}+\.[0-9]{2}+\.[0-9]{4}$#', $birthday) || empty($birthday)) {
												if ($gender == 1 || $gender == 2) {
													if (utf_strlen($info) <= 1000) {
														if ($log == $config['nickname']) {
															$access = $level;
														} else {
															$access = $user['users_level'];
														}

														if (!empty($pass)) {
															echo '<b><span style="color:#ff0000">Внимание! Вы изменили пароль пользователя!</span></b><br />';
															echo 'Не забудьте ему напомнить его новый пароль: <b>'.$pass.'</b><br /><br />';
															$mdpass = md5(md5($pass));
														} else {
															$mdpass = $user['users_pass'];
														}

														list($uday, $umonth, $uyear) = explode(".", $joined);
														$joined = mktime('0', '0', '0', $umonth, $uday, $uyear);

														$name = utf_substr($name, 0, 20);
														$country = utf_substr($country, 0, 30);
														$city = utf_substr($city, 0, 50);
														$info = no_br($info);
														$rating = $posrating - $negrating;

														DB::run() -> query("UPDATE `users` SET `users_pass`=?, `users_email`=?, `users_joined`=?, `users_level`=?, `users_name`=?, `users_nickname`=?, `users_country`=?, `users_city`=?, `users_info`=?, `users_site`=?, `users_icq`=?, `users_jabber`=?, `users_gender`=?, `users_birthday`=?, `users_themes`=?, `users_point`=?, `users_money`=?, `users_status`=?, `users_avatar`=?, `users_rating`=?, `users_posrating`=?, `users_negrating`=? WHERE `users_login`=? LIMIT 1;", array($mdpass, $email, $joined, $access, $name, $nickname, $country, $city, $info, $site, $icq, $jabber, $gender, $birthday, $themes, $point, $money, $status, $avatar, $rating, $posrating, $negrating, $uz));

														save_title();
														save_nickname();

														echo '<img src="/images/img/open.gif" alt="image" /> <b>Данные пользователя успешно изменены!</b><br /><br />';
													} else {
														show_error('Ошибка! Слишком большая информация в графе о себе, не более 1000 символов!');
													}
												} else {
													show_error('Ошибка! Вы не указали пол пользователя!');
												}
											} else {
												show_error('Ошибка! Недопустимая дата дня рождения, необходим формат (дд.мм.гггг)!');
											}
										} else {
											show_error('Ошибка! Недопустимая дата регистрации, необходим формат (дд.мм.гггг)!');
										}
									} else {
										show_error('Ошибка! Недопустимый адрес сайта, необходим формат http://site.domen!');
									}
								} else {
									show_error('Ошибка! Недопустимый формат Jabber, необходим формат name@site.domen!');
								}
							} else {
								show_error('Ошибка! Вы ввели неверный адрес e-mail, необходим формат name@site.domen!');
							}
						} else {
							show_error('Ошибка! Недопустимые символы в пароле. Разрешены знаки латинского алфавита, цифры и дефис!');
						}
					} else {
						show_error('Ошибка! У вас недостаточно прав для редактирования этого профиля!');
					}
				} else {
					show_error('Ошибка! Пользователя с данным логином не существует!');
				}
			} else {
				show_error('Ошибка! Неверный идентификатор сессии, повторите действие!');
			}

			echo '<img src="/images/img/back.gif" alt="image" /> <a href="users.php?act=edit&amp;uz='.$uz.'">Вернуться</a><br />';
			echo '<img src="/images/img/reload.gif" alt="image" /> <a href="users.php">Выбор юзера</a><br />';
		break;

		############################################################################################
		##                           Подтверждение удаление профиля                               ##
		############################################################################################
		case 'poddel':

			echo '<img src="/images/img/error.gif" alt="image" /> Вы подтверждаете, что хотите полностью удалить аккаунт пользователя <b>'.$uz.'</b>?<br /><br />';

			echo '<div class="form">';
			echo '<form action="users.php?act=deluser&amp;uz='.$uz.'&amp;uid='.$_SESSION['token'].'" method="post">';

			echo '<b>Добавить в черный список:</b><br />';
			echo 'Логин пользователя: <input name="loginblack" type="checkbox" value="1"  checked="checked" /><br />';
			echo 'E-mail пользователя: <input name="mailblack" type="checkbox" value="1"  checked="checked" /><br /><br />';

			echo '<b>Удаление сообщений:</b><br />';
			echo 'Темы в форуме: <input name="deltopicforum" type="checkbox" value="1" /><br />';
			echo 'Темы и сообщения: <input name="delpostforum" type="checkbox" value="1" /><br />';
			echo 'Комментарии в галерее: <input name="delcommphoto" type="checkbox" value="1" /><br />';
			echo 'Комментарии в новостях: <input name="delcommnews" type="checkbox" value="1" /><br />';
			echo 'Комментарии в блогах: <input name="delcommblog" type="checkbox" value="1" /><br />';
			echo 'Комментарии в загрузках: <input name="delcommload" type="checkbox" value="1" /><br />';
			echo 'Фотографии в галерее: <input name="delimages" type="checkbox" value="1" /><br /><br />';

			echo '<input type="submit" value="Удалить профиль" /></form></div><br />';

			echo '<img src="/images/img/back.gif" alt="image" /> <a href="users.php?act=edit&amp;uz='.$uz.'">Вернуться</a><br />';
			echo '<img src="/images/img/reload.gif" alt="image" /> <a href="users.php">Выбор юзера</a><br />';
			break;
		############################################################################################
		##                                   Удаление профиля                                     ##
		############################################################################################
		case 'deluser':

			$uid = check($_GET['uid']);
			$loginblack = (empty($_POST['loginblack'])) ? 0 : 1;
			$mailblack = (empty($_POST['mailblack'])) ? 0 : 1;
			$deltopicforum = (empty($_POST['deltopicforum'])) ? 0 : 1;
			$delpostforum = (empty($_POST['delpostforum'])) ? 0 : 1;
			$delcommphoto = (empty($_POST['delcommphoto'])) ? 0 : 1;
			$delcommnews = (empty($_POST['delcommnews'])) ? 0 : 1;
			$delcommblog = (empty($_POST['delcommblog'])) ? 0 : 1;
			$delcommload = (empty($_POST['delcommload'])) ? 0 : 1;
			$delimages = (empty($_POST['delimages'])) ? 0 : 1;

			if ($uid == $_SESSION['token']) {
				$user = DB::run() -> queryFetch("SELECT * FROM `users` WHERE `users_login`=? LIMIT 1;", array($uz));

				if (!empty($user)) {
					if ($user['users_level'] < 101 || $user['users_level'] > 105) {

						// -------------//
						if (!empty($mailblack)) {
							$blackmail = DB::run() -> querySingle("SELECT `black_id` FROM `blacklist` WHERE `black_type`=? AND `black_value`=? LIMIT 1;", array(1, $user['users_email']));
							if (empty($blackmail) && !empty($user['users_email'])) {
								DB::run() -> query("INSERT INTO `blacklist` (`black_type`, `black_value`, `black_user`, `black_time`) VALUES (?, ?, ?, ?);", array(1, $user['users_email'], $log, SITETIME));
							}
						}

						// -------------//
						if (!empty($loginblack)) {
							$blacklogin = DB::run() -> querySingle("SELECT `black_id` FROM `blacklist` WHERE `black_type`=? AND `black_value`=? LIMIT 1;", array(2, strtolower($user['users_login'])));
							if (empty($blacklogin)) {
								DB::run() -> query("INSERT INTO `blacklist` (`black_type`, `black_value`, `black_user`, `black_time`) VALUES (?, ?, ?, ?);", array(2, $user['users_login'], $log, SITETIME));
							}
						}

						// ------ Удаление фотографий в галерее -------//
						if (!empty($delimages)) {
							delete_album($uz);
						}

						// ------ Удаление тем в форуме -------//
						if (!empty($delpostforum) || !empty($deltopicforum)) {

							$query = DB::run() -> query("SELECT `topics_id` FROM `topics` WHERE `topics_author`=?;", array($uz));
							$topics = $query -> fetchAll(PDO::FETCH_COLUMN);

							if (!empty($topics)){
								$strtopics = implode(',', $topics);

								// ------ Удаление загруженных файлов -------//
								foreach($topics as $delDir){
									removeDir(BASEDIR.'/upload/forum/'.$delDir);
								}
								DB::run() -> query("DELETE FROM `files_forum` WHERE `file_posts_id` IN (".$strtopics.");");
								// ------ Удаление загруженных файлов -------//

								DB::run() -> query("DELETE FROM `posts` WHERE `posts_topics_id` IN (".$strtopics.");");
								DB::run() -> query("DELETE FROM `topics` WHERE `topics_id` IN (".$strtopics.");");
							}

							// ------ Удаление сообщений в форуме -------//
							if (!empty($delpostforum)) {
								DB::run() -> query("DELETE FROM `posts` WHERE `posts_user`=?;", array($uz));

								// ------ Удаление загруженных файлов -------//
								$queryfiles = DB::run() -> query("SELECT * FROM `files_forum` WHERE `file_user`=?;", array($uz));
								$files = $queryfiles->fetchAll();

								if (!empty($files)){
									foreach ($files as $file){
										if (file_exists(BASEDIR.'/upload/forum/'.$file['file_topics_id'].'/'.$file['file_hash'])){
											unlink(BASEDIR.'/upload/forum/'.$file['file_topics_id'].'/'.$file['file_hash']);
										}
									}
									DB::run() -> query("DELETE FROM `files_forum` WHERE `file_user`=?;", array($uz));
								}
								// ------ Удаление загруженных файлов -------//
							}

							restatement('forum');
						}

						// ------ Удаление коментарий -------//
						if (!empty($delcommblog)) {
							DB::run() -> query("DELETE FROM `commblog` WHERE `commblog_author`=?;", array($uz));
							restatement('blog');
						}

						if (!empty($delcommload)) {
							DB::run() -> query("DELETE FROM `commload` WHERE `commload_author`=?;", array($uz));
							restatement('load');
						}

						if (!empty($delcommphoto)) {
							DB::run() -> query("DELETE FROM `commphoto` WHERE `commphoto_user`=?;", array($uz));
							restatement('gallery');
						}

						if (!empty($delcommnews)) {
							DB::run() -> query("DELETE FROM `commnews` WHERE `commnews_author`=?;", array($uz));
							restatement('news');
						}

						// Удаление профиля
						delete_users($uz);

						echo '<img src="/images/img/open.gif" alt="image" /> <b>Профиль пользователя успешно удален!</b><br /><br />';
					} else {
						show_error('Ошибка! У вас недостаточно прав для удаления этого профиля!');
					}
				} else {
					show_error('Ошибка! Пользователя с данным логином не существует!');
				}
			} else {
				show_error('Ошибка! Неверный идентификатор сессии, повторите действие!');
			}

			echo '<img src="/images/img/back.gif" alt="image" /> <a href="users.php">Вернуться</a><br />';
		break;

	default:
		redirect("users.php");
	endswitch;

	echo '<img src="/images/img/panel.gif" alt="image" /> <a href="index.php">В админку</a><br />';

} else {
	redirect('/index.php');
}

include_once ('../themes/footer.php');
?>
