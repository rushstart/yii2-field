<?php

namespace rushstart\field;

use rushstart\field\models\Instance;
use rushstart\field\types\BaseFieldType;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "field_instance".
 *
 * @property int $id
 * @property string $field_type
 * @property string $field_name
 * @property string $label
 * @property string $entity
 * @property string $bundle
 * @property int $max
 * @property int $required
 * @property array $settings
 * @property int $weight
 * @property int $status
 *
 * @property mixed $values
 * @property string $name
 * @property BaseFieldType[] $fields
 *
 */
class FieldInstance extends Instance
{

    protected $_oldValues;

    public $hole;


    /** @var ActiveRecord */
    public $model;

    /**
     * @return ActiveQuery
     */
    public function getFields(): ActiveQuery
    {
        return $this->hasMany($this->getFieldTypeClass(), ['field_instance_id' => 'id'])->andWhere(['entity_id' => $this->model->primaryKey]);
    }


    /**
     * @param ActiveForm $form
     * @return string
     * @throws \Exception
     */
    public function getFieldFormFields(ActiveForm $form): string
    {
        /** @var BaseFieldType $fieldTypeClass */
        $fieldTypeClass = $this->getFieldTypeClass();
        if ($this->max === 1) {
            return join('', $fieldTypeClass::getFieldFormFields($this, $this->model, $form));
        }
        return MultipleInput::widget([
            'model' => $this->model,
            'fieldInstance' => $this,
            'attribute' => "$this->bundle",
            'max' => $this->max,
            'columns' => $fieldTypeClass::getFieldFormFieldColumns($this, $this->model, $form),
            'id' => 'fc' . uniqid(),
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->label ?? 'field';
    }

    /**
     * @return array
     */
    public function getValues()
    {
        if ($this->max === 1) {
            if ($this->fields) {
                $field = current($this->fields);
                return $field->value;
            }
            return $this->default;
        }
        return ArrayHelper::map($this->fields, 'delta', 'value');
    }

    /**
     * @throws Throwable
     */
    public function fieldsDelete()
    {
        /** @var BaseFieldType $fieldTypeClass */
        $fieldTypeClass = $this->getFieldTypeClass();
        $fieldTypeClass::deleteAll(['field_instance_id' => $this->id, 'entity_id' => $this->model->primaryKey]);
    }

    /**
     * @param $values
     */
    public function setValues($values)
    {

        /** @var BaseFieldType $fieldTypeClass */
        $fieldTypeClass = $this->getFieldTypeClass();
        $fields = [];

        if ($this->max == 1) {
            $field = new $fieldTypeClass;
            $field->set($this, $values);
            $fields[] = $field;
        } else {
            $values = array_filter((array)$values);
            foreach ($values as $delta => $value) {
                $field = new $fieldTypeClass;
                $field->set($this, $value, $delta);
                $fields[] = $field;
            }
        }

        $this->populateRelation('fields', $fields);
    }

    /**
     * Saves field values to DB
     * @throws Throwable
     */
    public function updateFields()
    {

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->fieldsDelete();
            foreach ($this->fields as $delta => $field) {
                if (!$field->save()) {
                    throw new Exception("Ошибка в поле {$this->label}: " . join(', ', $field->getErrorSummary(false)));
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::$app->session->addFlash('error', $e->getMessage());
        }

    }

    public function validateFields()
    {
        foreach ($this->fields as $delta => $field) {
            if (!$field->validate()) {
                foreach ($field->errors as $attribute => $error) {
                    if ($this->max == 1) {
                        if ($attribute === 'value') {
                            $this->model->addError("{$this->bundle}.{$this->field_name}", current($error));
                        } else {
                            $this->model->addError("{$this->bundle}.{$this->field_name}.{$attribute}", current($error));
                        }
                    } else {
                        if ($attribute === 'value') {
                            $this->model->addError("{$this->bundle}.{$this->field_name}.{$delta}", current($error));
                        } else {
                            $this->model->addError("{$this->bundle}.{$this->field_name}.{$delta}.{$attribute}", current($error));
                        }
                    }
                }
            }
        }
    }

    public function __get($name)
    {
        return parent::__get($name);
    }
}