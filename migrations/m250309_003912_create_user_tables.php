<?php

use yii\db\Migration;

class m250309_003912_create_user_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->null()->unique(),
            'email' => $this->string()->null()->unique(),
            'phone' => $this->string(15)->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull()->defaultValue(null),
            'full_name' => $this->string()->null(),
            'birth_date' => $this->date()->null(),
            'role_id' => $this->integer()->notNull(),
            'address_id' => $this->integer()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);


        $this->addForeignKey('fk_users_role', 'users', 'role_id', 'roles', 'id', 'CASCADE');
        $this->addForeignKey('fk_users_address', 'users', 'address_id', 'addresses', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
     $this->dropTable('users');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_003912_create_user_tables cannot be reverted.\n";

        return false;
    }
    */
}
