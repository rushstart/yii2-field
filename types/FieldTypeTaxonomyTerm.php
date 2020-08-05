<?php

namespace rushstart\field\types;

use rushstart\field\FieldInstance;
use rushstart\taxonomy\models\TaxonomyTerm;

/**
 * This is the model class for table "field_type_taxonomy_term".
 *
 * @property int $field_instance_id
 * @property int $entity_id
 * @property int $delta
 * @property string $value
 *
 */
class FieldTypeTaxonomyTerm extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'field_type_taxonomy_term';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['value', 'integer']
        ]);
    }

    public function set(FieldInstance $fieldInstance, $value, $delta = 0)
    {
        parent::set($fieldInstance, $value, $delta);
        if ($value instanceof TaxonomyTerm) {
            $this->value = $value->primaryKey;
        }

    }
}
