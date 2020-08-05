<?php


namespace rushstart\field;

use Throwable;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\validators\Validator;

class FieldBehavior extends Behavior
{
    /** @var ActiveRecord|FieldsOwnerInterface */
    public $owner;

    public $entity;
    public $bundle = 'fields';


    /**
     * @var FieldProvider
     */
    protected $_fieldProvider;

    /**
     * {@inheritdoc}
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $this->_fieldProvider = new FieldProvider([
            'entity' => $this->entity,
            'bundle' => $this->bundle,
            'model' => $owner,
        ]);
        $this->owner->validators[] = Validator::createValidator('safe', $this->owner, [$this->bundle]);

    }


    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    /**
     * Event handler
     * @throws Throwable
     */
    public function afterValidate()
    {
        $this->_fieldProvider->validate();
    }

    /**
     * Event handler
     * @throws Throwable
     */
    public function afterSave()
    {
        $this->_fieldProvider->save();
    }

    /**
     * {@inheritdoc}
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return $name === $this->bundle || parent::canGetProperty($name, $checkVars);
    }

    /**
     * {@inheritdoc}
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return $name === $this->bundle || parent::canSetProperty($name, $checkVars);
    }

    /**
     * {@inheritdoc}
     */
    public function __set($name, $value)
    {
        if ($name === $this->bundle) {
            $this->_fieldProvider->load($value);
            return;
        }
        parent::__set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        if ($name === $this->bundle) {
            return $this->_fieldProvider;
        }
        return parent::__get($name);
    }
}