<?php

use Phinx\Migration\AbstractMigration;

class CreateNewsTable extends AbstractMigration
{
	/**
	 * Change Method.
	 */
	public function change()
	{
		$table = $this->table('news');
		$table->addColumn('user_id', 'integer')
			->addColumn('title', 'string', ['limit' => 50])
			->addColumn('text', 'text', ['null' => true])
			->addColumn('image', 'string', ['limit' => 50, 'null' => true])
			->addColumn('updated_at', 'timestamp')
			->addColumn('created_at', 'timestamp', ['null' => true])
			->addIndex('created_at')
			->create();
	}
}
