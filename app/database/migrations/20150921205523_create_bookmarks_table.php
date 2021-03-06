<?php

use Phinx\Migration\AbstractMigration;

class CreateBookmarksTable extends AbstractMigration
{
	/**
	 * Change Method.
	 */
	public function change()
	{
		$table = $this->table('bookmarks');
		$table->addColumn('topic_id', 'integer')
			->addColumn('user_id', 'integer')
			->addColumn('posts', 'integer')
			->addColumn('updated_at', 'timestamp')
			->addColumn('created_at', 'timestamp', ['null' => true])
			->addIndex('topic_id')
			->addIndex('user_id')
			->create();
	}
}
