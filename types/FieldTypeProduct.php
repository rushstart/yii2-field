<?php

namespace rushstart\field\types;


use rushstart\field\FieldInstance;
use yii\base\Model;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "field_type_product".
 *
 * @property int $field_instance_id
 * @property int $entity_id
 * @property int $delta
 * @property int $value
 *
 * @property FieldInstance $fieldInstance
 */
class FieldTypeProduct extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'field_type_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            ['name', 'string'],
            ['cost', 'integer'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'Наименование товара',
            'cost' => 'Стоимость товара',
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
            '<fieldset>',
            "<legend>{$fieldInstance->name}</legend>",
            'name' => $form->field($model, "{$fieldInstance->bundle}[{$fieldInstance->field_name}][name]")
                ->textInput()->label('Наименование товара'),
            'cost' => $form->field($model, "{$fieldInstance->bundle}[{$fieldInstance->field_name}][cost]")
                ->textInput()->label('Стоимость товара'),
            '</fieldset>',
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
                'name' => 'name',
                'title' => 'Наименование товара',
            ],
            [
                'name' => 'cost',
                'title' => 'Стоимость товара',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'name' => 'name',
            'cost' => 'cost'
        ];
    }

    public function getValue()
    {
        return $this->toArray();
    }

    public function setValue($value)
    {
        $this->setAttributes($value);
    }
}
