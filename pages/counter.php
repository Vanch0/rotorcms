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

$currhour = date("G", SITETIME);
$currday = date("j", SITETIME);

$act = (isset($_GET['act'])) ? check($_GET['act']) : 'index';

show_title('Количество посещений');

switch ($act):
############################################################################################
##                                   Вывод статистики                                     ##
############################################################################################
	case 'index':

		$online = stats_online();
		$count = stats_counter();

		echo 'Всего посетителей на сайте: <b>'.$online[1].'</b><br />';
		echo 'Всего авторизованных: <b>'.$online[0].'</b><br />';
		echo 'Всего гостей: <b>'.($online[1] - $online[0]).'</b><br /><br />';

		echo 'Хостов сегодня: <b>'.$count->dayhosts.'</b><br />';
		echo 'Хитов сегодня: <b>'.$count->dayhits.'</b><br />';
		echo 'Всего хостов: <b>'.$count->allhosts.'</b><br />';
		echo 'Всего хитов: <b>'.$count->allhits.'</b><br /><br />';

		echo 'Хостов за текущий час: <b>'.$count->hosts24.'</b><br />';
		echo 'Хитов за текущий час: <b>'.$count->hits24.'</b><br /><br />';

		//$counts24 = DB::run() -> queryFetch("SELECT SUM(`count_hosts`) AS `hosts`, SUM(`count_hits`) AS `hits` FROM `counter24`;");
		$counts24 = Counter24::find(array('select' => 'SUM(hosts) as hosts, SUM(hits) as hits'));

		echo 'Хостов за 24 часа: <b>'.($counts24->hosts + $count->hosts24).'</b><br />';
		echo 'Хитов за 24 часа: <b>'.($counts24->hits + $count->hits24).'</b><br /><br />';

		//$counts31 = DB::run() -> queryFetch("SELECT SUM(`count_hosts`) AS `hosts`, SUM(`count_hits`) AS `hits` FROM `counter31`;");
		$counts31 = Counter31::find(array('select' => 'SUM(hosts) as hosts, SUM(hits) as hits'));

		echo 'Хостов за месяц: <b>'.($counts31->hosts + $count->dayhosts).'</b><br />';
		echo 'Хитов за месяц: <b>'.($counts31->hits + $count->dayhits).'</b><br /><br />';

		echo 'Динамика за неделю<br />';
		include_once(BASEDIR.'/includes/counter7.php');

		echo 'Динамика за сутки<br />';
		include_once(BASEDIR.'/includes/counter24.php');

		echo 'Динамика за месяц<br />';
		include_once(BASEDIR.'/includes/counter31.php');

		echo '<a href="counter.php?act=count24">Статистика по часам</a><br />';
		echo '<a href="counter.php?act=count31">Статистика по дням </a><br /><br />';
	break;

	############################################################################################
	##                                Статистика за 24 часа                                   ##
	############################################################################################
	case 'count24':
		show_title('Статистика по часам');

		echo 'Динамика за сутки<br />';
		include_once(BASEDIR.'/includes/counter24.php');

		if ($currhour > 0) {
			$hour = floor((gmmktime(date("H"), 0, 0, date("m"), date("d"), date("Y")) - gmmktime((date("Z") / 3600), 0, 0, 1, 1, 1970)) / 3600);

			//$querycount = DB::run() -> query("SELECT * FROM `counter24` ORDER BY `count_hour` DESC;");
			//$counts = $querycount -> fetchAll();
			$counts = Counter24::all(array('order' => 'hour DESC'));

			$arrhits = array();
			$arrhosts = array();
			$hits_data = array();
			$host_data = array();

			foreach ($counts as $val) {
				$arrhits[$val->hour] = $val->hits;
				$arrhosts[$val->hour] = $val->hosts;
			}

			for ($i = 0, $tekhour = $hour; $i < 24; $tekhour -= 1, $i++) {
				if (isset($arrhits[$tekhour])) {
					$hits_data[$tekhour] = $arrhits[$tekhour];
				} else {
					$hits_data[$tekhour] = 0;
				}

				if (isset($arrhosts[$tekhour])) {
					$host_data[$tekhour] = $arrhosts[$tekhour];
				} else {
					$host_data[$tekhour] = 0;
				}
			}

			$hits_data = array_reverse($hits_data, true);
			$host_data = array_reverse($host_data, true);

			echo '<b>Время — Хосты / Хиты</b><br />';
			for ($i = 0, $tekhour = $hour; $i < $currhour; $tekhour -= 1, $i++) {
				echo date_fixed(floor(($tekhour-1) * 3600), 'H:i').' - '.date_fixed(floor($tekhour * 3600), 'H:i').' — <b>'.$host_data[$tekhour].'</b> / <b>'.$hits_data[$tekhour].'</b><br />';
			}

			echo '<br />';
		} else {
			show_error('Статистика за текущие сутки еще не обновилась!');
		}

		render('includes/back', array('link' => 'counter.php', 'title' => 'Вернуться'));
	break;

	############################################################################################
	##                                  Статистика за месяц                                   ##
	############################################################################################
	case 'count31':
		show_title('Статистика по дням');

		echo 'Динамика за месяц<br />';
		include_once(BASEDIR.'/includes/counter31.php');

		if ($currday > 1) {
			$day = floor((gmmktime(0, 0, 0, date("m"), date("d"), date("Y")) - gmmktime(0, 0, 0, 1, 1, 1970)) / 86400);

			//$querycount = DB::run() -> query("SELECT * FROM `counter31` ORDER BY `count_days` DESC;");
			//$counts = $querycount -> fetchAll();
			$counts = Counter31::all(array('order' => 'day DESC'));

			$arrhits = array();
			$arrhosts = array();
			$hits_data = array();
			$host_data = array();

			foreach ($counts as $val) {
				$arrhits[$val->day] = $val->hits;
				$arrhosts[$val->day] = $val->hosts;
			}

			for ($i = 0, $tekday = $day; $i < 31; $tekday -= 1, $i++) {
				if (isset($arrhits[$tekday])) {
					$hits_data[$tekday] = $arrhits[$tekday];
				} else {
					$hits_data[$tekday] = 0;
				}

				if (isset($arrhosts[$tekday])) {
					$host_data[$tekday] = $arrhosts[$tekday];
				} else {
					$host_data[$tekday] = 0;
				}
			}

			$hits_data = array_reverse($hits_data, true);
			$host_data = array_reverse($host_data, true);

			echo '<b>Дата — Хосты / Хиты</b><br />';
			for ($i = 1, $tekday = $day; $i < $currday; $tekday -= 1, $i++) {
				echo date_fixed(floor(($tekday-1) * 86400), 'd.m').' - '.date_fixed(floor($tekday * 86400), 'd.m').' — <b>'.$host_data[$tekday].'</b> / <b>'.$hits_data[$tekday].'</b><br />';
			}

			echo '<br />';
		} else {
			show_error('Статистика за текущий месяц еще не обновилась!');
		}

		render('includes/back', array('link' => 'counter.php', 'title' => 'Вернуться'));
	break;

default:
	redirect("counter.php");
endswitch;

include_once ('../themes/footer.php');
?>
