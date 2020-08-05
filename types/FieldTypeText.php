<?php

namespace rushstart\field\types;


/**
 * This is the model class for table "field_type_text".
 *
 * @property int $field_instance_id
 * @property int $entity_id
 * @property int $delta
 * @property string $value
 *
 */
class FieldTypeText extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'field_type_text';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['value', 'string']
        ]);
    }

}
