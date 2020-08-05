<?php

namespace rushstart\field\types;


use rushstart\field\FieldInstance;
use rushstart\field\MultipleInputColumn;
use yii\base\Model;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "field_type_boolean".
 */
class FieldTypeBoolean extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'field_type_boolean';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['value', 'boolean']
        ]);
    }

    /**
     * @param FieldInstance $fieldInstance
     * @param Model $model
     * @param ActiveForm $form
     * @return array
     */
    public static function getFieldFormFields(FieldInstance $fieldInstance, Model $model, ActiveForm $form): array
    {
        return [
            $fieldInstance->field_name => $form->field($model, "{$fieldInstance->bundle}[{$fieldInstance->field_name}]")
               ->checkbox(['label' => $fieldInstance->name])->label(false)
        ];
    }

    /**
     * @param FieldInstance $fieldInstance
     * @param Model $model
     * @param ActiveForm $form
     * @return array
     */
    public static function getFieldFormFieldColumns(FieldInstance $fieldInstance, Model $model, ActiveForm $form): array
    {
        return [
            [
                'name' => $fieldInstance->field_name,
                'title' => $fieldInstance->name,
                'type' => MultipleInputColumn::TYPE_CHECKBOX
            ]
        ];
    }

}
