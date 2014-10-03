<?php

use yii\db\Schema;
use yii\db\Migration;

class m141002_204322_create_online_status_table extends Migration
{
    public function safeUp()
    {
	    $this->createTable('users_online', [
		    'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'date_last_online' => Schema::TYPE_DATETIME. ' NOT NULL',
	    ]);
	    $this->createIndex('unique_user_online', 'users_online', 'user_id', true);
    }

    public function down()
    {
	    $this->dropTable('users_online');
    }
}
