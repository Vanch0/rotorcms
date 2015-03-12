<?php
$act = isset(App::router('params')['action']) ? check(App::router('params')['action']) : 'index';
$page = !empty(App::router('params')['page']) ? intval(App::router('params')['page']) : 1;


//show_title('Гостевая книга', 'Общение без ограничений');

switch ($act):
############################################################################################
##                                    Главная страница                                    ##
############################################################################################
case 'index':

	$total = Guest::count();

	if ($total > 0 && ($page * Setting::get('bookpost')) >= $total) {
		$page = ceil($total / Setting::get('bookpost'));
	}

	//$config['newtitle'] = 'Гостевая книга (Стр. '.$page.')';
	$offset = intval(($page * Setting::get('bookpost')) - Setting::get('bookpost'));

	$posts = Guest::all(array(
		'offset' => $offset,
		'limit' => Setting::get('bookpost'),
		'order' => 'created_at desc',
		'include' => array('user'),
	));

	App::view('guestbook/index', compact('posts', 'page', 'total'));

break;

############################################################################################
##                                    Добавление сообщения                                ##
############################################################################################
case 'create':

	$msg = isset($_POST['msg']) ? check($_POST['msg']) : '';
	$token = !empty($_POST['token']) ? check($_POST['token']) : 0;

	if (is_user()) {
		//if (is_flood($log)) {

			$user = User::find_by_id($current_user->id);
			$user->allguest = $user->allguest + 1;
			$user->point = $user->point + 1;
			$user->money = $user->money + 20;
			$user->save();

			$post = new Guest;
			$post->token = $token;
			$post->user_id = $current_user->id;
			$post->text = $msg;
			$post->ip = $ip;
			$post->brow = $brow;

			if ($post->save()) {

				// Удаляем старые сообщения
				//$posts = Guest::all(array('offset' => $config['maxpostbook'], 'limit' => 10, 'order' => 'created_at desc'));
				//$delete = ActiveRecord\collect($posts, 'id');
				//Guest::table()->delete(array('id' => array($delete)));

				notice('Сообщение успешно добавлено!');
				redirect('/guestbook');
			} else {
				show_error($post->getErrors());
			}
		//} else {
		//	show_error('Антифлуд! Разрешается отправлять сообщения раз в '.flood_period().' секунд!');
		//}

		############################################################################################
		##                                   Добавление для гостей                                ##
		############################################################################################
	} elseif ($config['bookadds'] == 1) {
		$provkod = (isset($_POST['provkod'])) ? check(strtolower($_POST['provkod'])) : '';

		if ($provkod == $_SESSION['protect']) {
			//if (is_flood($log)) {

				$post = new Guest;
				$post->user_id = 0;
				$post->text = $msg;
				$post->ip = $ip;
				$post->brow = $brow;

				if ($post->save()) {
					notice('Сообщение успешно добавлено!');
					redirect("/guestbook");
				} else {
					show_error($post->getErrors());
				}
			//} else {
			//	show_error('Антифлуд! Разрешается отправлять сообщения раз в '.flood_period().' секунд!');
			//}
		} else {
			show_error('Ошибка! Проверочное число не совпало с данными на картинке!');
		}
	} else {
		show_login('Вы не авторизованы, чтобы добавить сообщение, необходимо');
	}

	App::render('includes/back', array('link' => '/guestbook', 'title' => 'Вернуться'));
break;

############################################################################################
##                                   Подготовка к редактированию                          ##
############################################################################################
case 'edit':
	show_title('Редактирование сообщения');

	$id = isset($current_router['params']['id']) ? check($current_router['params']['id']) : 0;

	if (is_user()) {
		$post = Guest::find_by_id_and_user_id($id, $current_user->id);

		if ($post) {
			if ($post->created_at->getTimestamp() > time() - 600) {

				$post->text = $post->text;

				App::render('book/edit', compact('post', 'id', 'start'));

			} else {
				show_error('Ошибка! Редактирование невозможно, прошло более 10 минут!!');
			}
		} else {
			show_error('Ошибка! Сообщение удалено или вы не автор этого сообщения!');
		}
	} else {
		show_login('Вы не авторизованы, чтобы редактировать сообщения, необходимо');
	}

	App::render('includes/back', array('link' => '/guestbook', 'title' => 'Вернуться'));
break;

############################################################################################
##                                    Редактирование сообщения                            ##
############################################################################################
case 'update':

	$msg = isset($_POST['msg']) ? check($_POST['msg']) : '';
	$id = isset($_POST['id']) ? check($_POST['id']) : 0;
	$token = !empty($_POST['token']) ? check($_POST['token']) : 0;

	if (is_user()) {

		$post = Guest::find_by_id_and_user_id($id, $current_user->id);

		if ($post) {
			if ($post->created_at->getTimestamp() > time() - 600) {

				$post->token = $token;
				$post->text = $msg;
				if ($post->save()) {
					notice('Сообщение успешно отредактировано!');
					redirect("/guestbook");
				} else {
					show_error($post->getErrors());
				}

			} else {
				show_error('Ошибка! Редактирование невозможно, прошло более 10 минут!!');
			}
		} else {
			show_error('Ошибка! Сообщение удалено или вы не автор этого сообщения!');
		}
	} else {
		show_login('Вы не авторизованы, чтобы редактировать сообщения, необходимо');
	}

	App::render('includes/back', array('link' => '/guestbook/'.$id.'/edit', 'title' => 'Вернуться'));
	App::render('includes/back', array('link' => '/guestbook', 'title' => 'В гостевую', 'icon' => 'fa-arrow-circle-up'));
break;

default:
	redirect("/guestbook");
endswitch;
?>
