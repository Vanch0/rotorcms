<?php if (isset($_SESSION['note'])): ?>

	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<?= $_SESSION['note'] ?>
	</div>

<?php endif; ?>

<?php if (is_user()): ?>
	<?php if (!empty($udata['users_newprivat'])): ?>
		<?php if (!strsearch($php_self, array('/pages/ban.php', '/pages/key.php', '/pages/private.php', '/pages/rules.php', '/pages/closed.php'))): ?>
			<img src="/images/img/new_mail.gif" alt="image" /> <b><a href="/pages/private.php"><span style="color:#ff0000">Приватное сообщение! (<?=$udata['users_newprivat']?>)</span></a></b><br />
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
