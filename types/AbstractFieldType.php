<?php


namespace rushstart\field\types;


use rushstart\field\FieldInstance;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\widgets\ActiveForm;

/**
 * Class BaseFieldType
 * @property mixed $value
 * @property int $delta
 * @property int $entity_id
 * @property int $field_instance_id
 *
 * @property FieldInstance $fieldInstance
 */
class AbstractFieldType extends ActiveRecord
{

    public $blackHole;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['delta'], 'required'],
            [['delta'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'value' => $this->fieldInstance->name,
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
            $fieldInstance->field_name => $form->field($model, "{$fieldInstance->bundle}[{$fieldInstance->field_name}]")->textInput()->label($fieldInstance->name)
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
        return [];
    }

    /**
     * @param FieldInstance $fieldInstance
     * @param Model $model
     * @return string
     */
    public static function format(FieldInstance $fieldInstance, Model $model): string
    {
        return join(', ', $fieldInstance->values);
    }

    /**
     * @return ActiveQuery
     */
    public function getFieldInstance(): ActiveQuery
    {
        return $this->hasOne(FieldInstance::class, ['id' => 'field_instance_id']);
    }

    /**
     * @param FieldInstance $fieldInstance
     * @param $value
     * @param $delta
     */
    public function set(FieldInstance $fieldInstance, $value, $delta = 0)
    {
        $this->field_instance_id = $fieldInstance->id;
        $this->entity_id = $fieldInstance->model->primaryKey;
        $this->delta = $delta;
        $this->value = $value;
    }
}