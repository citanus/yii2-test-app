<?php

use yii\db\Schema;
use yii\db\Migration;


/**
 * Class m141002_165820_create_messenger_tables
 *
 * Create/drop tables for users a their messages
 */
class m141002_165820_create_messenger_tables extends Migration
{
    public function safeUp()
    {
	    $this->createTable('users', [
		    'id' => 'pk',
		    'name' => Schema::TYPE_STRING . ' NOT NULL',
		    'email' => Schema::TYPE_STRING. ' NOT NULL',
		    'password_hash' => Schema::TYPE_STRING. ' NOT NULL',
		    'auth_key' => Schema::TYPE_STRING. ' NOT NULL',
		    'status' => Schema::TYPE_STRING. ' NOT NULL',
	    ]);
	    $this->createIndex('unique_email_as_username', 'users', 'email', true);

	    $this->createTable('messages', [
		    'id' => 'pk',
		    'date_posted' => Schema::TYPE_DATETIME. ' NOT NULL',
		    'date_seen' => Schema::TYPE_DATETIME ,
		    'author_id' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'recipient_id' => Schema::TYPE_INTEGER . ' NOT NULL',
		    'message' => Schema::TYPE_TEXT. ' NOT NULL',
	    ]);
    }

    public function safeDown()
    {
        $this->dropTable('messages');

	    $this->dropIndex('unique_email_as_username', 'users');
	    $this->dropTable('users');
    }
}
