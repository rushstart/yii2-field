<?php

namespace rushstart\field\types;


use rushstart\field\FieldInstance;

/**
 * This is the model class for table "field_type_number".
 *
 * @property int $field_instance_id
 * @property int $entity_id
 * @property int $delta
 * @property int $value
 *
 * @property FieldInstance $fieldInstance
 */
class FieldTypeNumber extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'field_type_number';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['value', 'number']
        ]);
    }
}
