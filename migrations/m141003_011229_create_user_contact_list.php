<?php

use yii\db\Schema;
use yii\db\Migration;

class m141003_011229_create_user_contact_list extends Migration
{
    public function safeUp()
    {
	    $this->createTable('user_contact_list', [
		    'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'contact_id' => Schema::TYPE_INTEGER . ' NOT NULL',
	    ]);
	    $this->createIndex('unique_contact_id_peruser_id', 'user_contact_list', ['user_id', 'contact_id'], true);
    }

    public function safeDown()
    {
        $this->dropTable('user_contact_list');
    }
}
