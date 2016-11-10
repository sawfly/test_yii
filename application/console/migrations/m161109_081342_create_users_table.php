<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m161109_081342_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'password' => $this->string(),
            'auth_key' => $this->string(),
            'email' => $this->string(),
            'status_id' => $this->smallInteger()->unsigned()->defaultValue(0),
            'permission_id' => $this->smallInteger()->unsigned()->defaultValue(0)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
