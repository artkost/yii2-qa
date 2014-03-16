<?php

use yii\db\Schema;
use artkost\qa\models\Answer;

class m140314_120441_create_qa_answer_table extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(Answer::tableName(), [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'question_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'content' => 'TEXT NOT NULL',
            'votes' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'status' => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT ' . Answer::STATUS_PUBLISHED,
            'created_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable(Answer::tableName());
    }
}
