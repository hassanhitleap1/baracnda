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
            'address_id' => $this->integer()->null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);


       
        $this->addForeignKey('fk_users_address', 'users', 'address_id', 'addresses', 'id', 'CASCADE');

        $data = [
            [
            'username' =>'0799263494',
            'email' => '0799263494',
            'phone' => '0799263494',
            'password_hash' => Yii::$app->security->generatePasswordHash("0799263494"),              
            'auth_key' => Yii::$app->security->generateRandomString(),
            'full_name' => "superAdmin",
            'birth_date' => null,
            'address_id' => null,
            'status' => 10
 
            ],
            [
                'username' =>'0799263492',
                'email' => '0799263492',
                'phone' => '0799263492',
                'password_hash' => Yii::$app->security->generatePasswordHash("0799263492"),              
                'auth_key' => Yii::$app->security->generateRandomString(),
                'full_name' => "Admin",
                'birth_date' => null,
                'address_id' => null,
                'status' => 10
     
            ],
            [
                'username' =>'0799263493',
                'email' => '0799263493',
                'phone' => '0799263493',
                'password_hash' => Yii::$app->security->generatePasswordHash("0799263493"),              
                'auth_key' => Yii::$app->security->generateRandomString(),
                'full_name' => "Saller",
                'birth_date' => null,
                'address_id' => null,
                'status' => 10
     
            ],
            [
                'username' =>'0799263495',
                'email' => '0799263495',
                'phone' => '0799263495',
                'password_hash' => Yii::$app->security->generatePasswordHash("0799263495"),              
                'auth_key' => Yii::$app->security->generateRandomString(),
                'full_name' => "dataEntry",
                'birth_date' => null,
                'address_id' => null,
                'status' => 10
     
            ]
        ];


        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                'users',
                [
                    'username' ,
                    'email' ,
                    'phone' ,
                    'password_hash',              
                    'auth_key' ,
                    'full_name' ,
                    'birth_date',
                    'address_id' ,
                    'status'
                ],
                $data
            )
            ->execute();



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
