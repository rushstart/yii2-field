<?php

namespace rushstart\field\models;

use rushstart\db\ActiveRecord;
use rushstart\field\types\BaseFieldType;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "field_instance".
 *
 * @property int $id
 * @property string $field_type
 * @property string $field_name
 * @property string $label
 * @property string $entity
 * @property string $bundle
 * @property mixed $default
 * @property int $max
 * @property int $required
 * @property array $settings
 * @property int $weight
 * @property int $status
 *
 * @property BaseFieldType[] $fields
 *
 */
class Instance extends ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_DELETED = 0;

    const FIELD_TYPE_BOOLEAN = 'boolean';
    const FIELD_TYPE_NUMBER = 'number';
    const FIELD_TYPE_PRODUCT = 'product';
    const FIELD_TYPE_STRING = 'string';
    const FIELD_TYPE_TAXONOMY_TERM = 'taxonomy_term';
    const FIELD_TYPE_TEXT = 'text';


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'field_instance';
    }

    /**
     * {@inheritdoc}
     * @return InstanceQuery the active query used by this AR class.
     */
    public static function find(): ActiveQuery
    {
        return new InstanceQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            static::STATUS_ACTIVE => 'активно',
            static::STATUS_DELETED => 'удалено',
        ];
    }

    /**
     * @return array
     */
    public static function getFieldTypes(): array
    {
        return [
            static::FIELD_TYPE_BOOLEAN => 'Логическое',
            static::FIELD_TYPE_NUMBER => 'Число',
            static::FIELD_TYPE_PRODUCT => 'Товар',
            static::FIELD_TYPE_STRING => 'Строка',
            static::FIELD_TYPE_TAXONOMY_TERM => 'Соловарь',
            static::FIELD_TYPE_TEXT => 'Текст',
        ];
    }

    /**
     * @return array
     */
    public static function getFieldTypeClasses(): array
    {
        return [
            static::FIELD_TYPE_BOOLEAN => 'rushstart\field\types\FieldTypeBoolean',
            static::FIELD_TYPE_NUMBER => 'rushstart\field\types\FieldTypeNumber',
            static::FIELD_TYPE_PRODUCT => 'rushstart\field\types\FieldTypeProduct',
            static::FIELD_TYPE_STRING => 'rushstart\field\types\FieldTypeString',
            static::FIELD_TYPE_TAXONOMY_TERM => 'rushstart\field\types\FieldTypeTaxonomyTerm',
            static::FIELD_TYPE_TEXT => 'rushstart\field\types\FieldTypeText',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'field_type' => 'Тип поля',
            'field_name' => 'Имя поля',
            'label' => 'Подпись',
            'default' => 'Значение по умолчанию',
            'entity' => 'Предназначение',
            'bundle' => 'Системное имя',
            'max' => 'Максимально количество значений',
            'required' => 'Обязательное',
            'settings' => 'Settings',
            'weight' => 'Вес',
            'status' => 'Статус',
        ];
    }

    /**
     * @return string
     */
    public function getFieldTypeName(): string
    {
        return ArrayHelper::getValue(static::getFieldTypes(), $this->field_type, '');
    }

    /**
     * @return mixed
     */
    public function getFieldTypeClass()
    {
        return ArrayHelper::getValue(static::getFieldTypeClasses(), $this->field_type);
    }

}