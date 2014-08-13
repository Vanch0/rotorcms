<a href="/pages/rules.php">Правила</a> /
<a href="/pages/smiles.php">Смайлы</a> /
<a href="/pages/tags.php">Теги</a>

<?php if (is_admin()):?>
	/ <a href="/admin/book.php?start=<?= $start ?>">Управление</a>
<?php endif;?>
<hr />


<?php if ($total > 0): ?>
	<?php foreach ($posts as $data): ?>

		<div id="post">
		<div class="b">
			<div class="img"><?= user_avatars($data->login) ?></div>
			<?php if ($data->login == $config['guestsuser']): ?>
				<b><?= $data->login ?></b> <small>(<?= $data->created_at ?>)</small>
			<?php else: ?>
				<b><?= profile($data->login) ?></b> <small>(<?= $data->created_at ?>)</small><br />
				<?= user_title($data->login) ?> <?= user_online($data->login) ?>
			<?php endif; ?>
		</div>

		<?php if (!empty($user) && $user->id != $data->login): ?>
			<div class="right">
			<a href="#" onclick="return reply('<?= $data->login ?>')">Отв</a> /

			<noindex><a href="index.php?act=spam&amp;id=<?= $data->id ?>&amp;start=<?= $start ?>&amp;token=<?= $_SESSION['token'] ?>" onclick="return confirm('Вы подтверждаете факт спама?')" rel="nofollow">Спам</a></noindex></div>
		<?php endif; ?>

		<?php if ($user->id == $data->login && $data->created_at->getTimestamp() + 600 > SITETIME): ?>
			<div class="right"><a href="index.php?act=edit&amp;id=<?= $data->id ?>&amp;start=<?= $start ?>">Редактировать</a></div>
		<?php endif; ?>

		<div>
			<span class="message"><?= bb_code($data->text) ?></span><br />

			<?php if (!empty($data->edit_user_id)): ?>
				<img src="/images/img/exclamation_small.gif" alt="image" /> <small>Отредактировано: <?= $data->login ?> (<?= $data->updated_at->format('long') ?>)</small><br />
			<?php endif; ?>

			<?php if (is_admin()): ?>
				<span class="data">(<?= $data->brow ?>, <?= $data->ip ?>)</span>
			<?php endif; ?>

			<?php if (!empty($data->reply)): ?>
				<br /><span style="color:#ff0000">Ответ: <?= $data->reply ?></span>
			<?php endif; ?>

		</div>
		</div>
	<?php endforeach; ?>

	<?php page_strnavigation('index.php?', $config['bookpost'], $start, $total); ?>

<?php else: ?>
	<?php show_error('Сообщений нет, будь первым!'); ?>
<?php endif; ?>


<?php if (is_user()): ?>
	<div class="form">
		<form action="index.php?act=add&amp;token=<?= $_SESSION['token'] ?>" method="post">

		<textarea id="markItUp" cols="25" rows="5" name="msg"></textarea><br />
		<input type="submit" value="Написать" /></form>
	</div><br />

<?php elseif ($config['bookadds'] == 1): ?>

	<div class="form">
		<form action="index.php?act=add&amp;token=<?= $_SESSION['token'] ?>" method="post">
		Сообщение:<br />
		<textarea cols="25" rows="5" name="msg"></textarea><br />

		Проверочный код:<br />
		<img src="/gallery/protect.php" alt="" /><br />
		<input name="provkod" size="6" maxlength="6" /><br />

		<input type="submit" value="Написать" /></form>
	</div><br />

<?php else: ?>
	<?php show_login('Вы не авторизованы, чтобы добавить сообщение, необходимо'); ?>
<?php endif; ?>

