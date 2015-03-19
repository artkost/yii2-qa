<?php

use yii\db\Schema;
use artkost\qa\models\Favorite;

class m140512_191636_create_qa_favorite_table extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(Favorite::tableName(), [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'question_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_ip' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable(Favorite::tableName());
    }
}
