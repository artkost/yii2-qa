<?php

use yii\db\Schema;
use artkost\qa\models\Tag;

class m140314_120505_create_qa_tag_table extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(Tag::tableName(), [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'frequency' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ]);
    }

    public function down()
    {
        $this->dropTable(Tag::tableName());
    }
}
