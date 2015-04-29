<?php

use artkost\qa\models\Answer;
use yii\db\Schema;
use yii\db\Migration;

class m150429_211628_alter_qa_answer_table_column_is_correct extends Migration
{
    public function up()
    {
        $this->addColumn(Answer::tableName(), 'is_correct', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn(Answer::tableName(), 'is_correct');
    }
}
