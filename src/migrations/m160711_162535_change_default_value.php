<?php

use yii\db\Migration;

class m160711_162535_change_default_value extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%eav_attribute}}', 'description', $this->string(255) . " DEFAULT ''");
    }

    public function safeDown()
    {
        $this->alterColumn('{{%eav_attribute}}', 'description', $this->string(255) . " DEFAULT 'NULL'");

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}