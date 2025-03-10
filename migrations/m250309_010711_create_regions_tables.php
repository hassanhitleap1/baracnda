<?php

use yii\db\Migration;

class m250309_010711_create_regions_tables extends Migration
{

    public $data = [
        ['name_ar' => 'عمان' ],
        ['name_ar' => 'اربد'],
        ['name_ar' => 'الزرقاء'],
        ['name_ar' => 'معان'],
        ['name_ar' => 'المفرق'],
        ['name_ar' => 'العقبة'],
        ['name_ar' => 'مادبا'],
        ['name_ar' => 'السلط'],
        ['name_ar' => 'الكرك'],
        ['name_ar' => 'الطفيلة'],
        ['name_ar' => 'عجلون'],
        ['name_ar' => 'جرش'],
        ['name_ar' => 'البلقاء'],
    ];
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%regions}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250)->defaultValue(null),
            'country_id'=> $this->integer()->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey('fk_countries_country', 'regions', 'country_id', 'countries', 'id', 'CASCADE');
        Yii::$app->db
        ->createCommand()
        ->batchInsert('regions', ['name'], $this->data)
        ->execute();
    }

    public function down()
    {
        $this->dropTable('{{%regions}}');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250309_010711_create_regions_tables cannot be reverted.\n";

        return false;
    }
    */
}
