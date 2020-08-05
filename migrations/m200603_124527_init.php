<?php

use yii\db\Migration;

/**
 * Class m200603_124526_init
 */
class m200603_124527_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('field_instance', [
            'id' => $this->primaryKey(),
            'field_type' => $this->string(50)->notNull(),
            'entity' => $this->string(50)->notNull(),
            'bundle' => $this->string(50)->notNull(),
            'field_name' => $this->string(50)->notNull(),
            'label' => $this->string(50)->notNull(),
            'default' => $this->json(),
            'max' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1),
            'required' => $this->tinyInteger()->unsigned(),
            'settings' => $this->json(),
            'weight' => $this->tinyInteger()->unsigned(),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(10),
        ]);
        $this->createIndex('uniq-field_instance-field_name-entity-bundle', 'field_instance', ['entity', 'bundle', 'field_name'], true);
        $this->createIndex('idx-field_instance-field_type', 'field_instance', ['field_type']);
        $this->createIndex('idx-field_instance-status', 'field_instance', ['status']);

        $this->createTable('field_type_number', [
            'field_instance_id' => $this->integer()->notNull(),
            'entity_id' => $this->string(50)->notNull(),
            'delta' => $this->integer()->notNull()->defaultValue(0),
            'value' => $this->float()->notNull(),
        ]);
        $this->addForeignKey('fk-field_type_number-field_instance_id', 'field_type_number', 'field_instance_id', 'field_instance', 'id', 'CASCADE');
        $this->addPrimaryKey('pk-field_type_number', 'field_type_number', ['field_instance_id', 'entity_id', 'delta']);
        $this->createIndex('idx-field_type_number-value', 'field_type_number', ['value']);

        $this->createTable('field_type_string', [
            'field_instance_id' => $this->integer()->notNull(),
            'entity_id' => $this->string(50)->notNull(),
            'delta' => $this->integer()->notNull()->defaultValue(0),
            'value' => $this->string(255)->notNull(),
        ]);
        $this->addForeignKey('fk-field_type_string-field_instance_id', 'field_type_string', 'field_instance_id', 'field_instance', 'id', 'CASCADE');
        $this->addPrimaryKey('pk-field_type_string', 'field_type_string', ['field_instance_id', 'entity_id', 'delta']);
        $this->createIndex('idx-field_type_string-value', 'field_type_string', ['value']);

        $this->createTable('field_type_boolean', [
            'field_instance_id' => $this->integer()->notNull(),
            'entity_id' => $this->string(50)->notNull(),
            'delta' => $this->integer()->notNull()->defaultValue(0),
            'value' => $this->tinyInteger()->notNull(),
        ]);
        $this->addForeignKey('fk-field_type_boolean-field_instance_id', 'field_type_boolean', 'field_instance_id', 'field_instance', 'id', 'CASCADE');
        $this->addPrimaryKey('pk-field_type_boolean', 'field_type_boolean', ['field_instance_id', 'entity_id', 'delta']);
        $this->createIndex('idx-field_type_boolean-value', 'field_type_boolean', ['value']);

        $this->createTable('field_type_text', [
            'field_instance_id' => $this->integer()->notNull(),
            'entity_id' => $this->string(50)->notNull(),
            'delta' => $this->integer()->notNull()->defaultValue(0),
            'value' => $this->text(),
        ]);
        $this->addForeignKey('fk-field_type_text-field_instance_id', 'field_type_text', 'field_instance_id', 'field_instance', 'id', 'CASCADE');
        $this->addPrimaryKey('pk-field_type_text', 'field_type_text', ['field_instance_id', 'entity_id', 'delta']);

        $this->createTable('field_type_product', [
            'field_instance_id' => $this->integer()->notNull(),
            'entity_id' => $this->string(50)->notNull(),
            'delta' => $this->integer()->notNull()->defaultValue(0),
            'name' => $this->string(255)->notNull(),
            'cost' => $this->float()->notNull(),
        ]);
        $this->addForeignKey('fk-field_type_product-field_instance_id', 'field_type_product', 'field_instance_id', 'field_instance', 'id', 'CASCADE');
        $this->addPrimaryKey('pk-field_type_product', 'field_type_product', ['field_instance_id', 'entity_id', 'delta']);
        $this->createIndex('idx-field_type_product-name', 'field_type_product', ['name']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-field_type_product-field_instance_id', 'field_type_product');
        $this->dropForeignKey('fk-field_type_number-field_instance_id', 'field_type_number');
        $this->dropForeignKey('fk-field_type_string-field_instance_id', 'field_type_string');
        $this->dropForeignKey('fk-field_type_boolean-field_instance_id', 'field_type_boolean');
        $this->dropForeignKey('fk-field_type_text-field_instance_id', 'field_type_text');
        $this->dropTable('field_type_text');
        $this->dropTable('field_type_boolean');
        $this->dropTable('field_type_string');
        $this->dropTable('field_type_number');
        $this->dropTable('field_type_product');
        $this->dropTable('field_instance');
    }
}
