<?php
#---------------------------------------------#
#      ********* RotorCMS *********           #
#           Author  :  Vantuz                 #
#            Email  :  visavi.net@mail.ru     #
#             Site  :  http://visavi.net      #
#              ICQ  :  36-44-66               #
#            Skype  :  vantuzilla             #
#---------------------------------------------#
define('STARTTIME', microtime(1));
define('BASEDIR', dirname(__DIR__));
define('APP', BASEDIR.'/app');
define('HOME', BASEDIR.'/public');
define('STORAGE', APP.'/storage');
define('PCLZIP_TEMPORARY_DIR', STORAGE.'/temp/');
define('VERSION', '5.0.1');

/**
 * Автозагрузка классов
 */
require_once BASEDIR.'/vendor/autoload.php';

/**
 * Регистрация классов
 */
$aliases = [
	'Blade'       => 'Philo\Blade\Blade',
	'Carbon'      => 'Carbon\Carbon',
	'SimpleImage' => 'abeautifulsite\SimpleImage',
	'Utf8'        => 'Patchwork\Utf8',
	'Curl'        => 'Curl\Curl',
];
AliasLoader::getInstance($aliases)->register();

if (! env('APP_ENV')) {
	$dotenv = new Dotenv\Dotenv(BASEDIR);
	$dotenv->load();
}

if (env('APP_DEBUG')) {
	$whoops = new Whoops\Run;
	$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
	$whoops->pushHandler(function($exception, $inspector, $run) {
		$_SERVER = array_except($_SERVER, array_keys($_ENV));
		$_ENV = [];
	});
	$whoops->register();
}

/**
 * ActiveRecord initialize
 */
ActiveRecord\Config::initialize(function($cfg) {

	$cfg->set_model_directory(APP.'/models');
	$cfg->set_connections(array(
		'development' => env('DB_DRIVER').'://'.env('DB_USERNAME').':'.env('DB_PASSWORD').'@'.env('DB_HOST').'/'.env('DB_DATABASE').';charset=utf8'
	));

	//$cfg->set_cache('memcache://localhost', ['expire' => 60]);

	if (env('APP_DEBUG')) {
		$conf = ['append' => false, 'mode' => '0666', 'lineFormat' => '[%3$s] %4$s [%1$s]'];
		$logger = Log::singleton('file', STORAGE.'/temp/queries.dat', null, $conf);

		$cfg->set_logger($logger);
		$cfg->set_logging(true);
	}
});

ActiveRecord\Connection::$datetime_format = 'Y-m-d H:i:s';
