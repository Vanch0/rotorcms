<?php
#---------------------------------------------#
#      ********* RotorCMS *********           #
#           Author  :  Vantuz                 #
#            Email  :  visavi.net@mail.ru     #
#             Site  :  http://visavi.net      #
#              ICQ  :  36-44-66               #
#            Skype  :  vantuzilla             #
#---------------------------------------------#
if (!defined('BASEDIR')) {
	header("Location: /index.php");
	exit;
}

// --------------------------- Функция перевода секунд во время -----------------------------//
function maketime($time) {
	if ($time < 3600) {
		$time = sprintf("%02d:%02d", (int)($time / 60) % 60, $time % 60);
	} else {
		$time = sprintf("%02d:%02d:%02d", (int)($time / 3600) % 24, (int)($time / 60) % 60, $time % 60);
	}
	return $time;
}

// --------------------------- Функция перевода секунд в дни -----------------------------//
function makestime($time) {
	$day = floor($time / 86400);
	$hours = floor(($time / 3600) - $day * 24);
	$min = floor(($time - $hours * 3600 - $day * 86400) / 60);
	$sec = $time - ($min * 60 + $hours * 3600 + $day * 86400);

	return sprintf("%01d дн. %02d:%02d:%02d", $day, $hours, $min, $sec);
}

// --------------------------- Функция временного сдвига -----------------------------//
function date_fixed($timestamp, $format = "d.m.y / H:i") { // Не используется
	global $udata;

	if (!is_numeric($timestamp)) {
		$timestamp = SITETIME;
	}
	$shift = $udata['users_timezone'] * 3600;
	$datestamp = date($format, $timestamp + $shift);

	$today = date("d.m.y", SITETIME + $shift);
	$yesterday = date("d.m.y", strtotime("-1 day", SITETIME + $shift));

	$datestamp = str_replace($today, 'Сегодня', $datestamp);
	$datestamp = str_replace($yesterday, 'Вчера', $datestamp);

	$search = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	$replace = array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря');
	$datestamp = str_replace($search, $replace, $datestamp);

	return $datestamp;
}

// --------------- Функция удаление картинки с проверкой -------------------//
function unlink_image($dir, $image) {

	if (!empty($image)) {
		$prename = str_replace('/', '_' ,$dir);

		if (file_exists(BASEDIR.'/'.$dir.$image)) {
			unlink(BASEDIR.'/'.$dir.$image);
		}

		if (file_exists(BASEDIR.'/upload/thumbnail/'.$prename . $image)) {
			unlink(BASEDIR.'/upload/thumbnail/'.$prename . $image);
		}
	}
}

// ------------------- Функция полного удаления юзера --------------------//
function delete_users($user) {
	if (!empty($user)){
	/*
		$userpic = DB::run() -> querySingle("SELECT `users_picture` FROM `users` WHERE `users_login`=? LIMIT 1;", array($user));

		unlink_image('upload/photos/', $userpic);
		unlink_image('upload/avatars/', $user.'.gif');

		DB::run() -> query("DELETE FROM `inbox` WHERE `inbox_user`=?;", array($user));
		DB::run() -> query("DELETE FROM `outbox` WHERE `outbox_author`=?;", array($user));
		DB::run() -> query("DELETE FROM `trash` WHERE `trash_user`=?;", array($user));
		DB::run() -> query("DELETE FROM `friends` WHERE `friend_user`=?;", array($user));
		DB::run() -> query("DELETE FROM `rating` WHERE `rating_user`=?;", array($user));
		DB::run() -> query("DELETE FROM `visit` WHERE `visit_user`=?;", array($user));
		DB::run() -> query("DELETE FROM `notebook` WHERE `note_user`=?;", array($user));
		DB::run() -> query("DELETE FROM `banhist` WHERE `ban_user`=?;", array($user));
		DB::run() -> query("DELETE FROM `note` WHERE `note_user`=?;", array($user));
		DB::run() -> query("DELETE FROM `bookmarks` WHERE `book_user`=?;", array($user));
		DB::run() -> query("DELETE FROM `invite` WHERE `user`=? OR `invited`=?;", array($user, $user));
		DB::run() -> query("DELETE FROM `users` WHERE `users_login`=?;", array($user));
	*/
	}
}

// ------------------- Функция удаления фотоальбома юзера --------------------//
function delete_album($user) {

	if (!empty($user)){
		$querydel = DB::run() -> query("SELECT `photo_id`, `photo_link` FROM `photo` WHERE `photo_user`=?;", array($user));
		$arr_photo = $querydel -> fetchAll();

		if (count($arr_photo) > 0) {
			foreach ($arr_photo as $delete) {
				DB::run() -> query("DELETE FROM `photo` WHERE `photo_id`=?;", array($delete['photo_id']));
				DB::run() -> query("DELETE FROM `commphoto` WHERE `commphoto_gid`=?;", array($delete['photo_id']));

				unlink_image('upload/pictures/', $delete['photo_link']);
			}
		}
	}
}

// --------------- Функция правильного окончания для денег -------------------//
function moneys($sum) {
	global $config;

	$sum = (int)$sum;
	$money = explode(',', $config['moneyname']);
	if (count($money) == 3) {
		$str1 = abs($sum) % 100;
		$str2 = $sum % 10;

		if ($str1 > 10 && $str1 < 20) return $sum.' '.$money[0];
		if ($str2 > 1 && $str2 < 5) return $sum.' '.$money[1];
		if ($str2 == 1) return $sum.' '.$money[2];
	}

	return $sum.' '.$money[0];
}

// --------------- Функция правильного окончания для актива -------------------//
function points($sum) {
	global $config;

	$sum = (int)$sum;
	$score = explode(',', $config['scorename']);
	if (count($score) == 3) {
		$str1 = abs($sum) % 100;
		$str2 = $sum % 10;

		if ($str1 > 10 && $str1 < 20) return $sum.' '.$score[0];
		if ($str2 > 1 && $str2 < 5) return $sum.' '.$score[1];
		if ($str2 == 1) return $sum.' '.$score[2];
	}

	return $sum.' '.$score[0];
}

// ------------------ Функция подсветки кода -------------------------//
function highlight_code($code) {

	if (is_array($code)) $code = $code[1];

/*	$code = nosmiles($code);
	$code = strtr($code, array('&lt;'=>'<', '&gt;'=>'>', '&amp;'=>'&', '&quot;'=>'"', '&#36;'=>'$', '&#37;'=>'%', '&#39;'=>"'", '&#92;'=>'\\', '&#94;'=>'^', '&#96;'=>'`', '&#124;' => '|', '<br />'=>"\r\n"));

	$code = highlight_string($code, true);
	$code = strtr($code, array("\r\n"=>'<br />', '://'=>'&#58//', '$'=>'&#36;', "'"=>'&#39;', '%'=>'&#37;', '\\'=>'&#92;', '`'=>'&#96;', '^'=>'&#94;', '|'=>'&#124;'));*/

	return '<pre class="prettyprint linenums">'.$code.'</pre>';
}

// ----------------------- Функция скрытого текста ------------------------//
/*function hidden_text($str) {

	if ($str[1]=='') $str[1] = 'Текст отсутствует';
	if (is_user()) {
		$text = '<div class="hiding"><span class="strong">Скрытый текст:</span> '.$str[1].'</div>';
	} else {
		$text = '<div class="hiding"><span class="strong">Скрытый текст.</span> Для просмотра необходимо авторизоваться!</div>';
	}

	return $text;
}*/

// ------------------ Вспомогательная функция для bb-кода --------------------//
/*function url_replace($url) {
	global $config;

	if (!isset($url[4])) {
		$target = (strpos($url[1], $config['home']) === false) ? ' target="_blank" rel="nofollow"' : '';
		$title = (utf_strlen($url[3]) > 80) ? utf_substr($url[3], 0, 70).'...' : $url[3];
		return '<a href="'.$url[1].'"'.$target.'>'.check(rawurldecode(html_entity_decode($title, ENT_QUOTES, 'utf-8'))).'</a>';
	} else {
		$target = (strpos($url[4], $config['home']) === false) ? ' target="_blank" rel="nofollow"' : '';
		$title = (utf_strlen($url[4]) > 80) ? utf_substr($url[4], 0, 70).'...' : $url[4];
		return '<a href="'.$url[4].'"'.$target.'>'.check(rawurldecode(html_entity_decode($title, ENT_QUOTES, 'utf-8'))).'</a>';
	}
}*/

// ----------------------- Функция вывода спойлера ------------------------//
/*function spoiler_text($match) {

	$title = (empty($match[1])) ? 'Спойлер' : $match[1];
	$text = (empty($match[2])) ? 'Текста нет' : $match[2];

	if (!isset($match[2])) {
		$title = 'Спойлер';
		$text = $match[1];
	}

	return '<div class="spoiler-wrap">
		<div class="spoiler-head open">'.$title.'</div>
		<div class="spoiler-body">'.$text.'</div>
	</div>';
}*/

// ------------------ Функция вставки BB-кода --------------------//
function bb_code($text) {

	static $list_smiles;

	$bbcode = new BBCodeParser;
	$text = $bbcode->parse($text);

	if (empty($list_smiles)) {

		if (!file_exists(DATADIR."/temp/smiles.dat")) {

			$smiles = \Smile::all(array('order' => 'LENGTH(code) desc'));
			$smiles = \ActiveRecord\assoc($smiles, 'code', 'name');
			file_put_contents(DATADIR."/temp/smiles.dat", serialize($smiles));
		}

		$list_smiles = unserialize(file_get_contents(DATADIR."/temp/smiles.dat"));
	}

	foreach($list_smiles as $code => $smile) {
		$text = str_replace($code, '<img src="/images/smiles/'.$smile.'" alt="'.$code.'" /> ', $text);
	}

	return $text;
/*	$parser = new JBBCode\Parser();
	$parser->addCodeDefinitionSet(new JBBCode\NewCodeDefinitionSet());

	$parser->parse($text);

	$smileyVisitor = new \JBBCode\visitors\SmilesVisitor();
	$parser->accept($smileyVisitor);

	return $parser->getAsHTML();*/
/*	$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/si', 'highlight_code', $text);
	$text = preg_replace_callback('#\[hide\](.*?)\[/hide\]#i', 'hidden_text', $text);

	$text = preg_replace_callback('#\[spoiler=(.*?)\](.*?)\[/spoiler\]#si', 'spoiler_text', $text);
	$text = preg_replace_callback('#\[spoiler\](.*?)\[/spoiler\]#si', 'spoiler_text', $text);

	$text = preg_replace_callback('~\[url=((https?|ftp)://.+?)\](.+?)\[/url\]|((https?|ftp)://[0-9a-zа-яё/.;?=\(\)\_\-&%#]+)~ui', 'url_replace', $text);
	$text = preg_replace('#\[youtube\](.*?)\[/youtube\]#si', '<iframe width="280" height="210" src="//www.youtube.com/embed/\1" frameborder="0"></iframe>', $text);
	$text = preg_replace('#\[big\](.*?)\[/big\]#si', '<big>\1</big>', $text);
	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $text);
	$text = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $text);
	$text = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $text);
	$text = preg_replace('#\[small\](.*?)\[/small\]#si', '<small>\1</small>', $text);
	$text = preg_replace('#\[red\](.*?)\[/red\]#si', '<span style="color:#ff0000">\1</span>', $text);
	$text = preg_replace('#\[green\](.*?)\[/green\]#si', '<span style="color:#00cc00">\1</span>', $text);
	$text = preg_replace('#\[blue\](.*?)\[/blue\]#si', '<span style="color:#0000ff">\1</span>', $text);
	$text = preg_replace('#\[q\](.*?)\[/q\]#si', '<div class="q">\1</div>', $text);
	$text = preg_replace('#\[del\](.*?)\[/del\]#si', '<del>\1</del>', $text);
	$text = nl2br($text);
	return $text;*/
}

// ------------------ Функция перекодировки из UTF в WIN --------------------//
function utf_to_win($str) {
	if (function_exists('mb_convert_encoding')) return mb_convert_encoding($str, 'windows-1251', 'utf-8');
	if (function_exists('iconv')) return iconv('utf-8', 'windows-1251', $str);

	$utf8win1251 = array("А" => "\xC0", "Б" => "\xC1", "В" => "\xC2", "Г" => "\xC3", "Д" => "\xC4", "Е" => "\xC5", "Ё" => "\xA8", "Ж" => "\xC6", "З" => "\xC7", "И" => "\xC8", "Й" => "\xC9", "К" => "\xCA", "Л" => "\xCB", "М" => "\xCC",
		"Н" => "\xCD", "О" => "\xCE", "П" => "\xCF", "Р" => "\xD0", "С" => "\xD1", "Т" => "\xD2", "У" => "\xD3", "Ф" => "\xD4", "Х" => "\xD5", "Ц" => "\xD6", "Ч" => "\xD7", "Ш" => "\xD8", "Щ" => "\xD9", "Ъ" => "\xDA",
		"Ы" => "\xDB", "Ь" => "\xDC", "Э" => "\xDD", "Ю" => "\xDE", "Я" => "\xDF", "а" => "\xE0", "б" => "\xE1", "в" => "\xE2", "г" => "\xE3", "д" => "\xE4", "е" => "\xE5", "ё" => "\xB8", "ж" => "\xE6", "з" => "\xE7",
		"и" => "\xE8", "й" => "\xE9", "к" => "\xEA", "л" => "\xEB", "м" => "\xEC", "н" => "\xED", "о" => "\xEE", "п" => "\xEF", "р" => "\xF0", "с" => "\xF1", "т" => "\xF2", "у" => "\xF3", "ф" => "\xF4", "х" => "\xF5",
		"ц" => "\xF6", "ч" => "\xF7", "ш" => "\xF8", "щ" => "\xF9", "ъ" => "\xFA", "ы" => "\xFB", "ь" => "\xFC", "э" => "\xFD", "ю" => "\xFE", "я" => "\xFF");

	return strtr($str, $utf8win1251);
}

// ------------------ Функция перекодировки из WIN в UTF --------------------//
function win_to_utf($str) {
	if (function_exists('mb_convert_encoding')) return mb_convert_encoding($str, 'utf-8', 'windows-1251');
	if (function_exists('iconv')) return iconv('windows-1251', 'utf-8', $str);

	$win1251utf8 = array("\xC0" => "А", "\xC1" => "Б", "\xC2" => "В", "\xC3" => "Г", "\xC4" => "Д", "\xC5" => "Е", "\xA8" => "Ё", "\xC6" => "Ж", "\xC7" => "З", "\xC8" => "И", "\xC9" => "Й", "\xCA" => "К", "\xCB" => "Л", "\xCC" => "М",
		"\xCD" => "Н", "\xCE" => "О", "\xCF" => "П", "\xD0" => "Р", "\xD1" => "С", "\xD2" => "Т", "\xD3" => "У", "\xD4" => "Ф", "\xD5" => "Х", "\xD6" => "Ц", "\xD7" => "Ч", "\xD8" => "Ш", "\xD9" => "Щ", "\xDA" => "Ъ",
		"\xDB" => "Ы", "\xDC" => "Ь", "\xDD" => "Э", "\xDE" => "Ю", "\xDF" => "Я", "\xE0" => "а", "\xE1" => "б", "\xE2" => "в", "\xE3" => "г", "\xE4" => "д", "\xE5" => "е", "\xB8" => "ё", "\xE6" => "ж", "\xE7" => "з",
		"\xE8" => "и", "\xE9" => "й", "\xEA" => "к", "\xEB" => "л", "\xEC" => "м", "\xED" => "н", "\xEE" => "о", "\xEF" => "п", "\xF0" => "р", "\xF1" => "с", "\xF2" => "т", "\xF3" => "у", "\xF4" => "ф", "\xF5" => "х",
		"\xF6" => "ц", "\xF7" => "ч", "\xF8" => "ш", "\xF9" => "щ", "\xFA" => "ъ", "\xFB" => "ы", "\xFC" => "ь", "\xFD" => "э", "\xFE" => "ю", "\xFF" => "я");

	return strtr($str, $win1251utf8);
}

// ------------------ Функция преобразования в нижний регистр для UTF ------------------//
function utf_lower($str) {
	if (function_exists('mb_strtolower')) return mb_strtolower($str, 'utf-8');

	$arraytolower = array('А' => 'а', 'Б' => 'б', 'В' => 'в', 'Г' => 'г', 'Д' => 'д', 'Е' => 'е', 'Ё' => 'ё', 'Ж' => 'ж', 'З' => 'з', 'И' => 'и', 'Й' => 'й', 'К' => 'к', 'Л' => 'л', 'М' => 'м', 'Н' => 'н', 'О' => 'о', 'П' => 'п', 'Р' => 'р', 'С' => 'с', 'Т' => 'т', 'У' => 'у', 'Ф' => 'ф', 'Х' => 'х', 'Ц' => 'ц', 'Ч' => 'ч', 'Ш' => 'ш', 'Щ' => 'щ', 'Ь' => 'ь', 'Ъ' => 'ъ', 'Ы' => 'ы', 'Э' => 'э', 'Ю' => 'ю', 'Я' => 'я',
		'A' => 'a', 'B' => 'b', 'C' => 'c', 'D' => 'd', 'E' => 'e', 'I' => 'i', 'F' => 'f', 'G' => 'g', 'H' => 'h', 'J' => 'j', 'K' => 'k', 'L' => 'l', 'M' => 'm', 'N' => 'n', 'O' => 'o', 'P' => 'p', 'Q' => 'q', 'R' => 'r', 'S' => 's', 'T' => 't', 'U' => 'u', 'V' => 'v', 'W' => 'w', 'X' => 'x', 'Y' => 'y', 'Z' => 'z');

	return strtr($str, $arraytolower);
}

// ----------------------- Функция экранирования основных знаков --------------------------//
function check($msg) {
	if (is_array($msg)) {
		foreach($msg as $key => $val) {
			$msg[$key] = check($val);
		}
	} else {

		$msg = htmlspecialchars(stripslashes(trim($msg)), ENT_QUOTES, 'UTF-8');

		$search = array("\0", "\x00", "\x1A", chr(226).chr(128).chr(174));
		$replace = array('', '', '', '');

		$msg = str_replace($search, $replace, $msg);
	}

	return $msg;
}

// ------------------ Функция определения браузера --------------------//
function get_user_agent() {
	$browser = new Browser();
	$brow = $browser->getBrowser();
	$version = $browser->getVersion();
	return $version == 'unknown' ? $brow : $brow.' '.subtok($browser->getVersion(), '.', 0, 2);
}

// ----------------------- Функция обрезки строки с условием -------------------------//
function subtok($string, $chr, $pos, $len = null) {
	return implode($chr, array_slice(explode($chr, $string), $pos, $len));
}

//deprecated  use nl2br
// ----------------------- Функция вырезания переноса строки -------------------------//
/*function no_br($msg) {
	$msg = nl2br($msg);
	$msg = preg_replace('|[\r\n]+|si', '', $msg);
	return $msg;
}*/

//deprecated
// ----------------------- Функция добавления переноса строки -------------------------//
/*function yes_br($msg) {
	$msg = preg_replace('|<br * /?>|i', "\r\n", $msg);
	return $msg;
}*/

// ----------------------- Функция добавления переноса строки -------------------------//
/*function br2nl($string) {
	return preg_replace('/\<br(\s*)?\/?\>/i', '', $string);
}*/

// ------------------------ Функция замены и вывода смайлов --------------------------//
/*function smiles($text) {

	$smiles = Smile::all(array('order' => 'LENGTH(code) desc'));

	foreach($smiles as $smile) {
		$text = str_replace($smile->code, '<img src="/images/smiles/'.$smile->name.'" alt="smile" /> ', $text);
	}

	return $text;
}*/

// --------------- Функция обратной замены смайлов -------------------//
/*function nosmiles($text) {

	return preg_replace('|<img src="/images/smiles/(.*?).(\w+)" alt="smile" /> |', ':$1', $text);

}*/

// --------------- Функция правильного вывода веса файла -------------------//
function formatsize($file_size) {
	if ($file_size >= 1048576000) {
		$file_size = round(($file_size / 1073741824), 2)." Gb";
	} elseif ($file_size >= 1024000) {
		$file_size = round(($file_size / 1048576), 2)." Mb";
	} elseif ($file_size >= 1000) {
		$file_size = round(($file_size / 1024), 2)." Kb";
	} else {
		$file_size = round($file_size)." byte";
	}
	return $file_size;
}

// --------------- Функция форматированного вывода размера файла -------------------//
function read_file($file) {
	if (file_exists($file)) {
		return formatsize(filesize($file));
	} else {
		return 0;
	}
}

// --------------- Функция подсчета веса директории -------------------//
/*function read_dir($dir) {
	if (empty($allsize)) {
		$allsize = 0;
	}

	if ($path = opendir($dir)) {
		while ($file_name = readdir($path)) {
			if (($file_name !== '.') && ($file_name !== '..')) {
				if (is_dir($dir."/".$file_name)) {
					$allsize += read_dir($dir."/".$file_name);
				} else {
					$allsize += filesize($dir."/".$file_name);
				}
			}
		}
		closedir ($path);
	}
	return $allsize;
}*/

// --------------- Функция правильного вывода времени -------------------//
function formattime($file_time, $round = 1) {
	if ($file_time >= 86400) {
		$file_time = round((($file_time / 60) / 60) / 24, $round).' дн.';
	} elseif ($file_time >= 3600) {
		$file_time = round(($file_time / 60) / 60, $round).' час.';
	} elseif ($file_time >= 60) {
		$file_time = round($file_time / 60).' мин.';
	} else {
		$file_time = round($file_time).' сек.';
	}
	return $file_time;
}

// ------------------ Функция антимата --------------------//
function antimat($str) {

	$antimat = Antimat::all(array('order' => 'LENGTH(string) desc'));
	$badwords = ActiveRecord\collect($antimat, 'string');
	//$querymat = DB::run() -> query("SELECT `mat_string` FROM `antimat` ORDER BY LENGTH(`mat_string`) DESC;");
	//$arrmat = $querymat -> fetchAll(PDO::FETCH_COLUMN);

	if (count($badwords) > 0) {
		foreach($badwords as $word) {
			$str = preg_replace('|'.preg_quote($word).'|iu', '***', $str);
		}
	}

	return $str;
}

// ------------------ Функция должности юзера --------------------//
function user_status($level) {
	global $config;

	$name = explode(',', $config['statusname']);

	switch ($level) {
		case 'superadmin': $status = $name[0];
			break;
		case 'admin': $status = $name[1];
			break;
		case 'supermoder': $status = $name[2];
			break;
		case 'moder': $status = $name[3];
			break;
		default: $status = $name[4];
	}

	return $status;
}

// ---------------- Функция кэширования статусов ------------------//
function save_title($time = 0) {
	if (empty($time) || @filemtime(DATADIR.'/temp/status.dat') < time() - $time) {

		$users = User::all(array(
			'select' => 'u.id, u.status, s.name, s.color',
			'from' => 'users u',
			'conditions' => 'u.point > 0',
			'joins' => 'LEFT JOIN status s ON u.point BETWEEN s.point AND s.topoint')
		);

		$allstat = array();
		foreach ($users as $user) {
			if (!empty($user->status)) {
				$allstat[$user->id] = '<span style="color:#ff0000">'.$user->status.'</span>';
				continue;
			}

			if (!empty($user->color)) {
				$allstat[$user->id] = '<span style="color:'.$user->color.'">'.$user->name.'</span>';
				continue;
			}

			$allstat[$user->id] = $user->name;
		}

		file_put_contents(DATADIR.'/temp/status.dat', serialize($allstat), LOCK_EX);
	}
}

// ------------- Функция вывода статусов пользователей -----------//
function user_title($user_id) {
	global $config;
	static $arrstat;

	if (empty($arrstat)) {
		save_title(3600);
		$arrstat = unserialize(file_get_contents(DATADIR.'/temp/status.dat'));
	}

	return (isset($arrstat[$user_id])) ? $arrstat[$user_id] : $config['statusdef'];
}

// --------------- Функция кэширования ников -------------------//
function save_nickname($time = 0) {
	if (empty($time) || @filemtime(DATADIR.'/temp/nickname.dat') < time() - $time) {
		$querynick = DB::run() -> query("SELECT `users_login`, `users_nickname` FROM `users` WHERE `users_nickname`<>?;", array(''));
		$allnick = $querynick -> fetchAssoc();
		file_put_contents(DATADIR.'/temp/nickname.dat', serialize($allnick), LOCK_EX);
	}
}

// --------------- Функция русского ника -------------------//
function nickname($login) {
	static $arrnick;

	if (empty($arrnick)) {
		save_nickname(10800);
		$arrnick = unserialize(file_get_contents(DATADIR."/temp/nickname.dat"));
	}

	return (isset($arrnick[$login])) ? $arrnick[$login] : $login;
}

// --------------- Функция кэширования настроек -------------------//
function save_setting() {
	$queryset = DB::run() -> query("SELECT `setting_name`, `setting_value` FROM `setting`;");
	$config = $queryset -> fetchAssoc();
	file_put_contents(DATADIR."/temp/setting.dat", serialize($config), LOCK_EX);
}

// --------------- Функция кэширования забаненных IP -------------------//
function save_ipban() {
	$query = Ban::all(array('select' => 'ip'));
	$banip = ActiveRecord\collect($query, 'ip');

	file_put_contents(DATADIR."/temp/ipban.dat", serialize($banip), LOCK_EX);
	return $banip;
}

// ------------------------- Функция времени антифлуда ------------------------------//
function flood_period() {
	global $config, $udata;

	$period = $config['floodstime'];

	if ($udata['users_point'] < 50) {
		$period = round($config['floodstime'] * 2);
	}
	if ($udata['users_point'] >= 500) {
		$period = round($config['floodstime'] / 2);
	}
	if ($udata['users_point'] >= 1000) {
		$period = round($config['floodstime'] / 3);
	}
	if ($udata['users_point'] >= 5000) {
		$period = round($config['floodstime'] / 6);
	}
	if (is_admin()) {
		$period = 0;
	}

	return $period;
}

// ------------------------- Функция антифлуда ------------------------------//
function is_flood($log, $period = 0) {
	// переделать временно выключено
	return true;
	global $php_self;

	if (empty($period)) {
		$period = flood_period();
	}
	if (empty($period)) {
		return true;
	}

	DB::run() -> query("DELETE FROM `flood` WHERE `flood_time`<?;", array(SITETIME));

	$queryflood = DB::run() -> querySingle("SELECT `flood_id` FROM `flood` WHERE `flood_user`=? AND `flood_page`=? LIMIT 1;", array($log, $php_self));

	if (empty($queryflood)) {
		DB::run() -> query("INSERT INTO `flood` (`flood_user`, `flood_page`, `flood_time`) VALUES (?, ?, ?);", array($log, $php_self, SITETIME + $period));

		return true;
	}

	return false;
}

// ------------------ Функция выводящая картинку в загрузках --------------------//
function raiting_vote($str) {
	if (empty($str)) {
		$str = '<img src="/images/img/rating0.gif" alt="0" />';
	}
	if ($str > '0' && $str <= '0.5') {
		$str = '<img src="/images/img/rating1.gif" alt="0.5" />';
	}
	if ($str > '0.5' && $str <= '1') {
		$str = '<img src="/images/img/rating2.gif" alt="1" />';
	}
	if ($str > '1' && $str <= '1.5') {
		$str = '<img src="/images/img/rating3.gif" alt="1.5" />';
	}
	if ($str > '1.5' && $str <= '2') {
		$str = '<img src="/images/img/rating4.gif" alt="2" />';
	}
	if ($str > '2' && $str <= '2.5') {
		$str = '<img src="/images/img/rating5.gif" alt="2.5" />';
	}
	if ($str > '2.5' && $str <= '3') {
		$str = '<img src="/images/img/rating6.gif" alt="3" />';
	}
	if ($str > '3' && $str <= '3.5') {
		$str = '<img src="/images/img/rating7.gif" alt="3.5" />';
	}
	if ($str > '3.5' && $str <= '4') {
		$str = '<img src="/images/img/rating8.gif" alt="4" />';
	}
	if ($str > '4' && $str <= '4.5') {
		$str = '<img src="/images/img/rating9.gif" alt="4.5" />';
	}
	if ($str > '4.5' && $str <= '5') {
		$str = '<img src="/images/img/rating10.gif" alt="5" />';
	}
	return $str;
}

// ------------------ Функция для обработки base64 --------------------//
function safe_encode($string) {
	$data = base64_encode($string);
	$data = str_replace(array('+', '/', '='), array('_', '-', ''), $data);
	return $data;
}

function safe_decode($string) {
	$string = str_replace(array('_', '-'), array('+', '/'), $string);
	$data = base64_decode($string);
	return $data;
}
// ------------------ Функция генерирования паролей --------------------//
function generate_password($length = "") {
	if (empty($length)) {
		$length = mt_rand(10, 12);
	}
	$salt = str_split('aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789');

	$makepass = "";
	for ($i = 0; $i < $length; $i++) {
		$makepass .= $salt[array_rand($salt)];
	}
	return $makepass;
}

// ------------------ Функция для читаемого вывода массива --------------------//
function text_dump($var, $level = 0) {
	if (is_array($var)) $type = "array[".count($var)."]";
	else if (is_object($var)) $type = "object";
	else $type = "";
	if ($type) {
		echo $type.'<br />';
		for(Reset($var), $level++; list($k, $v) = each($var);) {
			if (is_array($v) && $k === "GLOBALS") continue;
			for($i = 0; $i < $level * 3; $i++) echo ' ';
			echo '<b>'.htmlspecialchars($k).'</b> => ', text_dump($v, $level);
		}
	} else echo '"', htmlspecialchars($var), '"<br />';
}

function dump($var) {
	if ((is_array($var) || is_object($var)) && count($var)) {
		echo '<pre>', text_dump($var), '</pre>';
	} else {
		echo '<tt>', text_dump($var), '</tt>';
	}
}

// --------------- Функция листинга всех файлов и папок ---------------//
function scan_check($dirname) {
	global $arr, $config;

	if (empty($arr['files'])) {
		$arr['files'] = array();
	}
	if (empty($arr['totalfiles'])) {
		$arr['totalfiles'] = 0;
	}
	if (empty($arr['totaldirs'])) {
		$arr['totaldirs'] = 0;
	}

	$no_check = explode(',', $config['nocheck']);

	$dirs = array_diff(scandir($dirname), array(".", ".."));

	foreach ($dirs as $file) {
		if (is_file($dirname.'/'.$file)) {
			$ext = getExtension($file);

			if (!in_array($ext, $no_check)) {
				$arr['files'][] = $dirname.'/'.$file.' - '.date_fixed(filemtime($dirname.'/'.$file), 'j.m.Y / H:i').' - '.read_file($dirname.'/'.$file);
				$arr['totalfiles']++;
			}
		}

		if (is_dir($dirname.'/'.$file)) {
			$arr['files'][] = $dirname.'/'.$file;
			$arr['totaldirs']++;
			scan_check($dirname.'/'.$file);
		}
	}

	return $arr;
}

// --------------- Функция вывода календаря---------------//
function make_calendar ($month, $year) {
	$wday = date("w", mktime(0, 0, 0, $month, 1, $year));
	if ($wday == 0) {
		$wday = 7;
	}
	$n = - ($wday-2);
	$cal = array();
	for ($y = 0; $y < 6; $y++) {
		$row = array();
		$notEmpty = false;
		for ($x = 0; $x < 7; $x++, $n++) {
			if (checkdate($month, $n, $year)) {
				$row[] = $n;
				$notEmpty = true;
			} else {
				$row[] = "";
			}
		}
		if (!$notEmpty) break;
		$cal[] = $row;
	}
	return $cal;
}

// --------------- Функция подсчета денег у юзера ---------------//
function user_money($user_id) {
	return User::first(array('conditions' => array('user_id=?', $user_id)))->money;
}

// --------------- Функция подсчета писем у юзера ---------------//
function user_mail($user_id) {
	return Inbox::count(array('conditions' => array('user_id=?', $user_id)));
}

// --------------- Функция кэширования аватаров -------------------//
function save_avatar($time = 0) {
	if (empty($time) || @filemtime(DATADIR."/temp/avatars.dat") < time() - $time) {

		$allavat = array();
		$avatars = User::all(array('select' => 'id, avatar', 'conditions' => 'avatar IS NOT NULL'));

		foreach ($avatars as $avatar) {
			$allavat[$avatar->id] = $avatar->avatar;
		}

		file_put_contents(DATADIR."/temp/avatars.dat", serialize($allavat), LOCK_EX);
	}
}

// --------------- Функция вывода аватара пользователя ---------------//
function user_avatars($user_id) {
	global $config;
	static $avatars;

	$image = array(
		'class' => 'media-left',
		'src' => '/images/avatars/guest.png'
	);

	switch ($user_id) {
		case null:
			break;

		default:
			if (empty($avatars)) {
				save_avatar(3600);
				$avatars = unserialize(file_get_contents(DATADIR."/temp/avatars.dat"));
			}

			if (isset($avatars[$user_id]) && file_exists($avatars[$user_id])) {
				$image['src'] = $avatars[$user_id];
			} else {
				$image['src'] = '/images/avatars/noavatar.png';
			}
	}

	return App::render('includes/avatar', compact('image'));
}


// ------------------ Функция подсчета пользователей онлайн -----------------//
function stats_online($cache = 30) {
	if (@filemtime(DATADIR."/temp/online.dat") < time()-$cache) {

		$total = Online::count();
		$online = Online::count(array('conditions' => 'user_id IS NOT NULL'));
		file_put_contents(DATADIR."/temp/online.dat", serialize(array($online, $total)), LOCK_EX);

		include_once(BASEDIR.'/includes/count.php');
	}

	return unserialize(file_get_contents(DATADIR."/temp/online.dat"));
}

// ------------------ Функция вывода пользователей онлайн -----------------//
function show_online() {
	global $config;

	if ($config['onlines'] == 1) {
		$online = stats_online();
		App::render('includes/online', compact('online'));
	}
}

// ------------------ Функция подсчета посещений -----------------//
function stats_counter() {
	if (@filemtime(DATADIR."/temp/counter.dat") < time()-10) {

		$counts = Counter::first();
		file_put_contents(DATADIR."/temp/counter.dat", $counts->to_json(), LOCK_EX);
	}

	return json_decode(file_get_contents(DATADIR."/temp/counter.dat"));
}

// ------------------ Функция вывода счетчика посещений -----------------//
function show_counter() {
	global $config;

	if ($config['incount'] > 0) {
		$count = stats_counter();

		App::render('includes/counter', compact('count'));
	}
}


// --------------- Функция вывода количества зарегистрированных ---------------//
function stats_users() {
	if (@filemtime(DATADIR."/temp/statusers.dat") < time()-3600) {
		$total = User::count();
		$new = User::count(array('conditions' => 'DATE(created_at) = DATE(NOW())'));

		if (empty($new)) {
			$stat = $total;
		} else {
			$stat = $total.'/+'.$new;
		}

		file_put_contents(DATADIR."/temp/statusers.dat", $stat, LOCK_EX);
	}

	return file_get_contents(DATADIR."/temp/statusers.dat");
}

// --------------- Функция вывода количества админов и модеров --------------------//
function stats_admins() {
	if (@filemtime(DATADIR."/temp/statadmins.dat") < time()-3600) {
		$stat = User::count(array('conditions' => array('level <> ?', 'user')));

		file_put_contents(DATADIR."/temp/statadmins.dat", $stat, LOCK_EX);
	}

	return file_get_contents(DATADIR."/temp/statadmins.dat");
}

// --------------- Функция вывода количества жалоб --------------------//
function stats_spam() {
	return Spam::count();
	//return DB::run() -> querySingle("SELECT count(*) FROM `spam`;");
}
// --------------- Функция вывода количества забаненных --------------------//
function stats_banned() {
	return User::count(array('conditions' => 'ban = 1 AND timeban > NOW()'));
	//return DB::run() -> querySingle("SELECT count(*) FROM `users` WHERE `users_ban`=? AND `users_timeban`>?;", array(1, SITETIME));
}

// --------------- Функция вывода истории банов --------------------//
function stats_banhist() {
	return DB::run() -> querySingle("SELECT count(*) FROM `banhist`;");
}

// ------------ Функция вывода количества ожидающих регистрации -----------//
function stats_reglist() {
	return User::count(array('conditions' => 'confirmreg > 0'));
	//return DB::run() -> querySingle("SELECT count(*) FROM `users` WHERE `users_confirmreg`>?;", array(0));
}

// --------------- Функция вывода количества забаненных IP --------------------//
function stats_ipbanned() {
	return Ban::count();
	//return DB::run() -> querySingle("SELECT count(*) FROM `ban`;");
}

// --------------- Функция вывода количества фотографий --------------------//
function stats_gallery() {
	if (@filemtime(DATADIR."/temp/statgallery.dat") < time()-900) {

		$total = Photo::count();
		$totalnew = Photo::count(array('conditions' => 'created_at > NOW()-INTERVAL 3 DAY'));

		if (empty($totalnew)) {
			$stat = $total;
		} else {
			$stat = $total.'/+'.$totalnew;
		}

		file_put_contents(DATADIR."/temp/statgallery.dat", $stat, LOCK_EX);
	}

	return file_get_contents(DATADIR."/temp/statgallery.dat");
}

// --------------- Функция вывода количества новостей--------------------//
function stats_allnews() {
	return News::count();
	//return DB::run() -> querySingle("SELECT count(*) FROM `news`;");
}

// ---------- Функция вывода записей в черном списке ------------//
function stats_blacklist() {
	$query = DB::run() -> query("SELECT `black_type`, count(*) FROM `blacklist` GROUP BY `black_type`;");
	$blacklist = $query -> fetchAssoc();
	$list = $blacklist + array_fill(1, 3, 0);
	return $list[1].'/'.$list[2].'/'.$list[3];
}

// --------------- Функция вывода количества заголовков ----------------//
function stats_antimat() {
	return Antimat::count();
}

// --------------- Функция вывода количества смайлов ----------------//
function stats_smiles() {
	return Smile::count();
}

// --------------- Функция вывода количества аватаров ----------------//
function stats_avatars() {
	return Avatar::count();;
}

// ----------- Функция вывода даты последнего сканирования -------------//
function stats_checker() {
	if (file_exists(DATADIR."/temp/checker.dat")) {
		return date_fixed(filemtime(DATADIR."/temp/checker.dat"), "j.m.y");
	} else {
		return 0;
	}
}

// --------------- Функция вывода количества рекламы ----------------//
function stats_advert() {
	return DB::run() -> querySingle("SELECT count(*) FROM `reklama`;");
}

// --------------- Функция вывода количества приглашений --------------//
function stats_invite() {
	$invite = DB::run() -> querySingle("SELECT count(*) FROM `invite` WHERE `used`=?;", array(0));
	$used_invite = DB::run() -> querySingle("SELECT count(*) FROM `invite` WHERE `used`=?;", array(1));
	return $invite.'/'.$used_invite;
}

// --------------- Функция определение онлайн-статуса ---------------//
function user_online($user_id) {
	static $arrvisit;

	$statwho = '<span class="fa fa-asterisk text-danger"></span>';

	if (empty($arrvisit)) {
		if (@filemtime(DATADIR."/temp/visit.dat") < time()-10) {

			$visits = Visit::all(array('conditions' => 'created_at > NOW()-INTERVAL 10 MINUTE'));
			$allvisits = ActiveRecord\collect($visits, 'user_id');

			file_put_contents(DATADIR."/temp/visit.dat", serialize($allvisits), LOCK_EX);
		}

		$arrvisit = unserialize(file_get_contents(DATADIR."/temp/visit.dat"));
	}

	if (is_array($arrvisit) && in_array($user_id, $arrvisit)) {
		$statwho = '<span class="fa fa-asterisk fa-spin text-success "></span>';
	}

	return $statwho;
}

// --------------- Функция определение пола пользователя ---------------//
function user_gender($user_id) {
	static $arrgender;

	$gender = 'fa-male';

	if (empty($arrgender)) {
		if (@filemtime(DATADIR."/temp/gender.dat") < time()-600) {
			$genders = User::all(array('conditions' => array('gender = ?', 2), 'select' => 'id'));
			$allgender = ActiveRecord\collect($genders, 'id');

			//$querygender = DB::run() -> query("SELECT `users_login` FROM `users` WHERE `users_gender`=?;", array(2));
			//$allgender = $querygender -> fetchAll(PDO::FETCH_COLUMN);
			file_put_contents(DATADIR."/temp/gender.dat", serialize($allgender), LOCK_EX);
		}
		$arrgender = unserialize(file_get_contents(DATADIR."/temp/gender.dat"));
	}

	if (in_array($user_id, $arrgender)) {
		$gender = 'fa-female';
	}

	return '<span class="fa '.$gender.'"></span> ';
}

// --------------- Функция вывода пользователей онлайн ---------------//
function allonline() {
	if (@filemtime(DATADIR."/temp/allonline.dat") < time()-30) {
		$queryvisit = DB::run() -> query("SELECT `visit_user` FROM `visit` WHERE `visit_nowtime`>? ORDER BY `visit_nowtime` DESC;", array(SITETIME-600));
		$allvisits = $queryvisit -> fetchAll(PDO::FETCH_COLUMN);
		file_put_contents(DATADIR."/temp/allonline.dat", serialize($allvisits), LOCK_EX);
	}

	return unserialize(file_get_contents(DATADIR."/temp/allonline.dat"));
}

// ------------------ Функция определение последнего посещения ----------------//
function user_visit($user_id) {
	$status = '(Оффлайн)';

	$visit = Visit::first(array('conditions' => array('user_id = ?', $user_id)));

	if ($visit) {
		if ($visit->created_at->getTimestamp() > SITETIME-600) {
			$status = '(Сейчас на сайте)';
		} else {
			$status = '(Последний визит: '.date_fixed($visit->created_at->getTimestamp()).')';
		}
	}

	return $status;
}

/*// --------------- Функции сжатия страниц ---------------//
function compress_output_gzip($output) {
	return gzencode($output, 5);
}

function compress_output_deflate($output) {
	return gzdeflate($output, 5);
}*/

// ---------- Функция обработки строк данных и ссылок ---------//
function check_string($string) {
	$string = strtolower($string);
	$string = str_replace(array('http://www.', 'http://wap.', 'http://', 'https://'), '', $string);
	$string = strtok($string, '/?');
	return $string;
}

// ---------- Аналог функции substr для UTF-8 ---------//
function utf_substr($str, $offset, $length = null) {
	if ($length === null) {
		$length = utf_strlen($str);
	}
	if (function_exists('mb_substr')) return mb_substr($str, $offset, $length, 'utf-8');
	if (function_exists('iconv_substr')) return iconv_substr($str, $offset, $length, 'utf-8');

	$str = utf_to_win($str);
	$str = substr($str, $offset, $length);
	return win_to_utf($str);
}

// ---------------------- Аналог функции strlen для UTF-8 -----------------------//
function utf_strlen($str) {
	if (function_exists('mb_strlen')) return mb_strlen($str, 'utf-8');
	if (function_exists('iconv_strlen')) return iconv_strlen($str, 'utf-8');
	if (function_exists('utf8_decode')) return strlen(utf8_decode($str));
	return strlen(utf_to_win($str));
}

// ---------- Аналог функции wordwrap для UTF-8 ---------//
function utf_wordwrap($str, $width = 75, $break = ' ', $cut = 1) {
	$str = utf_to_win($str);
	$str = wordwrap($str, $width, $break, $cut);
	return win_to_utf($str);
}

// ---------- Аналог функции stristr для UTF-8 ---------//
function utf_stristr($str, $search, $before = false) {

	if (function_exists('mb_stristr'))  return mb_stristr($str, $search, $before, 'utf-8');

	if (utf_strlen($search) == 0 ) {
		return false;
	}

	preg_match('|^(.*)'.preg_quote($search).'|iusU', $str, $matches);

	if (count($matches) == 2) {
		if ($before) return utf_substr($str, 0, utf_strlen($matches[1]));
		return utf_substr($str, utf_strlen($matches[1]));
	}

	return false;
}

// ----------------------- Функция определения кодировки ------------------------//
function is_utf($str) {
	$c = 0;
	$b = 0;
	$bits = 0;
	$len = strlen($str);
	for($i = 0; $i < $len; $i++) {
		$c = ord($str[$i]);
		if ($c > 128) {
			if (($c >= 254)) return false;
			elseif ($c >= 252) $bits = 6;
			elseif ($c >= 248) $bits = 5;
			elseif ($c >= 240) $bits = 4;
			elseif ($c >= 224) $bits = 3;
			elseif ($c >= 192) $bits = 2;
			else return false;
			if (($i + $bits) > $len) return false;
			while ($bits > 1) {
				$i++;
				$b = ord($str[$i]);
				if ($b < 128 || $b > 191) return false;
				$bits--;
			}
		}
	}
	return true;
}

// ----------------------- Функция отправки письма по e-mail ------------------------//
function addmail($mail, $subject, $messages, $sendermail="", $sendername="") {
	global $config;

	if (empty($sendermail)) {
		$sendermail = $config['emails'];
		$sendername = $config['nickname'];
	}

	$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';

	$adds = "From: =?UTF-8?B?".base64_encode($sendername)."?= <".$sendermail.">\n";
	$adds .= "X-sender: =?UTF-8?B?".base64_encode($sendername)."?= <".$sendermail.">\n";
	$adds .= "List-Unsubscribe: <http://".$config['home']."/pages/account.php>\n";
	$adds .= "Content-Type: text/plain; charset=utf-8\n";
	$adds .= "MIME-Version: 1.0\n";
	$adds .= "Content-Transfer-Encoding: 8bit\n";
	$adds .= "X-Mailer: PHP v.".phpversion()."\n";
	$adds .= "Date: ".date("r")."\n";

	return mail($mail, $subject, $messages, $adds);
}

// ----------------------- Постраничная навигация ------------------------//
function page_strnavigation($url, $posts, $start, $total, $range = 3) {

	if ($total > 0) {
		$pages = array();

		$pg_cnt = ceil($total / $posts);
		$cur_page = ceil(($start + 1) / $posts);
		$idx_fst = max($cur_page - $range, 1);
		$idx_lst = min($cur_page + $range, $pg_cnt);

		if ($cur_page != 1) {
			$pages[] = array(
				'start' => (($cur_page - 2) * $posts),
				'title' => 'Назад',
				'name' => '&laquo;',
			);
		}

		if (($start - $posts) >= 0) {
			if ($cur_page > ($range + 1)) {

				$pages[] = array(
					'start' => 0,
					'title' => '1 страница',
					'name' => 1,
				);
				if ($cur_page != ($range + 2)) {
					$pages[] = array(
						'separator' => true,
						'name' => ' ... ',
					);
				}
			}
		}

		for ($i = $idx_fst; $i <= $idx_lst; $i++) {
			$offset_page = ($i - 1) * $posts;
			if ($i == $cur_page) {

				$pages[] = array(
					'current' => true,
					'name' => $i,
				);
			} else {

				$pages[] = array(
					'start' => $offset_page,
					'title' => $i.' страница',
					'name' => $i,
				);
			}
		}

		if (($start + $posts) < $total) {
			if ($cur_page < ($pg_cnt - $range)) {
				if ($cur_page != ($pg_cnt - $range - 1)) {
					$pages[] = array(
						'separator' => true,
						'name' => ' ... ',
					);
				}
				$pages[] = array(
					'start' => ($pg_cnt - 1) * $posts,
					'title' => $pg_cnt . ' страница',
					'name' => $pg_cnt,
				);
			}
		}

		if ($cur_page != $pg_cnt) {
			$pages[] = array(
				'start' => $cur_page * $posts,
				'title' => 'Вперед',
				'name' => '&raquo;',
			);
		}

		App::render('includes/pagination', compact('pages', 'url'));
	}
}

// ----------------------- Вывод страниц в форуме ------------------------//
function forum_navigation($url, $posts, $total) {
	if ($total > 0) {

		$pages = array();
		$last_page = ceil($total / $posts);
		$last_start = $last_page * $posts - $posts;
		$max = $posts * 5;

		for($i = 0; $i < $max;) {
			if ($i < $total && $i >= 0) {
				$pages[] = array(
					'start' => $i,
					'name' => floor(1 + $i / $posts),
				);
			}
			$i += $posts;
		}

		if ($max < $total) {

			if ($max + $posts < $total) {
				$pages[] = array(
					'separator' => true,
					'name' => ' ... ',
				);
			}

			$pages[] = array(
				'start' => $last_start,
				'name' => $last_page,
			);
		}

		App::render('includes/pagination_forum', compact('pages', 'url'));
	}
}

// --------------------- Функция вывода навигации в галерее ------------------------//
function photo_navigation($id) {

	if (empty($id)) {
		return false;
	}

	$next_id = DB::run() -> querySingle("SELECT `photo_id` FROM `photo` WHERE `photo_id`>? ORDER BY `photo_id` ASC LIMIT 1;", array($id));
	$prev_id = DB::run() -> querySingle("SELECT `photo_id` FROM `photo` WHERE `photo_id`<? ORDER BY `photo_id` DESC LIMIT 1;", array($id));
	return array('next' => $next_id, 'prev' => $prev_id);

}

// --------------------- Функция вывода статистики блогов ------------------------//
function stats_blog() {

	if (@filemtime(DATADIR."/temp/statblog.dat") < time()-900) {

		$totalblog = BlogCategory::find(array('select' => 'SUM(count) as sum'));
		$totalnew = Blog::count(array('conditions' => 'created_at > NOW()-INTERVAL 3 DAY'));

		if (empty($totalnew)) {
			$stat = intval($totalblog->sum);
		} else {
			$stat = intval($totalblog->sum).'/+'.intval($totalnew);
		}

		file_put_contents(DATADIR."/temp/statblog.dat", $stat, LOCK_EX);
	}

	return file_get_contents(DATADIR."/temp/statblog.dat");
}

// --------------------- Функция вывода статистики форума ------------------------//
function stats_forum() {
	if (@filemtime(DATADIR."/temp/statforum.dat") < time()-600) {

		//$forums = Forum::find(array('select' => 'SUM(topics) as topics, SUM(posts) as posts'));
		$topics = Forum::count();
		$posts = Post::count();

		file_put_contents(DATADIR."/temp/statforum.dat", $topics.'/'.$posts, LOCK_EX);
	}

	return file_get_contents(DATADIR."/temp/statforum.dat");
}

// --------------------- Функция вывода статистики гостевой ------------------------//
function stats_guest() {
	if (@filemtime(DATADIR."/temp/statguest.dat") < time()-600) {
		global $config;

		$total = Guest::count();

		if ($total > ($config['maxpostbook']-10)) {
			$guest = Guest::find(array('select' => 'MAX(id) AS max'));
			$stat = $guest->max;
		} else {
			$stat = $total;
		}

		file_put_contents(DATADIR."/temp/statguest.dat", (int)$stat, LOCK_EX);
	}

	return file_get_contents(DATADIR."/temp/statguest.dat");
}

// -------------------- Функция вывода статистики админ-чата -----------------------//
function stats_chat() {
	global $config;

	$total = Chat::count();

	if ($total > ($config['chatpost']-10)) {
		$total = Chat::find(array('select' => 'MAX(id)'));
	}

	return $total;
}

// ------------------ Функция вывода времени последнего сообщения --------------------//
function stats_newchat() {
	return DB::run() -> querySingle("SELECT MAX(`chat_time`) FROM `chat`;");
}

// --------------------- Функция вывода статистики загрузок ------------------------//
function stats_load() {

	if (@filemtime(DATADIR."/temp/statload.dat") < time()-900) {

		$total = Down::count();
		$totalnew = Down::count(array('conditions' => 'active = 1 AND created_at > NOW()-INTERVAL 5 DAY'));

		if (empty($totalnew)) {
			$stat = intval($total);
		} else {
			$stat = $total.'/+'.$totalnew;
		}
		file_put_contents(DATADIR."/temp/statload.dat", $stat, LOCK_EX);
	}

	return file_get_contents(DATADIR."/temp/statload.dat");
}

// --------------------- Функция подсчета непроверенных файлов ------------------------//
function stats_newload() {

	$totalnew = Down::count(array('conditions' => 'active = 0'));
	$totalcheck = Down::count(array('conditions' => 'active = 0 AND app = 1'));

	if (empty($totalcheck)) {
		return intval($totalnew);
	} else {
		return $totalnew.'/+'.$totalcheck;
	}
}

// --------------------- Функция шифровки Email-адреса ------------------------//
function crypt_mail($mail) {
	$output = "";
	$strlen = strlen($mail);
	for ($i = 0; $i < $strlen; $i++) {
		$output .= '&#'.ord($mail[$i]).';';
	}
	return $output;
}

// ------------------- Функция обработки массива (int) --------------------//
function intar($string) {
	if (is_array($string)) {
		$newstring = array_map('intval', $string);
	} else {
		$newstring = array(abs(intval($string)));
	}

	return $newstring;
}

// ------------------- Функция подсчета голосований --------------------//
function stats_votes() {  //Удалить функцию
	if (@filemtime(DATADIR."/temp/statvote.dat") < time()-900) {
		$data = DB::run() -> queryFetch("SELECT count(*) AS `count`, SUM(`vote_count`) AS `sum` FROM `vote` WHERE `vote_closed`=?;", array(0));

		if (empty($data['sum'])) {
			$data['sum'] = 0;
		}

		file_put_contents(DATADIR."/temp/statvote.dat", $data['count'].'/'.$data['sum'], LOCK_EX);
	}

	return file_get_contents(DATADIR."/temp/statvote.dat");
}

// ------------------- Функция показа даты последней новости --------------------//
function stats_news() {
	if (@filemtime(DATADIR."/temp/statnews.dat") < time()-900) {
		$stat = 0;

		$news = News::last(array('select' => 'created_at'));

		if ($news) {
			$date = $news->created_at->format('Ymd');

			if (date('Ymd') == $date) {
				$stat = '<span style="color:#ff0000">Сегодня</span>';
			}
		}

		file_put_contents(DATADIR."/temp/statnews.dat", $stat, LOCK_EX);
	}

	return file_get_contents(DATADIR."/temp/statnews.dat");
}

// --------------------------- Функция вывода новостей -------------------------//
function last_news() {
	global $config;

	if ($config['lastnews'] > 0) {

		$news = News::all(array('conditions' => 'top = 1', 'limit' => $config['lastnews'], 'order' => 'created_at desc'));
		$total = count($news);

		if ($total > 0) {
			foreach ($news as $data) {
				$data->text = str_replace('[cut]', '', $data->text);
				echo '<img src="/images/img/act.png" alt="Новость" /> <a href="/news/index.php?act=read&amp;id='.$data->id.'">'.$data->title.'</a> ('.$data->comments.') <img class="news-title" src="/images/img/downs.gif" alt="Открыть" /><br />';

				echo '<div class="news-text">'.bb_code($data->text).'<br />';
				echo '<a href="/news/index.php?act=comments&amp;id='.$data->id.'">Комментарии</a> ';
				echo '<a href="/news/index.php?act=end&amp;id='.$data->id.'">&raquo;</a></div>';
			}
		}
	}
}

// ------------------------- Функция проверки аккаунта  ------------------------//
function check_user($user_id) {
	if (!empty($user_id)) {
		$user = User::exists($user_id);
		//$user = DB::run() -> querySingle("SELECT `users_id` FROM `users` WHERE `users_login`=? LIMIT 1;", array($login));
		if ($user) {
			return true;
		}
	}
	return false;
}

// ------------------------- Функция проверки авторизации  ------------------------//
function is_user() {
	global $config;
	static $result = false;

	if (!$result) {
		if (isset($_SESSION['id']) && isset($_SESSION['password'])) {

			$user = User::first($_SESSION['id']);
			if ($user) {

				if ($_SESSION['password'] == md5($config['keypass'].$user->password)) {
					$result = $user;
				}
			}
		}
	}

	return $result;
}

// ------------------------- Функция проверки администрации  ------------------------//
function is_admin($access = array()) {
	if (empty($access)) {
		$access = array('superadmin', 'admin', 'supermoder', 'moder');
	}

	if ($current_user = is_user()) {

		if (in_array($current_user->level, $access)) {
			return true;
		}
	}

	return false;
}

// ------------------------- Функция вывода заголовков ------------------------//
function show_title($header, $subheader = false) {
	global $config;
	static $show;

	$config['newtitle'] = $header;
	$config['header'] = $header;
	$config['subheader'] = $subheader;

	if (empty($show)) {
		echo $show = App::render('includes/title', array(), true);
	}

	return $config;
}

// ------------------------- Функция вывода ошибок ------------------------//
function show_error($errors) {
	App::render('includes/error', compact('errors'));
}

// ------------------------- Функция вывода предупреждения ------------------------//
function show_login($notice) {
	App::render('includes/login', compact('notice'));
}

// ------------------------- Функция замены заголовков ------------------------//
function ob_processing($str) {
	global $config;
	if (isset($config['newtitle'])) {
		$str = str_replace('%TITLE%', $config['newtitle'].' - '.$config['title'], $str);
	} else {
		$str = str_replace('%TITLE%', $config['logos'].' - '.$config['title'], $str);
	}

	$str = str_replace('%KEYWORDS%', $config['keywords'], $str);
	$str = str_replace('%DESCRIPTION%', $config['description'], $str);

	$str = str_replace('%HEADER%',  isset($config['header']) ? $config['header'] : '', $str);
	$str = str_replace('%SUBHEADER%', isset($config['subheader']) ? $config['subheader'] : '', $str);

	return $str;
}

// ------------------ Функция вывода иконки расширения --------------------//
function icons($ext) {
	switch ($ext) {
		case 'dir': $ico = 'dir.gif';
			break;
		case 'php': $ico = 'php.gif';
			break;
		case 'txt': case 'css': case 'dat': $ico = 'txt.gif';
			break;
		case 'htm': case 'html': $ico = 'htm.gif';
			break;
		case 'wav': case 'amr': $ico = 'wav.gif';
			break;
		case 'zip': case 'rar': $ico = 'zip.gif';
			break;
		case 'jpg': case 'jpeg': $ico = 'jpg.gif';
			break;
		case 'bmp': case 'wbmp': $ico = 'bmp.gif';
			break;
		case 'gif': $ico = 'gif.gif';
			break;
		case 'png': $ico = 'png.gif';
			break;
		case 'mmf': $ico = 'mmf.gif';
			break;
		case 'jad': $ico = 'jad.gif';
			break;
		case 'jar': $ico = 'jar.gif';
			break;
		case 'mid': $ico = 'mid.gif';
			break;
		case 'mp3': $ico = 'mp3.gif';
			break;
		case 'exe': $ico = 'exe.gif';
			break;
		case 'ttf': $ico = 'ttf.gif';
			break;
		case 'htaccess': $ico = 'htaccess.gif';
			break;
		default: $ico = 'file.gif';
	}
	return $ico;
}

// ------------------ Функция смешивания ассоциативного массива --------------------//
function shuffle_assoc(&$array) {
	$keys = array_keys($array);

	shuffle($keys);
	$new = array();

	foreach($keys as $key) {
		$new[$key] = $array[$key];
	}

	$array = $new;
	return true;
}

// --------------- Функция обрезки слов -------------------//
function strip_str($str, $words = 20) {
	$str = str_replace('<br />', ' ', $str);
	return implode(' ', array_slice(explode(' ', strip_tags($str)), 0, $words));
}

// ------------------ Функция вывода админской рекламы --------------------//
function show_advertadmin() {

	return false; // Временно
	if (@filemtime(DATADIR."/temp/rekadmin.dat") < time()-1800) {
		save_advertadmin();
	}

	$datafile = unserialize(file_get_contents(DATADIR."/temp/rekadmin.dat"));

	if (!empty($datafile)) {
		$quot_rand = array_rand($datafile);
		return $datafile[$quot_rand];
	}
}

// --------------- Функция кэширования админской рекламы -------------------//
function save_advertadmin() {
	$queryadv = DB::run() -> query("SELECT * FROM `advert`;");
	$data = $queryadv -> fetchAll();

	$arraylink = array();

	if (count($data) > 0) {
		foreach ($data as $val) {
			if (!empty($val['adv_color'])) {
				$val['adv_title'] = '<span style="color:'.$val['adv_color'].'">'.$val['adv_title'].'</span>';
			}

			$arraylink[] = '<b><a href="'.$val['adv_url'].'" target="_blank" rel="nofollow">'.$val['adv_title'].'</a></b><br />';
		}
	}

	file_put_contents(DATADIR."/temp/rekadmin.dat", serialize($arraylink), LOCK_EX);
}

// ------------------ Функция вывода пользовательской рекламы --------------------//
function show_advertuser() {

	return false; // Временно
	global $config;

	if (!empty($config['rekusershow'])) {
		if (@filemtime(DATADIR."/temp/rekuser.dat") < time()-1800) {
			save_advertuser();
		}

		$datafile = unserialize(file_get_contents(DATADIR."/temp/rekuser.dat"));
		$total = count($datafile);

		if ($total > 0) {
			if ($config['rekusershow'] > $total) {
				$config['rekusershow'] = $total;
			}

			$quot_rand = array_rand($datafile, $config['rekusershow']);

			if ($config['rekusershow'] > 1) {
				$result = array();
				for($i = 0; $i < $config['rekusershow']; $i++) {
					$result[] = $datafile[$quot_rand[$i]];
				}
				$result = implode('<br />', $result);
			} else {
				$result = $datafile[$quot_rand];
			}

			return $result.' <small><a href="/pages/reklama.php?act=all" rel="nofollow">[+]</a></small>';
		}
	}
}

// --------------- Функция кэширования пользовательской рекламы -------------------//
function save_advertuser() {
	$queryrek = DB::run() -> query("SELECT * FROM `rekuser` WHERE `rek_time`>?;", array(SITETIME));
	$data = $queryrek -> fetchAll();

	$arraylink = array();

	if (count($data) > 0) {
		foreach ($data as $val) {
			if (!empty($val['rek_color'])) {
				$val['rek_name'] = '<span style="color:'.$val['rek_color'].'">'.$val['rek_name'].'</span>';
			}
			$link = '<a href="'.$val['rek_site'].'" target="_blank" rel="nofollow">'.$val['rek_name'].'</a>';

			if (!empty($val['rek_bold'])) {
				$link = '<b>'.$link.'</b>';
			}

			$arraylink[] = $link;
		}
	}

	file_put_contents(DATADIR."/temp/rekuser.dat", serialize($arraylink), LOCK_EX);
}

// ----------- Функция проверки лицензии и обновления ------------//
function site_verification() {
	global $config;

	if (!empty($config['rotorlicense'])) {
		echo '<img src="/images/img/key.gif" alt="image" /> <b><a href="changes.php?act=verifi"><span style="color:#00cc00">Лицензионная версия</span></a> (<a href="changes.php?act=reload">'.$config['rotorversion'].'</a>)</b><br /><br />';
	} else {
		echo '<img src="/images/img/exit.gif" alt="image" /> <b><a href="changes.php?act=verifi"><span style="color:#ff0000">Бесплатная версия ('.$config['rotorversion'].')</span></a></b><br /><br />';
	}

	if (stats_changes() > $config['rotorversion']) {
		if (file_exists(DATADIR.'/upgrade_'.stats_changes().'.dat')) {
			include_once (DATADIR.'/upgrade_'.stats_changes().'.dat');
		} else {
			echo '<img src="/images/img/custom.gif" alt="image" />  <b><a href="changes.php?"><span style="color:#ff0000">Доступна новая версия '.stats_changes().'</span></a></b><br /><br />';
		}
	}
}

function license_verification() {
	global $config;

	$servername = $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];

	$geturl = 'http://visavi.net/rotorcms/index.php?act=check&site='.$servername;

	if (@file_get_contents($geturl)) {
		$data = file_get_contents($geturl);
	} else {
		$data = curl_connect($geturl);
	}

	$verification = (stristr($data, '--VERIFICATION--')) ? 1 : 0;
	$activate = (stristr($data, '--LICENSE_SITE--')) ? 1 : 0;
	$banned = (stristr($data, '--BANNED_IP--')) ? 1 : 0;

	if (!empty($verification)) {
		if (!empty($activate)) {
			echo '<img src="/images/img/key.gif" alt="image" /> <b>Сайт '.$servername.' использует лицензионную версию RotorCMS</b><br /><br />';
		} else {
			echo '<img src="/images/img/exit.gif" alt="image" /> <b>На сайт '.$servername.'  не выдавалась лицензия, используется бесплатная версия RotorCMS</b><br /><br />';
			echo '<img src="/images/img/reload.gif" alt="image" /> <b><a href="http://visavi.net/rotorcms/?act=licensefaq">Подробнее о лицензии</a></b><br /><br />';
		}

		DB::run() -> query("REPLACE INTO `setting` (`setting_name`, `setting_value`) VALUES (?, ?);", array('rotorlicense', $activate));
		save_setting();
	} elseif (!empty($banned)) {
		show_error('Ошибка! IP-адрес вашего сервера забанен на сайте <a href="http://visavi.net">visavi.net</a>!');
		echo 'Пожалуйста свяжитесь с администратором сайта и сообщите о проблеме<br />';
	} else {
		show_error('Ошибка! Не удалось соединиться с сайтом <a href="http://visavi.net">visavi.net</a> для проверки лицензии!');
		echo 'Попробуйте повторить активацию лицензии через некоторое время<br />';
	}
}

// ----------- Функция определения последней версии RotorCMS ------------//
function stats_changes() {
	global $config;

	if (@filemtime(DATADIR."/temp/changes.dat") < time()-86400) {
		if (@copy("http://visavi.net/rotorcms/rotor.txt", DATADIR."/temp/changes.dat")) {
		} else {
			$data = curl_connect("http://visavi.net/rotorcms/rotor.txt");
			file_put_contents(DATADIR."/temp/changes.dat", $data);
		}
	}

	$data = file_get_contents(DATADIR."/temp/changes.dat");

	if (is_serialized($data)) {
		$data = unserialize($data);
		return $data['version'];
	}
}

// ----------- Функция проверки сериализации ------------//
function is_serialized($data) {
	if (trim($data) == "") {
		return false;
	}
	if (preg_match("/^(i|s|a|o|d)(.*);/si", $data)) {
		return true;
	}
	return false;
}

// ----------- Функция закачки файла через curl ------------//
function curl_connect($url, $user_agent = 'Mozilla/5.0') {
	if (function_exists('curl_init')) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_REFERER, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
		$result = curl_exec ($ch);
		curl_close ($ch);
		return $result;
	}
}

// --------------- Функция кэширования последних тем форума -------------------//
function recenttopics($show = 5) { return false;
	if (@filemtime(DATADIR."/temp/recenttopics.dat") < time()-180) {
		$querytopic = DB::run() -> query("SELECT * FROM `topics` ORDER BY `topics_last_time` DESC LIMIT ".$show.";");
		$recent = $querytopic -> fetchAll();

		file_put_contents(DATADIR."/temp/recenttopics.dat", serialize($recent), LOCK_EX);
	}

	$topics = unserialize(file_get_contents(DATADIR."/temp/recenttopics.dat"));

	if (count($topics) > 0) {
		foreach ($topics as $topic) {
			//echo '<img src="/images/img/act.png" alt="image" /> <a href="/blog/blog.php?act=view&amp;id='.$topic['blogs_id'].'">'.$topic['blogs_title'].'</a> ('.$topic['blogs_comments'].')<br />';
			echo '<img src="/images/img/act.png" alt="image" /> <a href="/forum/topic.php?tid='.$topic['topics_id'].'">'.$topic['topics_title'].'</a> ('.$topic['topics_posts'].')';
			echo '<a href="/forum/topic.php?act=end&amp;tid='.$topic['topics_id'].'">&raquo;</a><br />';
		}
	}


}

// ------------- Функция кэширования последних файлов в загрузках -----------------//
function recentfiles($show = 5) {  return false;
	if (@filemtime(DATADIR."/temp/recentfiles.dat") < time()-600) {
		$queryfiles = DB::run() -> query("SELECT * FROM `downs` WHERE `downs_active`=? ORDER BY `downs_time` DESC LIMIT ".$show.";", array(1));
		$recent = $queryfiles -> fetchAll();

		file_put_contents(DATADIR."/temp/recentfiles.dat", serialize($recent), LOCK_EX);
	}

	$files = unserialize(file_get_contents(DATADIR."/temp/recentfiles.dat"));

	if (count($files) > 0) {
		foreach ($files as $file){

			$filesize = (!empty($file['downs_link'])) ? read_file(BASEDIR.'/load/files/'.$file['downs_link']) : 0;
			echo '<img src="/images/img/act.png" alt="image" /> <a href="/load/down.php?act=view&amp;id='.$file['downs_id'].'">'.$file['downs_title'].'</a> ('.$filesize.')<br />';
		}
	}

}

// ------------- Функция кэширования последних статей в блогах -----------------//
function recentblogs() {  return false;
	if (@filemtime(DATADIR."/temp/recentblog.dat") < time()-600) {
		$queryblogs = DB::run() -> query("SELECT * FROM `blogs` ORDER BY `blogs_time` DESC LIMIT 5;");
		$recent = $queryblogs -> fetchAll();

		file_put_contents(DATADIR."/temp/recentblog.dat", serialize($recent), LOCK_EX);
	}

	$blogs = unserialize(file_get_contents(DATADIR."/temp/recentblog.dat"));

	if (count($blogs) > 0) {
		foreach ($blogs as $blog) {
			echo '<img src="/images/img/act.png" alt="image" /> <a href="/blog/blog.php?act=view&amp;id='.$blog['blogs_id'].'">'.$blog['blogs_title'].'</a> ('.$blog['blogs_comments'].')<br />';
		}
	}
}

// ------------------------- Функция открытия файла ------------------------//
function fn_open($name, $mode, $method, $level) {
	if ($method == 2) {
		$name = "{$name}.bz2";
		return bzopen($name, $mode);
	} elseif ($method == 1) {
		$name = "{$name}.gz";
		return gzopen($name, "{$mode}b{$level}");
	} else {
		return fopen($name, "{$mode}b");
	}
}

// ------------------------- Функция записи в файл ------------------------//
function fn_write($fp, $str, $method) {
	if ($method == 2) {
		bzwrite($fp, $str);
	} elseif ($method == 1) {
		gzwrite($fp, $str);
	} else {
		fwrite($fp, $str);
	}
}

// ------------------------- Функция закрытия файла ------------------------//
function fn_close($fp, $method) {
	if ($method == 2) {
		bzclose($fp);
	} elseif ($method == 1) {
		gzclose($fp);
	} else {
		fflush($fp);
		fclose($fp);
	}
}

// ------------------ Функция пересчета сообщений и комментарий ---------------//
function restatement($mode) {
	switch ($mode) {
		case 'forum':
			DB::run() -> query("UPDATE `forums` SET `forums_topics`=(SELECT count(*) FROM `topics` WHERE `forums`.`forums_id`=`topics`.`topics_forums_id`);");
			DB::run() -> query("UPDATE `forums` SET `forums_posts`=(SELECT count(*) FROM `posts` WHERE `forums`.`forums_id`=`posts`.`posts_forums_id`);");
			DB::run() -> query("UPDATE `topics` SET `topics_posts`=(SELECT count(*) FROM `posts` WHERE `topics`.`topics_id`=`posts`.`posts_topics_id`);");
			break;

		case 'blog':
			DB::run() -> query("UPDATE `catsblog` SET `cats_count`=(SELECT count(*) FROM `blogs` WHERE `catsblog`.`cats_id`=`blogs`.`blogs_cats_id`);");
			DB::run() -> query("UPDATE `blogs` SET `blogs_comments`=(SELECT count(*) FROM `commblog` WHERE `blogs`.`blogs_id`=`commblog`.`commblog_blog`);");
			break;

		case 'load':
			DB::run() -> query("UPDATE `cats` SET `cats_count`=(SELECT count(*) FROM `downs` WHERE `cats`.`cats_id`=`downs`.`downs_cats_id` AND `downs_active`=?);", array(1));
			DB::run() -> query("UPDATE `downs` SET `downs_comments`=(SELECT count(*) FROM `commload` WHERE `downs`.`downs_id`=`commload`.`commload_down`);");
			break;

		case 'news':
			DB::run() -> query("UPDATE `news` SET `news_comments`=(SELECT count(*) FROM `commnews` WHERE `news`.`news_id`=`commnews`.`commnews_news_id`);");
			break;

		case 'gallery':
			DB::run() -> query("UPDATE `photo` SET `photo_comments`=(SELECT count(*) FROM `commphoto` WHERE `photo`.`photo_id`=`commphoto`.`commphoto_gid`);");
			break;
	}
	return true;
}

// ------------------------ Функция записи в файл ------------------------//
function write_files($filename, $text, $clear = 0, $chmod = 0) {

	if (empty($clear)) {
		file_put_contents($filename, $text, FILE_APPEND | LOCK_EX);
	} else {
		file_put_contents($filename, $text, LOCK_EX);
	}

	if (!empty($chmod)) {
		@chmod($filename, $chmod);
	}
}

// ------------------- Функция подсчета строк в файле--------------------//
function counter_string($files) {
	$count_lines = 0;
	if (file_exists($files)) {
		$lines = file($files);
		$count_lines = count($lines);
	}
	return $count_lines;
}

// ------------------- Функция поиска текста в массиве --------------------//
function strsearch($str, $arr) {
	foreach ($arr as $search) {
		if (stristr($str, $search)) {
			return true;
		}
	}

	return false;
}

// --------------- Функция определения последней страницы -----------------//
function last_page($total, $posts) {
	return floor(($total - 1) / $posts) * $posts;
}

// ------------- Функция кэширования пользовательских функций -------------//
function cache_functions($cache=10800) {
	if (@filemtime(DATADIR.'/temp/functions.dat') < time()-$cache) {
		$files = array_diff(scandir(BASEDIR.'/includes/functions'), array('.', '..', '.htaccess'));

		file_put_contents(DATADIR.'/temp/functions.dat', serialize($files), LOCK_EX);
	}

	return unserialize(file_get_contents(DATADIR.'/temp/functions.dat'));
}

// ------------- Функция кэширования админских ссылок -------------//
function cache_admin_links($cache=10800) {
	if (@filemtime(DATADIR.'/temp/adminlinks.dat') < time()-$cache) {
		$files = array_diff(scandir(BASEDIR.'/admin/links'), array('.', '..', '.htaccess'));

		$links = array();

		foreach ($files as $file){
			$access = intval(preg_replace('/[^\d]+/', '', $file));
			$links[$access][] = $file;
		}

		file_put_contents(DATADIR.'/temp/adminlinks.dat', serialize($links), LOCK_EX);
	}

	return unserialize(file_get_contents(DATADIR.'/temp/adminlinks.dat'));
}

// ------------- Функция вывода админских ссылок -------------//
function show_admin_links($level = 0) {

	$links = cache_admin_links();

	if (!empty($links[$level])){
		foreach ($links[$level] as $link){
			if (file_exists(BASEDIR.'/admin/links/'.$link)){
				include_once(BASEDIR.'/admin/links/'.$link);
			}
		}
	}
}

// ------------- Функция кэширования уменьшенных изображений -------------//
function resize_image($dir, $name, $size, $alt="") {

	if (file_exists(BASEDIR.'/'.$dir.$name)){

		$sign = (!empty($alt)) ? $alt : $name;
		$prename = str_replace('/', '_' ,$dir);
		$imgsize = getimagesize(BASEDIR.'/'.$dir.$name);

		if ($imgsize[0] <= $size && $imgsize[1] <= $size) {
			return '<img src="/'.$dir.$name.'" alt="'.$sign.'" />';
		}

		if (!file_exists(BASEDIR.'/upload/thumbnail/'.$prename.$name) || filesize(BASEDIR.'/upload/thumbnail/'.$prename.$name) < 18) {

			$handle = new upload(BASEDIR.'/'.$dir.$name);

			if ($handle -> uploaded) {
				$handle -> file_name_body_pre = $prename;
				$handle -> image_resize = true;
				$handle -> image_ratio = true;
				$handle -> image_ratio_no_zoom_in = true;
				$handle -> image_y = $size;
				$handle -> image_x = $size;
				$handle -> file_overwrite = true;
				$handle -> process(BASEDIR.'/upload/thumbnail/');
			}
		}
		return '<img src="/upload/thumbnail/'.$prename.$name.'" alt="'.$sign.'" />';
	}
	$param = ($size<100) ? ' height="'.$size.'" width="'.$size.'"' : '';
	return '<img src="/images/img/photo.jpg" alt="nophoto"'.$param.' />';
}

// ------------- Функция переадресации -------------//
function redirect($url, $permanent = false){

	if ($permanent){
		header('HTTP/1.1 301 Moved Permanently');
	}

	exit(header('Location: '.$url));
}

// ------------- Функция вывода ссылки на анкету -------------//
function profile($user_id){
	global $config;

	if (!empty($user_id)){
		return '<a href="/pages/user.php?id='.$user_id.'">'.$user_id.'</a>';
	}
	return $config['guestsuser'];
}

// ------------- Функция вывода рейтинга -------------//
function format_num($num = 0){

	if ($num > 0) {
		return '<span style="color:#00aa00">+'.$num.'</span>';
	} elseif ($num < 0) {
		return '<span style="color:#ff0000">'.$num.'</span>';
	} else {
		return 0;
   }
}

// ------------- Прогресс бар -------------//
function progress_bar($percent, $title = ''){

	if ($title === ''){
		$title = $percent.'%';
	}

	echo '<div style="background:#eee; height:10px; width:200px; padding:0; margin:5px 0; border: 1px solid #ccc; border-radius:5px;">
		<span style="float:right; color:#000; font-size:85%; line-height:10px; margin-right:5px;">'.$title.'</span>
		<div style="background:#00dd00; height:10px; width:'.$percent.'%; border-radius:4px;"></div>
	</div>';
}

// ------------- Добавление пользовательского файла в ZIP-архив -------------//
function copyright_archive($filename){

	$readme_file = BASEDIR.'/vendor/Visavi_Readme.txt';
	$ext = getExtension($filename);

	if ($ext == 'zip' && file_exists($readme_file)){
		$archive = new PclZip($filename);
		$archive->add($readme_file, PCLZIP_OPT_REMOVE_PATH, dirname($readme_file));

		return true;
	}
}

// ------------- Функция загрузки и обработки изображений -------------//
function upload_image($file, $new_name = false){

	global $config;

	$handle = new upload($file);

	if ($handle -> uploaded) {
		$handle -> image_resize = true;
		$handle -> image_ratio = true;
		$handle -> image_ratio_no_zoom_in = true;
		$handle -> image_y = $config['screensize'];
		$handle -> image_x = $config['screensize'];
		$handle -> file_overwrite = true;

		if ($handle->file_src_name_ext == 'png'){
			$handle->image_convert = 'jpg';
		}
		if (!empty($new_name)) {
			$handle -> file_new_name_body = $new_name;
		}
		if (!empty($config['copyfoto'])) {
			$handle -> image_watermark = BASEDIR.'/images/img/watermark.png';
			$handle -> image_watermark_position = 'BR';
		}

		return $handle;
	}

	return false;
}

// ------------- Функция загрузки и обработки изображений -------------//
function upload_image2($file, $weight, $size, $new_name = false){

	global $config;

	$handle = new FileUpload($file);

	if ($handle->uploaded) {
		$handle -> image_resize = true;
		$handle -> image_ratio = true;
		$handle -> image_ratio_no_zoom_in = true;
		$handle -> image_y = $config['screensize'];
		$handle -> image_x = $config['screensize'];
		$handle -> file_overwrite = true;

		if ($handle->file_src_name_ext == 'png' ||
			$handle->file_src_name_ext == 'bmp'){
			$handle->image_convert = 'jpg';
		}
		if (!empty($new_name)) {
			$handle -> file_new_name_body = $new_name;
		}
		if (!empty($config['copyfoto'])) {
			$handle -> image_watermark = BASEDIR.'/images/img/watermark.png';
			$handle -> image_watermark_position = 'BR';
		}

		$handle -> ext_check = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
		$handle -> file_max_size = $weight;  // byte
		$handle -> image_max_width = $size;  // px
		$handle -> image_max_height = $size; // px
		$handle -> image_min_width = 32;     // px
		$handle -> image_min_height = 32;    // px

		return $handle;
	}

	return false;
}

// ------------- Функция определения расширения файла -------------//
function getExtension($filename){
	return strtolower(substr(strrchr($filename, '.'), 1));
}

// ---------------- Функция переименовывания файла ----------------//
function rename_file($filename, $newname){
	$ext = getExtension($filename);
	return $newname.'.'.$ext;
}

// ----- Функция определения входит ли пользователь в контакты -----//
function is_friend($login, $contact){

/*	if (check_user($contact)) {
		$check_contact = DB::run() -> queryFetch("SELECT * FROM `contact` WHERE `contact_user`=? AND `contact_name`=? LIMIT 1;", array($login, $contact));

		if (!empty($check_contact)){
			return true;
		}
	}
	return false;*/
}

// ----- Функция определения приватности у пользователя -----//
function user_privacy($login){
	$privacy = DB::run() -> querySingle("SELECT `users_privacy` FROM `users` WHERE `users_login`=? LIMIT 1;", array($login));
	return ($privacy) ? true : false;
}

// ----- Функция рекурсивного удаления директории -----//
function removeDir($dir){
	if (file_exists($dir)){
		if ($files = glob($dir.'/*')) {
			foreach($files as $file) {
				is_dir($file) ? removeDir($file) : unlink($file);
			}
		}
		rmdir($dir);
	}
}

// ----- Функция отправки приватного сообщения -----//
function send_private($user_id, $author_id, $text, $time = SITETIME){
	if (check_user($user_id)) {

		$inbox = new Inbox;
		$inbox->user_id = $user_id;
		$inbox->author_id = $author_id;
		$inbox->text = $text;
		$inbox->save();

		$user = User::find_by_id($user_id);
		$user->newprivat = $user->newprivat + 1;
		$user->save();

		//DB::run() -> query("INSERT INTO `inbox` (`inbox_user`, `inbox_author`, `inbox_text`, `inbox_time`) VALUES (?, ?, ?, ?);",
		//array($user_id, $sender, $text, $time));

		//DB::run() -> query("UPDATE `users` SET `users_newprivat`=`users_newprivat`+1 WHERE `users_login`=? LIMIT 1;", array($user_id));
		return true;
	}
	return false;
}

// ----- Функция подготовки приватного сообщения -----//
function text_private($id, $replace = array()){

	$notice = Notice::find_by_id($id);

	if ($notice) {

		foreach ($replace as $key=>$val){
			$message = str_replace($key, $val, $notice->text);
		}

	} else {
		$message = 'Отсутствует текст сообщения!';
	}
	return $message;
}

// ------------ Функция записи flash уведомлений -----------//
function notice($message, $color = false){

	if (!empty($color)){
		$message = '<span style="color:'.$color.'">'.$message.'</span>';
	}

	$_SESSION['note'] = (isset($_SESSION['note'])) ? $message.'<br />'.$_SESSION['note'] : $message;
}

// ------------ Функция статистики производительности -----------//
function perfomance (){
	global $config;

	if (is_admin() && !empty($config['performance'])){
		App::render ('includes/perfomance');
	}
}

// ------------ Подготовка массивов -----------//
function prepare_array($array, $key = 'show') {
	$prepared_array = array();

	if (is_array($array) && count($array)) {
		foreach ($array as &$el) {
			if (isset($el[$key]) && $el[$key] == false) continue;
				$prepared_array[] = $el;
		}
		return $prepared_array;
	}
	return false;
}

// ------------ Определение IP -----------//
function real_ip()
{
	$header_checks = array(
		'HTTP_CLIENT_IP',
		'HTTP_PRAGMA',
		'HTTP_XONNECTION',
		'HTTP_CACHE_INFO',
		'HTTP_XPROXY',
		'HTTP_PROXY',
		'HTTP_PROXY_CONNECTION',
		'HTTP_VIA',
		'HTTP_X_COMING_FROM',
		'HTTP_COMING_FROM',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'ZHTTP_CACHE_CONTROL',
		'REMOTE_ADDR'
	);

	foreach ($header_checks as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				$ip = trim($ip);

				//filter the ip with filter functions
				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
					return $ip;
				}
			}
		}
	}
	return '127.0.0.1';
}

// ------------- Кеширование пользовательских функций -------------//
$functions = cache_functions();

if (!empty($functions)) {
	foreach ($functions as $file) {
		if (file_exists(BASEDIR.'/includes/functions/'.$file)) {
			include_once (BASEDIR.'/includes/functions/'.$file);
		}
	}
}
