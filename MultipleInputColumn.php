<?php


namespace rushstart\field;


use yii\base\Model;

class MultipleInputColumn extends \unclead\multipleinput\MultipleInputColumn
{
    /** @var MultipleInput */
    public $context;

    /**
     * @param int|string|null $index
     * @return null|string
     */
    public function getFirstError($index)
    {
        if ($index === null) {
            return null;
        }

        if ($this->isRendererHasOneColumn()) {
            $attribute = "{$this->context->attribute}.{$this->name}.{$index}";
        } else {
            $attribute = $this->context->attribute . $this->getElementName($index, false);
        }
        $attribute = $this->getPrepareAttribute($attribute);

        $model = $this->context->model;
        if ($model instanceof Model) {
            return $model->getFirstError($attribute);
        }

        return null;
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
     * Returns element's name.
     *
     * @param int|null|string $index current row index
     * @param bool $withPrefix whether to add prefix.
     *
     * @return string
     */
    public function getElementName($index, $withPrefix = true)
    {
        if ($index === null) {
            $index = '{' . $this->renderer->getIndexPlaceholder() . '}';
        }
        $elementName = $this->isRendererHasOneColumn()
            ? '[' . $this->name . '][' . $index . ']'
            : '[' . $this->context->fieldInstance->field_name . '][' . $index . '][' . $this->name . ']';

        if (!$withPrefix) {
            return $elementName;
        }

        $prefix = $this->getInputNamePrefix();
        if ($this->context->isEmbedded && strpos($prefix, $this->context->name) === false) {
            $prefix = $this->context->name;
        }

        return $prefix . $elementName . (empty($this->nameSuffix) ? '' : ('_' . $this->nameSuffix));
    }

    /**
     * @return bool
     */
    private function isRendererHasOneColumn()
    {
        return count($this->renderer->columns) === 1;
    }
}