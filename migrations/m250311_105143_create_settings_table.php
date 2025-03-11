<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%settings}}`.
 */
class m250311_105143_create_settings_table extends Migration
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
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'facebook' => $this->string(255)->null(),
            'instagram' => $this->string(255)->null(),
            'snapchat' => $this->string(255)->null(),
            'tiktok' => $this->string(255)->null(),
            'youtube' => $this->string(255)->null(),
            'phone' => $this->string(255)->null(),
            'address' => $this->string(255)->null(),
            'fax' => $this->string(255)->null(),
            'lag' => $this->string(255)->null(),
            'lat' => $this->string(255)->null(),
            'mobile' => $this->string(255)->null(),
            'email' => $this->string(255)->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);


        $data = [
            [
                'facebook' => 'facebook',
                'instagram' => 'instagram',
                'snapchat' => 'snapchat',
                'tiktok' => 'tiktok',
                'youtube' => 'youtube',
                'phone' => 'phone',
                'address' => 'address',
                'fax' => 'fax',
                'lag' => 'lag',
                'lat' => 'lat',
                'mobile' => 'mobile',
                'email' => 'email'
            ]
        ];





        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                'settings',
                [
                    'facebook',
                    'instagram',
                    'snapchat',
                    'tiktok',
                    'youtube',
                    'phone',
                    'address',
                    'fax',
                    'lag',
                    'lat',
                    'mobile',
                    'email',
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
        $this->dropTable('{{%settings}}');
    }
}
