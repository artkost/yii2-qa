<?php

use yii\db\Schema;
use artkost\qa\models\Question;

class m140314_120159_create_qa_question_table extends \yii\db\Migration
{
	public function up()
	{
        $this->createTable(Question::tableName(), [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING . '(100) NOT NULL',
            'alias' => Schema::TYPE_STRING . '(100) NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'tags' => Schema::TYPE_TEXT,
            'answers' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'views' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'votes' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT ' . Question::STATUS_PUBLISHED,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
	}

	public function down()
	{
		$this->dropTable(Question::tableName());
	}
}
