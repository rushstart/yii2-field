<?php


namespace rushstart\field;


use yii\helpers\ArrayHelper;

/**
 * Class FieldProvider
 * @package rushstart\field
 * @property mixed $entityId
 * @property FieldInstance[] $instances
 */
class FieldProvider extends FieldCollection
{

    public $entity;
    public $bundle;
    public $model;

    /**
     * Collection constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->_fields = ArrayHelper::index(FieldInstance::findAll([
            'entity' => $this->entity,
            'bundle' => $this->bundle
        ]), 'field_name');
        foreach ($this->_fields as $field) {
            $field->model = $this->model;
        }
    }

    /**
     * @return array|FieldInstance[]
     */
    public function getInstances()
    {
        return $this->_fields;
    }

    public function validate()
    {
        foreach ($this->getFields() as $field){
            $field->validateFields();
        }
    }

    /**
     *
     */
    public function save()
    {
        foreach ($this->getFields() as $field){
            $field->updateFields();
        }
    }

    /**
     * @param $bundle
     * @return FieldInstance|null
     */
    public function getField($bundle)
    {
        return $this->getFields()[$bundle] ?? null;
    }

    /**
     * @param array $data
     */
    public function load(array $data)
    {
        foreach ($data as $bundle => $values) {
            if($this->getField($bundle)){
                $field = $this->getField($bundle);
                $field->setValues($values);
            }
        }

    }
}