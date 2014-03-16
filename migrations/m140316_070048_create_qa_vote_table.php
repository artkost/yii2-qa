<?php

use yii\db\Schema;
use artkost\qa\models\Vote;

class m140316_070048_create_qa_vote_table extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(Vote::tableName(), [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'entity_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'entity' => "ENUM('question', 'answer') NOT NULL",
            'vote' => 'TINYINT NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'created_ip' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable(Vote::tableName());
    }
}
