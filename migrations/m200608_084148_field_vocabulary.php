<?php

use yii\db\Migration;

/**
 * Class m200608_084148_field_vocabulary
 */
class m200608_084148_field_vocabulary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('field_type_taxonomy_term', [
            'field_instance_id' => $this->integer()->notNull(),
            'entity_id' => $this->string(50)->notNull(),
            'delta' => $this->integer()->notNull()->defaultValue(0),
            'value' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-field_type_taxonomy_term-field_instance_id', 'field_type_taxonomy_term', 'field_instance_id', 'field_instance', 'id', 'CASCADE');
        $this->addForeignKey('fk-field_type_taxonomy_term-value', 'field_type_taxonomy_term', 'value', 'taxonomy_term', 'id', 'CASCADE');
        $this->addPrimaryKey('pk-field_type_taxonomy_term', 'field_type_taxonomy_term', ['field_instance_id', 'entity_id', 'delta']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-field_type_taxonomy_term-field_instance_id', 'field_type_taxonomy_term');
        $this->dropForeignKey('fk-field_type_taxonomy_term-value', 'field_type_taxonomy_term');
        $this->dropTable('field_type_taxonomy_term');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200608_084148_field_vocabulary cannot be reverted.\n";

        return false;
    }
    */
}
