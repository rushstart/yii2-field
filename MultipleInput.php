<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace rushstart\field;

use Traversable;
use unclead\multipleinput\MultipleInputColumn;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * Widget for rendering multiple input for an attribute of model.
 *
 * @author Eugene Tupikov <unclead.nsk@gmail.com>
 */
class MultipleInput extends \unclead\multipleinput\MultipleInput
{
    /** @var FieldInstance */
    public $fieldInstance;

    public $columnClass = \rushstart\field\MultipleInputColumn::class;

    /**
     * Initializes data.
     */
    protected function initData()
    {
        if ($this->data !== null) {
            return;
        }

        if ($this->value !== null) {
            $this->data = $this->value;
            return;
        }

        if ($this->model instanceof Model) {

            $attribute = $this->getPrepareAttribute($this->attribute);

            $data = ArrayHelper::getValue($this->model, "$attribute.{$this->fieldInstance->field_name}", []);

            if (!is_array($data) && empty($data)) {
                return;
            }
            if (!($data instanceof Traversable)) {

                $data = (array)$data;
            }
            foreach ($data as $index => $value) {
                $this->data[$index] = $value;
            }

        }
    }

    protected function getPrepareAttribute($attribute)
    {
        $attribute = explode('[', $attribute);
        $attribute = array_map(function ($attr) {
            return trim($attr, ']');
        }, $attribute);
        return join('.', $attribute);
    }

    /**
     * This function tries to guess the columns to show from the given data
     * if [[columns]] are not explicitly specified.
     */
    protected function guessColumns()
    {

        if (empty($this->columns)) {
            $this->columns[] = [
                'name' => $this->fieldInstance->field_name,
                'type' => MultipleInputColumn::TYPE_TEXT_INPUT,
                'title' => $this->fieldInstance->name,
            ];
        }
    }

    /**
     * Run widget.
     */
    public function run()
    {
        $content = '';
        if ($this->isEmbedded === false && $this->hasModel()) {
            $content .= Html::hiddenInput(Html::getInputName($this->model, "{$this->attribute}[{$this->fieldInstance->field_name}]"), null);
        }

        $content .= $this->createRenderer()->render();

        return $content;
    }
}
