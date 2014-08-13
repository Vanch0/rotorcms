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

if (empty($_GET['uz'])) {
	$uz = check($log);
} else {
	$uz = check($_GET['uz']);
}
if (isset($_GET['start'])) {
	$start = abs(intval($_GET['start']));
} else {
	$start = 0;
}
if (isset($_GET['act'])) {
	$act = check($_GET['act']);
} else {
	$act = 'files';
}

switch ($act):
############################################################################################
##                                      Вывод файлов                                      ##
############################################################################################
	case 'files':
		show_title('Список всех файлов');

		echo '<img src="/images/img/document.gif" alt="image" /> ';
		echo '<a href="add.php">Публикация</a> / ';
		echo '<a href="add.php?act=waiting">Ожидающие</a> / ';
		echo '<b>Проверенные</b><hr />';

		$total = DB::run() -> querySingle("SELECT count(*) FROM `downs` WHERE `downs_active`=? AND `downs_user`=?;", array(1, $uz));

		if ($total > 0) {
			if ($start >= $total) {
				$start = 0;
			}

			$querydown = DB::run() -> query("SELECT `downs`.*, `cats_name` FROM `downs` LEFT JOIN `cats` ON `downs`.`downs_cats_id`=`cats`.`cats_id` WHERE `downs_active`=? AND `downs_user`=? ORDER BY `downs_time` DESC LIMIT ".$start.", ".$config['downlist'].";", array(1, $uz));

			while ($data = $querydown -> fetch()) {
				$filesize = (!empty($data['downs_link'])) ? read_file(BASEDIR.'/load/files/'.$data['downs_link']) : 0;

				echo '<div class="b"><img src="/images/img/zip.gif" alt="image" /> ';
				echo '<b><a href="down.php?act=view&amp;id='.$data['downs_id'].'">'.$data['downs_title'].'</a></b> ('.$filesize.')</div>';

				echo '<div>Категория: <a href="down.php?cid='.$data['downs_cats_id'].'">'.$data['cats_name'].'</a><br />';
				echo 'Скачиваний: '.$data['downs_load'].'<br />';
				echo '<a href="down.php?act=comments&amp;id='.$data['downs_id'].'">Комментарии</a> ('.$data['downs_comments'].') ';
				echo '<a href="down.php?act=end&amp;id='.$data['downs_id'].'">&raquo;</a></div>';
			}

			page_strnavigation('active.php?act=files&amp;uz='.$uz.'&amp;', $config['downlist'], $start, $total);
		} else {
			show_error('Опубликованных файлов не найдено!');
		}
	break;

	############################################################################################
	##                                     Вывод комментарий                                  ##
	############################################################################################
	case 'comments':
		show_title('Список всех комментариев');

		$total = DB::run() -> querySingle("SELECT count(*) FROM `commload` WHERE `commload_author`=?;", array($uz));

		if ($total > 0) {
			if ($start >= $total) {
				$start = 0;
			}

			$is_admin = is_admin();

			$querypost = DB::run() -> query("SELECT `commload`.*, `downs_title`, `downs_comments` FROM `commload` LEFT JOIN `downs` ON `commload`.`commload_down`=`downs`.`downs_id` WHERE `commload_author`=? ORDER BY `commload_time` DESC LIMIT ".$start.", ".$config['downlist'].";", array($uz));

			while ($data = $querypost -> fetch()) {
				echo '<div class="b">';

				echo '<img src="/images/img/balloon.gif" alt="image" /> <b><a href="active.php?act=viewcomm&amp;id='.$data['commload_down'].'&amp;cid='.$data['commload_id'].'">'.$data['downs_title'].'</a></b> ('.$data['downs_comments'].')';

				if ($is_admin) {
					echo ' — <a href="active.php?act=del&amp;id='.$data['commload_id'].'&amp;uz='.$uz.'&amp;start='.$start.'&amp;uid='.$_SESSION['token'].'">Удалить</a>';
				}

				echo '</div>';
				echo '<div>'.bb_code($data['commload_text']).'<br />';

				echo 'Написал: '.nickname($data['commload_author']).' <small>('.date_fixed($data['commload_time']).')</small><br />';

				if ($is_admin) {
					echo '<span class="data">('.$data['commload_brow'].', '.$data['commload_ip'].')</span>';
				}

				echo '</div>';
			}

			page_strnavigation('active.php?act=comments&amp;uz='.$uz.'&amp;', $config['downlist'], $start, $total);
		} else {
			show_error('Комментарии не найдены!');
		}
	break;

	############################################################################################
	##                                     Переход к сообщение                                ##
	############################################################################################
	case 'viewcomm':

		if (isset($_GET['id'])) {
			$id = abs(intval($_GET['id']));
		} else {
			$id = 0;
		}
		if (isset($_GET['cid'])) {
			$cid = abs(intval($_GET['cid']));
		} else {
			$cid = 0;
		}

		$querycomm = DB::run() -> querySingle("SELECT COUNT(*) FROM `commload` WHERE `commload_id`<=? AND `commload_down`=? ORDER BY `commload_time` ASC LIMIT 1;", array($cid, $id));

		if (!empty($querycomm)) {
			$end = floor(($querycomm - 1) / $config['downlist']) * $config['downlist'];

			redirect("down.php?act=comments&id=$id&start=$end");
		} else {
			show_error('Ошибка! Комментарий к данному файлу не существует!');
		}
	break;

	############################################################################################
	##                                 Удаление комментариев                                  ##
	############################################################################################
	case 'del':

		$uid = check($_GET['uid']);
		if (isset($_GET['id'])) {
			$id = abs(intval($_GET['id']));
		} else {
			$id = 0;
		}

		if (is_admin()) {
			if ($uid == $_SESSION['token']) {
				$downs = DB::run() -> querySingle("SELECT `commload_down` FROM `commload` WHERE `commload_id`=?;", array($id));
				if (!empty($downs)) {
					DB::run() -> query("DELETE FROM `commload` WHERE `commload_id`=? AND `commload_down`=?;", array($id, $downs));
					DB::run() -> query("UPDATE `downs` SET `downs_comments`=`downs_comments`-? WHERE `downs_id`=?;", array(1, $downs));

					$_SESSION['note'] = 'Комментарий успешно удален!';
					redirect("active.php?act=comments&uz=$uz&start=$start");
				} else {
					show_error('Ошибка! Данного комментария не существует!');
				}
			} else {
				show_error('Ошибка! Неверный идентификатор сессии, повторите действие!');
			}
		} else {
			show_error('Ошибка! Удалять комментарии могут только модераторы!');
		}

		echo '<img src="/images/img/back.gif" alt="image" /> <a href="active.php?act=comments&amp;uz='.$uz.'&amp;start='.$start.'">Вернуться</a><br />';
	break;

default:
	redirect("active.php");
endswitch;

echo '<img src="/images/img/reload.gif" alt="image" /> <a href="index.php">Категории</a><br />';

include_once ('../themes/footer.php');
?>
